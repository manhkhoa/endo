<?php

namespace App\Http\Requests\Task;

use App\Models\Option;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:2|max:200',
            'start_date' => 'required|date|before_or_equal:due_date',
            'due_date' => 'required|date',
            'due_time' => 'nullable|date_format:H:i:s',
            'category' => 'uuid',
            'priority' => 'uuid',
            'description' => 'min:2|max:10000',
        ];
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $uuid = $this->route('task');

            $taskCategory = $this->category ? Option::byTeam()->whereType('task_category')->whereUuid($this->category)->getOrFail(trans('task.category.category'), 'category') : null;

            $taskPriority = $this->priority ? Option::byTeam()->whereType('task_priority')->whereUuid($this->priority)->getOrFail(trans('task.priority.priority'), 'priority') : null;

            $this->merge([
                'task_category_id' => $taskCategory?->id,
                'task_priority_id' => $taskPriority?->id,
            ]);
        });
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => __('task.props.title'),
            'start_date' => __('task.props.start_date'),
            'due_date' => __('task.props.due_date'),
            'due_time' => __('task.props.due_time'),
            'category' => __('task.category.category'),
            'priority' => __('task.priority.priority'),
            'description' => __('task.props.description'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
