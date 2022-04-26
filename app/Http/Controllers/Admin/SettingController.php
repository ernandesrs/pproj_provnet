<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Setting;
use Illuminate\Http\Request;

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
        //
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
