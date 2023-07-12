<?php

namespace App\Enums\Finance;

use App\Concerns\HasEnum;
use App\Contracts\HasColor;

enum PaymentStatus: string implements HasColor
{
    use HasEnum;

    case NA = 'not_applicable';
    case PAID = 'paid';
    case UNPAID = 'unpaid';
    case PARTIALLY_PAID = 'partially_paid';

    public static function translation(): string
    {
        return 'list.payment_statuses.';
    }

    public function color(): string
    {
        return match ($this) {
            self::NA => 'info',
            self::PAID => 'success',
            self::UNPAID => 'danger',
            self::PARTIALLY_PAID => 'warning',
        };
    }

    public static function status($total = 0, $paid = 0): ?string
    {
        $balance = $total - $paid;

        if ($total <= 0) {
            return self::NA->value;
        } elseif ($paid == 0) {
            return self::UNPAID->value;
        } elseif ($paid > 0 && $balance > 0) {
            return self::PARTIALLY_PAID->value;
        } elseif ($balance <= 0) {
            return self::PAID->value;
        }
    }
}
