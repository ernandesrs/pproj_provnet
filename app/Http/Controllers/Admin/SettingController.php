<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Setting;
use App\Support\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::where("settings_for", Setting::FOR_SITE)->first();
        if (!$settings) {
            $settings = (new Setting())->init();
            $settings->save();
        }

        return view("admin.settings-index", [
            "appPath" => $this->path(null, [
                "site" => [
                    "name" => "Site",
                    "url" => null
                ],
                "setting" => [
                    "name" => "Configurações",
                    "url" => route("admin.settings.index")
                ]
            ]),

            "seo" => (object)[
                "title" => "Configurações do site"
            ],

            "settings" => $settings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $validated = Validator::validate($request->only(["title", "description", "logo", "favicon"]), [
            "title" => ["required", "string", "min:5", "max:255"],
            "description" => ["required", "string", "min:20", "max:255"],
            "logo" => ["mimes:jpg,png,svg,webp"],
            "favicon" => ["mimes:jpg,png,svg,webp"],
        ]);

        $setting->settings = json_decode($setting->settings);
        $setting->settings->title = $validated["title"];
        $setting->settings->description = $validated["description"];
        if ($validated["logo"] ?? false) {
            /** @var \Illuminate\Http\UploadedFile $logo */
            $logo = $validated["logo"];

            $path = $logo->storeAs("public/images/site", "logo." . $logo->getClientOriginalExtension());
            if ($path) {
                Storage::delete($setting->settings->logo);
                $setting->settings->logo = $path;
            }
        }

        if ($validated["favicon"] ?? false) {
            /** @var \Illuminate\Http\UploadedFile $favicon */
            $favicon = $validated["favicon"];

            $path = $favicon->storeAs("public/images/site", "favicon." . $favicon->getClientOriginalExtension());
            if ($path) {
                Storage::delete($setting->settings->favicon);
                $setting->settings->favicon = $path;
            }
        }

        $setting->settings = json_encode($setting->settings);
        $setting->save();

        Message::success("As novas configurações do site foram salvas!")->fixed()->flash();
        return response()->json([
            "reload" => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
