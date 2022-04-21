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
                    Banners cadastrados
                </h2>
                <a class="ml-3 btn btn-success btn-sm" href="#newbannerelement">
                    {{ icon('plus.plusLg') }} {{ __('Novo banner') }}
                </a>
                <a class="ml-3 btn btn-danger btn-sm jsButtonConfirm" role="button" data-type="danger"
                    data-title="Excluindo grupo de banner!"
                    data-message="Você está excluindo definitivamente o grupo de banner '<strong>{{ $banner->name }}</strong>' e todos seus banners, e isso não pode ser desfeito! Continuar?"
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
                                    $buttons = empty($config->buttons) ? [] : (array) $config->buttons;
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

                                        <div class="col-12 col-md-11 col-lg-10 mt-3 px-0">
                                            <span class="font-weight-medium">{{ __('Botões') }}:</span>
                                            <div class="d-flex flex-wrap justify-content-center py-3 px-0 bg-white border"
                                                style="position: relative;">
                                                @if (count($buttons))
                                                    @foreach ($buttons as $button)
                                                        <div class="dropdown mx-2">
                                                            <div class="btn-preview py-2 text-center dropdown-toggle"
                                                                data-toggle="dropdown">
                                                                <a class="btn {{ $button->style }}">
                                                                    {{ $button->text }}
                                                                </a>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <div class="d-flex py-1 px-3">
                                                                    <a class="btn btn-sm btn-outline-info jsEditButtonModal"
                                                                        data-action="{{ route('admin.banners.editButton', ['bannerElement' => $element->id, 'buttonId' => $button->id]) }}">
                                                                        {{ icon('edit') }} {{ __('Editar') }}
                                                                    </a>
                                                                    <span class="mx-1"></span>
                                                                    <a class="btn btn-sm btn-outline-danger jsButtonConfirm"
                                                                        data-type="danger"
                                                                        data-title="{{ __('Exclusão de botão') }}"
                                                                        data-message="{{ __('Você está excluindo o botão "<strong>:buttonName</strong>" e isso não pode ser desfeito! Continuar?', ['buttonName' => $button->text]) }}"
                                                                        data-action="{{ route('admin.banners.destroyButton', ['bannerElement' => $element->id, 'buttonId' => $button->id]) }}">
                                                                        {{ icon('trash') }} {{ __('Excluir') }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p class="mb-0 text-center font-weight-medium">
                                                        Nenhum botão para este banner
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="py-3 text-right">
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-md-11 col-lg-10 d-flex px-0">
                                                <a class="btn btn-sm btn-info jsAddButtonsModal"
                                                    data-action="{{ route('admin.banners.storeButton', ['bannerElement' => $element->id]) }}">
                                                    {{ icon('plus.plusLg') }} {{ __('Adicionar botões/links') }}
                                                </a>
                                                <span class="mx-auto"></span>
                                                <a class="btn btn-sm btn-info"
                                                    href="{{ route('admin.banners.editElement', ['banner' => $banner->id, 'bannerElement' => $element->id]) }}">
                                                    {{ icon('edit') }} {{ __('Editar') }}
                                                </a>
                                                <span class="mx-2"></span>
                                                <a class="btn btn-sm btn-danger jsButtonConfirm" data-type="danger"
                                                    data-title="{{ __('Excluindo banner!') }}"
                                                    data-message="Você está excluindo o banner '<strong>{{ $element->title }}</strong>' e isto não pode ser desfeito! Continuar?"
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
                    Nenhum banner cadastrado ainda para este grupo.
                </div>
            @endif
        </section>
    @endif

    {{-- formulários: editar e novo elemento de banner|novo banner --}}
    <section class="section">
        @if ($banner ?? false)
            {{-- título --}}
            <div class="d-flex align-items-center py-3">
                <h2 class="h5 mb-0">
                    @if (empty($bannerElement))
                        <span id="newbannerelement"></span>
                        {{ __('Cadastrar novos banners') }}
                    @else
                        {{ __('Editando banner') }}
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
                                                {{ __('Você pode inserir até 3 imagens para um banner.') }}
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
                                <button class="btn btn-sm btn-outline-danger jsButtonConfirm" type="submit"
                                    data-type="danger" data-title="Excluindo banner!"
                                    data-message="Você está excluindo um banner '<strong>{{ $bannerElement->title }}</strong>' e isto não pode ser desfeito! Continuar?"
                                    data-action="{{ route('admin.banners.destroyElement', ['bannerElement' => $bannerElement->id]) }}">
                                    {{ icon('trash') }} {{ __('Excluir') }}
                                </button>
                                <button class="btn btn-sm btn-info" type="submit">
                                    {{ icon('check.lg') }} {{ __('Atualizar') }}
                                </button>
                            @else
                                <button class="btn btn-sm btn-success" type="submit">
                                    {{ icon('plus.plusLg') }} {{ __('Cadastrar') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="row p-3 justify-content-center">
                <div class="col-12 col-md-10 col-xl-8">
                    <form class="jsFormSubmit" action="{{ route('admin.banners.store') }}" method="POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="jsMessageArea"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="page">Grupo de banner para:</label>
                                    <select class="form-control" name="page" id="page">
                                        <option value="none">Escolha uma página</option>
                                        @foreach ($pages as $page)
                                            <option value="{{ $page }}">{{ route($page) }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        {{ __('Uma página onde os banners serão exibidos.') }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('Nome') }}:</label>
                                    <input type="text" class="form-control" name="name" id="name">
                                    <small class="form-text text-muted">
                                        {{ __('Uma identificação para o grupo de banner(não será mostrada no front).') }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-12 text-right">
                                <button class="btn btn-success btn-sm" type="submit">
                                    {{ icon('plus.plusLg') }} {{ __('Cadastrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/previews.css') }}">
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

    @if (!empty($banner) && $banner->elements()->count())
        <script>
            $("#text").on("keyup", function(e) {
                $("#buttonPreview").text($(this).val());
            });

            $("#style").on("change", function(e) {
                let styles =
                    "{{ implode(' ', ['btn-primary', 'btn-outline-primary', 'btn-secondary', 'btn-outline-secondary', 'btn-link']) }}";
                $("#buttonPreview").removeClass(styles).addClass($(this).val());
            });

            $("#size").on("change", function(e) {
                let sizes = "{{ implode(' ', ['btn-lg', 'btn-sm']) }}";
                $("#buttonPreview").removeClass(sizes).addClass($(this).val());
            });
        </script>
    @endif
@endsection

@if (!empty($banner) && $banner->elements()->count())
    @section('modals')
        @include('includes.admin.modal-add-banner-buttons')
    @endsection
@endif
