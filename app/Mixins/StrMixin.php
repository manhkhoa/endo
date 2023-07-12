<?php

namespace App\Mixins;

use Illuminate\Support\Str;

class StrMixin
{
    /**
     * String padding left leading zero
     *
     * @param  string  $string
     * @param  int  $padding
     * @param  int  $digit
     */
    public function padLeftZero()
    {
        return function ($string, $padding, $digit): string {
            return $string.str_pad($padding, $digit, '0', STR_PAD_LEFT);
        };
    }

    /**
     * String convert to word
     *
     * @param  string  $string
     */
    public function toWord()
    {
        return function ($string): string {
            $string = preg_replace('/[^A-Za-z0-9]/', ' ', $string);

            return Str::title($string);
        };
    }

    /**
     * String convert to array of word
     *
     * @param  string  $string
     */
    public function toWordArray()
    {
        return function ($string): array {
            return preg_split('/ +/', $string);
        };
    }

    /**
     * String convert to array of word
     *
     * @param  string|array  $string
     */
    public function toArray()
    {
        return function ($string, $delimiter = ','): array {
            if (is_array($string)) {
                return $string;
            }

            return collect(preg_split('/'.$delimiter.'+/', $string))->filter(function ($item) {
                return ! empty($item) && ! is_null($item);
            })->unique()->toArray();
        };
    }
}
