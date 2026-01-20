<?php

declare(strict_types=1);

namespace App\Domain\Task\DTOs;

use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;

final class CreateTaskData
{
    public function __construct(
        public string $title,
        public ?string $description,
        public TaskPriority $priority,
        public TaskSeverity $severity,
        public ?string $due_at
    ) {}
}
