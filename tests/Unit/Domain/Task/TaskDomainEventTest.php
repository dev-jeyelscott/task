<?php

use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use App\Domain\Task\Events\TaskCompleted;
use App\Domain\Task\Models\Task;

test('task completion records events', function () {
    $task = Task::reconstitute(
        id: 1,
        title: 'Test Task',
        description: 'This is a test task',
        isCompleted: false,
        completedAt: null,
        priority: TaskPriority::low(),
        severity: TaskSeverity::low(),
        dueAt: null
    );

    $task->complete();

    $events = $task->pullDomainEvents();

    $this->assertCount(1, $events);
    $this->assertInstanceOf(TaskCompleted::class, $events[0]);
    $this->assertSame(1, $events[0]->taskId);
});
