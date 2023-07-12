<?php

namespace App\Actions\Config;

use App\Models\Config\Config;
use Illuminate\Http\Request;

class RemoveAsset
{
    public function execute(Request $request)
    {
        $asset = str_replace('/storage/', '', config('config.assets.'.$request->query('type')));

        if (\Storage::disk('public')->exists($asset)) {
            \Storage::disk('public')->delete($asset);
        }

        $config = Config::firstOrCreate(['name' => 'assets']);
        $value = $config->value;
        unset($value[$request->query('type')]);
        $config->value = $value;
        $config->save();
    }
}
