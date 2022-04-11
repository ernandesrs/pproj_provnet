<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function icons()
    {
        return view("admin.guide.icons", [
            "appPath" => $this->path("guide", [
                "guide" => [
                    "name" => "Guia",
                    "url" => null
                ],
                "icons" => [
                    "name" => "Ícones",
                    "url" => route("admin.guide.icons")
                ],
                "list" => [
                    "name" => "Lista de ícones",
                    "url" => null
                ],
            ]),

            "seo" => (object)[
                "title" => "Lista de ícones"
            ],
        ]);
    }
}
