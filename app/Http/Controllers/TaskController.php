<?php

namespace App\Http\Controllers;

use App\Domain\Task\Actions\CompleteTask;
use App\Domain\Task\Actions\DeleteTask;
use App\Domain\Task\Actions\ReopenTask;
use App\Domain\Task\Actions\StoreTask;
use App\Domain\Task\Actions\UpdateTask;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Task as TaskModel;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('tasks/index', [
            'taskItems' => Inertia::scroll(fn () => TaskModel::latest()->paginate(25)->toResourceCollection(TaskResource::class)),
        ]);
    }

    public function create()
    {
        return Inertia::render('tasks/create');
    }

    public function store(StoreTaskRequest $request, StoreTask $action)
    {
        $id = $action->execute(
            $request->title,
            $request->description,
            $request->priority,
            $request->severity,
            $request->due_at,
        );

        return redirect()->to(route('tasks.show', $id))
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task created successfully.',
            ]);
    }

    public function show(TaskModel $task)
    {
        return Inertia::render('tasks/show', [
            'task' => (new TaskResource($task))->resolve(),
        ]);
    }

    public function edit(TaskModel $task)
    {
        return Inertia::render('tasks/edit', [
            'task' => new TaskResource($task)->resolve(),
        ]);
    }

    public function update(TaskModel $task, UpdateTaskRequest $request, UpdateTask $action)
    {
        $action->execute(
            $task->id,
            $request->title,
            $request->description,
            $request->priority,
            $request->severity,
            $request->due_at,
        );

        return redirect()->to(route('tasks.show', $task->id))
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task updated successfully.',
            ]);
    }

    public function destroy(TaskModel $task, DeleteTask $action)
    {
        $action->execute($task->id);

        return redirect()->to(route('tasks.index'))
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task deleted successfully.',
            ]);
    }

    public function complete(TaskModel $task, CompleteTask $action)
    {
        $action->execute($task->id);

        return redirect()->to(route('tasks.show', $task->id))
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task completed successfully.',
            ]);
    }

    public function reopen(TaskModel $task, ReopenTask $action)
    {
        $action->execute($task->id);

        return redirect()->to(route('tasks.show', $task->id))
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task reopened successfully.',
            ]);
    }
}
