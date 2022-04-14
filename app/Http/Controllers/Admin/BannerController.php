<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Banner;
use App\Models\BannerElement;
use App\Support\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BannerController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();

        return view("admin.banners.index", [
            "appPath" => $this->path(null, [
                "site" => [
                    "name" => "Site",
                    "url" => null
                ],
                "banners" => [
                    "name" => "Banners",
                    "url" => route("admin.banners.index")
                ]
            ]),

            "actions" => (object) [
                "new" => (object) [
                    "text" => "Novo banner",
                    "url" => route("admin.banners.create")
                ],
            ],

            "seo" => (object)[
                "title" => "Lista de banners"
            ],

            "banners" => $banners
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.banners.edit", [
            "appPath" => $this->path(null, [
                "site" => [
                    "name" => "Site",
                    "url" => null
                ],
                "banners" => [
                    "name" => "Banners",
                    "url" => route("admin.banners.index")
                ],
                "new" => [
                    "name" => "Novo banner",
                    "url" => route("admin.banners.create")
                ]
            ]),

            "seo" => (object)[
                "title" => "Novo banner"
            ],

            "pages" => ["site.index", "site.about", "site.contact", "site.blog.index"]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::validate(
            $request->only(["page", "name"]),
            [
                "page" => ["required", "unique:banners,route_name", Rule::in(["site.index", "site.about", "site.contact", "site.blog.index"])],
                "name" => ["required"]
            ],
            [
                "page.in" => __("Escolha uma página válida."),
                "page.unique" => __("Já existe um banner para a página selecionada."),
            ]
        );

        $banner = new Banner();
        $banner->name = $validated["name"];
        $banner->route_name = $validated["page"];
        $banner->protection = "none";
        $banner->save();

        Message::success("Novo banner '<strong>{$banner->name}</strong>' cadastrado com sucesso! Agora adicione elementos ao banner.")->fixed()->flash();
        return response()->json([
            "redirect" => route("admin.banners.edit", ["banner" => $banner->id])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeElement(Banner $banner, Request $request)
    {
        $validated = $this->bannerElementValidator($request->only(["title", "subtitle", "interval", "image1", "image2", "image3"]));
        $files[] = $validated["image1"] ?? null;
        $files[] = $validated["image2"] ?? null;
        $files[] = $validated["image3"] ?? null;
        $imagesPath = [];

        /**
         * store images
         */
        foreach ($files as $file) {
            if ($file) {
                $path = $file->store("public/images/banners/" . date("Y-m"));

                if (!$path) {
                    return;
                }

                $imagesPath[] = $path;
            }
        }

        if (!$banner->addElement($validated + ["images" => $imagesPath])) {
            return;
        }

        Message::success("Um novo elemento para o banner foi adicionado com sucesso!", "Novo elemento criado!")->fixed()->flash();
        return response()->json([
            "redirect" => route("admin.banners.edit", ["banner" => $banner->id]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        return view("admin.banners.edit", [
            "appPath" => $this->path(null, [
                "site" => [
                    "name" => "Site",
                    "url" => null
                ],
                "banners" => [
                    "name" => "Banners",
                    "url" => route("admin.banners.index")
                ],
                "new" => [
                    "name" => "Editar banner",
                    "url" => route("admin.banners.edit", ["banner" => $banner->id])
                ]
            ]),

            "seo" => (object)[
                "title" => "Editar banner " . $banner->name
            ],

            "banner" => $banner
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @param  \App\Models\BannerElement  $bannerElement
     * @return \Illuminate\Http\Response
     */
    public function editElement(Banner $banner, BannerElement $bannerElement)
    {
        return view("admin.banners.edit", [
            "appPath" => $this->path(null, [
                "site" => [
                    "name" => "Site",
                    "url" => null
                ],
                "banners" => [
                    "name" => "Banners",
                    "url" => route("admin.banners.index")
                ],
                "edit" => [
                    "name" => "Editar banner",
                    "url" => route("admin.banners.edit", ["banner" => $banner->id])
                ],
                "editElement" => [
                    "name" => "Editar elemento de banner",
                    "url" => route("admin.banners.editElement", ["banner" => $banner->id, "bannerElement" => $bannerElement->id])
                ]
            ]),

            "seo" => (object)[
                "title" => "Editar banner " . $banner->name
            ],

            "banner" => $banner,
            "bannerElement" => $bannerElement
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BannerElement  $bannerElement
     * @return \Illuminate\Http\Response
     */
    public function updateElement(Request $request, BannerElement $bannerElement)
    {
        $validated = $this->bannerElementValidator($request->only(["title", "subtitle", "interval"]));

        $bannerElement->title = $validated["title"];
        $bannerElement->subtitle = $validated["subtitle"];

        $config = json_decode($bannerElement->config);
        $config->interval = $validated["interval"];
        $bannerElement->config = json_encode($config);

        $bannerElement->save();

        Message::info("O elemento de banner foi atualizado com sucesso!")->fixed()->flash();
        return response()->json([
            "redirect" => route("admin.banners.edit", ["banner" => $bannerElement->banners_id])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        if ($banner->isSystemBanner()) {
            return response()->json([
                "message" => Message::warning("Este é um banner padrão e não pode ser excluído.", "Banner protegido")->get()
            ]);
        }

        $bannerElements = $banner->elements()->get();
        if ($bannerElements->count()) {
            foreach ($bannerElements as $bannerElement) {
                $images = (json_decode($bannerElement->config))->images;
                foreach ($images as $image) {
                    Storage::delete($image->image);
                }
            }
        }

        $bannerName = $banner->name;
        $banner->delete();

        Message::warning("O banner '<strong>{$bannerName}</strong>' e todos seus elementos foram excluídos com sucesso!", "Banner excluído!")->fixed()->flash();
        return response()->json([
            "redirect" => route("admin.banners.index")
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BannerElement  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroyElement(BannerElement $bannerElement)
    {
        $config = json_decode($bannerElement->config);
        $images = $config->images ?? [];
        foreach ($images as $image) {
            Storage::delete($image->image);
        }

        $bannerElement->delete();

        Message::warning("O elemento de banner foi excluído com sucesso!", "Excluído com sucesso!")->fixed()->flash();
        return response()->json([
            "redirect" => route("admin.banners.edit", ["banner" => $bannerElement->banners_id])
        ]);
    }

    public function bannerElementValidator(array $data)
    {
        $rules = [
            "title" => ["required", "min:12", "max:45"],
            "subtitle" => ["required", "min:24", "max:255"],
            "interval" => ["required", "numeric", "min:2500", "max:60000"]
        ];

        if ($data["image1"] ?? false) {
            $rules += ["image1" => ['mimes:jpg,png,svg', "max:1024"]];
        }

        if ($data["image2"] ?? false) {
            $rules += ["image2" => ['mimes:jpg,png,svg']];
        }

        if ($data["image3"] ?? false) {
            $rules += ["image3" => ['mimes:jpg,png,svg', "max:1024"]];
        }

        return Validator::validate($data, $rules);
    }
}
