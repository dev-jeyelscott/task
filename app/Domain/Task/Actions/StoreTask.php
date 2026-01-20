<?php

declare(strict_types=1);

namespace App\Domain\Task\Actions;

use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use App\Domain\Task\Models\Task as TaskModel;
use App\Domain\Task\Repositories\TaskRepository;
use Carbon\CarbonImmutable;

final class StoreTask
{
    public function __construct(
        private TaskRepository $task_repository
    ) {}

    public function execute(
        string $title,
        ?string $description,
        string $priority,
        string $severity,
        ?string $dueAt
    ): int {
        $task = TaskModel::create(
            $title,
            $description,
            new TaskPriority($priority),
            new TaskSeverity($severity),
            $dueAt !== null ? new CarbonImmutable($dueAt) : null,
        );

        $taskId = $this->task_repository->store($task);

        return $taskId;
    }
}
