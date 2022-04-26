<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public const FOR_SITE = "site";
    public const FOR_ADMIN = "admin";

    /**
     * @return Setting
     */
    public function init(): Setting
    {
        copy(resource_path("img/favicon.ico"), public_path("img/site/favicon.ico"));
        copy(resource_path("img/logo.png"), public_path("img/site/logo.png"));

        $settings = [
            "logo" => "img/site/logo.png",
            "favicon" => "img/site/favicon.png",
            "title" => "Lorem ipsum dolor sit amet consectetur",
            "description" => "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit doloribus obcaecati aperiam rem voluptatem natus atque inventore facere fuga sint, ducimus quae nesciunt tempore quas porro quis recusandae?",
        ];

        $this->settings_for = self::FOR_SITE;
        $this->settings = json_encode($settings);

        return $this;
    }
}
