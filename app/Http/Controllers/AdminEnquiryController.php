<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminEnquiryController extends Controller
{
    public function index(Request $request): View
    {
        $filter = (string) $request->query('filter', 'all');

        $query = Enquiry::query()->with('client')->latest();

        match ($filter) {
            'ready_now' => $query->where('timeline', 'ready_now'),
            'this_week' => $query->where('created_at', '>=', now()->startOfWeek()),
            'today' => $query->whereDate('created_at', today()),
            'paid' => $query->where('utm_medium', 'cpc'),
            default => null,
        };

        $enquiries = $query->paginate(25)->withQueryString();

        $stats = [
            'total' => Enquiry::count(),
            'ready_now' => Enquiry::query()->where('timeline', 'ready_now')->count(),
            'this_week' => Enquiry::query()->where('created_at', '>=', now()->startOfWeek())->count(),
            'today' => Enquiry::query()->whereDate('created_at', today())->count(),
            'paid' => Enquiry::query()->where('utm_medium', 'cpc')->count(),
        ];

        $headings = [
            'all' => 'All enquiries',
            'ready_now' => 'Ready now leads',
            'this_week' => 'This week leads',
            'today' => 'Today\'s leads',
            'paid' => 'Paid ad leads (CPC)',
        ];

        $pageHeading = $headings[$filter] ?? 'Website enquiries';

        return view('admin.enquiries.index', compact('enquiries', 'stats', 'filter', 'pageHeading'));
    }

    public function convert(Enquiry $enquiry): RedirectResponse
    {
        $existing = Client::query()->where('enquiry_id', $enquiry->id)->first();

        if ($existing) {
            return redirect()
                ->route('admin.clients.show', $existing)
                ->with('success', 'This enquiry already has a client file.');
        }

        $client = Client::query()->create(Client::fromEnquiry($enquiry));

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
