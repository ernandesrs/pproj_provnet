<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use App\Models\Banner;
use App\Models\ContactMessage;
use App\Models\Plan;
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
        $plans = [
            "fiber" => Plan::where("category", Plan::CATEGORY_FIBER)->whereNotNull("published")->get(),
            "radio" => Plan::where("category", Plan::CATEGORY_RADIO)->whereNotNull("published")->get(),
            "custom" => Plan::where("category", Plan::CATEGORY_CUSTOM)->whereNotNull("published")->get()
        ];

        return view("site.index", [
            "appPath" => $this->path(),
            "head" => (new Seo())->render(
                config("app.name") . " | " . $this->settings->title,
                $this->settings->description,
                route("site.index")
            ),
            "settings" => $this->settings,
            "banner" => $banner,
            "aboutus" => [
                (object) [
                    "title" => "Internet via fibra",
                    "content" => "<p>Tecnologia de ponta para conexão à internet,
                    oferecendo altas velocidades, alta estabilidade e
                    baixíssima latência.</p><p>Downloads rápidos, vídeos e streaming
                    em altíssima qualidade sem travamentos!</p>",
                    "icon" => "speed.speed",
                    "button" => (object)[
                        "name" => "Planos FIBRA",
                        "link" => "#fiber"
                    ],
                ],
                (object) [
                    "title" => "Internet via rádio",
                    "content" => "<p>Chega aonde a fibra não chega. Nossos
                    equipamentos de tecnologia de ponta possibilita
                    uma conexão em altas velocidades, estabilidade
                    e a mais baixa latência possível.</p>",
                    "icon" => "speed.speed",
                    "button" => (object)[
                        "name" => "Planos RÁDIO",
                        "link" => "#radio"
                    ],
                ],
            ],
            "plans" => $plans,
            "clients" => [
                (object) [
                    "planInfo" => (object)[
                        "name" => "CLIENTE FIBRA",
                        "date" => "12/2022",
                    ],
                    "name" => "Ricardo Oliveira",
                    "photo" => null,
                    "testimonial" => "Lorem ipsum dolor sit amet conctetur adipiscing. Elit lorem ipsum dolor amet consectetur adipiscing.",
                ],
                (object) [
                    "planInfo" => (object)[
                        "name" => "CLIENTE FIBRA",
                        "date" => "01/2023",
                    ],
                    "name" => "Ana Paula Siva",
                    "photo" => null,
                    "testimonial" => "Lorem ipsum dolor sit amet conctetur adipiscing. Elit lorem ipsum dolor amet consectetur adipiscing.",
                ],
                (object) [
                    "planInfo" => (object)[
                        "name" => "CLIENTE RÁDIO",
                        "date" => "12/2022",
                    ],
                    "name" => "Gilberto Souza",
                    "photo" => null,
                    "testimonial" => "Lorem ipsum dolor sit amet conctetur adipiscing. Elit lorem ipsum dolor amet consectetur adipiscing.",
                ],
                (object) [
                    "planInfo" => (object)[
                        "name" => "CLIENTE RÁDIO",
                        "date" => "11/2022",
                    ],
                    "name" => "Eliziane Alves",
                    "photo" => null,
                    "testimonial" => "Lorem ipsum dolor sit amet conctetur adipiscing. Elit lorem ipsum dolor amet consectetur adipiscing.",
                ],
            ],
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
