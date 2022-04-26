<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! $head !!}

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/site/custom.css') }}" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset($settings->favicon) }}" type="image/x-icon">

    @yield("styles")
</head>

<body>

    <div class="header-wrapp">
        <header class="d-flex aling-items-center header">
            <div class="container d-flex aling-items-center">
                <nav class="navbar navbar-expand-lg navbar-light w-100">
                    <a class="navbar-brand" href="{{ route('site.index') }}">
                        <span class="sr-only">{{ config('app.name') }}</span>
                        <img src="{{ asset($settings->logo) }}" alt="{{ config('app.name') }}">
                    </a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#headerNavigation"
                        aria-controls="headerNavigation" aria-expanded="false"
                        aria-label="{{ __('site.header.buttonToggler.ariaLabel') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="headerNavigation">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item {{ $appPath->route == 'site.index' ? 'active' : null }}">
                                <a class="nav-link" href="{{ route('site.index') }}">
                                    {{ __('site.header.nav.home') }}
                                    <span class="sr-only">
                                        {{ __('site.header.nav.currentPage') }}
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#plans">
                                    {{ __('Planos') }}
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#cover">
                                    {{ __('Cobertura') }}
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#contact">
                                    {{ __('Contato') }}
                                </a>
                            </li>

                            <li class="nav-item {{ $appPath->route == 'site.blog.index' ? 'active' : null }}">
                                <a class="nav-link" href="{{ route('site.blog.index') }}">
                                    {{ __('site.header.nav.blog') }}
                                </a>
                            </li>

                            <li class="nav-item {{ $appPath->route == 'site.about' ? 'active' : null }}">
                                <a class="nav-link" href="{{ route('site.about') }}">
                                    {{ __('site.header.nav.about') }}
                                </a>
                            </li>
                        </ul>

                        <div class="ml-2">
                            @auth
                                @if (Gate::allows('admin-panel-access'))
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.index') }}">
                                        {{ icon('chart.chart') }}
                                        {{ __('site.header.nav.panel') }}
                                    </a>
                                @endif

                                <a class="btn btn-sm btn-outline-primary" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ icon('logout') }}
                                    {{ __('site.header.nav.logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            @else
                                <a class="btn btn-sm btn-primary" href="{{ route('login') }}">
                                    {{ icon('login') }}
                                    {{ __('site.header.nav.login') }}
                                </a>
                                <a class="btn btn-sm btn-outline-primary" href="#">
                                    {{ __('site.header.nav.purchase') }}
                                </a>
                            @endauth

                        </div>
                    </div>
                </nav>
            </div>
        </header>

        @include('includes.site.banner')
    </div>

    <main class="main">
        <div class="container">
            <div class="message-area jsMessageArea">
                @include('includes.message')
            </div>
        </div>

        @yield("content")
    </main>

    <footer class="footer bg-light">
        <div
            class="container d-flex flex-column flex-sm-row justify-content-center align-items-center py-2 text-center copyrights">
            <p class="mb-0">
                <a href="{{ route('site.index') }}">{{ config('app.name') }}</a> &copy; {{ date('Y') }}
            </p>
            <span class="d-none d-sm-block px-2">|</span>
            <p class="mb-0">
                Todos os direitos reservados
            </p>
        </div>
    </footer>

    <div id="models" class="d-none">
        @include('includes.message', ['isModel' => true])
        @yield("models")

    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('js/plugins/messages.js') }}"></script>
    <script src="{{ asset('js/plugins/buttons.js') }}"></script>
    <script src="{{ asset('js/plugins/backdrops.js') }}"></script>
    <script src="{{ asset('js/plugins/form-errors.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/site/scripts.js') }}"></script>

    @yield("scripts")
</body>

</html>
