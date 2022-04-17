@extends('layouts.site')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/splide.min.css') }}">
@endsection

@section('content')
    {{-- abouts --}}
    <section id="aboutus" class="d-flex align-items-center section section-aboutus">
        <div class="container">
            <div class="row justify-content-center section-aboutus-list">
                @if (count($aboutus))
                    @foreach ($aboutus as $aus)
                        <div class="col-12 col-sm-6 col-lg-4 mb-4">
                            <div class="card border-0 h-100 py-4 section-aboutus-list-item jsAboutUsItem">
                                <div class="card-body d-flex flex-column align-items-center px-4 px-xl-5">
                                    <div
                                        class="d-flex justify-content-center align-items-center rounded-circle mb-3 icon-area">
                                        {{ icon($aus->icon) }}
                                    </div>
                                    <h2 class="text-center title">
                                        {{ $aus->title }}
                                    </h2>
                                    <article class="text-center">
                                        {!! $aus->content !!}
                                    </article>
                                    <a class="btn btn-lg btn-primary mt-auto" href="{{ $aus->button->link }}"
                                        title="{{ $aus->title }}">
                                        {{ $aus->button->name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 col-md-10 col-lg-8 text-center">
                        <span class="font-weight-medium">
                            Nenhuma informação cadastrada
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- plans --}}
    <div id="plans" class="py-5 section section-plans">
        <div class="container">
            <div class="section-header">
                <h1 class="text-center title">
                    Nossos planos
                </h1>
                <ul class="nav nav-pills mb-3 justify-content-center" id="plans-tab" role="tablist">
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($plans as $key => $plan)
                        @php
                            $plansCategory = [
                                'fiber' => 'Fibra',
                                'radio' => 'Rádio',
                                'enterprise' => 'Empresarial',
                            ];
                            $i++;
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $i == 1 ? 'active' : null }}" id="plans-{{ $key }}-tab"
                                data-toggle="pill" href="#plans-{{ $key }}">
                                {{ $plansCategory[$key] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if (count($plans))
                <div class="tab-content" id="plans-tabContent">
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($plans as $key => $plan)
                        @php
                            $i++;
                        @endphp
                        <div class="tab-pane fade {{ $i == 1 ? 'show active' : null }}" id="plans-{{ $key }}">
                            <div class="row justify-content-center section-plans-list">
                                @foreach ($plan as $key => $p)
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                        <div
                                            class="card shadow-sm border-0 section-plans-list-item {{ $key == 1 ? 'featured' : null }}">
                                            <div class="card-body py-5 pb-2">
                                                <h2 class="title">
                                                    {{ $p->title }}
                                                </h2>
                                                <div class="price">
                                                    <span class="currency">
                                                        R$
                                                    </span>
                                                    <span class="price">
                                                        {{ $p->price }}
                                                    </span>
                                                    <span class="recurrence">
                                                        /{{ $p->recurrence == 'monthly' ? __('mês') : ($p->recurrence == 'semiannual' ? __('ano') : __('semestre')) }}
                                                    </span>
                                                </div>
                                                <div class="details">
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            {{ icon('download.cloudDownAlt') }}
                                                            <span>
                                                                Download: {{ $p->download_speed }} Mbps
                                                            </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            {{ icon('upload.cloudUpAlt') }}
                                                            <span>
                                                                Upload: {{ $p->upload_speed }} Mbps
                                                            </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            {{ icon('speed.speed') }}
                                                            <span>
                                                                {{ $p->limit ? __('Limitada: :limit', ['limit' => $p->limit]) : __('Sem limites') }}
                                                            </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            @if ($p->telephone_line)
                                                                {{ icon('phone.telephone') }}
                                                            @else
                                                                {{ icon('phone.telephoneX') }}
                                                            @endif
                                                            <span>
                                                                {{ $p->telephone_line ? __('Necessário linha telefônica') : __('Sem linha telefônica') }}
                                                            </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            @if ($p->free_router)
                                                                {{ icon('router.router') }}
                                                                <span>
                                                                    {{ __('Roteador grátis') }}
                                                                </span>
                                                            @else
                                                                {{ icon('x.xLg') }}
                                                                <span class="disabled">
                                                                    {{ __('Roteador grátis') }}
                                                                </span>
                                                            @endif
                                                        </li>
                                                        @php
                                                            $moreDetails = json_decode($p->content);
                                                        @endphp
                                                        @if (count($moreDetails))
                                                            <li
                                                                class="list-group-item d-flex justify-content-center more-details">
                                                                <a class="btn-more-details" data-toggle="collapse"
                                                                    href="#plan{{ $key }}MoreDetail"
                                                                    role="button">
                                                                    {{ icon('arrow.chevronDownDouble') }}
                                                                </a>
                                                            </li>
                                                            <div class="collapse"
                                                                id="plan{{ $key }}MoreDetail">
                                                                @foreach ($moreDetails as $exDetail)
                                                                    <li class="list-group-item">
                                                                        @if ($exDetail->icon)
                                                                            {{ icon($exDetail->icon) }}
                                                                        @else
                                                                            {{ icon('list.listCheck') }}
                                                                        @endif
                                                                        <span>{{ $exDetail->text }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="pt-3">
                                                    <a class="btn btn-primary" href="#contact"
                                                        title="Quero o plano {{ $p->title }}">
                                                        Quero este
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row justify-content-center section-plans-list">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card border-0">
                            <div class="card-body">
                                <span class="font-weight-medium h5">Nenhum plano cadastrado ainda :(</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- clients/testimonials --}}
    <div id="clients" class="py-5 section section-clients">
        <div class="container">
            <div class="section-header">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
                        <h1 class="text-center title">
                            {{ __('Nossos clientes') }}
                        </h1>
                        <p class="text-center description">
                            {{ __('Veja oque alguns de nossos muitos clientes dizem sobre nosso serviços.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center section-clients-list">
                @foreach ($clients as $client)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card border-0 shadow-sm text-center section-clients-list-item">
                            <div class="card-body">
                                <img class="img-fluid rounded-circle photo" src="{{ user_thumb() }}"
                                    alt="{{ $client->name }}">
                                <div>
                                    <div class="plan-info">
                                        <span class="name">
                                            {{ $client->planInfo->name }}
                                        </span>
                                        <span class="date">
                                            desde {{ $client->planInfo->date }}
                                        </span>
                                    </div>
                                    <span class="name">
                                        {{ $client->name }}
                                    </span>
                                    <p class="testimonial">
                                        {{ $client->testimonial }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- contact --}}
    <section id="contact" class="py-5 section contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 d-flex flex-column align-items-center text-center">
                    <h2 class="">
                        Entre em contato
                    </h2>
                    <p class="mb-0">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Enim eveniet quae a exercitationem
                        dignissimos ipsum minima non voluptates odit esse.
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <form class="jsFormSubmit" action="{{ route('site.contact') }}" method="post">
                        @csrf
                        <div class="message-area jsMessageArea"></div>

                        <div class="form-row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">Nome:</label>
                                    <input class="form-control" type="text" name="name" id="name">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input class="form-control" type="text" name="email" id="email">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="subject">Assunto:</label>
                                    <input class="form-control" type="text" name="subject" id="subject">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="message">Mensagem:</label>
                                    <textarea class="form-control" name="message" id="message"></textarea>
                                </div>
                            </div>

                            <div class="col-12 py-3 text-center">
                                <button class="btn btn-primary" type="submit">
                                    {{ icon('send.check') }}
                                    <span class="bnt-text">
                                        Enviar
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        const btnStyleToggle = (el) => {
            let btn = $(el).find(".btn");
            if (btn.hasClass("btn-primary"))
                btn.removeClass("btn-primary").addClass("btn-outline-primary");
            else
                btn.removeClass("btn-outline-primary").addClass("btn-primary");
        };

        $(".jsAboutUsItem").on("mouseover", function(e) {
            btnStyleToggle($(this));
        });

        $(".jsAboutUsItem").on("mouseout", function(e) {
            btnStyleToggle($(this));
        });
    </script>

    @if ($banner)
        @php
            $bannerElements = $banner->elements();
        @endphp
        <script src="{{ asset('js/splide.js') }}"></script>
        <script>
            var splide = new Splide('.banners', {
                type: 'loop',
                autoplay: true,
                perPage: 1,
                arrows: false,
                pagination: {{ $bannerElements->count() > 1 ? 'true' : 'false' }},
                speed: 400,
                breakpoints: {
                    968: {
                        arrows: true,
                        pagination: false,
                    },
                }
            });
            splide.mount();
        </script>
    @endif
@endsection
