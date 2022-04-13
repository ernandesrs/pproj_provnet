<?php

namespace App\Models;

use App\Models\BannerElement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    public function addElement(array $data)
    {
        $element = new BannerElement();
        $element->banners_id = $this->id;
        $element->title = $data["title"];
        $element->subtitle = $data["subtitle"];
        $element->config = $this->images($data["images"]);
        $element->config += [
            "interval" => $data["interval"]
        ];

        /**
         * config to json
         */
        $element->config = json_encode($element->config);

        return $element->save();
    }

    /**
     * @param array $images
     * @return array
     */
    protected function images(array $images)
    {
        $arr = [];
        if (count($images) == 1) {
            $arr[] = [
                "type" => "main",
                "image" => $images[0]
            ];
        } else {
            foreach ($images as $key => $image) {
                $arr[] = [
                    "type" => $key == 0 ? "background" : ($key == 1 ? "main" : "last"),
                    "image" => $image
                ];
            }
        }
        return ["images" => $arr];
    }

    public function elements()
    {
        return $this->hasMany(BannerElement::class, "banners_id", "id");
    }

    /**
     * @return bool
     */
    public function isSystemBanner(): bool
    {
        return $this->protection == "system";
    }
}
