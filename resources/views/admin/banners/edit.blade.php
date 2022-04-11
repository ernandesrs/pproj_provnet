@extends("layouts.admin")

@section('content')
    @include('includes.admin.filter', [
        'filter' => route('admin.banners.filter'),
        'filterGroup' => 'banners',
    ])

    <section class="section section-list">
    </section>
