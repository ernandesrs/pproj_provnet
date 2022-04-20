@extends("layouts.admin")

@section('content')
    @include('includes.admin.filter', [
        'filter' => route('admin.sections.filter'),
        'filterGroup' => 'sections',
    ])

    <section class="section section-list">
        <div class="row justify-content-center py-2">
            <div class="col-12">
                <div class="alert alert-secondary text-center">
                    <small>
                        {{ __('Lista de páginas que possuem seções registradas') }}
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($sections as $section)
                <div class="col-12 col-md-6 mb-4">
                    <div class="card rounded-0 border-0 shadow-sm section-list-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-xl-9 mb-3 mb-xl-0">
                                    <h2 class="h5 mb-0">
                                        <span class="badge badge-secondary">
                                            {{ $section->name }}
                                        </span>
                                    </h2>
                                    <p class="mb-0">
                                        <small>
                                            Seções para <a class="" href="{{ route($section->route_name) }}"
                                                target="_blank">{{ route($section->route_name) }}</a>
                                        </small>
                                    </p>
                                    <p class="mb-0">
                                        <span class="badge badge-success">{{ $section->elements()->count() }}</span>
                                        elemento(s) de seção
                                    </p>
                                </div>

                                <div
                                    class="col-12 col-xl-3 d-flex justify-content-start justify-content-end align-items-center">
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('admin.sections.edit', ['section' => $section->id]) }}">
                                        {{ icon('edit') }} {{ __('Editar') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
