<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Services\DocumentSigningManager;

class AdminClientController extends Controller
{
    public function index(Request $request): View
    {
        $filter = (string) $request->query('filter', 'active');

        $query = Client::query()
            ->with('enquiry')
            ->withCount(['tasks as open_tasks_count' => fn ($q) => $q->open()])
            ->latest();

        match ($filter) {
            'archived' => $query->where('status', 'archived'),
            'all' => null,
            default => $query->where('status', 'active'),
        };

        $clients = $query->paginate(15)->withQueryString();

        $stats = [
            'active' => Client::query()->where('status', 'active')->count(),
            'open_tasks' => \App\Models\Task::query()->open()->count(),
            'overdue_tasks' => \App\Models\Task::query()->overdue()->count(),
            'archived' => Client::query()->where('status', 'archived')->count(),
        ];

        $headings = [
            'active' => 'Active client files',
            'archived' => 'Archived client files',
            'all' => 'All client files',
        ];

        $pageHeading = $headings[$filter] ?? 'Client files';

        return view('admin.clients.index', compact('clients', 'stats', 'filter', 'pageHeading'));
    }

    public function create(): View
    {
        $brokers = User::query()->where('is_admin', true)->orderBy('username')->get();

        return view('admin.clients.create', compact('brokers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateClient($request);

        $client = Client::query()->create($validated);

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Client file created.');
    }

    public function show(Client $client, DocumentSigningManager $signing): View
    {
        $client->load([
            'enquiry',
            'assignedUser',
            'tasks' => fn ($q) => $q->latest(),
            'documents' => fn ($q) => $q->latest(),
        ]);

        $brokers = User::query()->where('is_admin', true)->orderBy('username')->get();
        $signingConfigured = $signing->active()->isConfigured();
        $signingProviderLabel = $signing->active()->providerLabel();

        return view('admin.clients.show', compact('client', 'brokers', 'signingConfigured', 'signingProviderLabel'));
    }

    public function edit(Client $client): View
    {
        $brokers = User::query()->where('is_admin', true)->orderBy('username')->get();

        return view('admin.clients.edit', compact('client', 'brokers'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $client->update($this->validateClient($request, $client));

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Client file updated.');
    }

    public function archive(Client $client): RedirectResponse
    {
        $client->update(['status' => 'archived']);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client file archived.');
    }

    public function restore(Client $client): RedirectResponse
    {
        $client->update(['status' => 'active']);

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Client file restored to active.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateClient(Request $request, ?Client $client = null): array
    {
        $statuses = array_keys(config('riskwisdom.client_statuses', []));
        $loanTypes = array_keys(config('riskwisdom.loan_types', []));
        $states = array_keys(config('riskwisdom.states', []));

        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'loan_type' => ['nullable', 'string', 'in:'.implode(',', $loanTypes)],
            'state' => ['nullable', 'string', 'in:'.implode(',', $states)],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];

        if ($client !== null) {
            $rules['status'] = ['required', 'string', 'in:'.implode(',', $statuses)];
        }

        $validated = $request->validate($rules);

        if ($client === null) {
            $validated['status'] = 'active';
        }

        return $validated;
    }
}
