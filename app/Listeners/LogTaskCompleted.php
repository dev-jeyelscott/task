<?php

namespace App\Listeners;

final class LogTaskCompleted
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        logger()->info('Task completed: ', ['task' => $event->taskId]);
    }
}
