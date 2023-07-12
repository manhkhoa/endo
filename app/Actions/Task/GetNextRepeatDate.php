<?php

namespace App\Actions\Task;

use App\Enums\Day;
use App\Enums\Task\RepeatFrequency;
use App\Helpers\CalHelper;
use App\Models\Task\Task;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GetNextRepeatDate
{
    public function execute(Task $task): ?string
    {
        $repeatation = $task->repeatation;
        $frequency = Arr::get($repeatation, 'frequency');

        $startDate = Arr::get($repeatation, 'start_date');
        $endDate = Arr::get($repeatation, 'end_date');
        $lastRepeatDate = Arr::get($repeatation, 'last_repeat_date');

        if ($frequency == RepeatFrequency::DAY_WISE->value) {
            $days = Arr::sort(Day::getNumberValues(Arr::get($repeatation, 'days')));

            $isFirstDay = $lastRepeatDate == null ? true : false;

            $lastRepeatDate = $lastRepeatDate ? $lastRepeatDate : $startDate;

            $currentDay = strtolower(Carbon::parse($lastRepeatDate)->format('l'));
            $currentDateValue = Day::tryFrom($currentDay)->getNumberValue();

            $nextDay = Arr::first($days, function ($day) use ($currentDateValue) {
                return $day > $currentDateValue;
            });

            $nextDay = $nextDay ?: Arr::first($days);

            if ($isFirstDay && $currentDateValue == $nextDay) {
                $nextRepeatDate = Carbon::parse($lastRepeatDate);
            } else {
                $nextRepeatDate = Carbon::parse($lastRepeatDate)->next(Day::getDayValue($nextDay)->value);
            }
        } elseif ($frequency == RepeatFrequency::DATE_WISE->value) {
            $dates = Arr::sort(Arr::get($repeatation, 'dates'));
            $lastRepeatDate = $lastRepeatDate ? $lastRepeatDate : $startDate;
            $currentDate = Carbon::parse($lastRepeatDate)->format('d');

            $nextDate = Arr::first($dates, function ($date) use ($currentDate) {
                return $date >= $currentDate;
            });

            $startwithCurrentMonth = $nextDate ? true : false;
            $nextDate = $nextDate ?: Arr::first($dates);

            $nextRepeatDate = $this->getDateWiseNextRepeatDate($lastRepeatDate, $nextDate, $startwithCurrentMonth);
        } else {
            if (! $lastRepeatDate) {
                $nextRepeatDate = Carbon::parse($startDate);
            } else {
                $repeatFrom = Carbon::parse($lastRepeatDate);
                if ($frequency == RepeatFrequency::DAY_WISE_COUNT->value) {
                    $nextRepeatDate = $repeatFrom->addDays(Arr::get($repeatation, 'day_wise_count', 1));
                } elseif ($frequency == RepeatFrequency::WEEKLY->value) {
                    $nextRepeatDate = $repeatFrom->addWeek(1);
                } elseif ($frequency == RepeatFrequency::FORTNIGHTLY->value) {
                    $nextRepeatDate = $repeatFrom->addWeek(2);
                } elseif ($frequency == RepeatFrequency::MONTHLY->value) {
                    $nextRepeatDate = $repeatFrom->addMonth(1);
                } elseif ($frequency == RepeatFrequency::BI_MONTHLY->value) {
                    $nextRepeatDate = $repeatFrom->addMonths(2);
                } elseif ($frequency == RepeatFrequency::QUARTERLY->value) {
                    $nextRepeatDate = $repeatFrom->addMonths(3);
                } elseif ($frequency == RepeatFrequency::HALF_YEARLY->value) {
                    $nextRepeatDate = $repeatFrom->addMonths(6);
                } elseif ($frequency == RepeatFrequency::YEARLY->value) {
                    $nextRepeatDate = $repeatFrom->addYear(1);
                }
            }
        }

        if ($nextRepeatDate->toDateString() > $endDate) {
            return null;
        }

        return $nextRepeatDate->toDateString();
    }

    private function getDateWiseNextRepeatDate(string $lastRepeatDate, string $dayNumber, bool $currentMonth = false): mixed
    {
        $monthNumber = Carbon::parse($lastRepeatDate)->month;
        $yearNumber = Carbon::parse($lastRepeatDate)->year;
        $months = $currentMonth ? [$yearNumber.'-'.Str::padLeft($monthNumber, 2, 0)] : [];

        for ($i = 1; $i <= 5; $i++) {
            if ($monthNumber >= 12) {
                $monthNumber = 1;
                $yearNumber++;
            } else {
                $monthNumber++;
            }

            $months[] = $yearNumber.'-'.Str::padLeft($monthNumber, 2, 0);
        }

        foreach ($months as $month) {
            $date = $month.'-'.$dayNumber;
            if (CalHelper::validateDateFormat($date)) {
                return Carbon::parse($date);
            }
        }
    }
}
