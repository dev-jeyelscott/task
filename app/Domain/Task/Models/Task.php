<?php

declare(strict_types=1);

namespace App\Domain\Task\Models;

use App\Domain\Task\DTOs\CreateTaskData;
use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;

class Task
{
    public ?int $id;
    public string $title;
    public ?string $description;
    public bool $is_completed;
    public ?string $completed_at;
    public TaskPriority $priority;
    public TaskSeverity $severity;
    public ?string $due_at;

    public function __construct(
        ?int $id,
        string $title,
        ?string $description,
        bool $is_completed,
        ?string $completed_at,
        TaskPriority $priority,
        TaskSeverity $severity,
        ?string $due_at
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->is_completed = $is_completed;
        $this->completed_at = $completed_at;
        $this->priority = $priority;
        $this->severity = $severity;
        $this->due_at = $due_at;
    }

    public static function create(CreateTaskData $data): self
    {
        return new self(
            null,
            $data->title,
            $data->description,
            false,
            null,
            $data->priority,
            $data->severity,
            $data->due_at
        );
    }
}
