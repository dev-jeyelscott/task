<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use App\Domain\Task\Models\Task as TaskModel;
use App\Domain\Task\Repositories\TaskRepository;
use App\Models\Task as EloquentTask;

final class EloquentTaskRepository implements TaskRepository
{
    public function find(int $id): TaskModel
    {
        $task = EloquentTask::findOrFail($id);

        return TaskModel::reconstitute(
            $task->id,
            $task->title,
            $task->description,
            (bool) $task->is_completed,
            $task->completed_at,
            new TaskPriority($task->priority),
            new TaskSeverity($task->severity),
            $task->due_at,
        );
    }

    public function store(TaskModel $taskModel): int
    {
        $eloquentTask = EloquentTask::create([
            'title' => $taskModel->title(),
            'description' => $taskModel->description(),
            'is_completed' => $taskModel->isCompleted(),
            'completed_at' => $taskModel->completedAt(),
            'due_at' => $taskModel->dueAt(),
            'priority' => $taskModel->priority()->value(),
            'severity' => $taskModel->severity()->value(),
        ]);

        return $eloquentTask->id;
    }

    public function save(TaskModel $taskModel): void
    {
        $eloquentTask = EloquentTask::findOrFail($taskModel->id());

        $eloquentTask->fill([
            'title' => $taskModel->title(),
            'description' => $taskModel->description(),
            'due_at' => $taskModel->dueAt(),
            'priority' => $taskModel->priority()->value(),
            'severity' => $taskModel->severity()->value(),
            'is_completed' => $taskModel->isCompleted(),
            'completed_at' => $taskModel->completedAt(),
        ]);

        $eloquentTask->save();
    }

    public function deleteById(int $id): void
    {
        $eloquentTask = EloquentTask::findOrFail($id);

        $eloquentTask->delete();
    }
}
