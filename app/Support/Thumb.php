<?php

namespace App\Support;

class Thumb
{
    /** @var \CoffeeCode\Cropper\Cropper $engine */
    private $engine;

    public function __construct(
        string $path = null,
        int $quality = 75,
        int $compressor = 5,
        bool $webP = false
    ) {
        $this->engine = new \CoffeeCode\Cropper\Cropper(($path ? storage_path("app/public/cache/{$path}") : storage_path("app/public/cache")), $quality, $compressor, $webP);
    }

    /**
     * @param string $imagePath
     * @param int $width
     * @param int|null $height
     * @return string|null
     */
    public function make(string $imagePath, int $width, ?int $height = null): ?string
    {
        return $this->cleanPath($this->engine->make($imagePath, $width, $height));
    }

    /**
     * @param string|null $imagePath
     * @return void
     */
    public function clear(?string $imagePath = null)
    {
        $this->engine->flush($imagePath);
        return;
    }

    /**
     * @param string|null $path
     * @return string|null
     */
    private function cleanPath(?string $path): ?string
    {
        return $path ? str_replace(storage_path("app/public/"), "", $path) : null;
    }
}
