<?php

namespace App\Services\Task;

use App\Models\Employee\Employee;
use App\Models\Task\Member;
use App\Models\Task\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MemberService
{
    public function findByUuidOrFail(Task $task, string $uuid): Member
    {
        return Member::whereTaskId($task->id)->whereUuid($uuid)->getOrFail(trans('task.member.member'));
    }

    public function create(Request $request, Task $task): void
    {
        if (in_array($task->owner->uuid, $request->employees)) {
            throw ValidationException::withMessages(['message' => trans('task.member.could_not_perform_if_owner_selected')]);
        }

        $employee = Employee::auth()->first();

        if (in_array($employee->uuid, $request->employees)) {
            throw ValidationException::withMessages(['message' => trans('task.member.could_not_perform_self_action')]);
        }

        \DB::beginTransaction();

        foreach ($request->employee_ids as $employee) {
            $member = Member::firstOrCreate([
                'task_id' => $task->id,
                'employee_id' => $employee,
            ]);

            $meta = $member->meta;
            $meta['permission'] = [
                'manage_member' => $request->boolean('manage_member'),
                'manage_checklist' => $request->boolean('manage_checklist'),
                'manage_media' => $request->boolean('manage_media'),
                'manage_completion' => $request->boolean('manage_completion'),
                'manage_task_list' => $request->boolean('manage_task_list'),
            ];
            $member->meta = $meta;
            $member->save();
        }

        \DB::commit();
    }

    public function deletable(Task $task, Member $member): void
    {
        if ($task->owner_id == $member->employee_id) {
            throw ValidationException::withMessages(['message' => trans('task.member.could_not_perform_if_owner_selected')]);
        }

        $employee = Employee::auth()->first();

        if ($employee->id == $member->employee_id) {
            throw ValidationException::withMessages(['message' => trans('task.member.could_not_perform_self_action')]);
        }
    }
}
