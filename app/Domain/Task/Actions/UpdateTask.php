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
        string $priority,
        string $severity,
        ?string $dueAt
    ): void {
        $task = $this->task_repository->find($id);

        $task->rename($title);
        $task->changeDescription($description);
        $task->changePriority(new TaskPriority($priority));
        $task->changeSeverity(new TaskSeverity($severity));

        if ($dueAt !== null) {
            $task->reschedule(new CarbonImmutable($dueAt));
        } else {
            $task->clearDueDate();
        }

        $this->task_repository->save($task);
    }
}
