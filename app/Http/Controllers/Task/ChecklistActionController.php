<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task\Checklist;
use App\Models\Task\Task;
use App\Services\Task\ChecklistActionService;
use Illuminate\Http\Request;

class ChecklistActionController extends Controller
{
    public function __construct()
    {
        //
    }

    public function toggleStatus(Request $request, string $task, string $checklist, ChecklistActionService $service)
    {
        $task = Task::findIfExists($task);

        $task->ensureCanManage('checklist');

        $checklist = Checklist::whereTaskId($task->id)->findIfExists($checklist);

        $service->toggleStatus($request, $task, $checklist);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('task.checklist.props.status')]),
        ]);
    }
}
