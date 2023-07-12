<?php

namespace App\Actions\Config;

use App\Models\Config\Config;
use Illuminate\Http\Request;

class UploadAsset
{
    public function execute(Request $request)
    {
        $prefix = '';

        request()->validate([
            'image' => 'required|image',
        ]);

        $asset = str_replace('/storage/', '', config('config.assets.'.$request->query('type')));

        if ($asset && \Storage::disk('public')->exists($asset)) {
            \Storage::disk('public')->delete($asset);
        }

        $image = \Storage::disk('public')->putFile($prefix.'assets/'.$request->query('type'), request()->file('image'));

        $config = Config::firstOrCreate(['name' => 'assets']);
        $value = $config->value;
        $value[$request->query('type')] = '/storage/'.$image;
        $config->value = $value;
        $config->save();
    }
}
