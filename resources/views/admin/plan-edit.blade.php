@extends("layouts.admin")

@section('content')
    @php
    $recurrencesText = [
        1 => 'Mês',
        2 => 'Bimestre',
        3 => 'Trimestre',
        6 => 'Semestre',
        12 => 'Anual',
    ];
    $categoriesText = [
        'fiber' => 'Fibra',
        'radio' => 'Rádio',
        'custom' => 'Personalizado',
    ];
    $allowedRecurrences = \App\Models\Plan::ALLOWED_RECURRENCES;
    $allowedCategories = \App\Models\Plan::ALLOWED_CATEGORIES;

    $plan = $plan ?? null;
    $planContent = $plan ?? null ? json_decode($plan->content ?? []) : null;
    @endphp
    <section class="section py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <form class="jsFormSubmit"
                    action="{{ empty($plan) ? route('admin.plans.store') : route('admin.plans.update', ['plan' => $plan->id]) }}">
                    <div class="jsMessageArea"></div>
                    <div class="form-row">
                        <div class="col-12 col-sm-8">
                            <div class="form-group">
                                <label for="title">{{ __('Título') }}:</label>
                                <input class="form-control" type="text" name="title" value="{{ $plan->title ?? null }}">
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="category">{{ __('Categoria') }}:</label>
                                <select class="form-control" name="category" id="category">
                                    @foreach ($allowedCategories as $ac)
                                        <option value="{{ $ac }}"
                                            {{ $plan->category ?? null ? ($plan->category == $ac ? 'selected' : null) : null }}>
                                            {{ $categoriesText[$ac] ?? 'undefined' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-7">
                            <div class="form-group">
                                <label for="price">{{ __('Preço') }}:</label>
                                <input class="form-control" type="text" name="price" value="{{ $plan->price ?? null }}">
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="form-group">
                                <label for="recurrence">{{ __('Recorrência') }}</label>
                                <select class="form-control" name="recurrence" id="recurrence">
                                    @foreach ($allowedRecurrences as $ar)
                                        <option value="{{ $ar }}"
                                            {{ $plan->recurrence ?? null ? ($plan->recurrence == $ar ? 'selected' : null) : null }}>
                                            {{ $recurrencesText[$ar] ?? 'undefined' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6 col-sm-4">
                            <div class="form-group">
                                <label for="download_speed">{{ __('Download') }}:</label>
                                <input class="form-control" type="text" name="download_speed"
                                    value="{{ $planContent->download_speed ?? null }}">
                            </div>
                        </div>

                        <div class="col-6 col-sm-4">
                            <div class="form-group">
                                <label for="upload_speed">{{ __('Upload') }}:</label>
                                <input class="form-control" type="text" name="upload_speed"
                                    value="{{ $planContent->upload_speed ?? null }}">
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="limit">{{ __('Limite') }}:</label>
                                <input class="form-control" type="text" name="limit"
                                    value="{{ $planContent->limit ?? null }}">
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="telephone_line"
                                        name="telephone_line"
                                        {{ $planContent->telephone_line ?? null ? 'checked' : null }}>
                                    <label class="custom-control-label"
                                        for="telephone_line">{{ __('Necessário linha telefônica') }}:</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="free_router" id="free_router"
                                        {{ $planContent->free_router ?? null ? 'checked' : null }}>
                                    <label class="custom-control-label"
                                        for="free_router">{{ __('Roteador grátis') }}:</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="publish" id="publish"
                                        {{ $plan->published ?? null ? 'checked' : null }}>
                                    <label class="custom-control-label" for="publish">{{ __('Publicar') }}:</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        @if ($plan)
                            <a class="btn btn-sm btn-danger jsButtonConfirm" data-type="danger"
                                data-title="{{ __('Exluindo plano!') }}"
                                data-message="{{ __('Você está excluindo "<strong>' . $plan->title . '</strong>" e isso não pode ser desfeito! Deseja continuar?') }}"
                                data-action="{{ route('admin.plans.destroy', ['plan' => $plan->id]) }}">
                                {{ icon('trash') }} {{ __('Excluir') }}
                            </a>

                            <button class="btn btn-sm btn-info">
                                {{ icon('check.checkLg') }} {{ __('Atualizar') }}
                            </button>
                        @else
                            <button class="btn btn-sm btn-success">
                                {{ icon('plus.plusLg') }} {{ __('Cadastrar') }}
                            </button>
                        @endif

                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('modals')
    @include('includes.confirmation-modal')
@endsection
