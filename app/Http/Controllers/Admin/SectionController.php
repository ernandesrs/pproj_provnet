<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();

        return view("admin.sections-index", [
            "appPath" => $this->path(null, [
                "site" => [
                    "name" => "Site",
                    "url" => null
                ],
                "sections" => [
                    "name" => "Seções",
                    "url" => route("admin.sections.index")
                ]
            ]),

            "actions" => (object) [
                "new" => (object) [
                    "text" => "Nova seção",
                    "url" => route("admin.sections.create")
                ],
            ],

            "seo" => (object)[
                "title" => "Lista de seções"
            ],

            "sections" => $sections
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
     * @param  \App\Models\Section  $sections
     * @return \Illuminate\Http\Response
     */
    public function show(Section $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $sections
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $sections
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $sections)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $sections
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $sections)
    {
        //
    }
}
