<?php

return [

    /**
     * DASHBOARD SIDEBAR
     */
    'sidebar' => [
        'dashboard' => [
            'text' => 'Dashboard',
            'items' => [
                0 => [
                    'text' => 'Visão geral',
                    'route' => 'admin.index',
                    'targetBlank' => false,
                    'activeIn' => ['admin.index'],
                    'icon' => 'chart.chart',
                ],
                1 => [
                    'text' => 'Usuários',
                    'route' => 'admin.users.index',
                    'targetBlank' => false,
                    'activeIn' => ['admin.users.index', 'admin.users.edit', 'admin.users.create'],
                    'icon' => 'user.users',
                ],
            ],
        ],
        'site' => [
            'text' => 'Site(' . config("app.name") . ')',
            'items' => [
                [
                    'text' => 'Banners',
                    'route' => 'admin.banners.index',
                    'targetBlank' => false,
                    'activeIn' => ['admin.banners.index', 'admin.banners.edit', 'admin.banners.create', 'admin.banners.editElement'],
                    'icon' => 'tv.tv',
                ],
                [
                    'text' => 'Planos',
                    'route' => 'admin.plans.index',
                    'targetBlank' => false,
                    'activeIn' => ['admin.plans.index'],
                    'icon' => 'default',
                ],
                [
                    'text' => 'Configurações',
                    'route' => 'admin.settings.index',
                    'targetBlank' => false,
                    'activeIn' => ['admin.settings.index'],
                    'icon' => 'gear.gear',
                ],
            ],
        ],
        'others' => [
            'text' => "Outros",
            'items' => [
                [
                    'text' => 'Guia',
                    'group' => 'guide',
                    'icon' => 'compass.compass',
                    'items' => [
                        0 => [
                            'text' => "Ícones",
                            'route' => 'admin.guide.icons',
                            'targetBlank' => false,
                            'activeIn' => ['admin.guide.icons'],
                            'icon' => 'grid.grid3x3'
                        ]
                    ]
                ],
                [
                    'text' => "Perfil",
                    'route' => 'admin.profile',
                    'targetBlank' => false,
                    'activeIn' => ['admin.profile'],
                    'icon' => 'user.profileFill'
                ],
                [
                    'text' => "Ir para " . env("APP_NAME"),
                    'targetBlank' => true,
                    'route' => 'site.index',
                    'activeIn' => [],
                    'icon' => 'link.linkExternal'
                ]
            ]
        ],
    ],


    'icons' => [
        'boxicons' => true,
        'bootstrapicons' => true,
        'fontawesom' => false,
    ]

];
