<?php

namespace App\Concerns;

use Illuminate\Support\Arr;

trait HasTags
{
    public function showTags(): array
    {
        if (! $this->relationLoaded('tags')) {
            return [];
        }

        $tagsDisplay = '';
        $limitedTags = 2;

        $additionalTags = $this->tags()->count() > $limitedTags ? ($this->tags()->count() - $limitedTags) : 0;
        $tagsDisplay = Arr::toString($this->tags()->limit($limitedTags)->get()->map(function ($tag) {
            return $tag->name;
        })->all());

        if ($additionalTags) {
            $tagsDisplay .= (' '.trans('global.and_others', ['attribute' => $additionalTags]));
        }

        return [
            'tags_display' => $tagsDisplay,
            'additional_tags' => $additionalTags,
            'limited_tags' => $limitedTags,
        ];
    }
}
