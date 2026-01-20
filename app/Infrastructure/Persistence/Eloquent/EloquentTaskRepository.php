<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use App\Domain\Task\Models\Task;
use App\Domain\Task\Repositories\TaskRepository;
use App\Models\Task as EloquentTask;

final class EloquentTaskRepository implements TaskRepository
{
    public function find(int $id): Task
    {
        $task = EloquentTask::findOrFail($id);

        return new Task(
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

    public function store(Task $task): void
    {
        EloquentTask::create([
            'title' => $task->title(),
            'description' => $task->description(),
            'is_completed' => $task->isCompleted(),
            'completed_at' => $task->completedAt(),
            'due_at' => $task->dueAt(),
            'priority' => $task->priority->value(),
            'severity' => $task->severity->value(),
        ]);
    }

    public function update(Task $task): void
    {
        $eloquentTask = EloquentTask::findOrFail($task->id());

        $eloquentTask->title = $task->title();
        $eloquentTask->description = $task->description();
        $eloquentTask->due_at = $task->dueAt();
        $eloquentTask->priority = $task->priority()->value();
        $eloquentTask->severity = $task->severity()->value();

        $eloquentTask->update();
    }

    public function deleteById(int $id): void
    {
        $eloquentTask = EloquentTask::findOrFail($id);

        $eloquentTask->delete();
    }

    public function toggleCompletion(Task $task): void
    {
        $eloquentTask = EloquentTask::findOrFail($task->id());

        $eloquentTask->update([
            'is_completed' => $task->isCompleted(),
            'completed_at' => $task->completedAt(),
        ]);
    }
}
