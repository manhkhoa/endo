<?php

namespace App\Concerns;

use Illuminate\Support\Arr;

trait HasMeta
{
    public static function bootHasMeta()
    {
    }

    public function getMeta(string $option)
    {
        return Arr::get($this->meta, $option);
    }
}
