<?php

namespace App\Http\Requests\Calendar;

use App\Helpers\CalHelper;
use App\Models\Calendar\Holiday;
use Illuminate\Foundation\Http\FormRequest;

class HolidayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:range,dates',
            'name' => 'required|min:2|max:100',
            'start_date' => 'required_if:type,range|date',
            'end_date' => 'required_if:type,range|date|after_or_equal:start_date',
            'dates' => 'required_if:type,dates',
            'description' => 'nullable|min:2|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $uuid = $this->route('holiday.uuid');

            if ($this->type == 'dates') {
                $dates = explode(',', $this->dates);

                foreach ($dates as $date) {
                    if (! CalHelper::validateDate($date)) {
                        $validator->errors()->add('dates', trans('validation.date', ['attribute' => trans('calendar.holiday.props.dates')]));

                        return;
                    }

                    $overlappingHoliday = Holiday::where('start_date', '<=', $date)->where('end_date', '>=', $date)->count();

                    if ($overlappingHoliday) {
                        $validator->errors()->add('dates', trans('calendar.holiday.exists', ['attribute' => CalHelper::showDate($date)]));
                    }
                }

                $this->merge([
                    'dates' => $dates,
                ]);
            } else {
                $overlappingHoliday = Holiday::when($uuid, function ($q, $uuid) {
                    $q->where('uuid', '!=', $uuid);
                })
                ->betweenPeriod($this->start_date, $this->end_date)
                ->count();

                if ($overlappingHoliday) {
                    $validator->errors()->add('message', trans('calendar.holiday.range_exists', ['start' => CalHelper::showDate($this->start_date), 'end' => CalHelper::showDate($this->end_date)]));
                }
            }
        });
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('calendar.holiday.props.name'),
            'type' => __('calendar.holiday.props.type'),
            'start_date' => __('calendar.holiday.props.start_date'),
            'end_date' => __('calendar.holiday.props.end_date'),
            'dates' => __('calendar.holiday.props.dates'),
            'description' => __('calendar.holiday.props.description'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
