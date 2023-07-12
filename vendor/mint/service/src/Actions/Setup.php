<?php

namespace Mint\Service\Actions;

use App\Actions\CreateContact;
use App\Models\Employee\Employee;
use Closure;
use Illuminate\Support\Arr;

class Setup
{
    public function handle($params, Closure $next)
    {
        $contact = (new CreateContact)->execute($params);

        $contact->email = Arr::get($params, 'email');
        $contact->team_id = 1;
        $contact->user_id = 1;
        $contact->save();

        $employee = Employee::forceCreate([
            'contact_id' => $contact->id,
            'code_number' => 'SA1',
            'joining_date' => today()->toDateString(),
            'meta' => array('is_default' => true)
        ]);

        return $next($params);
    }
}
