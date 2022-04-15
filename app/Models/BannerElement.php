<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerElement extends Model
{
    use HasFactory;

    /**
     * @param array $data
     * @return bool
     */
    public function addButton(array $data): bool
    {
        $id = md5($data["text"]);
        $newButton[$id] = [
            "id" => $id,
            "text" => $data["text"],
            "link" => empty($data["link"]) ? null : ($data["local"] == "internal" ? $data["link"] : $data["local"] . "://" . $data["link"]),
            "local" => $data["local"],
            "style" => $data["style"],
            "size" => $data["size"],
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

    /**
     * @param string $id
     * @return bool
     */
    public function removeButton(string $id): bool
    {
        $config = json_decode($this->config);
        $buttons = (array) $config->buttons ?? [];
        unset($buttons[$id]);
        $config->buttons = (object) $buttons;
        $this->config = json_encode($config);
        if (!$this->config) return false;
        return $this->save();
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasButton(string $id): bool
    {
        return key_exists($id, json_decode($this->config)->buttons ?? []);
    }
}
