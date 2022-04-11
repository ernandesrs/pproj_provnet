<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class BlogController extends Controller
{
    public function index()
    {
        return view("site.blog.list", [
            "appPath" => $this->path(),
            "head" => (new Seo())->render(
                config("app.name") . " | " . __("site.blog.title"),
                __("site.blog.subtitle", ["appName" => config("app.name")]),
                route("site.blog.index")
            )
        ]);
    }
}
