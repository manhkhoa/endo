<?php

namespace App\Imports\Company;

use App\Concerns\ItemImport;
use App\Models\Company\Branch;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BranchImport implements ToCollection, WithHeadingRow
{
    use ItemImport;

    protected $limit = 100;

    public function collection(Collection $rows)
    {
        if (count($rows) > $this->limit) {
            throw ValidationException::withMessages(['message' => trans('general.errors.max_import_limit_crossed', ['attribute' => $this->limit])]);
        }

        $logFile = $this->getLogFile('branch');

        $errors = $this->validate($rows);

        $this->checkForErrors('branch', $errors);

        if (! request()->boolean('validate') && ! \Storage::exists($logFile)) {
            $this->import($rows);
        }
    }

    private function import(Collection $rows)
    {
        activity()->disableLogging();

        $branches = Branch::byTeam()->get();

        foreach ($rows as $row) {
            $parent = Arr::get($row, 'parent');

            $parentId = $parent ? $branches->firstWhere('name', $parent)?->id : null;

            $branch = Branch::forceCreate([
                'team_id' => session('team_id'),
                'name' => Arr::get($row, 'name'),
                'parent_id' => $parentId,
                'alias' => Arr::get($row, 'alias'),
                'description' => Arr::get($row, 'description'),
            ]);

            $branches->push($branch);
        }

        activity()->enableLogging();
    }

    private function validate(Collection $rows)
    {
        $existingNames = Branch::byTeam()->pluck('name')->all();
        $existingAliases = Branch::byTeam()->pluck('alias')->all();

        $errors = [];

        $newNames = [];
        $newAliases = [];
        foreach ($rows as $index => $row) {
            $rowNo = $index + 2;

            $name = Arr::get($row, 'name');
            $alias = Arr::get($row, 'alias');
            $parent = Arr::get($row, 'parent');
            $description = Arr::get($row, 'description');

            if (! $name) {
                $errors[] = $this->setError($rowNo, trans('company.branch.props.name'), 'required');
            } elseif (strlen($name) < 2 || strlen($name) > 100) {
                $errors[] = $this->setError($rowNo, trans('company.branch.props.name'), 'min_max', ['min' => 2, 'max' => 100]);
            } elseif (in_array($name, $existingNames)) {
                $errors[] = $this->setError($rowNo, trans('company.branch.props.name'), 'exists');
            } elseif (in_array($name, $newNames)) {
                $errors[] = $this->setError($rowNo, trans('company.branch.props.name'), 'duplicate');
            }

            if ($parent) {
                if (! in_array($parent, $existingNames) && ! in_array($parent, $newNames)) {
                    $errors[] = $this->setError($rowNo, trans('company.branch.props.parent'), 'invalid');
                }
            }

            if ($alias) {
                if (strlen($alias) < 2 || strlen($alias) > 100) {
                    $errors[] = $this->setError($rowNo, trans('company.branch.props.alias'), 'min_max', ['min' => 2, 'max' => 100]);
                } elseif (in_array($alias, $existingAliases)) {
                    $errors[] = $this->setError($rowNo, trans('company.branch.props.alias'), 'exists');
                } elseif (in_array($alias, $newAliases)) {
                    $errors[] = $this->setError($rowNo, trans('company.branch.props.alias'), 'duplicate');
                }
            }

            if ($description && (strlen($description) < 2 || strlen($description) > 1000)) {
                $errors[] = $this->setError($rowNo, trans('company.branch.props.description'), 'min_max', ['min' => 2, 'max' => 100]);
            }

            $newNames[] = $name;
            $newAliases[] = $alias;
        }

        return $errors;
    }
}
