<?php

declare(strict_types=1);

namespace App\Domain\Task\Models;

use App\Domain\Task\DTOs\CreateTaskData;
use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use Carbon\CarbonImmutable;

class Task
{
    public ?int $id;

    public string $title;

    public ?string $description;

    public bool $is_completed;

    public ?CarbonImmutable $completed_at;

    public TaskPriority $priority;

    public TaskSeverity $severity;

    public ?CarbonImmutable $due_at;

    public function __construct(
        ?int $id,
        string $title,
        ?string $description,
        bool $is_completed,
        ?CarbonImmutable $completed_at,
        TaskPriority $priority,
        TaskSeverity $severity,
        ?CarbonImmutable $due_at
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

    public function rename(string $title): void
    {
        if (trim($title) === '') {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        $this->title = $title;
    }

    public function changeDescription(string $description): void
    {
        $this->description = $description;
    }

    public function changePriority(TaskPriority $priority): void
    {
        $this->priority = $priority;
    }

    public function changeSeverity(TaskSeverity $severity): void
    {
        $this->severity = $severity;
    }

    public function reschedule(CarbonImmutable $due_at): void
    {
        if ($due_at->isBefore(CarbonImmutable::today())) {
            throw new \InvalidArgumentException('Due date cannot be in the past');
        }

        $this->due_at = $due_at;
    }

    public function toggleCompletion(): void
    {
        $this->is_completed = ! $this->is_completed;

        $this->completed_at = $this->is_completed ? CarbonImmutable::now() : null;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function isCompleted(): bool
    {
        return $this->is_completed;
    }

    public function completedAt(): ?CarbonImmutable
    {
        return $this->completed_at;
    }

    public function priority(): TaskPriority
    {
        return $this->priority;
    }

    public function severity(): TaskSeverity
    {
        return $this->severity;
    }

    public function dueAt(): ?CarbonImmutable
    {
        return $this->due_at;
    }
}
