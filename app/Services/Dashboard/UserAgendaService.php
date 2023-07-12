<?php

namespace App\Services\Dashboard;

use App\Helpers\CalHelper;
use App\Models\Utility\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserAgendaService
{
    public function getData(Request $request): array
    {
        $todos = Todo::query()
            ->whereUserId(auth()->id())
            ->where('due_date', '<=', today()->addWeek(1)->toDateString())
            ->orderBy('due_date', 'desc')
            ->take(5)
            ->get();

        $agenda = [];

        foreach ($todos as $todo) {
            $detail = $this->getDetail($todo);

            array_push($agenda, [
                'title' => $todo->title,
                'date' => CalHelper::showDate($todo->due_date),
                'icon' => Arr::get($detail, 'icon'),
                'color' => Arr::get($detail, 'color'),
            ]);
        }

        return $agenda;
    }

    private function getDetail(Todo $todo): array
    {
        if ($todo->completed_at) {
            return ['icon' => 'fas fa-check', 'color' => 'bg-success'];
        } elseif ($todo->due_date > today()->toDateString()) {
            return ['icon' => 'fas fa-clock', 'color' => 'bg-info'];
        } elseif ($todo->due_date == today()->toDateString()) {
            return ['icon' => 'fas fa-exclamation', 'color' => 'bg-waning'];
        } elseif ($todo->due_date < today()->toDateString()) {
            return ['icon' => 'fas fa-times', 'color' => 'bg-danger'];
        }
    }
}
