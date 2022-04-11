<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Message;
use App\Support\Thumb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->only(["first_name", "last_name", "username", "gender", "photo", "password", "password_confirmation"]);
        $validated = $this->updateValidator($data);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->first_name = $validated["first_name"];
        $user->last_name = $validated["last_name"];
        $user->username = $validated["username"];
        $user->gender = $validated["gender"];
        $user->password = isset($validated["password"]) ? Hash::make($validated['password']) : $user->password;
        
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

        $user->save();

        Message::success($user->first_name . ", seus dados foram atualizados com sucesso!", "Dados atualizados!")->fixed()->flash();
        return response()->json([
            "reload" => true
        ]);
    }

    protected function updateValidator(array $data)
    {
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
}
