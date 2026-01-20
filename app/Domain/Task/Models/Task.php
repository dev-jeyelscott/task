<?php

declare(strict_types=1);

namespace App\Domain\Task\Models;

use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use Carbon\CarbonImmutable;

class Task
{
    private ?int $id;

    private string $title;

    private ?string $description;

    private bool $isCompleted;

    private ?CarbonImmutable $completedAt;

    private TaskPriority $priority;

    private TaskSeverity $severity;

    private ?CarbonImmutable $dueAt;

    private function __construct(
        ?int $id,
        string $title,
        ?string $description,
        bool $isCompleted,
        ?CarbonImmutable $completedAt,
        TaskPriority $priority,
        TaskSeverity $severity,
        ?CarbonImmutable $dueAt
    ) {
        if (trim($title) === '') {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        if ($isCompleted && $completedAt === null) {
            throw new \InvalidArgumentException('Completed at cannot be null');
        }

        if ($dueAt !== null && $dueAt->isBefore(CarbonImmutable::today())) {
            throw new \InvalidArgumentException('Due date cannot be in the past');
        }

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->isCompleted = $isCompleted;
        $this->completedAt = $completedAt;
        $this->priority = $priority;
        $this->severity = $severity;
        $this->dueAt = $dueAt;
    }

    public static function reconstitute(
        int $id,
        string $title,
        ?string $description,
        bool $isCompleted,
        ?CarbonImmutable $completedAt,
        TaskPriority $priority,
        TaskSeverity $severity,
        ?CarbonImmutable $dueAt
    ): self {
        return new self(
            $id,
            $title,
            $description,
            $isCompleted,
            $completedAt,
            $priority,
            $severity,
            $dueAt
        );
    }

    public static function create(
        string $title,
        ?string $description,
        TaskPriority $priority,
        TaskSeverity $severity,
        ?CarbonImmutable $dueAt
    ): self {
        return new self(
            null,
            $title,
            $description,
            false,
            null,
            $priority,
            $severity,
            $dueAt
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

    public function reschedule(CarbonImmutable $dueAt): void
    {
        if ($dueAt->isBefore(CarbonImmutable::today())) {
            throw new \InvalidArgumentException('Due date cannot be in the past');
        }

        $this->dueAt = $dueAt;
    }

    public function clearDueDate(): void
    {
        $this->dueAt = null;
    }

    public function complete(): void
    {
        if ($this->isCompleted) {
            throw new \InvalidArgumentException('Task is already completed');
        }

        $this->isCompleted = true;
        $this->completedAt = CarbonImmutable::now();
    }

    public function reopen(): void
    {
        if (! $this->isCompleted) {
            throw new \InvalidArgumentException('Task is not completed');
        }

        $this->isCompleted = false;
        $this->completedAt = null;
    }

    public function id(): ?int
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
        return $this->isCompleted;
    }

    public function completedAt(): ?CarbonImmutable
    {
        return $this->completedAt;
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
        return $this->dueAt;
    }
}
