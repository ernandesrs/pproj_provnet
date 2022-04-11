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
                    'activeIn' => ['admin.banners.index', 'admin.banners.show', 'admin.banners.edit', 'admin.banners.create'],
                    'icon' => 'collection.collection',
                ],
                [
                    'text' => 'Blog',
                    'group' => 'blog',
                    'icon' => 'page.page',
                    'items' => [
                        0 => [
                            'text' => 'Categorias',
                            'route' => 'admin.blog.categories',
                            'targetBlank' => false,
                            'activeIn' => ['admin.blog.categories'],
                            'icon' => 'tag.tags',
                        ],
                        1 => [
                            'text' => 'Artigos',
                            'route' => 'admin.blog.articles',
                            'targetBlank' => false,
                            'activeIn' => ['admin.blog.articles'],
                            'icon' => 'page.blog',
                        ],
                        2 => [
                            'text' => 'Comentários',
                            'route' => 'admin.blog.comments',
                            'targetBlank' => false,
                            'activeIn' => ['admin.blog.comments'],
                            'icon' => 'chat.leftText',
                        ],
                    ]
                ],
                [
                    'text' => 'Páginas',
                    'route' => 'admin.pages',
                    'targetBlank' => false,
                    'activeIn' => ['admin.pages'],
                    'icon' => 'page.blog',
                ]
            ],
        ],
        'app' => [
            'text' => 'App',
            'items' => [
                0 => [
                    'text' => 'Example 1',
                    'group' => 'example1',
                    'icon' => 'default',
                    'items' => [
                        0 => [
                            'text' => 'Item 1',
                            'route' => '',
                            'targetBlank' => false,
                            'activeIn' => [''],
                            'icon' => 'default'
                        ],
                        1 => [
                            'text' => 'Item 2',
                            'route' => '',
                            'targetBlank' => false,
                            'activeIn' => [''],
                            'icon' => 'default'
                        ],
                    ],
                ],
                1 => [
                    'text' => 'Example 2',
                    'group' => 'example2',
                    'icon' => 'default',
                    'items' => [
                        0 => [
                            'text' => 'Item 2',
                            'route' => '',
                            'targetBlank' => false,
                            'activeIn' => [''],
                            'icon' => 'default'
                        ],
                        1 => [
                            'text' => 'Item 3',
                            'route' => '',
                            'targetBlank' => false,
                            'activeIn' => [''],
                            'icon' => 'default'
                        ],
                    ],
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
