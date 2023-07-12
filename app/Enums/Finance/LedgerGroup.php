<?php

namespace App\Enums\Finance;

use App\Concerns\HasEnum;

enum LedgerGroup: string
{
    use HasEnum;

    case CASH = 'cash';
    case BANK_ACCOUNT = 'bank_account';
    case OVERDRAFT_ACCOUNT = 'overdraft_account';
    case SUNDRY_DEBTOR = 'sundry_debtor';
    case SUNDRY_CREDITOR = 'sundry_creditor';
    case DIRECT_EXPENSE = 'direct_expense';
    case INDIRECT_EXPENSE = 'indirect_expense';
    case DIRECT_INCOME = 'direct_income';
    case INDIRECT_INCOME = 'indirect_income';

    public static function translation(): string
    {
        return 'finance.ledger_type.groups.';
    }

    public static function primaryLedgers(): array
    {
        return [self::CASH->value, self::BANK_ACCOUNT->value, self::OVERDRAFT_ACCOUNT->value];
    }

    public static function secondaryLedgers(): array
    {
        return [self::SUNDRY_DEBTOR->value, self::SUNDRY_CREDITOR->value, self::DIRECT_EXPENSE->value, self::INDIRECT_EXPENSE->value, self::DIRECT_INCOME->value, self::INDIRECT_INCOME->value];
    }

    public static function isPrimaryLedger(string $value = ''): bool
    {
        $ledgerGroup = self::tryFrom($value);

        if (! $ledgerGroup) {
            return false;
        }

        return in_array($ledgerGroup->value, self::primaryLedgers());
    }

    public static function isSecondaryLedger(string $value = ''): bool
    {
        $ledgerGroup = self::tryFrom($value);

        if (! $ledgerGroup) {
            return false;
        }

        return in_array($ledgerGroup->value, self::secondaryLedgers());
    }

    public static function hasAccount(string $value = ''): bool
    {
        $ledgerGroup = self::tryFrom($value);

        if (! $ledgerGroup) {
            return false;
        }

        return in_array($ledgerGroup, [self::BANK_ACCOUNT, self::OVERDRAFT_ACCOUNT]);
    }

    public static function hasContact(string $value = ''): bool
    {
        $ledgerGroup = self::tryFrom($value);

        if (! $ledgerGroup) {
            return false;
        }

        return in_array($ledgerGroup, [self::SUNDRY_DEBTOR, self::SUNDRY_CREDITOR]);
    }
}
