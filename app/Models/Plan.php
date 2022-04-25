<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public const CATEGORY_FIBER = 'fiber';
    public const CATEGORY_RADIO = 'radio';
    public const CATEGORY_CUSTOM = 'custom';
    public const ALLOWED_RECURRENCES = [1, 2, 3, 6, 12];
    public const ALLOWED_CATEGORIES = ['fiber', 'radio', 'custom'];

    use HasFactory;

    /**
     * @param array $data validated data
     * @return bool
     */
    public function add(array $data): bool
    {
        $this->title = $data["title"];
        $this->slug = \Illuminate\Support\Str::slug($data["title"]);
        $this->category = $data["category"];
        $this->price = $data["price"];
        $this->recurrence = $data["recurrence"];
        $this->published = isset($data["publish"]) ? true : false;
        $this->tag = "none";
        $this->content = json_encode($this->content($data));

        return $this->save();
    }

    /**
     * @param array $data validated data
     * @return array
     */
    private function content(array $data): array
    {
        return [
            "download_speed" => $data["download_speed"],
            "upload_speed" => $data["upload_speed"],
            "limit" => empty($data["limit"]) ? null : $data["limit"],
            "telephone_line" => isset($data["telephone_line"]) ? true : false,
            "free_router" => isset($data["free_router"]) ? true : false,
        ];
    }

    public function subscribers()
    {
        return [];
    }
}
