    @php
        $hasBanner = false;
        if ($banner) {
            $banners = $banner->elements()->get();
        
            if ($banners->count()) {
                $hasBanner = true;
            }
        }
    @endphp

    @if ($hasBanner)
        <section id="banner" class="d-flex align-items-center banner">
            <div class="container">
                <div class="banners splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($banners as $banner)
                                @php
                                    $config = json_decode($banner->config);
                                    $images = $config->images;
                                    $buttons = $config->buttons ?? [];
                                @endphp
                                <li class="splide__slide d-flex align-items-center"
                                    data-splide-interval="{{ $config->interval ?? 5000 }}">
                                    <div class="row">
                                        <div class="col-12 col-md-6 order-md-12 banner-images">
                                            @if (count($images))
                                                <div class="animated-banner-images">
                                                    @foreach ($images as $key => $image)
                                                        <img class="img-fluid animation-{{ $image->type }}"
                                                            src="{{ Storage::url($image->image) }}"
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
                                                {{ $banner->subtitle }}
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
            </div>
        </section>

        @section('scripts')
            <script src="{{ asset('js/splide.js') }}"></script>
            <script>
                var splide = new Splide('.banners', {
                    type: 'loop',
                    autoplay: true,
                    perPage: 1,
                    arrows: false,
                    pagination: {{ $banners->count() > 1 ? 'true' : 'false' }},
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
        @endsection
    @endif
