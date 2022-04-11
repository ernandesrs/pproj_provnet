<?php

use App\Models\User;


/**
 * Gera/obtém um thumbnail para a foto de um usuário
 * @param User|null $user
 * @param string $size
 * @return string
 */
function user_thumb(?User $user = null, string $size = "medium"): string
{
    $imagePath = $user && $user->photo ? (file_exists(storage_path("app/{$user->photo}")) ? storage_path("app/{$user->photo}") : null) : null;
    if (!$imagePath) $imagePath = resource_path("img/default-user.png");

    $function = "thumb_" . $size;
    
    return function_exists($function) ? $function("photos", $imagePath) : thumb_medium("photos", $imagePath);
}

/**
 * Gera/obtém um thumbnail para a foto do usuário
 * @param User|null $user
 * @return string
 */
function user_thumb_profile(?User $user = null): string
{
    return user_thumb($user, "normal");
}

/**
 * Gera/obtém um thumbnail para a foto do usuário
 * @param User|null $user
 * @return string
 */
function user_thumb_list(?User $user = null): string
{
    return user_thumb($user, "small");
}
