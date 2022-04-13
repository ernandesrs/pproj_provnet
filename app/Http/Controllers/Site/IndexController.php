<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use App\Models\Banner;
use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\ContactMessageReceivedNotification;
use App\Support\Message;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public function index()
    {
        $banner = Banner::where("route_name", Route::currentRouteName())->get()->first();

        return view("site.index", [
            "appPath" => $this->path(),
            "head" => (new Seo())->render(
                config("app.name") . " | " . __("site.index.title"),
                __("site.index.subtitle"),
                route("site.index")
            ),
            "banner" => $banner
        ]);
    }

    public function about()
    {
        return view("site.about", [
            "appPath" => $this->path(),
            "head" => (new Seo())->render(
                config("app.name") . " | " . __("site.about.title"),
                __("site.about.subtitle", ["appName" => config("app.name")]),
                route("site.about")
            )
        ]);
    }

    public function contact(Request $request)
    {
        $validated = $this->contactValidate($request);

        /** @var \App\Models\ContactMessage $contact */
        $contact = (new ContactMessage())->new($validated);
        if ($contact->hasSentToday()) {
            return response()->json([
                "message" => Message::info("Obrigado {$validated['name']}, você já enviou uma mensagem hoje. Aguarde nosso retorno.", "Mensagem já enviada!")->get()
            ]);
        }

        $contact->save();

        Mail::to(env("MAIL_CONTACT_TO_ADDRESS"), env("MAIL_CONTACT_TO_ADDRESS"))
            ->send((new ContactMessageMail($contact))->build());

        /**
         * Notifica os administradores sobre a mensagem recebida
         * @var User $admin
         * */
        $admins = User::where("level", ">=", User::LEVEL_ADMIN)->get();
        if ($admins->count()) {
            foreach ($admins as $admin) {
                $admin->notify((new ContactMessageReceivedNotification($contact)));
            }
        }

        return response()->json([
            "message" => Message::success("Olá " . $validated["name"] . ", sua mensagem foi enviada com sucesso.", "Mensagem enviada")->get()
        ]);
    }

    private function contactValidate(Request $request)
    {
        return Validator::validate($request->only(["name", "email", "subject", "message"]), [
            "name" => ["required", "min:5"],
            "email" => ["required", "email"],
            "subject" => ["required", "min:5"],
            "message" => ["required", "min:5"],
        ], [
            "name.required" => "Informe seu nome",
            "name.min" => "Informe o nome completo",
            "email.required" => "Informe o email",
            "email.email" => "Email inválido",
            "subject.required" => "Informe um assunto",
            "subject.min" => "Assunto muito curto",
            "message.required" => "Informe uma mensagem",
            "message.min" => "Mensagem muito curta",
        ]);
    }
}
