<?php

namespace App\Domain\Task\Repositories;

use App\Domain\Task\Models\Task;

interface TaskRepository
{
    public function find(int $id);

    public function store(Task $task): void;
}
