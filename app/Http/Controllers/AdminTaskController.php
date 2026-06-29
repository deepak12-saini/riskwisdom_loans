<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTaskController extends Controller
{
    public function index(Request $request): View
    {
        $filter = (string) $request->query('filter', 'open');

        $query = Task::query()
            ->with(['client', 'assignedUser'])
            ->latest();

        match ($filter) {
            'overdue' => $query->overdue(),
            'done' => $query->where('status', 'done'),
            'all' => null,
            default => $query->open(),
        };

        $tasks = $query->paginate(25)->withQueryString();

        $stats = [
            'open' => Task::query()->open()->count(),
            'overdue' => Task::query()->overdue()->count(),
            'done' => Task::query()->where('status', 'done')->count(),
            'total' => Task::count(),
        ];

        $headings = [
            'open' => 'Open tasks',
            'overdue' => 'Overdue tasks',
            'done' => 'Completed tasks',
            'all' => 'All tasks',
        ];

        $pageHeading = $headings[$filter] ?? 'Tasks';

        return view('admin.tasks.index', compact('tasks', 'stats', 'filter', 'pageHeading'));
    }

    public function store(Request $request, Client $client): RedirectResponse
    {
        $validated = $this->validateTask($request);
        $validated['client_id'] = $client->id;

        $client->tasks()->create($validated);

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Task added.');
    }

    public function update(Request $request, Client $client, Task $task): RedirectResponse
    {
        abort_unless($task->client_id === $client->id, 404);

        $validated = $this->validateTask($request);
        $validated = $this->applyStatusTimestamps($validated, $task);

        $task->update($validated);

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Task updated.');
    }

    public function close(Client $client, Task $task): RedirectResponse
    {
        abort_unless($task->client_id === $client->id, 404);

        $task->update([
            'status' => 'done',
            'closed_at' => now(),
        ]);

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Task closed.');
    }

    public function destroy(Client $client, Task $task): RedirectResponse
    {
        abort_unless($task->client_id === $client->id, 404);

        $task->delete();

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Task removed.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateTask(Request $request): array
    {
        $statuses = array_keys(config('riskwisdom.task_statuses', []));
        $owners = array_keys(config('riskwisdom.task_owners', []));
        $priorities = array_keys(config('riskwisdom.task_priorities', []));

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'owner' => ['required', 'string', 'in:'.implode(',', $owners)],
            'status' => ['required', 'string', 'in:'.implode(',', $statuses)],
            'priority' => ['required', 'string', 'in:'.implode(',', $priorities)],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function applyStatusTimestamps(array $validated, Task $task): array
    {
        if ($validated['status'] === 'done' && $task->status !== 'done') {
            $validated['closed_at'] = now();
        }

        if ($validated['status'] !== 'done') {
            $validated['closed_at'] = null;
        }

        return $validated;
    }
}
