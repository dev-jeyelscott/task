<?php

use App\Domain\Task\Entities\TaskPriority;
use App\Domain\Task\Entities\TaskSeverity;
use App\Domain\Task\Models\Task;
use Carbon\CarbonImmutable;

test('task can be reconstituted from persistence', function () {
    $task = Task::reconstitute(
        1,
        'Test Task',
        'This is a test task',
        false,
        null,
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    expect($task->id())->toBe(1);
    expect($task->title())->toBe('Test Task');
    expect($task->description())->toBe('This is a test task');
    expect($task->isCompleted())->toBe(false);
    expect($task->completedAt())->toBeNull();
    expect($task->priority()->value())->toBe(TaskPriority::low()->value());
    expect($task->severity()->value())->toBe(TaskSeverity::low()->value());
    expect($task->dueAt())->toBeNull();
});

test('task can be created', function () {
    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    expect($task->title())->toBe('Test Task');
    expect($task->description())->toBe('This is a test task');
    expect($task->priority()->value())->toBe(TaskPriority::low()->value());
    expect($task->severity()->value())->toBe(TaskSeverity::low()->value());
    expect($task->dueAt())->toBeNull();
});

test('task cannot be created without a title', function () {
    Task::create(
        '',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );
})->throws(\InvalidArgumentException::class, 'Title cannot be empty');

test('task can be renamed', function () {
    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    $task->rename('New Test Task');

    expect($task->title())->toBe('New Test Task');
});

test('task cannot be renamed to an empty title', function () {
    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    $task->rename('');
})->throws(\InvalidArgumentException::class, 'Title cannot be empty');

test('task description can be changed', function () {
    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    $task->changeDescription('New description');

    expect($task->description())->toBe('New description');
});

test('task priority can be changed', function () {
    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    $task->changePriority(TaskPriority::high());

    expect($task->priority()->value())->toBe(TaskPriority::high()->value());
});

test('task severity can be changed', function () {
    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    $task->changeSeverity(TaskSeverity::high());

    expect($task->severity()->value())->toBe(TaskSeverity::high()->value());
});

test('task cannot create a task with a due date in the past', function () {
    Task::create(
        'Test Task',
        'This is test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        CarbonImmutable::now()->subDay(1)
    );
})->throws(\InvalidArgumentException::class, 'Due date cannot be in the past');

test('task due date can be rescheduled', function () {
    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    $oneWeekFromNow = CarbonImmutable::create(2030, 1, 1);

    $task->reschedule($oneWeekFromNow);

    expect($task->dueAt())->toBeInstanceOf(CarbonImmutable::class);
    expect($task->dueAt()->get('year'))->toBe(2030);
    expect($task->dueAt()->get('month'))->toBe(1);
    expect($task->dueAt()->get('day'))->toBe(1);
});

test('task cannot be rescheduled with a due date in the past', function () {
    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    $task->reschedule(CarbonImmutable::now()->subDay(1));
})->throws(\InvalidArgumentException::class, 'Due date cannot be in the past');

test('task due date can be cleared', function () {

    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        CarbonImmutable::now()
    );

    $task->clearDueDate();

    expect($task->dueAt())->toBeNull();
});

test('task can be completed', function () {
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

    expect($task->isCompleted())->toBe(true);
    expect($task->completedAt())->toBeInstanceOf(CarbonImmutable::class);
});

test('task cannot be completed if already completed', function () {
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

    $task->complete();
})->throws(\InvalidArgumentException::class, 'Task is already completed');

test('task cannot be reopened if not completed', function () {
    $task = Task::create(
        'Test Task',
        'This is a test task',
        TaskPriority::low(),
        TaskSeverity::low(),
        null
    );

    $task->reopen();
})->throws(\InvalidArgumentException::class, 'Task is not completed');

test('task can be reopened', function () {
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
    $task->reopen();

    expect($task->isCompleted())->toBe(false);
    expect($task->completedAt())->toBeNull();
});
