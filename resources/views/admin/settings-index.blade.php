@extends("layouts.admin")

@section('content')
    <form action="{{ route('admin.settings.update', ['settings' => $settings->id]) }}" class="jsFormSubmit"></form>
    <section class="py-4 section section-list">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0 h5">Logo do site</h1>
            </div>
            <div class="card-body"></div>
        </div>
    </section>
@endsection
