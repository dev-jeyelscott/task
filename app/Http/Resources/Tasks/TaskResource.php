<?php

namespace App\Http\Resources\Tasks;

use App\Enums\Priority;
use App\Enums\Severity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TaskResource extends JsonResource
{
    /**
     * Return an array containing the task's details.
     */
    public function toArray(Request $request): array
    {
        $task = $this->resource;

        return [
            'id' => $task->id,
            'title' => Str::limit($task->title, 30, '...'),
            'description' => $task->description ? Str::limit($task->description, 50, '...') : null,
            'priority' => Priority::from($task->priority),
            'severity' => Severity::from($task->severity),
            'is_completed' => $task->is_completed ? true : false,
            'completed_at' => $task->completed_at ? Carbon::parse($task->completed_at)->format('Y-m-d') : null,
            'due_at' => $task->due_at ? Carbon::parse($task->due_at)->format('Y-m-d') : null,
            'created_at' => $task->created_at->diffForHumans(),
            'updated_at' => $task->updated_at->diffForHumans(),
        ];
    }
}
