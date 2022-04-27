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
        foreach (["site", "admin"] as $dir) {
            $path = storage_path("/app/public/images/{$dir}");
            if (!is_dir($path)) {
                mkdir($path);
            }
        }

        copy(resource_path("img/favicon.svg"), storage_path("app/public/images/site/favicon.svg"));
        copy(resource_path("img/logo.svg"), storage_path("app/public/images/site/logo.svg"));

        $settings = [
            "logo" => "images/site/logo.svg",
            "favicon" => "images/site/favicon.svg",
            "title" => "Lorem ipsum dolor sit amet consectetur",
            "description" => "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit doloribus obcaecati aperiam rem voluptatem natus atque inventore facere fuga sint, ducimus quae nesciunt tempore quas porro quis recusandae?",
        ];

        $this->settings_for = self::FOR_SITE;
        $this->settings = json_encode($settings);

        return $this;
    }
}
