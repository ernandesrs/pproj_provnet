<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $settings =  Setting::where("settings_for", Setting::FOR_SITE)->first();
        if (!$settings) {
            $settings = (new Setting())->init();
            $settings->save();
        };
        $this->settings = json_decode($settings->settings);
    }

    public function path(string $groupName = null, $params = [])
    {
        return (object) [
            "route" => Route::currentRouteName(),
            "group" => $groupName,
            "paths" => array_map(function ($item) {
                return $item ? (object) $item : null;
            }, $params)
        ];
    }
}
