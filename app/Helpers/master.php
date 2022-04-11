<?php

require_once __DIR__ . "/users.php";

use App\Support\Thumb;
use Illuminate\Support\Facades\Storage;

/**
 * @param string|null $key chave para a classe do ícone definido em config/icons.php
 * @param bool $class se true retorna a classe do ícone, se for false mostra o ícone
 */
function icon(?string $key = null, bool $class = false)
{
    $icon = $key ? "icon " . app('config')->get("icons." . $key, null) : "icon";

    if ($class) return $icon;

    echo "<i class='{$icon}'></i>";
    return;
}

/**
 * Mostra ícone com um ícone alternativo
 * @param string $key
 * @param string $keyAlt
 */
function icons(string $key, string $keyAlt)
{
    $icon = icon($key, true);
    $iconAlt = icon($keyAlt, true);

    echo "<i class='{$icon}' data-icon='{$iconAlt}'></i>";
    return;
}

/**
 * Gera/obtem um thumbnail do tamanho informado para uma imagem
 * @param string|null $dir diretório da imagem
 * @param string|null $imagePath caminho da imagem
 * @param int $width largura do thumbnail
 * @param int|null $height altura do thumbnail
 * @return string url pública para a imagem
 */
function thumbMaker(?string $dir = null, ?string $imagePath = null, int $width, ?int $height = null, int $quality = 75, int $compressor = 5)
{
    return Storage::url((new Thumb($dir, $quality, $compressor))->make($imagePath, $width, $height));
}

/**
 * @param string|null $dir
 * @param string|null $imagePath
 * @param int $quality
 * @param int $compressor
 * @return string
 */
function thumb_small(?string $dir = null, ?string $imagePath = null)
{
    return thumbMaker($dir, $imagePath, 75, 75);
}

/**
 * @param string|null $dir
 * @param string|null $imagePath
 * @param int $quality
 * @param int $compressor
 * @return string
 */
function thumb_normal(?string $dir = null, ?string $imagePath = null)
{
    return thumbMaker($dir, $imagePath, 275, 275);
}

/**
 * @param string|null $dir
 * @param string|null $imagePath
 * @param int $quality
 * @param int $compressor
 * @return string
 */
function thumb_medium(?string $dir = null, ?string $imagePath = null)
{
    return thumbMaker($dir, $imagePath, 475, 475);
}
