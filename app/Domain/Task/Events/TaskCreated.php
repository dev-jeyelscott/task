<?php

declare(strict_types=1);

namespace App\Domain\Task\Events;

use App\Domain\Shared\DomainEvent;
use DateTimeImmutable;

final class TaskCreated implements DomainEvent
{
    private DateTimeImmutable $occuredOn;

    public function __construct(
        public readonly int $taskId
    ) {
        $this->occuredOn = new DateTimeImmutable;
    }

    public function occuredOn(): DateTimeImmutable
    {
        return $this->occuredOn;
    }
}
