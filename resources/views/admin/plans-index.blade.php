@extends("layouts.admin")

@section('content')
    @include('includes.admin.filter', [
        'filter' => route('admin.plans.filter'),
        'filterGroup' => 'plans',
    ])

    @php
    $recurrencesText = [
        1 => 'Mensal',
        2 => 'Bimestral',
        3 => 'Trimestral',
        6 => 'Semestral',
        12 => 'Anual',
    ];
    $categoriesText = [
        'fiber' => 'Fibra',
        'radio' => 'Rádio',
        'custom' => 'Personalizado',
    ];
    @endphp
    <section class="section section-list">
        <div class="row">
            @foreach ($plans as $plan)
                <div class="col-12 col-md-6 mb-4">
                    <div class="card rounded-0 border-0 shadow-sm section-list-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-xl-9 mb-3 mb-xl-0">
                                    <h2 class="h5 mb-0">
                                        {{ $plan->title }}
                                    </h2>
                                    <p class="mb-0">
                                        <span class="badge badge-info">
                                            Plano: {{ $categoriesText[$plan->category] ?? 'undefined' }}
                                        </span>
                                        <span class="badge badge-secondary">
                                            Recorrência: {{ $recurrencesText[$plan->recurrence] ?? 'undefined' }}
                                        </span>
                                    </p>
                                    <p class="mb-0">
                                        <span class="badge badge-{{ $plan->published ? 'success' : 'light' }}">
                                            Status: {{ $plan->published ? 'Publicado' : 'Não publicado' }}
                                        </span>
                                    </p>
                                </div>

                                <div
                                    class="col-12 col-xl-3 d-flex justify-content-start justify-content-end align-items-center">
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('admin.plans.edit', ['plan' => $plan->id]) }}">
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
