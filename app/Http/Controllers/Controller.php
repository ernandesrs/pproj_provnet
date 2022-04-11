<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
