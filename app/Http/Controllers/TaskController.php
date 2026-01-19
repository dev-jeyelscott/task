<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('tasks/index', [
            'taskItems' => Inertia::scroll(fn () => Task::latest()->paginate(25)->toResourceCollection(TaskResource::class)),
        ]);
    }

    public function create()
    {
        return Inertia::render('tasks/create');
    }

    public function store(StoreTaskRequest $request)
    {
        Task::create($request->all());

        return Response::redirectTo(route('tasks.index'))
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task created successfully.',
            ]);
    }

    public function show(Task $task)
    {
        return Inertia::render('tasks/show', [
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'priority' => $task->priority,
                'severity' => $task->severity,
                'due_at' => $task->due_at ? Carbon::parse($task->due_at)->format('Y-m-d') : null,
            ],
        ]);
    }

    public function edit(Task $task)
    {
        return Inertia::render('tasks/edit', [
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'priority' => $task->priority,
                'severity' => $task->severity,
                'due_at' => $task->due_at ? Carbon::parse($task->due_at)->format('Y-m-d') : null,
            ],
        ]);
    }

    public function update(Task $task, UpdateTaskRequest $request)
    {
        $task->update($request->all());

        return Response::redirectToRoute('tasks.edit', $task->id)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task updated successfully.',
            ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return Response::redirectToRoute('tasks.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task deleted successfully.',
            ]);
    }
}
