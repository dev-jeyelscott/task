<?php

namespace App\Http\Requests\Tasks;

use App\Enums\Priority;
use App\Enums\Severity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['string', 'max:255'],
            'priority' => [Rule::enum(Priority::class)],
            'severity' => [Rule::enum(Severity::class)],
            'due_at' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'priority' => 'priority',
            'severity' => 'severity',
            'due_at' => 'due date',
        ];
    }
}
