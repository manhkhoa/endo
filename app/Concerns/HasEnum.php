<?php

namespace App\Concerns;

use Illuminate\Support\Arr;

trait HasEnum
{
    public static function getKeys(): array
    {
        return Arr::pluck(self::cases(), 'value');
    }

    public static function isValid($key = ''): bool
    {
        $keys = self::getKeys();

        if ($key && in_array($key, $keys)) {
            return true;
        }

        return false;
    }

    public static function getOptions(): array
    {
        $options = [];

        foreach (self::cases() as $option) {
            $options[] = ['label' => trans(self::translation().$option->value), 'value' => $option->value];
        }

        return $options;
    }

    public static function getValue($value = null): ?self
    {
        if (! $value) {
            return null;
        }

        return self::tryFrom($value);
    }

    public static function getDetail($value = null): array
    {
        if (! $value) {
            return [];
        }

        $status = self::tryFrom($value);

        if (! $status) {
            return [];
        }

        $item = [
            'label' => trans(self::translation().$status->value),
            'value' => $status->value,
        ];

        if (method_exists(static::class, 'color')) {
            $item['color'] = $status->color();
        }

        return $item;
    }

    public static function getLabel($value = null): ?string
    {
        if (! $value) {
            return '-';
        }

        $status = self::tryFrom($value);

        if (! $status) {
            return '-';
        }

        return trans(self::translation().$status->value);
    }

    public static function getLabels(string|array $values = []): ?string
    {
        if (is_string($values)) {
            $values = explode(',', $values);
        }

        foreach ($values as $value) {
            $labels[] = self::getLabel($value);
        }

        return implode(', ', $labels);
    }

    public static function getColor($value = null): ?string
    {
        if (! $value) {
            return '-';
        }

        $status = self::tryFrom($value);

        if (! $status) {
            return '-';
        }

        return $status->color();
    }
}
