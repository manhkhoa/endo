<?php

namespace App\Support;

use App\Actions\Config\SetAppConfig;
use App\Actions\Config\SetAuthConfig;
use App\Actions\Config\SetMailConfig;
use App\Actions\Config\SetPusherConfig;
use App\Actions\Config\SetSocialLoginConfig;
use App\Actions\Config\SetSystemConfig;
use App\Helpers\SysHelper;
use App\Models\Config\Config;
use Illuminate\Pipeline\Pipeline;

class SetConfig
{
    public function set()
    {
        if (! SysHelper::isInstalled()) {
            return;
        }

        $config = Config::listAll();

        // if (! app()->environment('testing')) {
        //     $config = Config::listAll();
        // }

        $results = app(Pipeline::class)
            ->send($config)
            ->through([
                SetAppConfig::class,
                SetSystemConfig::class,
                SetAuthConfig::class,
                SetSocialLoginConfig::class,
                SetMailConfig::class,
                SetPusherConfig::class,
            ])
            ->thenReturn();
    }
}
