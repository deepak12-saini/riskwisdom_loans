<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Enquiry;
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

        $query = Enquiry::query()->with('client')->latest();

        match ($filter) {
            'ready_now' => $query->where('timeline', 'ready_now'),
            'this_week' => $query->where('created_at', '>=', now()->startOfWeek()),
            'today' => $query->whereDate('created_at', today()),
            'calendly' => $query->where('lead_type', 'calendly'),
            'callbacks_due' => $query
                ->where('call_status', 'callback')
                ->where(function ($builder) {
                    $builder
                        ->whereNull('callback_at')
                        ->orWhere('callback_at', '<=', now()->endOfDay());
                }),
            'new_leads' => $query->where('call_status', 'new'),
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
            'callbacks_due' => Enquiry::query()
                ->where('call_status', 'callback')
                ->where(function ($builder) {
                    $builder
                        ->whereNull('callback_at')
                        ->orWhere('callback_at', '<=', now()->endOfDay());
                })
                ->count(),
            'new_leads' => Enquiry::query()->where('call_status', 'new')->count(),
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
            'paid' => 'Paid ad leads (CPC)',
            'converted' => 'Leads with client file',
            'lead_only' => 'Lead only',
        ];

        $pageHeading = $headings[$filter] ?? 'Website enquiries';

        return view('admin.enquiries.index', compact('enquiries', 'stats', 'filter', 'pageHeading', 'showPaidAds', 'q'));
    }

    public function show(Enquiry $enquiry): View
    {
        $enquiry->load('client');

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function updateCallTracking(Request $request, Enquiry $enquiry): RedirectResponse
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

        $enquiry->update($updates);

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

    public function convert(Enquiry $enquiry, MailchimpService $mailchimp): RedirectResponse
    {
        $existing = Client::query()->where('enquiry_id', $enquiry->id)->first();

        if ($existing) {
            return redirect()
                ->route('admin.clients.show', $existing)
                ->with('success', 'This enquiry already has a client file.');
        }

        $client = Client::query()->create(Client::fromEnquiry($enquiry));

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
