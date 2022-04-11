<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class BlogController extends AdminController
{
    public function categories()
    {
        return view("admin.index", [
            "appPath" => $this->path("blog", [
                "blog" => [
                    "name" => "Blog",
                    "url" => null
                ],
                "categories" => [
                    "name" => "Categorias",
                    "url" => route("admin.blog.categories")
                ]
            ]),

            "seo" => (object)[
                "title" => "Lista de categorias"
            ],
        ]);
    }

    public function comments()
    {
        return view("admin.index", [
            "appPath" => $this->path("blog", [
                "blog" => [
                    "name" => "Blog",
                    "url" => null
                ],
                "comments" => [
                    "name" => "Comentários",
                    "url" => route("admin.blog.comments")
                ],
            ]),

            "seo" => (object)[
                "title" => "Lista de comentários"
            ],
        ]);
    }

    public function articles()
    {
        return view("admin.index", [
            "appPath" => $this->path("blog", [
                "blog" => [
                    "name" => "Blog",
                    "url" => null
                ],
                "articles" => [
                    "name" => "Artigos",
                    "url" => route("admin.blog.articles")
                ],
            ]),

            "seo" => (object)[
                "title" => "Lista de artigos"
            ],
        ]);
    }
}
