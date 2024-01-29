<?php

namespace App\Helpers;

use Illuminate\Contracts\Support\Htmlable;

class CustomHtmlable implements Htmlable
{
    protected $html;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function toHtml(): string
    {
        return $this->html;
    }
}
