<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Notifications\ContactMessageReceivedNotification;
use Illuminate\Http\Request;

class IndexController extends AdminController
{
    public function index()
    {
        return view("admin.index", [
            "appPath" => $this->path(null, [
                "dashboard" => [
                    "name" => "dashboard",
                    "url" => null
                ],
                "overview" => [
                    "name" => "Visão geral",
                    "url" => route("admin.index")
                ],
            ]),

            "seo" => (object) [
                "title" => "Dashboard"
            ],
        ]);
    }

    public function profile()
    {
        return view("admin.profile", [
            "appPath" => $this->path(null, [
                "others" => [
                    "name" => "outros",
                    "url" => null
                ],
                "profile" => [
                    "name" => "perfil",
                    "url" => route("admin.profile")
                ],
            ]),

            "seo" => (object)[
                "title" => "Meu perfil"
            ],

            "user" => auth()->user()
        ]);
    }

    /**
     * @param Request $request
     * 
     * @return [type]
     */
    public function notifications(Request $request)
    {
        $read = $request->get("read", 0);
        if ($read) {
            $this->readNotifications($request);
            return;
        }

        $overview = $request->get("overview", 0);

        $notifications = [];
        $boxes = null;

        $notifications = auth()->user()->unreadNotifications;
        $messageCount = 0;
        $messageIds = [];
        foreach ($notifications as $notification) {
            if ($notification->type == ContactMessageReceivedNotification::class) {
                $messageIds[] = $notification->id;
                $messageCount++;
            }
        }

        $notifications = [
            "messages" => [
                "ids" => implode(",", $messageIds),
                "name" => "messages",
                "title" => "Novas mensagens",
                "count" => $messageCount,
                "icon" => null,
                "url" => "#"
            ]
        ];

        if ($overview) {
            $boxes = [
                "users" => [
                    "name" => "users",
                    "title" => "Usuários",
                    "description" => "Resumo geral de usuários",
                    "url" => null,
                    "icon" => null,
                    "data" => [
                        0 => [
                            "title" => "Total",
                            "description" => "Total de usuários",
                            "value" => User::whereNotNull("id")->count(),
                            "url" => null,
                        ],
                        1 => [
                            "title" => "Novos",
                            "description" => "Usuários registrados nos últimos 7 dias",
                            "value" => User::where("created_at", ">=", date("Y-m-d H:i", strtotime("- 7days")))->count(),
                            "url" => null,
                        ]
                    ]
                ],
                "example" => [
                    "name" => "example",
                    "title" => "Example 1",
                    "description" => "Lorem ipsum dolor sit amet consectetur",
                    "url" => null,
                    "icon" => null,
                    "data" => [
                        0 => [
                            "title" => "Item 1",
                            "description" => null,
                            "value" => 10,
                            "url" => null,
                        ],
                        1 => [
                            "title" => "Item 2",
                            "description" => null,
                            "value" => 12,
                            "url" => null,
                        ]
                    ]
                ],
                "example1" => [
                    "name" => "example1",
                    "title" => "Exemplo 1",
                    "description" => "Lorem ipsum dolor sit amet consectetur",
                    "url" => null,
                    "icon" => null,
                    "data" => [
                        0 => [
                            "title" => "Item 1",
                            "description" => null,
                            "value" => 3,
                            "url" => null,
                        ],
                        1 => [
                            "title" => "Item 2",
                            "description" => null,
                            "value" => 1,
                            "url" => null,
                        ],
                        2 => [
                            "title" => "Item 3",
                            "description" => null,
                            "value" => 7,
                            "url" => null,
                        ]
                    ]
                ],
            ];
        }

        return response()->json([
            "notifications" => [
                "total" => array_sum(array_map(function ($notification) {
                    if (isset($notification["count"]))
                        return $notification["count"];
                    else
                        return 0;
                }, $notifications)),
                "list" => $notifications
            ],
            "boxes" => $boxes,
        ]);
    }

    public function readNotifications(Request $request)
    {
        $ids = $request->get("ids", null);
        if (!$ids) return;

        $idsArr = explode(",", $ids);
        $notifications = auth()->user()->unreadNotifications;
        foreach ($notifications as $notification) {
            if (in_array($notification->id, $idsArr))
                $notification->markAsRead();
        }

        return;
    }
}
