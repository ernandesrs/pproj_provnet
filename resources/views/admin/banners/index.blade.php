@extends("layouts.admin")

@section('content')
    @include('includes.admin.filter', [
        'filter' => route('admin.banners.filter'),
        'filterGroup' => 'banners',
    ])

    <section class="section section-list">
        <div class="row">
            @foreach ($banners as $banner)
                <div class="col-12 col-md-6">
                    <div class="card section-list-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-7 col-lg-9">
                                    <div class="d-flex align-items-center mb-2">
                                        <h2 class="h5 mb-0">
                                            <span class="badge badge-secondary">
                                                {{ $banner->name }}
                                            </span>
                                        </h2>
                                        <small class="ml-2">
                                            Banner para <a class="" href="{{ route($banner->route_name) }}" target="_blank">{{ route($banner->route_name) }}</a>
                                        </small>
                                    </div>
                                    <p class="mb-0">
                                        <span class="badge badge-success">{{ $banner->elements()->count() }}</span>
                                        elemento(s) de
                                        banner
                                    </p>
                                </div>
                                <div class="col-12 col-md-5 col-lg-3 d-flex align-items-center">
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
