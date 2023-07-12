<?php

namespace App\Enums;

use App\Concerns\HasEnum;

enum Day: string
{
    use HasEnum;

    case MONDAY = 'Thứ 2 月曜日';
    case TUESDAY = 'Thứ 3 火曜日';
    case WEDNESDAY = 'Thứ 4 水曜日';
    case THURSDAY = 'Thứ 5 木曜日';
    case FRIDAY = 'Thứ 6 金曜日';
    case SATURDAY = 'Thứ 7 土曜日';
    case SUNDAY = 'Chủ nhật 日曜日';

    public static function translation(): string
    {
        return 'list.days.';
    }

    public static function getNumberValues(array|string $days = []): array
    {
        $items = [];

        if (is_string($days)) {
            $days = explode(',', $days);
        }

        foreach ($days as $day) {
            $items[] = self::tryFrom($day)->getNumberValue();
        }

        return $items;
    }

    public static function getDayValue($day): self
    {
        return match ($day) {
            1 => self::MONDAY,
            2 => self::TUESDAY,
            3 => self::WEDNESDAY,
            4 => self::THURSDAY,
            5 => self::FRIDAY,
            6 => self::SATURDAY,
            7 => self::SUNDAY
        };
    }

    public function getNumberValue(): int
    {
        return match ($this) {
            self::MONDAY => 1,
            self::TUESDAY => 2,
            self::WEDNESDAY => 3,
            self::THURSDAY => 4,
            self::FRIDAY => 5,
            self::SATURDAY => 6,
            self::SUNDAY => 7
        };
    }
}
