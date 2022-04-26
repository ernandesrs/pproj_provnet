@extends("layouts.admin")

@section('content')
    @php
    $settings->settings = json_decode($settings->settings);
    @endphp
    <section class="py-4 section section-list">
        <form action="{{ route('admin.settings.update', ['settings' => $settings->id]) }}" class="jsFormSubmit">
            <div class="jsMessageArea"></div>

            <div class="card">
                <div class="card-header py-2">
                    <h1 class="mb-0 h5">{{ __('SEO') }}:</h1>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title">{{ __('Título') }}:</label>
                                <input class="form-control" type="text" name="title"
                                    value="{{ $settings->settings->title ?? null }}">
                            </div>
                            <div class="form-group">
                                <label for="description">{{ __('Descrição') }}:</label>
                                <textarea class="form-control" name="description">{{ $settings->settings->description ?? null }}</textarea>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="logo">{{ __('Logo') }}:</label>
                                <input class="form-control" type="file" name="logo">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="favicon">{{ __('Favicon') }}:</label>
                                <input class="form-control" type="file" name="favicon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end py-4">
                <button class="btn btn-sm btn-success">
                    {{ icon('check.checkLg') }} {{ __('Salvar configurações') }}
                </button>
            </div>
        </form>
    </section>
@endsection
