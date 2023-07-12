<?php

namespace App\Providers;

use App\Concerns\ModelRelation;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    use ModelRelation;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Activity::saving(function (Activity $activity) {
            $activity->properties = $activity->properties->put('ip', \Request::ip());
            $activity->properties = $activity->properties->put('user_agent', \Request::header('User-Agent'));
        });

        JsonResource::withoutWrapping();
        Validator::includeUnvalidatedArrayKeys();

        Relation::morphMap($this->relations());
    }
}
