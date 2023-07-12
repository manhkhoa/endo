<?php

namespace App\Actions\Task;

use App\Models\Task\Task;
use Illuminate\Support\Arr;

class UpdateNextRepeatDate
{
    public function execute(Task $task): void
    {
        $repeatation = $task->repeatation;
        $repeatation['last_repeat_date'] = $repeatation['next_repeat_date'];
        $task->repeatation = $repeatation;

        $nextRepeateDate = (new GetNextRepeatDate)->execute($task);

        $repeatation['next_repeat_date'] = $nextRepeateDate;
        $repeatation['repeated_count'] = Arr::get($repeatation, 'repeated_count', 0) + 1;
        $task->repeatation = $repeatation;

        $task->save();
    }
}
