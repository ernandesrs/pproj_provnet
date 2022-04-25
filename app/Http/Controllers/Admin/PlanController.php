<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Plan;
use App\Support\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PlanController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = Plan::all();

        return view("admin.plans-index", [
            "appPath" => $this->path(null, [
                "site" => [
                    "name" => "Site",
                    "url" => null
                ],
                "plans" => [
                    "name" => "Planos",
                    "url" => route("admin.plans.index")
                ]
            ]),

            "actions" => (object) [
                "new" => (object) [
                    "text" => "Novo plano",
                    "url" => route("admin.plans.create")
                ],
            ],

            "seo" => (object)[
                "title" => "Lista de planos"
            ],

            "plans" => $plans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.plan-edit", [
            "appPath" => $this->path(null, [
                "site" => [
                    "name" => "Site",
                    "url" => null
                ],
                "plans" => [
                    "name" => "Planos",
                    "url" => route("admin.plans.index")
                ],
                "new" => [
                    "name" => "Novo plano",
                    "url" => route("admin.plans.create")
                ]
            ]),

            "seo" => (object)[
                "title" => "Cadastrar novo plano"
            ],
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
        $validated = $this->planValidate($request->only(["title", "category", "price", "recurrence", "download_speed", "upload_speed", "limit", "telephone_line", "free_router"]));

        if (!(new Plan())->add($validated)) {
            return response()->json([
                "message" => Message::error("Houve um erro inesperado ao tentar criar novo plano.")->get()
            ]);
        }

        Message::success("O plano '<strong>{$validated['title']}</strong>' foi cadastrado com sucesso!")->fixed()->flash();
        return response()->json([
            "redirect" => route("admin.plans.index")
        ]);
    }

    /**
     * @param array $data
     * @param null|Plan $plan
     * 
     * @return array
     */
    protected function planValidate(array $data, Plan $plan = null): array
    {
        $rules = [
            "category" => ["required", Rule::in(Plan::ALLOWED_CATEGORIES)],
            "price" => ["required", "string"],
            "recurrence" => ["required", Rule::in(Plan::ALLOWED_RECURRENCES)],
            "download_speed" => ["required", "string"],
            "upload_speed" => ["required", "string"],
            "limit" => [],
            "telephone_line" => [],
            "free_router" => [],
            "publish" => [],
        ];

        if ($plan) {
            $rules += [
                "title" => ["required", "min:2", "max:45", Rule::unique("plans")->ignore($plan->id)],
            ];
        } else {
            $rules += [
                "title" => ["required", "min:2", "max:45", "unique:plans,title"],
            ];
        }

        return Validator::validate($data, $rules);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        return view("admin.plan-edit", [
            "appPath" => $this->path(null, [
                "site" => [
                    "name" => "Site",
                    "url" => null
                ],
                "plans" => [
                    "name" => "Planos",
                    "url" => route("admin.plans.index")
                ],
                "new" => [
                    "name" => "Editar plano",
                    "url" => route("admin.plans.create")
                ]
            ]),

            "seo" => (object)[
                "title" => "Editar o plano {$plan->title}"
            ],

            "plan" => $plan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $this->planValidate($request->only(["title", "category", "price", "recurrence", "download_speed", "upload_speed", "limit", "telephone_line", "free_router", "publish"]), $plan);

        if (!$plan->add($validated)) {
            return response()->json([
                "message" => Message::error("Houve um erro não esperado ao tentar salvar os dados informados. Um log foi registrado.")->get()
            ]);
        }

        Message::success("O plano '<strong>{$plan->title}</strong>' foi atualizado com sucesso.")->fixed()->flash();
        return response()->json([
            "redirect" => route("admin.plans.index")
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        if ($plan->subscribers()) {
            return response()->json([
                "message" => Message::warning("Não é possível excluir, o plano possui assinantes ativos.", "Plano possui assinantes")->get()
            ]);
        }

        $planName = $plan->title;
        $plan->delete();

        Message::warning("O '<strong>{$planName}</strong>' foi excluído com sucesso!")->fixed()->flash();
        return response()->json([
            "redirect" => route("admin.plans.index")
        ]);
    }
}
