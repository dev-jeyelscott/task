<?php

namespace App\Http\Controllers;

use App\Domain\Task\Actions\StoreTask;
use App\Domain\Task\DTOs\CreateTaskData;
use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Task as TaskModel;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('tasks/index', [
            'taskItems' => Inertia::scroll(fn() => TaskModel::latest()->paginate(25)->toResourceCollection(TaskResource::class)),
        ]);
    }

    public function create()
    {
        return Inertia::render('tasks/create');
    }

    public function store(StoreTaskRequest $request, StoreTask $action)
    {
        $data = new CreateTaskData(
            $request->title,
            $request->description,
            new TaskPriority($request->priority),
            new TaskSeverity($request->severity),
            new CarbonImmutable($request->due_at),
        );

        $action->execute($data);

        return Response::redirectToRoute('tasks.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task created successfully.',
            ]);
    }

    public function show(TaskModel $task)
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

    public function edit(TaskModel $task)
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

    public function update(TaskModel $task, UpdateTaskRequest $request)
    {
        $task->update($request->all());

        return Response::redirectToRoute('tasks.edit', $task->id)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task updated successfully.',
            ]);
    }

    public function destroy(TaskModel $task)
    {
        $task->delete();

        return Response::redirectToRoute('tasks.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task deleted successfully.',
            ]);
    }
}
