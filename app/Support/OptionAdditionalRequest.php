<?php

namespace App\Support;

use Illuminate\Support\Arr;

class OptionAdditionalRequest
{
    public static $types = [
        'payment_method' => [
            'rules' => [
                'details' => 'array',
                'details.instrument_number' => 'boolean',
                'details.instrument_date' => 'boolean',
                'details.instrument_clearing_date' => 'boolean',
                'details.bank_detail' => 'boolean',
                'details.reference_number' => 'boolean',
            ],
            'attributes' => [
                'details.instrument_number' => 'finance.payment_method.props.instrument_number',
                'details.instrument_date' => 'finance.payment_method.props.instrument_date',
                'details.instrument_clearing_date' => 'finance.payment_method.props.instrument_clearing_date',
                'details.bank_detail' => 'finance.payment_method.props.bank_detail',
                'details.reference_number' => 'finance.payment_method.props.reference_number',
            ],
            'messages' => [],
        ],
    ];

    public static function getDetail($type, $value = 'rules')
    {
        $default = [];
        if ($value == 'rules') {
            $default = ['details' => 'array'];
        }

        return Arr::get(self::$types, $type.'.'.$value, $default);
    }

    public static function getTransAttributes($type)
    {
        $attributes = self::getDetail($type, 'attributes');

        $newAttributes = [];

        foreach ($attributes as $key => $attribute) {
            $newAttributes[$key] = trans($attribute);
        }

        return $newAttributes;
    }
}
