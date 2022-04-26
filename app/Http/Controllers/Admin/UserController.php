<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Support\Message;
use App\Support\Thumb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::whereNotNull("id");
        $data = $request->only(["filter", "search", "status"]);
        if ($data["filter"] ?? null) {
            if ($data["search"] ?? null) {
                echo "aqui 1";
                $users->whereRaw("MATCH(first_name, last_name, username, email) AGAINST('{$data["search"]}')");
            }

            if (($data["status"] ?? null) && in_array($data["status"], \App\Models\User::ALLOWED_STATUS)) {
                $users->where("status", "=", $data["status"]);
            }
        }

        return view("admin.users-index", [
            "appPath" => $this->path(null, [
                "dashboard" => [
                    "name" => "dashboard",
                    "url" => null,
                ],
                "users" => [
                    "name" => "Usuários",
                    "url" => route("admin.users.index")
                ],
                "filter" => isset($data["filter"]) && $data["filter"] ? [
                    "name" => "Filtrando usuários",
                    "url" => null,
                ] : null,
            ]),

            "seo" => (object)[
                "title" => isset($data["filter"]) && $data["filter"] ? "Filtrando usuários" : "Lista de usuários"
            ],

            "users" => $users->orderBy("level", "desc")->orderBy("created_at", "desc")->paginate(12)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where("id", $id)->first();
        if (!$user) {
            Message::error("Usuário não foi encontrado ou já foi excluído.", "Erro!")->fixed()->flash();
            return back();
        }

        return view("admin.user-edit", [
            "appPath" => $this->path(null, [
                "dashboard" => [
                    "name" => "dashboard",
                    "url" => null,
                ],
                "users" => [
                    "name" => "Usuários",
                    "url" => route("admin.users.index"),
                ],
                "edit" => [
                    "name" => "Gerenciando usuário",
                    "url" => route("admin.users.edit", ["id" => $user->id]),
                ],
            ]),

            "seo" => (object)[
                "title" => "Gerenciando " . $user->first_name
            ],

            "user" => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $this->userValidate($request);
        $user = User::find($id);

        if (!$user) {
            Message::error("Usuário não foi encontrado ou já foi excluído.", "Erro!")->fixed()->flash();
            return response()->json([
                "redirect" => route("admin.users.index")
            ]);
        }

        if (!Gate::allows("user-to-user-permissions", $user)) {
            return response()->json([
                "message" => Message::warning("Você não possui esse tipo de permissão sobre este usuário.", "Sem permissão!")->get()
            ]);
        }

        /**
         * photo upload
         */
        if ($photo = $validated["photo"] ?? null) {
            $path = $photo->storeAs("public/images/photos/" . date("Y-m"), $photo->getClientOriginalName());
            if (!$path) {
                return response()->json([
                    "message" => Message::error("Houve um erro ao fazer upload da foto de perfil.", "Falha no upload!")->get()
                ]);
            }

            if ($user->photo) {
                (new Thumb("photos"))->clear($user->photo);
                Storage::delete($user->photo);
            }

            $user->photo = $path;
        }

        $user->first_name = $validated["first_name"];
        $user->last_name = $validated["last_name"];
        $user->username = $validated["username"];
        $user->gender = $validated["gender"];
        if (!empty($validated["password"])) {
            $user->password = Hash::make($validated["password"]);
        }

        if (!$user->isDirty()) {
            return response()->json([
                "message" => Message::warning("Nada para atualizar, pois nenhuma informação foi alterada.", "Opa!")->fixed()->get(),
            ]);
        }

        $user->save();

        Message::success("Os dados do usuário foram atualizados com sucesso.", "Atualizado!")->fixed()->flash();
        return response()->json([
            "reload" => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "message" => Message::error("Usuário não foi encontrado ou já foi excluído.", "Erro!")->get()
            ]);
        }

        if (!Gate::allows("user-to-user-permissions", $user)) {
            return response()->json([
                "message" => Message::warning("Você não possui esse tipo de permissão sobre este usuário.", "Sem permissão!")->get()
            ]);
        }

        if ($photo = $user->photo) {
            Storage::delete($photo);
            (new Thumb("photos"))->clear($user->photo);
        }

        $user->delete();

        Message::warning("O usuário foi excluído com sucesso!", "Excluído!")->fixed()->flash();
        return response()->json([
            "redirect" => route("admin.users.index")
        ]);
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $data = Validator::validate($request->only(["search", "status"]), [
            "search" => ["nullable", "string"],
            "status" => ["nullable", "string", Rule::in((\App\Models\User::ALLOWED_STATUS + ["all" => "all"]))],
        ]);

        if (!$data["search"] && $data["status"] == "all") {
            return response()->json([
                "redirect" => route("admin.users.index")
            ]);
        }

        $data += [
            "filter" => true
        ];

        return response()->json([
            "redirect" => route("admin.users.index", $data)
        ]);
    }

    /**
     * @param mixed $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function promote($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "message" => Message::error("Usuário não foi encontrado ou já foi excluído.", "Erro!")->get()
            ]);
        }

        if (!Gate::allows("user-to-user-permissions", $user)) {
            return response()->json([
                "message" => Message::warning("Você não possui esse tipo de permissão sobre este usuário.", "Sem permissão!")->get()
            ]);
        }

        if (in_array($user->status, [User::STATUS_ARCHIVED, User::STATUS_BANNED]) || !$user->email_verified_at) {
            return response()->json([
                "message" => Message::warning("Usuário não pode ser promovido pois está banido, arquivado ou email não verificado", "Impossível promover!")->get()
            ]);
        }

        $user->level = User::LEVEL_ADMIN;
        $user->save();

        Message::success("Usuário promovido a administrador com sucesso!", "Promovido!")->fixed()->flash();
        return response()->json([
            "reload" => true
        ]);
    }

    /**
     * @param mixed $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function demote($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "message" => Message::error("Usuário não foi encontrado ou já foi excluído.", "Erro!")->get()
            ]);
        }

        if (!Gate::allows("user-to-user-permissions", $user)) {
            return response()->json([
                "message" => Message::warning("Você não possui esse tipo de permissão sobre este usuário.", "Sem permissão!")->get()
            ]);
        }

        $user->level = User::LEVEL_USER;
        $user->save();

        Message::success("Usuário rebaixado com sucesso!", "Rebaixado!")->fixed()->flash();
        return response()->json([
            "reload" => true
        ]);
    }

    /**
     * @param mixed $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function ban(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "message" => Message::error("Usuário não foi encontrado ou já foi excluído.", "Erro!")->get()
            ]);
        }

        if (!Gate::allows("user-to-user-permissions", $user)) {
            return response()->json([
                "message" => Message::warning("Você não possui esse tipo de permissão sobre este usuário.", "Sem permissão!")->get()
            ]);
        }

        if ($user->status == \App\Models\User::STATUS_BANNED) {
            return response()->json([
                "message" => Message::warning("O usuário já está banido.", "Erro ao banir!")->get()
            ]);
        }

        $validated = $this->banValidate($request);

        $user->ban($validated);

        Message::success("Usuário foi banido com sucesso!", "Banido!")->fixed()->flash();
        return response()->json([
            "reload" => true
        ]);
    }

    /**
     * @param mixed $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function unban($id)
    {
        /** @va \App\Models\User $user */
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "message" => Message::error("Usuário não foi encontrado ou já foi excluído.", "Erro!")->get()
            ]);
        }

        if (!Gate::allows("user-to-user-permissions", $user)) {
            return response()->json([
                "message" => Message::warning("Você não possui esse tipo de permissão sobre este usuário.", "Sem permissão!")->get()
            ]);
        }

        if ($user->status != \App\Models\User::STATUS_BANNED) {
            return response()->json([
                "message" => Message::warning("Este usuário não está banido.", "Erro ao desbanir!")->get()
            ]);
        }

        $user->unban();

        Message::success("Usuário foi desbanido com sucesso!", "Desbanido!")->fixed()->flash();
        return response()->json([
            "reload" => true
        ]);
    }

    /**
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function bans($id)
    {
        $user = User::find($id);
        if (!$user) {
            Message::error("Oops! Parece que este usuário não existe ou pode ter sido excluído!", "Usuário não encontrado!")->fixed()->flash();
            return response()->json([
                "reload" => true
            ]);
        }

        $bans = $user->bans();
        if ($bans->count() == 0) {
            return response()->json([
                "message" => Message::info("Este usuário não possui histórico de banimentos.", "Sem histórico!")->get()
            ]);
        }

        return response()->json([
            "bans" => $bans->get()
        ]);
    }

    /**
     * @param Request $request
     * 
     * @return array
     */
    public function userValidate(Request $request)
    {
        $data = $request->only([
            "first_name",
            "last_name",
            "username",
            "gender",
            "photo",
            "password",
            "password_confirmation"
        ]);

        $rules = [
            "first_name" => ["required", "min:5"],
            "last_name" => ["required", "min:5"],
            "username" => ["required", "min:5"],
            "gender" => ["required", Rule::in(User::ALLOWED_GENDERS)],
            "photo" => ["mimes:jpg,jpeg,png", "max:1024"],
        ];

        if (!empty($data["password"]) || !empty($data["password_confirmation"])) {
            $rules += [
                "password" => ["confirmed"],
            ];
        }

        return Validator::validate($data, $rules, [
            "first_name.required" => "Campo obrigatório",
            "last_name.required" => "Campo obrigatório",
            "username.required" => "Campo obrigatório",
            "gender.required" => "Campo obrigatório",
            "password.confirmed" => "As senhas não correspondem",
        ]);
    }

    public function banValidate(Request $request)
    {
        $data = $request->only([
            "type",
            "days",
            "description"
        ]);

        $rules = [
            "type" => ["required", Rule::in(\App\Models\Ban::ALLOWED_TYPES)],
            "description" => ["required", "min:12"]
        ];

        if ($data["type"] == \App\Models\Ban::TYPE_TEMP) {
            $rules += [
                "days" => ["required", "numeric", "min:1"]
            ];
        }

        return Validator::validate($data, $rules, [
            "type" => "Informe um tipo válido",
            "days.required" => "Informe o tempo de banimento",
            "days.number" => "Valor precisa ser numérico",
            "days.min" => "Valor não pode ser menor que 1",
            "description.required" => "Informe um motivo para o banimento",
            "description.min" => "Descriçao muito curta",
        ]);
    }
}
