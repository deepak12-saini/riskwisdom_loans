<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Enquiry;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $canLeads = (bool) $user?->canAdmin('enquiries.view');
        $canClients = (bool) $user?->canAdmin('clients.view');
        $canTasks = (bool) $user?->canAdmin('tasks.view');

        $myLeadsCount = $canLeads && $user
            ? Enquiry::query()->assignedTo((int) $user->id)->count()
            : 0;
        $unassignedCount = $canLeads ? Enquiry::query()->unassigned()->count() : 0;
        $newLeadsCount = $canLeads ? Enquiry::query()->newLeads()->count() : 0;
        $callbacksCount = $canLeads ? Enquiry::query()->callbacksDueToday()->count() : 0;
        $todayLeadsCount = $canLeads ? Enquiry::query()->whereDate('created_at', today())->count() : 0;
        $readyNowCount = $canLeads ? Enquiry::query()->where('timeline', 'ready_now')->count() : 0;
        $weekLeadsCount = $canLeads ? Enquiry::query()->where('created_at', '>=', now()->startOfWeek())->count() : 0;
        $openTasksCount = $canTasks ? Task::query()->open()->count() : 0;
        $overdueTasksCount = $canTasks ? Task::query()->overdue()->count() : 0;
        $activeClientsCount = $canClients ? Client::query()->where('status', 'active')->count() : 0;

        $myLeads = $canLeads && $user
            ? Enquiry::query()->assignedTo((int) $user->id)->with('assignedUser')->latest()->limit(6)->get()
            : collect();

        $newLeads = $canLeads
            ? Enquiry::query()->newLeads()->latest()->limit(6)->get()
            : collect();

        $callbacks = $canLeads
            ? Enquiry::query()
                ->callbacksDueToday()
                ->orderByRaw('CASE WHEN callback_at IS NULL THEN 1 ELSE 0 END')
                ->orderBy('callback_at')
                ->limit(6)
                ->get()
            : collect();

        $todayBookings = $canLeads ? $this->todaysBookings() : collect();

        $overdueTasks = $canTasks
            ? Task::query()->overdue()->with('client')->orderBy('due_date')->limit(6)->get()
            : collect();

        $recentLeads = $canLeads
            ? Enquiry::query()->latest()->limit(6)->get()
            : collect();

        $stats = [
            [
                'key' => 'mine',
                'label' => 'My leads',
                'value' => $myLeadsCount,
                'hint' => $unassignedCount.' unassigned',
                'href' => $canLeads ? route('admin.enquiries.index', ['filter' => 'mine']) : null,
                'tone' => 'navy',
            ],
            [
                'key' => 'new',
                'label' => 'New leads',
                'value' => $newLeadsCount,
                'hint' => 'Not called yet',
                'href' => $canLeads ? route('admin.enquiries.index', ['filter' => 'new_leads']) : null,
                'tone' => 'blue',
            ],
            [
                'key' => 'callbacks',
                'label' => 'Callbacks today',
                'value' => $callbacksCount,
                'hint' => 'Due today or overdue',
                'href' => $canLeads ? route('admin.enquiries.index', ['filter' => 'callbacks_due']) : null,
                'tone' => 'purple',
            ],
            [
                'key' => 'today',
                'label' => 'Leads today',
                'value' => $todayLeadsCount,
                'hint' => 'Arrived today',
                'href' => $canLeads ? route('admin.enquiries.index', ['filter' => 'today']) : null,
                'tone' => 'navy',
            ],
            [
                'key' => 'ready',
                'label' => 'Ready now',
                'value' => $readyNowCount,
                'hint' => 'Hot timeline',
                'href' => $canLeads ? route('admin.enquiries.index', ['filter' => 'ready_now']) : null,
                'tone' => 'amber',
            ],
            [
                'key' => 'bookings',
                'label' => 'Meetings today',
                'value' => $todayBookings->count(),
                'hint' => 'Calendly bookings',
                'href' => $canLeads ? route('admin.enquiries.index', ['filter' => 'calendly']) : null,
                'tone' => 'green',
            ],
            [
                'key' => 'overdue',
                'label' => 'Overdue tasks',
                'value' => $overdueTasksCount,
                'hint' => $openTasksCount.' open in total',
                'href' => $canTasks ? route('admin.tasks.index', ['filter' => 'overdue']) : null,
                'tone' => 'red',
            ],
        ];

        return view('admin.dashboard', [
            'greeting' => $this->greeting(),
            'userName' => $user?->name ?: $user?->username ?: 'there',
            'todayLabel' => now()->timezone(config('app.timezone'))->format('l, j F Y'),
            'stats' => $stats,
            'myLeads' => $myLeads,
            'newLeads' => $newLeads,
            'callbacks' => $callbacks,
            'todayBookings' => $todayBookings,
            'overdueTasks' => $overdueTasks,
            'recentLeads' => $recentLeads,
            'weekLeadsCount' => $weekLeadsCount,
            'activeClientsCount' => $activeClientsCount,
            'openTasksCount' => $openTasksCount,
            'canLeads' => $canLeads,
            'canClients' => $canClients,
            'canTasks' => $canTasks,
        ]);
    }

    private function greeting(): string
    {
        $hour = (int) now()->timezone(config('app.timezone'))->format('G');

        return match (true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default => 'Good evening',
        };
    }

    /**
     * @return \Illuminate\Support\Collection<int, Enquiry>
     */
    private function todaysBookings()
    {
        $timezone = (string) config('app.timezone', 'Australia/Sydney');

        return Enquiry::query()
            ->where('lead_type', 'calendly')
            ->latest()
            ->limit(40)
            ->get()
            ->filter(function (Enquiry $enquiry) use ($timezone): bool {
                $start = $enquiry->metadata['calendly_start_time'] ?? null;

                if (! is_string($start) || trim($start) === '') {
                    return $enquiry->created_at?->timezone($timezone)->isToday() ?? false;
                }

                try {
                    return Carbon::parse($start)->timezone($timezone)->isToday();
                } catch (\Throwable) {
                    return false;
                }
            })
            ->sortBy(function (Enquiry $enquiry) {
                return $enquiry->metadata['calendly_start_time'] ?? $enquiry->created_at?->toIso8601String();
            })
            ->take(6)
            ->values();
    }
}
