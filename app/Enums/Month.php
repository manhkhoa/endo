<?php

namespace App\Enums;

use App\Concerns\HasEnum;

enum Month: string
{
    use HasEnum;

    case JANUARY = 'Tháng 1';
    case FEBRUARY = 'Tháng 2';
    case MARCH = 'Tháng 3';
    case APRIL = 'Tháng 4';
    case MAY = 'Tháng 5';
    case JUNE = 'Tháng 6';
    case JULY = 'Tháng 7';
    case AUGUST = 'Tháng 8';
    case SEPTEMBER = 'Tháng 9';
    case OCTOBER = 'Tháng 10';
    case NOVEMBER = 'Tháng 11';
    case DECEMBER = 'Tháng 12';

    public static function translation(): string
    {
        return 'list.months.';
    }
}
