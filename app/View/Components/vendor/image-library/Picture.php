<?php

namespace App\Components\vendor\image-library;

use App\Components\vendor\imageuse;
use Illuminate\Contracts\View\View;

Closure;

class Picture extends Image
{
    public function render(): View|Closure|string
    {
        return view('image-library::components.picture');
    }

    public function srcsetWebp(): ?string
    {
        $image = $this->imageConversion ?? $this->image;

        if (is_null($image)) {
            return null;
        }

        $responsiveVariants = $image->getResponsiveVariants(true);

        if ($responsiveVariants->isEmpty()) {
            return null;
        }

        return $responsiveVariants->map(function ($variant) {
            return "{$variant->url} {$variant->width}w";
        })->implode(', ');
    }
}
