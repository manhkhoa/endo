<?php

namespace App\Models\Employee;

use App\Concerns\HasFilter;
use App\Concerns\HasMedia;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use App\Models\Company\Branch;
use App\Models\Company\Department;
use App\Models\Company\Designation;
use App\Models\Option;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Record extends Model
{
    use HasFactory, HasFilter, HasUuid, HasMeta, HasMedia, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'employee_records';

    protected $casts = [
        'meta' => 'array',
    ];

    public function getModelName(): string
    {
        return 'EmployeeRecord';
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'employment_status_id');
    }

    public function scopeWithDetail(Builder $query)
    {
        $query->select('employee_records.*',
            'departments.name as department_name', 'departments.uuid as department_uuid', 'departments.id as department_id',
            'designations.name as designation_name', 'designations.uuid as designation_uuid', 'designations.id as designation_id',
            'branches.name as branch_name', 'branches.uuid as branch_uuid', 'branches.id as branch_id',
            'options.name as employment_status_name', 'options.uuid as employment_status_uuid', 'options.id as employment_status_id'
        )
            ->join('departments', 'employee_records.department_id', '=', 'departments.id')
            ->join('designations', 'employee_records.designation_id', '=', 'designations.id')
            ->join('branches', 'employee_records.branch_id', '=', 'branches.id')
            ->join('options', 'employee_records.employment_status_id', '=', 'options.id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('employee')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}
