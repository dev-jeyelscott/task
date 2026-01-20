<?php

namespace App\Http\Controllers;

use App\Domain\Task\Actions\DeleteTask;
use App\Domain\Task\Actions\StoreTask;
use App\Domain\Task\Actions\ToggleCompletion;
use App\Domain\Task\Actions\UpdateTask;
use App\Domain\Task\DTOs\CreateTaskData;
use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Task as TaskModel;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Response;
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
            new TaskPriority($request->priority),
            new TaskSeverity($request->severity),
            new CarbonImmutable($request->due_at),
        );

        return Response::redirectToRoute('tasks.edit', $task->id)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task updated successfully.',
            ]);
    }

    public function destroy(TaskModel $task, DeleteTask $action)
    {
        $action->execute($task->id);

        return Response::redirectToRoute('tasks.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task deleted successfully.',
            ]);
    }

    public function toggleCompletion(TaskModel $task, ToggleCompletion $action)
    {
        $action->execute($task->id);

        return Response::redirectToRoute('tasks.show', $task->id)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Task toggled successfully.',
            ]);
    }
}
