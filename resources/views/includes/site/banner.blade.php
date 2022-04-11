@if (in_array(Route::currentRouteName(), $routesWBanner ?? ['site.index']))
    <section id="banner" class="d-flex align-items-center banner">
        <div class="container">
            @if ($banners)
                <div class="banners splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($banners as $banner)
                                @php
                                    $config = json_decode($banner->config);
                                    $images = json_decode($config->images);
                                    $buttons = json_decode($config->buttons);
                                @endphp
                                <li class="splide__slide d-flex align-items-center"
                                    data-splide-interval="{{ $config->interval ?? 5000 }}">
                                    <div class="row">
                                        <div class="col-12 col-md-6 order-md-12 banner-images">
                                            @if (count($images))
                                                <div class="animated-banner-images">
                                                    @foreach ($images as $key => $image)
                                                        <img class="img-fluid animation-{{ $image->type }}"
                                                            src="{{ asset($image->image) }}"
                                                            alt="{{ $banner->title . ' ' . ($key + 1) }}">
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div
                                            class="col-12 col-md-6 d-flex flex-column justify-content-center text-center text-md-left order-md-1">
                                            <h1 class="title">
                                                {{ $banner->title }}
                                            </h1>
                                            <p class="description">
                                                {{ $banner->description }}
                                            </p>
                                            @if (count($buttons))
                                                <div class="buttons">
                                                    @foreach ($buttons as $button)
                                                        <a class="btn {{ $button->style }}"
                                                            href="{{ $button->link }}"
                                                            target="{{ $button->target }}">{{ $button->text }}</a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <ul class="splide__pagination"></ul>
                </div>
            @endif
        </div>
    </section>
@endif
