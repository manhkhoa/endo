<?php

namespace App\Scopes\Task;

use App\Models\Task\Member;
use Illuminate\Database\Eloquent\Builder;

trait TaskScope
{
    public function scopeFindIfExists(Builder $query, string $uuid, $field = 'message'): self
    {
        return $query->where('tasks.uuid', $uuid)
            ->byTeam()
            ->select(
                'tasks.*',
                'task_members.employee_id as member_employee_id',
                'task_members.meta as member_meta',
                'task_members.is_favorite as is_favorite',
                'employees.contact_id as member_contact_id',
                'contacts.user_id as member_user_id'
            )
            ->leftJoin('task_members', function ($join) {
                $join->on('tasks.id', '=', 'task_members.task_id')
                ->join('employees', 'task_members.employee_id', '=', 'employees.id')
                ->join('contacts', function ($join) {
                    $join->on('employees.contact_id', '=', 'contacts.id')->where('contacts.user_id', auth()->id());
                });
            })
            ->withOwner()
            ->with('memberLists:employee_id,task_id,is_owner')
            ->getOrFail(trans('task.task'), $field);
    }

    public function scopeWithOwner(Builder $query)
    {
        $query->addSelect(['owner_id' => Member::select('employee_id')
            ->whereColumn('task_id', 'tasks.id')
            ->where('is_owner', 1)
            ->limit(1),
        ])->with(['owner' => fn ($q) => $q->withSummaryRecord(false)]);
    }

    public function scopeWithMember(Builder $query)
    {
        $query->leftJoin('task_members', function ($join) {
            $join->on('tasks.id', '=', 'task_members.task_id')
            ->join('employees', function ($join) {
                $join->on('task_members.employee_id', '=', 'employees.id')
                ->join('contacts', 'employees.contact_id', '=', 'contacts.id')
                ->where('contacts.user_id', auth()->id());
            });
        });
    }

    public function scopeByTeam(Builder $query)
    {
        $query->where('tasks.team_id', session('team_id'));
    }
}
