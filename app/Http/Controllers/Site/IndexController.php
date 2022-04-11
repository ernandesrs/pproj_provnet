<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\ContactMessageReceivedNotification;
use App\Support\Message;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public function index()
    {
        return view("site.index", [
            "appPath" => $this->path(),
            "head" => (new Seo())->render(
                config("app.name") . " | " . __("site.index.title"),
                __("site.index.subtitle"),
                route("site.index")
            ),
            "banners" => [
                0 => (object) [
                    "title" => "Nós temos a internet que você precisa",
                    "description" => "Temos internet via fibra ótica e rádio com planos residenciais e empresariais. Do que você precisa no momento?",
                    "config" => json_encode([
                        "interval" => 5000,
                        "background" => null,
                        "images" => json_encode([
                            [
                                "image" => "img/site/banner_ilustration_blob.svg",
                                "type" => "background"
                            ],
                            [
                                "image" => "img/site/banner_ilustration.png",
                                "type" => "main"
                            ]
                        ]),
                        "buttons" => json_encode([
                            [
                                "order" => 1,
                                "text" => "Fibra",
                                "link" => "#fiber",
                                "target" => "_self",
                                "style" => "btn-primary"
                            ], [
                                "order" => 2,
                                "text" => "Rádio",
                                "link" => "#radio",
                                "target" => "_self",
                                "style" => "btn-primary"
                            ], [
                                "order" => 3,
                                "text" => "Conhecer",
                                "link" => "#aboutus",
                                "target" => "_self",
                                "style" => "btn-outline-primary"
                            ]
                        ]),
                    ]),
                ],
                1 => (object) [
                    "title" => "Nossa internet via rádio chega onde a fibra não chega",
                    "description" => "Temos internet via fibra ótica e rádio com planos residenciais e empresariais. Do que você precisa no momento?",
                    "config" => json_encode([
                        "interval" => 5000,
                        "background" => null,
                        "images" => json_encode([
                            [
                                "image" => "img/site/banner_ilustration.png",
                                "type" => "main"
                            ]
                        ]),
                        "buttons" => json_encode([
                            [
                                "order" => 1,
                                "text" => "Ver planos via rádio",
                                "link" => "#radio",
                                "target" => "_self",
                                "style" => "btn-outline-primary"
                            ], [
                                "order" => 3,
                                "text" => "Conhecer",
                                "link" => "#aboutus",
                                "target" => "_self",
                                "style" => "btn-primary"
                            ]
                        ]),
                    ]),
                ],
            ]
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
