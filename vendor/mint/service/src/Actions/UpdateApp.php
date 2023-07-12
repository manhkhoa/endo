<?php

namespace Mint\Service\Actions;

use App\Helpers\SysHelper;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class UpdateApp
{
    public function execute($data = array()) : void
    {
        $build = Arr::get($data, 'product.next_release_build');
        $version = Arr::get($data, 'product.next_release_version');

        $zip = new \ZipArchive;
        if (! $zip) {
            throw ValidationException::withMessages(['message' => 'Zip extension missing.']);
        }

        if (! \File::exists('../'.$build.".zip")) {
            throw ValidationException::withMessages(['message' => 'Update file doesn\'t exist.']);
        }

        \File::copyDirectory(public_path('build'), public_path('build-' . date('YmdHis')));
        \File::deleteDirectory(public_path('build'));

        if ($zip->open('../'.$build.".zip") === TRUE) {
            $zip->extractTo(base_path());
            $zip->close();
        } else {
            unlink('../'.$build.".zip");
            throw ValidationException::withMessages(['message' => 'Zip file corrupted.']);
        }

        \Artisan::call('optimize:clear');

        \Artisan::call('migrate', ['--force' => true]);

        SysHelper::setApp(['VERSION' => $version]);

        unlink('../'.$build.".zip");
    }
}
