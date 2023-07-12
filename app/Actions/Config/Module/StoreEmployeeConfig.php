<?php

namespace App\Actions\Config\Module;

class StoreEmployeeConfig
{
    public static function handle(): array
    {
        $input = request()->validate([
            'enable_middle_name_field' => 'sometimes|boolean',
            'enable_third_name_field' => 'sometimes|boolean',
            'code_number_prefix' => 'sometimes|max:100',
            'code_number_digit' => 'sometimes|required|integer|min:0|max:9',
            'code_number_suffix' => 'sometimes|max:100',
            'unique_id_number1_label' => 'sometimes|required|min:2|max:100',
            'unique_id_number2_label' => 'sometimes|required|min:2|max:100',
            'unique_id_number3_label' => 'sometimes|required|min:2|max:100',
            'is_unique_id_number1_required' => 'sometimes|boolean',
            'is_unique_id_number2_required' => 'sometimes|boolean',
            'is_unique_id_number3_required' => 'sometimes|boolean',
        ], [], [
            'enable_middle_name_field' => __('employee.config.props.enable_middle_name_field'),
            'enable_third_name_field' => __('employee.config.props.enable_third_name_field'),
            'code_number_prefix' => __('employee.config.props.number_prefix'),
            'code_number_digit' => __('employee.config.props.number_digit'),
            'code_number_suffix' => __('employee.config.props.number_suffix'),
            'unique_id_number1_label' => __('employee.config.props.unique_id_number1_label'),
            'unique_id_number2_label' => __('employee.config.props.unique_id_number2_label'),
            'unique_id_number3_label' => __('employee.config.props.unique_id_number3_label'),
            'is_unique_id_number1_required' => __('employee.config.props.unique_id_number1_required'),
            'is_unique_id_number2_required' => __('employee.config.props.unique_id_number2_required'),
            'is_unique_id_number3_required' => __('employee.config.props.unique_id_number3_required'),
        ]);

        return $input;
    }
}
