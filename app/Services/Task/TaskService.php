<?php

namespace App\Services\Task;

use App\Enums\OptionType;
use App\Http\Resources\OptionResource;
use App\Models\Option;
use App\Models\Task\Task;
use Illuminate\Http\Request;

class TaskService
{
    public function preRequisite(Request $request): array
    {
        $taskLists = OptionResource::collection(Option::query()
            ->byTeam()
            ->where('type', OptionType::TASK_LIST->value)
            ->orderBy('meta->position', 'asc')
            ->get()
        );

        return compact('taskLists');
    }

    public function deletable(Task $task): void
    {
        $task->ensureIsDeletable();
    }
}
