<?php

namespace App\Providers;

use App\Domain\Task\Repositories\TaskRepository;
use App\Infrastructure\Persistence\Eloquent\EloquestTaskRepository;
use Illuminate\Support\ServiceProvider;

final class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TaskRepository::class,
            EloquestTaskRepository::class
        );
    }
}
