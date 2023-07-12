<?php

namespace App\Actions\Task;

use App\Helpers\CalHelper;
use Illuminate\Http\Request;

class FormatTaskParam
{
    public function execute(Request $request): array
    {
        $dueDate = $request->due_date ?: null;
        $dueTime = $request->due_date && $request->due_time ? CalHelper::storeDateTime($dueDate.' '.$request->due_time)->toTimeString() : null;

        $formatted = [
            'title' => $request->title,
            'start_date' => $request->start_date,
            'due_date' => $dueDate,
            'due_time' => $dueTime,
            'task_category_id' => $request->task_category_id,
            'task_priority_id' => $request->task_priority_id,
            'description' => clean($request->description),
        ];

        return $formatted;
    }
}
