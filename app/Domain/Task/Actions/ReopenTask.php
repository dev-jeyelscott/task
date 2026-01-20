<?php

declare(strict_types=1);

namespace App\Domain\Task\Actions;

use App\Domain\Task\Repositories\TaskRepository;

final class ReopenTask
{
    public function __construct(
        private TaskRepository $task_repository
    ) {}

    public function execute(int $id): void
    {
        $task = $this->task_repository->find($id);

        $task->reopen();

        $this->task_repository->save($task);
    }
}
