<?php

declare(strict_types=1);

namespace App\Domain\Task\Actions;

use App\Domain\Task\Repositories\TaskRepository;
use Illuminate\Contracts\Events\Dispatcher;

final class CompleteTask
{
    public function __construct(
        private TaskRepository $task_repository,
        private Dispatcher $dispatcher
    ) {}

    public function execute(int $id): void
    {
        $task = $this->task_repository->find($id);

        $task->complete();

        $this->task_repository->save($task);

        foreach ($task->pullDomainEvents() as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}
