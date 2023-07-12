<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Config\Config' => 'App\Policies\Config\ConfigPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Utility\Todo' => 'App\Policies\Utility\TodoPolicy',
        'App\Models\Company\Department' => 'App\Policies\Company\DepartmentPolicy',
        'App\Models\Company\Designation' => 'App\Policies\Company\DesignationPolicy',
        'App\Models\Company\Branch' => 'App\Policies\Company\BranchPolicy',
        'App\Models\Employee\Employee' => 'App\Policies\Employee\EmployeePolicy',
        'App\Models\Finance\Ledger' => 'App\Policies\Finance\LedgerPolicy',
        'App\Models\Finance\Transaction' => 'App\Policies\Finance\TransactionPolicy',
        'App\Models\Calendar\Holiday' => 'App\Policies\Calendar\HolidayPolicy',
        'App\Models\Task\Task' => 'App\Policies\Task\TaskPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return ($user->is_default || $user->hasRole('admin')) ? true : null;
        });

        // Gate::after(function ($user, $ability) {
        //     return $user->hasRole('admin');
        // });
    }
}
