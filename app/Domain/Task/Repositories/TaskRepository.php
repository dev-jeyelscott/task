<?php

namespace App\Domain\Task\Repositories;

use App\Domain\Task\Models\Task;

interface TaskRepository
{
    public function find(int $id): ?Task;

    public function store(Task $task): int;

    public function save(Task $task): void;

    public function deleteById(int $id): void;
}
