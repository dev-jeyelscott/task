<?php

declare(strict_types=1);

namespace App\Domain\Task\Actions;

use App\Domain\Task\DTOs\CreateTaskData;
use App\Domain\Task\Models\Task;
use App\Domain\Task\Repositories\TaskRepository;

final class StoreTask
{
    public function __construct(
        private TaskRepository $task_repository
    ) {}

    public function execute(CreateTaskData $data): void
    {
        $task = Task::create($data);

        $this->task_repository->store($task);
    }
}
