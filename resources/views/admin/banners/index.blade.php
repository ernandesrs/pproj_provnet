@extends("layouts.admin")

@section('content')
    @include('includes.admin.filter', [
        'filter' => route('admin.banners.filter'),
        'filterGroup' => 'banners',
    ])

    <section class="section section-list">
        @foreach ($banners as $banner)
            <div class="row">
                <div class="col-12 col-md-7 col-lg-9">
                    <h2 class="h5 mb-0">{{ $banner->name }}</h2>
                </div>
                <div class="col-12 col-md-5 col-lg-3">
                    <a href="{{ route('admin.banners.edit', ['banner' => $banner->id]) }}">
                        Editar
                    </a>
                </div>
            </div>
        @endforeach
    </section>
@endsection