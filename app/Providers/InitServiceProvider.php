<?php

namespace App\Providers;

use App\Mixins\ArrMixin;
use App\Mixins\CollectionMixin;
use App\Mixins\QueryMixin;
use App\Mixins\ResponseMixin;
use App\Mixins\StrMixin;
use App\Mixins\ViewMixin;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class InitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Str::mixin(new StrMixin());
        Arr::mixin(new ArrMixin());
        Response::mixin(new ResponseMixin());
        View::mixin(new ViewMixin());
        Collection::mixin(new CollectionMixin());
        Builder::mixin(new QueryMixin());
        // Carbon::mixin(new CarbonMixin());
    }
}
