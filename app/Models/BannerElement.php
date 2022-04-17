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

        if ($this->hasButtonWithText($data["text"]))
            return false;

        $config->buttons = ((array) $buttons + $newButton);

        $config = json_encode($config);
        if (!$config)
            return false;

        $this->config = $config;

        return $this->save();
    }

    /**
     * @param array $data
     * @param string $buttonId
     * @return bool
     */
    public function updateButton(array $data, string $buttonId): bool
    {
        $config = json_decode($this->config);
        $buttons = (array) $config->buttons;

        $buttons[$buttonId]->text = $data["text"];
        $buttons[$buttonId]->link = empty($data["link"]) ? null : ($data["local"] == "internal" ? $data["link"] : $data["local"] . "://" . $data["link"]);
        $buttons[$buttonId]->local = $data["local"];
        $buttons[$buttonId]->style = $data["style"];
        $buttons[$buttonId]->size = $data["size"];
        $buttons[$buttonId]->target = $data["target"];

        $config->buttons = (object) $buttons;
        $this->config = json_encode($config);

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
     * @return object|null
     */
    public function getButton(string $id): ?object
    {
        $config = json_decode($this->config);
        $buttons = empty($config->buttons) ? [] : (array) $config->buttons;
        return empty($buttons[$id]) ? null : $buttons[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasButton(string $id): bool
    {
        return key_exists($id, (array)(json_decode($this->config)->buttons ?? []));
    }

    /**
     * @param string $text
     * @return bool
     */
    public function hasButtonWithText(string $text): bool
    {
        $buttons = json_decode($this->config)->buttons ?? [];
        foreach ($buttons as $button) {
            if ($button->text == $text) return true;
        }

        return false;
    }
}
