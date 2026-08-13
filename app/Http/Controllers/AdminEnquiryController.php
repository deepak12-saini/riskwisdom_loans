<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Enquiry;
use App\Models\User;
use App\Services\EnquiryActivityLogger;
use App\Services\MailchimpService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminEnquiryController extends Controller
{
    public function index(Request $request): View
    {
        $filter = (string) $request->query('filter', 'all');
        $q = trim((string) $request->query('q', ''));
        $showPaidAds = (bool) config('riskwisdom.admin_show_paid_ads', false);

        if ($filter === 'paid' && ! $showPaidAds) {
            $filter = 'all';
        }

        $query = Enquiry::query()->with(['client', 'assignedUser'])->latest();

        match ($filter) {
            'ready_now' => $query->where('timeline', 'ready_now'),
            'this_week' => $query->where('created_at', '>=', now()->startOfWeek()),
            'today' => $query->whereDate('created_at', today()),
            'calendly' => $query->where('lead_type', 'calendly'),
            'callbacks_due' => $query->callbacksDueToday(),
            'new_leads' => $query->newLeads(),
            'mine' => $query->assignedTo((int) $request->user()->id),
            'unassigned' => $query->unassigned(),
            'paid' => $showPaidAds ? $query->where('utm_medium', 'cpc') : null,
            'converted' => $query->whereHas('client'),
            'lead_only' => $query->whereDoesntHave('client'),
            default => null,
        };

        if ($q !== '') {
            $query->where(function ($builder) use ($q) {
                $builder
                    ->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('enquiry', 'like', "%{$q}%");
            });
        }

        $enquiries = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Enquiry::count(),
            'ready_now' => Enquiry::query()->where('timeline', 'ready_now')->count(),
            'this_week' => Enquiry::query()->where('created_at', '>=', now()->startOfWeek())->count(),
            'today' => Enquiry::query()->whereDate('created_at', today())->count(),
            'calendly' => Enquiry::query()->where('lead_type', 'calendly')->count(),
            'callbacks_due' => Enquiry::query()->callbacksDueToday()->count(),
            'new_leads' => Enquiry::query()->newLeads()->count(),
            'mine' => Enquiry::query()->assignedTo((int) $request->user()->id)->count(),
            'unassigned' => Enquiry::query()->unassigned()->count(),
            'converted' => Enquiry::query()->whereHas('client')->count(),
            'lead_only' => Enquiry::query()->whereDoesntHave('client')->count(),
        ];

        if ($showPaidAds) {
            $stats['paid'] = Enquiry::query()->where('utm_medium', 'cpc')->count();
        }

        $headings = [
            'all' => 'All enquiries',
            'ready_now' => 'Ready now leads',
            'this_week' => 'This week leads',
            'today' => 'Today\'s leads',
            'calendly' => 'Calendly bookings',
            'callbacks_due' => 'Callbacks due',
            'new_leads' => 'New — not yet called',
            'mine' => 'My leads',
            'unassigned' => 'Unassigned leads',
            'paid' => 'Paid ad leads (CPC)',
            'converted' => 'Leads with client file',
            'lead_only' => 'Lead only',
        ];

        $pageHeading = $headings[$filter] ?? 'Website enquiries';

        return view('admin.enquiries.index', compact('enquiries', 'stats', 'filter', 'pageHeading', 'showPaidAds', 'q'));
    }

    public function show(Enquiry $enquiry): View
    {
        $enquiry->load(['client', 'assignedUser', 'activities.user']);
        $panelUsers = User::panelUsers();

        return view('admin.enquiries.show', compact('enquiry', 'panelUsers'));
    }

    public function updateAssignment(Request $request, Enquiry $enquiry, EnquiryActivityLogger $logger): RedirectResponse
    {
        $user = $request->user();
        $assigneeId = $request->input('assigned_user_id');
        $assigneeId = $assigneeId === '' || $assigneeId === null ? null : (int) $assigneeId;

        $panelIds = array_map('intval', User::panelUsers()->pluck('id')->all());

        if ($assigneeId !== null && ! in_array($assigneeId, $panelIds, true)) {
            return back()->with('error', 'Choose a valid staff member.');
        }

        if (! $user->isPanelAdmin()) {
            $allowed = [$user->id, null];
            if ($enquiry->assigned_user_id !== null && (int) $enquiry->assigned_user_id !== (int) $user->id) {
                return back()->with('error', 'This lead is assigned to someone else.');
            }
            if (! in_array($assigneeId, $allowed, true)) {
                return back()->with('error', 'You can only take this lead or leave it unassigned.');
            }
        }

        $previous = $enquiry->assignedUser;
        $next = $assigneeId ? User::query()->find($assigneeId) : null;

        if ((int) ($enquiry->assigned_user_id ?? 0) === (int) ($assigneeId ?? 0)) {
            return back()->with('success', 'Assignment unchanged.');
        }

        $enquiry->update([
            'assigned_user_id' => $assigneeId,
            'assigned_at' => $assigneeId ? now() : null,
        ]);

        if ($next) {
            $logger->record($enquiry, 'assigned', 'Assigned to '.$next->displayName().'.', $user);
        } else {
            $logger->record(
                $enquiry,
                'unassigned',
                'Unassigned'.($previous ? ' (was '.$previous->displayName().')' : '').'.',
                $user,
            );
        }

        return redirect()
            ->route('admin.enquiries.show', $enquiry)
            ->with('success', $next ? 'Lead assigned to '.$next->displayName().'.' : 'Lead is now unassigned.');
    }

    public function updateCallTracking(Request $request, Enquiry $enquiry, EnquiryActivityLogger $logger): RedirectResponse
    {
        $statuses = array_keys(config('riskwisdom.call_statuses', []));

        $validated = $request->validate([
            'call_status' => ['required', 'string', 'in:'.implode(',', $statuses)],
            'call_notes' => ['nullable', 'string', 'max:5000'],
            'callback_at' => ['nullable', 'date'],
        ]);

        if ($validated['call_status'] === 'callback' && empty($validated['callback_at'])) {
            return back()
                ->withErrors(['callback_at' => 'Set a callback date and time when status is Callback.'])
                ->withInput();
        }

        $previousStatus = $enquiry->call_status ?? 'new';
        $previousNotes = (string) ($enquiry->call_notes ?? '');

        $updates = [
            'call_status' => $validated['call_status'],
            'call_notes' => $validated['call_notes'] ?? null,
            'callback_at' => $validated['call_status'] === 'callback'
                ? $validated['callback_at']
                : null,
        ];

        if (in_array($validated['call_status'], ['called', 'no_answer', 'booked', 'callback', 'not_interested'], true)) {
            $updates['last_called_at'] = now();
        }

        $user = $request->user();

        if ($enquiry->assigned_user_id === null && $user) {
            $updates['assigned_user_id'] = $user->id;
            $updates['assigned_at'] = now();
        }

        $enquiry->update($updates);

        if ($enquiry->wasChanged('assigned_user_id') && $user) {
            $logger->record($enquiry, 'assigned', 'Assigned to '.$user->displayName().' (took lead).', $user);
        }

        $statusLabel = config('riskwisdom.call_statuses')[$validated['call_status']] ?? $validated['call_status'];
        $parts = [];

        if ($previousStatus !== $validated['call_status']) {
            $parts[] = 'Call status set to '.$statusLabel;
        }

        $newNotes = trim((string) ($validated['call_notes'] ?? ''));
        if ($newNotes !== '' && $newNotes !== trim($previousNotes)) {
            $parts[] = $newNotes;
        }

        if ($parts === []) {
            $parts[] = 'Call tracking updated ('.$statusLabel.').';
        }

        $logger->record($enquiry, 'call_status', implode(' — ', $parts).'.', $user, [
            'call_status' => $validated['call_status'],
        ]);

        return redirect()
            ->route('admin.enquiries.show', $enquiry)
            ->with('success', 'Call status updated.');
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        if ($enquiry->client) {
            return redirect()
                ->route('admin.enquiries.index')
                ->with('error', 'This enquiry has a client file. Archive or delete the client file first.');
        }

        $enquiry->delete();

        return redirect()
            ->route('admin.enquiries.index')
            ->with('success', 'Enquiry deleted.');
    }

    public function convert(Request $request, Enquiry $enquiry, MailchimpService $mailchimp): RedirectResponse
    {
        $existing = Client::query()->where('enquiry_id', $enquiry->id)->first();

        if ($existing) {
            return redirect()
                ->route('admin.clients.show', $existing)
                ->with('success', 'This enquiry already has a client file.');
        }

        $client = Client::query()->create(Client::fromEnquiry($enquiry));

        app(EnquiryActivityLogger::class)->record(
            $enquiry,
            'converted',
            'Converted to client file.',
            $request->user(),
        );

        if ($enquiry->mailchimp_synced_at && $mailchimp->isConfigured()) {
            try {
                $mailchimp->addTags($enquiry->email, ['client']);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Client file created from enquiry. Add tasks to track outstanding items.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'enquiries-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Date', 'Lead type', 'First name', 'Last name', 'Phone', 'Email',
                'Loan type', 'Timeline', 'State', 'Source', 'Enquiry',
            ]);

            Enquiry::query()->latest()->chunk(100, function ($enquiries) use ($handle) {
                foreach ($enquiries as $enquiry) {
                    fputcsv($handle, [
                        $enquiry->id,
                        $enquiry->created_at?->toDateTimeString(),
                        $enquiry->lead_type,
                        $enquiry->first_name,
                        $enquiry->last_name,
                        $enquiry->phone,
                        $enquiry->email,
                        $enquiry->loan_type,
                        $enquiry->timeline,
                        $enquiry->state,
                        $enquiry->source,
                        $enquiry->enquiry,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
