<?php

namespace App\Services\Employee;

use App\Models\Employee\Document;
use App\Models\Employee\Employee;
use Illuminate\Http\Request;

class DocumentService
{
    public function preRequisite(Request $request): array
    {
        return [];
    }

    public function findByUuidOrFail(Employee $employee, string $uuid): Document
    {
        return Document::whereEmployeeId($employee->id)->whereUuid($uuid)->getOrFail(trans('employee.document.document'));
    }

    public function create(Request $request, Employee $employee): Document
    {
        \DB::beginTransaction();

        $document = Document::forceCreate($this->formatParams($request, $employee));

        $document->addMedia($request);

        \DB::commit();

        return $document;
    }

    private function formatParams(Request $request, Employee $employee, ?Document $document = null): array
    {
        $formatted = [
            'type_id' => $request->type_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        if (! $document) {
            $formatted['employee_id'] = $employee->id;
        }

        return $formatted;
    }

    public function update(Request $request, Employee $employee, Document $document): void
    {
        \DB::beginTransaction();

        $document->forceFill($this->formatParams($request, $employee, $document))->save();

        $document->updateMedia($request);

        \DB::commit();
    }

    public function deletable(Employee $employee, Document $document): void
    {
        //
    }
}
