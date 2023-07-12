<?php

namespace App\Services\Task;

use App\Helpers\CalHelper;
use App\Models\Employee\Employee;
use App\Models\Task\Checklist;
use App\Models\Task\Task;
use Illuminate\Http\Request;

class ChecklistService
{
    public function findByUuidOrFail(Task $task, string $uuid): Checklist
    {
        return Checklist::whereTaskId($task->id)->whereUuid($uuid)->getOrFail(trans('task.checklist.checklist'));
    }

    public function create(Request $request, Task $task): Checklist
    {
        \DB::beginTransaction();

        $checklist = Checklist::forceCreate($this->formatParams($request, $task));

        \DB::commit();

        return $checklist;
    }

    private function formatParams(Request $request, Task $task, ?Checklist $checklist = null): array
    {
        $dueDate = $request->due_date ?: null;
        $dueTime = $request->due_date && $request->due_time ? CalHelper::storeDateTime($dueDate.' '.$request->due_time)->toTimeString() : null;

        $formatted = [
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $dueDate,
            'due_time' => $dueTime,
        ];

        $employee = Employee::auth()->first();

        if (! $checklist) {
            $formatted['task_id'] = $task->id;
            $formatted['owner_id'] = $employee?->id;
        }

        return $formatted;
    }

    public function update(Request $request, Task $task, Checklist $checklist): void
    {
        \DB::beginTransaction();

        $checklist->forceFill($this->formatParams($request, $task, $checklist))->save();

        \DB::commit();
    }

    public function deletable(Task $task, Checklist $checklist): void
    {
        //
    }
}
