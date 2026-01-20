<?php

declare(strict_types=1);

namespace App\Domain\Task\Models;

use App\Domain\Shared\DomainEvent;
use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use App\Domain\Task\Events\TaskCompleted;
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

    /** @var DomainEvent[] */
    private array $recordedEvents = [];

    /**
     * Constructs a new task.
     *
     * @param  int|null  $id  The task id.
     * @param  string  $title  The task title.
     * @param  string|null  $description  The task description.
     * @param  bool  $isCompleted  Whether the task is completed.
     * @param  CarbonImmutable|null  $completedAt  The task completion date.
     * @param  TaskPriority  $priority  The task priority.
     * @param  TaskSeverity  $severity  The task severity.
     * @param  CarbonImmutable|null  $dueAt  The task due date.
     *
     * @throws \InvalidArgumentException If title is empty, completed at is null, or due date is in the past.
     */
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

    /**
     * Reconstitute a task.
     *
     * @param  int  $id  The task id.
     * @param  string  $title  The task title.
     * @param  string|null  $description  The task description.
     * @param  bool  $isCompleted  Whether the task is completed.
     * @param  CarbonImmutable|null  $completedAt  The task completion date.
     * @param  TaskPriority  $priority  The task priority.
     * @param  TaskSeverity  $severity  The task severity.
     * @param  CarbonImmutable|null  $dueAt  The task due date.
     */
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

    /**
     * Create a new task.
     *
     * @param  string  $title  The task title.
     * @param  string|null  $description  The task description.
     * @param  TaskPriority  $priority  The task priority.
     * @param  TaskSeverity  $severity  The task severity.
     * @param  CarbonImmutable|null  $dueAt  The task due date.
     */
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

    /**
     * Rename the task.
     *
     *
     * @throws \InvalidArgumentException
     */
    public function rename(string $title): void
    {
        if (trim($title) === '') {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        $this->title = $title;
    }

    /**
     * Change the description of the task.
     */
    public function changeDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Change the priority of the task.
     */
    public function changePriority(TaskPriority $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Change the severity of the task.
     */
    public function changeSeverity(TaskSeverity $severity): void
    {
        $this->severity = $severity;
    }

    /**
     * Reschedule the task.
     *
     *
     * @throws \InvalidArgumentException
     */
    public function reschedule(CarbonImmutable $dueAt): void
    {
        if ($dueAt->isBefore(CarbonImmutable::today())) {
            throw new \InvalidArgumentException('Due date cannot be in the past');
        }

        $this->dueAt = $dueAt;
    }

    /*
     * Clear the due date.
     *
     */
    public function clearDueDate(): void
    {
        $this->dueAt = null;
    }

    /**
     * Records the given domain event.
     */
    private function recordThat(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    /**
     * Pull the domain events recorded for this task.
     *
     * @return array The domain events recorded for this task.
     */
    public function pullDomainEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }

    /**
     * Mark the task as completed.
     *
     * @throws \InvalidArgumentException If the task is already completed.
     */
    public function complete(): void
    {
        if ($this->isCompleted) {
            throw new \InvalidArgumentException('Task is already completed');
        }

        $this->isCompleted = true;
        $this->completedAt = CarbonImmutable::now();

        $this->recordThat(new TaskCompleted($this->id));
    }

    /**
     * Reopen a completed task.
     *
     * @throws \InvalidArgumentException If the task is not completed.
     */
    public function reopen(): void
    {
        if (! $this->isCompleted) {
            throw new \InvalidArgumentException('Task is not completed');
        }

        $this->isCompleted = false;
        $this->completedAt = null;
    }

    /**
     * Get the id of the task.
     */
    public function id(): ?int
    {
        return $this->id;
    }

    /**
     * Get the title of the task.
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get the description of the task.
     */
    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * Determine if the task has been completed.
     *
     * @return bool True if the task is completed, false otherwise.
     */
    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    /**
     * Get the completion date of the task.
     */
    public function completedAt(): ?CarbonImmutable
    {
        return $this->completedAt;
    }

    /**
     * Get the priority of the task.
     *
     * @return TaskPriority The priority of the task.
     */
    public function priority(): TaskPriority
    {
        return $this->priority;
    }

    /**
     * Get the severity of the task.
     *
     * @return TaskSeverity The severity of the task.
     */
    public function severity(): TaskSeverity
    {
        return $this->severity;
    }

    /**
     * Get the due date of the task.
     */
    public function dueAt(): ?CarbonImmutable
    {
        return $this->dueAt;
    }
}
