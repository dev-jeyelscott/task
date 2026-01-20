<?php

namespace App\Domain\Task\Actions;

use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use App\Domain\Task\Repositories\TaskRepository;
use Carbon\CarbonImmutable;

final class UpdateTask
{
    public function __construct(
        private TaskRepository $task_repository
    ) {}

    public function execute(
        int $id,
        string $title,
        ?string $description,
        TaskPriority $priority,
        TaskSeverity $severity,
        CarbonImmutable $due_at
    ): void {
        $task = $this->task_repository->find($id);

        $task->rename($title);
        $task->changeDescription($description);
        $task->changePriority($priority);
        $task->changeSeverity($severity);
        $task->reschedule($due_at);

        $this->task_repository->update($task);
    }
}
