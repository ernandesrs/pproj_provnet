<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Laravel') }} ADMIN - {{ $seo->title }}
    </title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/custom.css') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @yield("styles")
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-content">
            {{-- sidebar header: profile --}}
            <div class="px-3 pt-2 pb-3 d-flex align-items-center user-info">
                @php
                    $user = auth()->user();
                @endphp
                <img class="img-thumbnail rounded-circle user-photo" src="{{ user_thumb_list($user) }}" alt="">

                <div class="px-3 w-100">
                    <div class="user-name">
                        {{ $user->username }}
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge badge-{{ $user->level > 1 ? 'success' : 'light' }}">
                            {{ $user->level == 3 ? 'Super' : ($user->level == 2 ? 'Admin' : 'Usuário') }}
                        </span>
                        <a class="ml-auto user-profile-link" href="{{ route('admin.profile') }}">Perfil</a>
                    </div>
                </div>
            </div>

            <div class="sidebar-elems">
                @foreach (config('laravel_base.sidebar') as $sidebarElemKey => $sidebarElem)
                    <div class="sidebar-elem">
                        <div class="title">
                            {{ $sidebarElem['text'] }}
                        </div>

                        <div class="accordion jsSidebarAccordion nav flex-column"
                            id="accordion{{ Str::ucfirst($sidebarElemKey) }}">

                            @foreach ($sidebarElem['items'] as $sidebarElemItem)
                                @if (isset($sidebarElemItem['items']))
                                    <a class="nav-link nav-group-toggler {{ $appPath->group == $sidebarElemItem['group'] ? 'active' : null }}"
                                        href="" data-target="#{{ $sidebarElemItem['group'] }}Group"
                                        data-toggle="collapse">
                                        {{ icon($sidebarElemItem['icon']) }}
                                        <span class="text">{{ $sidebarElemItem['text'] }}</span>
                                        <span class="nav-group-indicator">
                                            @if ($appPath->group == $sidebarElemItem['group'])
                                                {{ icons('arrow.chevronDown', 'arrow.chevronLeft') }}
                                            @else
                                                {{ icons('arrow.chevronLeft', 'arrow.chevronDown') }}
                                            @endif
                                        </span>
                                    </a>
                                    <div class="collapse {{ $appPath->group == $sidebarElemItem['group'] ? 'show' : null }}"
                                        id="{{ $sidebarElemItem['group'] }}Group"
                                        data-parent="#accordion{{ Str::ucfirst($sidebarElemKey) }}">
                                        <div class="nav flex-column">
                                            @foreach ($sidebarElemItem['items'] as $sidebarElemItemItem)
                                                <a class="nav-link {{ $appPath->route == $sidebarElemItemItem['route'] ? 'active' : null }}"
                                                    href="{{ route(empty($sidebarElemItemItem['route']) ? 'admin.index' : $sidebarElemItemItem['route']) }}"
                                                    {{ $sidebarElemItemItem['targetBlank'] ? 'target="_blank"' : null }}>
                                                    {{ icon($sidebarElemItemItem['icon']) }}
                                                    {{ $sidebarElemItemItem['text'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a class="nav-link {{ in_array($appPath->route, $sidebarElemItem['activeIn']) ? 'active' : null }}"
                                        href="{{ route(empty($sidebarElemItem['route']) ? 'admin.index' : $sidebarElemItem['route']) }}"
                                        {{ $sidebarElemItem['targetBlank'] ? 'target="_blank"' : null }}>
                                        {{ icon($sidebarElemItem['icon']) }} {{ $sidebarElemItem['text'] }}
                                    </a>
                                @endif
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </aside>

    <section class="wrapp">
        <header class="d-flex align-items-center bg-white header">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="btn-sidebar-toggle jsSidebarToggle">
                        {{ icon('menu.menu') }}
                    </button>

                    {{-- breadcrumb --}}
                    @php
                        $paths = $appPath->paths ?? null;
                    @endphp
                    @if ($paths)
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 m-0 ml-2 bg-transparent">
                                @foreach ($paths as $path)
                                    @if ($path)
                                        <li class="breadcrumb-item">
                                            @if ($path->url)
                                                <a href="{{ $path->url }}">{{ Str::ucfirst($path->name) }}</a>
                                            @else
                                                {{ Str::ucfirst($path->name) }}
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                    @endif

                    <div class="ml-auto mr-4 mr-lg-0 header-nav">
                        <div class="nav">
                            {{-- notifications --}}
                            <div class="dropdown notifications jsNotifications"
                                data-action="{{ route('admin.notifications', ['overview' => $appPath->route == 'admin.index' ? true : false]) }}">
                                <a class="nav-link dropdown-toggle" href="" id="notifications" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    {{ icon('bell.bell') }}
                                    <span class="badge badge-success notification-count">0</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right notifications-list jsNotificationsList"
                                    aria-labelledby="notifications">
                                    <div class="notifications-header text-center py-1 px-2" style="">Lista de
                                        notificações</div>
                                </div>
                            </div>

                            {{-- user --}}
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle" href="" id="notifications" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    {{ icon('user.user') }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifications">
                                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                        {{ icon('user.profile') }} Perfil
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ icon('logout') }}
                                        {{ __('site.header.nav.logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="main h-100 py-3 px-2">
            <div class="container-fluid">
                @if (Route::currentRouteName() != 'admin.index')
                    <div class="py-2 d-flex align-items-center">
                        <h1 class="mb-0 h4">
                            {{ icon('grid.grid3x3') }}
                            {{ $seo->title }}
                        </h1>

                        @if ($ac = $actions ?? null)
                            <div class="pl-4">
                                @if ($ac->new ?? false)
                                    <a class="btn btn-sm btn-success" href="{{ $ac->new->url }}"
                                        title="Novo">{{ icon('plus.plusLg') }} {{ $ac->new->text ?? 'Novo' }}</a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <div class="message-area jsMessageArea">
                    @include('includes.message')
                </div>
            </div>
            <div class="container-fluid">
                @yield("content")
            </div>
        </main>

        <footer class="footer">
            <div class="container-fluid py-2">
                <p class="mb-0 text-center copyrights">
                    {{ config('app.name') }} ADMIN &copy; {{ date('Y') }}
                </p>
            </div>
        </footer>
    </section>

    <div id="models" class="d-none">
        {{-- message model --}}
        @include('includes.message', ['isModel' => true])

        {{-- notifications item model --}}
        <a class="dropdown-item notifications-item jsNotificationsItem" href="#">
            <span class="text title"></span>
            <span class="badge badge-success count"></span>
        </a>

        {{-- confirmation modal model --}}
        @include('includes.confirmation-modal')

        @yield("models")
    </div>

    @yield("modals")

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/backdrops.js') }}"></script>
    <script src="{{ asset('js/plugins/buttons.js') }}"></script>
    <script src="{{ asset('js/plugins/form-errors.js') }}"></script>
    <script src="{{ asset('js/plugins/messages.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/admin/scripts.js') }}"></script>
    @yield("scripts")
</body>

</html>
