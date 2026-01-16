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
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => Str::limit($this->title, 30, '...'),
            'description' => $this->description ? Str::limit($this->description, 50, '...') : null,
            'priority' => Priority::from($this->priority),
            'severity' => Severity::from($this->severity),
            'is_completed' => $this->is_completed ? true : false,
            'completed_at' => $this->completed_at ? Carbon::parse($this->completed_at)->format('M d Y') : null,
            'due_at' => $this->due_at ? Carbon::parse($this->due_at)->format('M d Y') : null,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
