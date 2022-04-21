@extends("layouts.admin")

@section('content')
    @include('includes.admin.filter', [
        'filter' => route('admin.banners.filter'),
        'filterGroup' => 'banners',
    ])

    <section class="section section-list">
        <div class="row">
            @foreach ($banners as $banner)
                <div class="col-12 col-md-6 mb-4">
                    <div class="card rounded-0 border-0 shadow-sm section-list-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-xl-9 mb-3 mb-xl-0">
                                    <h2 class="h5 mb-0">
                                        <span class="badge badge-secondary">
                                            {{ $banner->name }}
                                        </span>
                                    </h2>
                                    <p class="mb-0">
                                        <small>
                                            Grupo de banners para <a class="" href="{{ route($banner->route_name) }}"
                                                target="_blank">{{ route($banner->route_name) }}</a>
                                        </small>
                                    </p>
                                    <p class="mb-0">
                                        <span class="badge badge-success">{{ $banner->elements()->count() }}</span>
                                        banner(s)
                                    </p>
                                </div>

                                <div class="col-12 col-xl-3 d-flex justify-content-start justify-content-end align-items-center">
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('admin.banners.edit', ['banner' => $banner->id]) }}">
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
