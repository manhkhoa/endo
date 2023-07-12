<?php

namespace App\Services\Calendar;

use App\Models\Calendar\Holiday;
use Illuminate\Http\Request;

class HolidayService
{
    public function create(Request $request): Holiday
    {
        \DB::beginTransaction();

        if ($request->type == 'range') {
            $holiday = Holiday::forceCreate($this->formatParams($request));
        } else {
            foreach ($request->dates as $date) {
                $request->start_date = $date;
                $request->end_date = $date;
                $holiday = Holiday::forceCreate($this->formatParams($request));
            }
        }

        \DB::commit();

        return $holiday;
    }

    private function formatParams(Request $request, ?Holiday $holiday = null): array
    {
        $formatted = [
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ];

        if (! $holiday) {
            $formatted['team_id'] = session('team_id');
        }

        return $formatted;
    }

    public function update(Request $request, Holiday $holiday): void
    {
        \DB::beginTransaction();

        $holiday->forceFill($this->formatParams($request, $holiday))->save();

        \DB::commit();
    }

    public function deletable(Holiday $holiday): void
    {
    }
}
