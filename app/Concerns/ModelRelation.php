<?php

namespace App\Concerns;

trait ModelRelation
{
    /**
     * Get all relations
     *
     * @return array
     */
    public function relations()
    {
        return [
            'Team' => 'App\Models\Team',
            'User' => 'App\Models\User',
            'Tag' => 'App\Models\Tag',
            'Comment' => 'App\Models\Comment',
            'Config' => 'App\Models\Config\Config',
            'Todo' => 'App\Models\Utility\Todo',
            'Role' => 'App\Models\Config\Role',
            'Option' => 'App\Models\Option',
            'Account' => 'App\Models\Account',
            'Department' => 'App\Models\Department',
            'Designation' => 'App\Models\Designation',
            'Branch' => 'App\Models\Branch',
            'Employee' => 'App\Models\Employee\Employee',
            'EmployeeRecord' => 'App\Models\Employee\Record',
            'EmployeeQualification' => 'App\Models\Employee\Qualification',
            'EmployeeDocument' => 'App\Models\Employee\Document',
            'EmployeeExperience' => 'App\Models\Employee\Experience',
            'LedgerType' => 'App\Models\Finance\LedgerType',
            'Ledger' => 'App\Models\Finance\Ledger',
            'Transaction' => 'App\Models\Finance\Transaction',
            'Task' => 'App\Models\Task\Task',
            'TaskChecklist' => 'App\Models\Task\Checklist',
        ];
    }
}
