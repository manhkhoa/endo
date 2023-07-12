<?php

namespace App\Actions\Config\Module;

class StoreFinanceConfig
{
    public static function handle(): array
    {
        $input = request()->validate([
            'payment_code_number_prefix' => 'sometimes|max:100',
            'payment_code_number_digit' => 'sometimes|required|integer|min:0|max:9',
            'payment_code_number_suffix' => 'sometimes|max:100',
            'receipt_code_number_prefix' => 'sometimes|max:100',
            'receipt_code_number_digit' => 'sometimes|required|integer|min:0|max:9',
            'receipt_code_number_suffix' => 'sometimes|max:100',
            'contra_code_number_prefix' => 'sometimes|max:100',
            'contra_code_number_digit' => 'sometimes|required|integer|min:0|max:9',
            'contra_code_number_suffix' => 'sometimes|max:100',
            'enable_bank_code1' => 'sometimes|boolean',
            'enable_bank_code2' => 'sometimes|boolean',
            'enable_bank_code3' => 'sometimes|boolean',
            'bank_code1_label' => 'sometimes|required|min:2|max:100',
            'bank_code2_label' => 'sometimes|required|min:2|max:100',
            'bank_code3_label' => 'sometimes|required|min:2|max:100',
            'is_bank_code1_required' => 'sometimes|boolean',
            'is_bank_code2_required' => 'sometimes|boolean',
            'is_bank_code3_required' => 'sometimes|boolean',
        ], [], [
            'payment_number_prefix' => __('finance.config.props.payment_number_prefix'),
            'payment_number_digit' => __('finance.config.props.payment_number_digit'),
            'payment_number_suffix' => __('finance.config.props.payment_number_suffix'),
            'receipt_number_prefix' => __('finance.config.props.receipt_number_prefix'),
            'receipt_number_digit' => __('finance.config.props.receipt_number_digit'),
            'receipt_number_suffix' => __('finance.config.props.receipt_number_suffix'),
            'contra_number_prefix' => __('finance.config.props.contra_number_prefix'),
            'contra_number_digit' => __('finance.config.props.contra_number_digit'),
            'contra_number_suffix' => __('finance.config.props.contra_number_suffix'),
            'enable_bank_code1' => __('finance.config.props.enable_bank_code1'),
            'enable_bank_code2' => __('finance.config.props.enable_bank_code2'),
            'enable_bank_code3' => __('finance.config.props.enable_bank_code3'),
            'bank_code1_label' => __('finance.config.props.bank_code1_label'),
            'bank_code2_label' => __('finance.config.props.bank_code2_label'),
            'bank_code3_label' => __('finance.config.props.bank_code3_label'),
            'is_bank_code1_required' => __('finance.config.props.bank_code1_required'),
            'is_bank_code2_required' => __('finance.config.props.bank_code2_required'),
            'is_bank_code3_required' => __('finance.config.props.bank_code3_required'),
        ]);

        return $input;
    }
}
