<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerElement extends Model
{
    use HasFactory;

    public function addButton(array $data)
    {
        $id = md5($data["text"]);
        $newButton[$id] = [
            "id" => $id,
            "text" => $data["text"],
            "link" => $data["link"] ?? null,
            "style" => $data["style"],
            "target" => $data["target"],
        ];

        $config = json_decode($this->config);
        $buttons = ($config->buttons ?? []);

        if (key_exists($id, $buttons))
            return false;

        $config->buttons = ((array)$buttons + $newButton);

        $config = json_encode($config);
        if (!$config)
            return false;

        $this->config = $config;

        return $this->save();
    }
}
