<?php

namespace App\Models;

use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{

    public function advices()
    {
        return $this->morphedByMany(\App\Models\Advice::class, 'taggable');
    }

    public function events()
    {
        return $this->morphedByMany(\App\Models\Event::class, 'taggable');
    }

    public function softwares()
    {
        return $this->morphedByMany(\App\Models\Software::class, 'taggable');
    }

    public function hardwares()
    {
        return $this->morphedByMany(\App\Models\Hardware::class, 'taggable');
    }
}
