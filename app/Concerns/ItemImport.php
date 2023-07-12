<?php

namespace App\Concerns;

use App\Exceptions\ImportErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

trait ItemImport
{
    public function validateFile(Request $request)
    {
        if (! $request->file('file')) {
            throw ValidationException::withMessages(['message' => trans('validation.required', ['attribute' => trans('general.file')])]);
        }

        $extension = $request->file('file')->getClientOriginalExtension();

        if (! in_array($extension, ['xls', 'xlsx'])) {
            throw ValidationException::withMessages(['message' => trans('general.errors.invalid_input')]);
        }
    }

    public function getErrorHeaders()
    {
        return ['row' => trans('general.row'), 'column' => trans('general.column'), 'message' => trans('general.message')];
    }

    public function setError($row, $column, $error, $options = [])
    {
        $message = '';

        if ($error == 'required') {
            $message = trans('global.missing', ['attribute' => $column]);
        } elseif ($error == 'min_max') {
            $message = trans('global.min_max', ['attribute' => $column, 'min' => Arr::get($options, 'min'), 'max' => Arr::get($options, 'max')]);
        } elseif ($error == 'max') {
            $message = trans('global.max', ['attribute' => $column, 'max' => Arr::get($options, 'max')]);
        } elseif ($error == 'min') {
            $message = trans('global.min', ['attribute' => $column, 'min' => Arr::get($options, 'min')]);
        } elseif ($error == 'exists') {
            $message = trans('global.exists', ['attribute' => $column]);
        } elseif ($error == 'duplicate') {
            $message = trans('global.duplicate', ['attribute' => $column]);
        } elseif ($error == 'invalid') {
            $message = trans('global.invalid', ['attribute' => $column]);
        }

        return compact('row', 'column', 'message');
    }

    public function getLogFile($name = 'item')
    {
        $prefix = '';

        return $prefix.'import/'.$name.'-'.\Auth::id().'-'.date('Ymd', time()).'.csv';
    }

    public function deleteLogFile($name = 'item')
    {
        $logFile = $this->getLogFile($name);

        \Storage::delete($logFile);
    }

    public function checkForErrors($name = 'item', $errors = []): void
    {
        if (! count($errors)) {
            return;
        }

        array_unshift($errors, $this->getErrorHeaders());

        $logFile = $this->getLogFile($name);

        \Storage::put($logFile, '');

        $file = fopen(storage_path('app/'.$logFile), 'w');
        foreach ($errors as $error) {
            fputcsv($file, [
                Arr::get($error, 'row'),
                Arr::get($error, 'column'),
                Arr::get($error, 'message'),
            ]);
        }
        fclose($file);
    }

    public function reportError($name = 'item'): void
    {
        $logFile = $this->getLogFile($name);

        if (! \Storage::exists($logFile)) {
            return;
        }

        $items = array_map('str_getcsv', file(storage_path('app/'.$logFile)));
        $errorCount = count($items) - 1;

        throw (new ImportErrorException(trans('general.errors.import_error_message', ['attribute' => $errorCount])))
            ->withItems(array_slice($items, 0, 100))
            ->withCount($errorCount)
            ->withErrorLog(true);
    }
}
