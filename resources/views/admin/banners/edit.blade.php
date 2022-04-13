@php
$bannerElement = $bannerElement ?? null;
if ($bannerElement) {
    $bannerElement->config = json_decode($bannerElement->config);
}
@endphp
@extends("layouts.admin")

@section('content')
    {{-- 1. Lista de elementos cadastrados/mensagem;
        2. Botões de ação sobre o banner --}}
    @if (($banner ?? false) && !($bannerElement ?? false))
        @php
            $elements = $banner->elements()->get();
        @endphp
        <section class="section mb-3">
            {{-- botões --}}
            <div class="d-flex align-items-center mb-3 py-3 border-bottom">
                <h2 class="h5 mb-0">
                    Elementos de banner cadastrados
                </h2>
                <a class="ml-3 btn btn-success btn-sm" href="#newbannerelement">
                    {{ icon('plus.plusLg') }} {{ __('Novo elemento') }}
                </a>
                <a class="ml-3 btn btn-danger btn-sm jsButtonConfirm" role="button" data-type="danger"
                    data-title="Exclusão de banner!"
                    data-message="Você está excluindo definitivamente o banner '<strong>{{ $banner->name }}</strong>' e todos seus elementos e isso não pode ser desfeito! Continuar?"
                    data-action="{{ route('admin.banners.destroy', ['banner' => $banner->id]) }}">
                    {{ icon('trash') }} {{ __('Excluir') }}
                </a>
            </div>

            {{-- lista de elementos/mensagem --}}
            @if ($elements->count())
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <ul class="nav nav-pills justify-content-center" id="bannerElements">
                            @foreach ($elements as $key => $element)
                                <li class="nav-item">
                                    <a class="nav-link {{ $key == 0 ? 'active' : null }}"
                                        id="element{{ $key + 1 }}-tab" data-toggle="tab"
                                        href="#element{{ $key + 1 }}">
                                        {{ $key + 1 }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="bannerElementsContent">
                            @foreach ($elements as $key => $element)
                                @php
                                    $config = json_decode($element->config);
                                    $images = $config->images;
                                @endphp
                                <div class="tab-pane fade py-3 {{ $key == 0 ? 'show active' : null }}"
                                    id="element{{ $key + 1 }}">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-11 col-lg-10">
                                            <div class="row border px-4 ">
                                                <div
                                                    class="col-12 col-md-6 d-none d-md-flex flex-column justify-content-center">
                                                    <h1 class="h4 mb-0">
                                                        {{ $element->title }}
                                                    </h1>
                                                    <p class="mb-0">
                                                        {{ $element->subtitle }}
                                                    </p>
                                                </div>
                                                <div class="col-12 col-md-6"
                                                    style="position: relative; width: 275px; height: 275px;">
                                                    @foreach ($images as $key => $image)
                                                        <img src="{{ Storage::url($image->image) }}" alt=""
                                                            style="width: 100%; max-width: 275px; height: 100%; max-height: 275px; position: absolute; right: 0; top: 0; transform: translateX(0%); z-index: {{ $key + 1 }}">
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="py-3 text-right">
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-md-11 col-lg-10">
                                                <a class="btn btn-sm btn-info"
                                                    href="{{ route('admin.banners.editElement', ['banner' => $banner->id, 'bannerElement' => $element->id]) }}">
                                                    {{ icon('edit') }} {{ __('Editar') }}
                                                </a>
                                                <a class="btn btn-sm btn-danger jsButtonConfirm" data-type="danger"
                                                    data-title="{{ __('Exclusão de elemento de banner!') }}"
                                                    data-message="Você está excluindo o elemento de banner '<strong>{{ $element->title }}</strong>' e isto não pode ser desfeito! Continuar?"
                                                    data-action="{{ route('admin.banners.destroyElement', ['bannerElement' => $element->id]) }}">
                                                    {{ icon('trash') }} {{ __('Excluir') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary text-center">
                    Nenhum elemento de banner cadastrado ainda.
                </div>
            @endif
        </section>
    @endif

    @if ($banner ?? false)
        <section class="section">
            {{-- título --}}
            <div class="d-flex align-items-center py-3">
                <h2 class="h5 mb-0">
                    @if (empty($bannerElement))
                        <span id="newbannerelement"></span>
                        {{ __('Cadastrar novos elementos de banner') }}
                    @else
                        {{ __('Editando elemento de banner') }}
                    @endif
                </h2>
            </div>

            {{-- form --}}
            <div class="row p-3">
                <div class="col-12">
                    <form class="jsFormSubmit"
                        action="{{ empty($bannerElement)? route('admin.banners.storeElement', ['banner' => $banner->id]): route('admin.banners.updateElement', ['bannerElement' => $bannerElement->id]) }}"
                        method="POST" enctype="multipart/form-data">
                        <div class="row border px-2 px-md-5 py-3 py-md-5">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="title">{{ __('Título') }}:</label>
                                    <input class="form-control" type="text" name="title"
                                        value="{{ !empty($bannerElement) ? $bannerElement->title : null }}">
                                </div>

                                <div class="form-group">
                                    <label for="subtitle">{{ __('Descrição') }}:</label>
                                    <textarea class="form-control" type="text"
                                        name="subtitle">{{ !empty($bannerElement) ? $bannerElement->subtitle : null }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="interval">{{ __('Intervalo(em milisegundos)') }}:</label>
                                    <input type="number" class="form-control"
                                        value="{{ !empty($bannerElement) ? $bannerElement->config->interval : 5000 }}"
                                        id="interval" name="interval">
                                </div>

                                @if (!($bannerElement ?? false))
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="form-group">
                                            @if ($i == 0)
                                                <label for="">Imagens:</label>
                                            @endif
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input"
                                                        id="image{{ $i + 1 }}" name="image{{ $i + 1 }}">
                                                    <label class="custom-file-label" for="image{{ $i + 1 }}">
                                                        Imagem {{ $i + 1 }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor

                                    <div class="alert alert-secondary">
                                        <small>
                                            <p class="font-weight-medium mb-0">
                                                Você pode inserir até 3 imagens para um elemento de banner.
                                            </p>
                                            <ul>
                                                <li><span class="font-weight-medium">Imagem 1:</span> será o background</li>
                                                <li><span class="font-weight-medium">Imagem 2:</span> será imagem principal
                                                </li>
                                                <li><span class="font-weight-medium">Imagem 3:</span> será a útima imagem
                                                </li>
                                            </ul>
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 col-md-6 d-flex flex-column align-items-center">
                                <div class="form-group">
                                    <label for="">{{ __('Preview') }}</label>
                                    <div class="border mb-4 jsBannerPreview"
                                        style="position: relative; width: 225px; height: 225px;">
                                        @if ($bannerElement)
                                            @foreach ($bannerElement->config->images as $key => $image)
                                                <img src="{{ Storage::url($image->image) }}" alt=""
                                                    style="width: 100%; max-width: 275px; height: 100%; max-height: 275px; position: absolute; right: 0; top: 0; transform: translateX(0%); z-index: {{ $key + 1 }}">
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right py-3 py-md-3">
                            @if ($bannerElement ?? false)
                                <button class="btn btn-outline-danger jsButtonConfirm" type="submit" data-type="danger"
                                    data-title="Excluindo elemento de banner!"
                                    data-message="Você está excluindo o elemento de banner '<strong>{{ $bannerElement->title }}</strong>' e isto não pode ser desfeito! Continuar?"
                                    data-action="{{ route('admin.banners.destroyElement', ['bannerElement' => $bannerElement->id]) }}">
                                    {{ icon('trash') }} {{ __('Excluir') }}
                                </button>
                                <button class="btn btn-info" type="submit">
                                    {{ icon('check.lg') }} {{ __('Atualizar') }}
                                </button>
                            @else
                                <button class="btn btn-success" type="submit">
                                    {{ icon('plus.plusLg') }} {{ __('Cadastrar') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </section>
    @endif
@endsection

@section('scripts')
    <script>
        $("#image1, #image2, #image3").on("change", function(e) {
            let img = $("<img />");
            img.attr("src", URL.createObjectURL($(this)[0].files[0]));
            img.css({
                "width": "225px",
                "height": "225px",
                "position": "absolute",
                "top": "0",
                "right": "0",
            });

            $(this).parents().eq(5).find(".jsBannerPreview").append(img[0]);
        });
    </script>
@endsection
