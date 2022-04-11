<?php

namespace App\Support;

class Seo
{
    private $optmizer;

    public function __construct()
    {
        $this->optmizer = new \CoffeeCode\Optimizer\Optimizer();
    }

    public function render(string $title, string $description, string $url, string $image = "", bool $follow = true)
    {
        return $this->optmizer
            ->optimize($title, $description, $url, $image, $follow)
            ->render();
    }
}