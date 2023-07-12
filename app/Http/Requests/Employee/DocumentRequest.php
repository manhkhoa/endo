<?php

namespace App\Http\Requests\Employee;

use App\Models\Employee\Document;
use App\Models\Media;
use App\Models\Option;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class DocumentRequest extends FormRequest
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
            'type' => 'required',
            'title' => 'required|min:2|max:100',
            'description' => 'nullable|min:2|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $mediaModel = (new Document)->getModelName();

            $employeeUuid = $this->route('employee');
            $documentUuid = $this->route('document');

            $documentType = Option::byTeam()->whereType('document_type')->whereUuid($this->type)->getOrFail(__('employee.document_type.document_type'), 'document_type');

            $existingDocument = Document::whereHas('employee', function ($q) use ($employeeUuid) {
                $q->whereUuid($employeeUuid);
            })
                ->when($documentUuid, function ($q, $documentUuid) {
                    $q->where('uuid', '!=', $documentUuid);
                })
                ->whereTitle($this->title)
                ->exists();

            if ($existingDocument) {
                $validator->errors()->add('title', trans('validation.unique', ['attribute' => __('employee.document.props.title')]));
            }

            $attachedMedia = Media::whereModelType($mediaModel)
                ->whereToken($this->media_token)
                ->where('meta->hash', $this->media_hash)
                ->where('meta->is_temp_deleted', false)
                ->where(function ($q) use ($documentUuid) {
                    $q->whereStatus(0)
                    ->when($documentUuid, function ($q) {
                        $q->orWhere('status', 1);
                    });
                })
                ->exists();

            if (! $attachedMedia) {
                throw ValidationException::withMessages(['message' => trans('global.could_not_find', ['attribute' => trans('general.attachment')])]);
            }

            $this->merge([
                'type_id' => $documentType->id,
            ]);
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
            'title' => __('employee.document.props.title'),
            'description' => __('employee.document.props.description'),
            'start_date' => __('employee.document.props.start_date'),
            'end_date' => __('employee.document.props.end_date'),
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
