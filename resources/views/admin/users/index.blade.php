@extends("layouts.admin")

@section('content')
    @include('includes.admin.filter', [
        'filter' => route('admin.users.filter'),
        'filterGroup' => 'users',
    ])

    <section class="section section-list user-list">
        @foreach ($users as $user)
            @php
                $bans = $user->bans();
            @endphp
            <div
                class="d-flex flex-column flex-md-row align-md-items-center shadow-sm px-3 py-2 mb-3 section-list-item user-item">
                <div class="d-flex align-items-center">
                    <img class="img-thumbnail rounded-circle list-thumb" src="{{ user_thumb_list($user) }}" alt="">
                    <div class="ml-2">
                        <p class="mb-0 username">
                            {{ $user->first_name . ' ' . $user->last_name }}
                        </p>
                        <div class="mb-0 user-details">
                            @php
                                if ($user->isSuper()) {
                                    $levelStyle = 'success';
                                    $levelText = 'Super';
                                } elseif ($user->isAdmin()) {
                                    $levelStyle = 'success';
                                    $levelText = 'Admin';
                                } else {
                                    $levelStyle = 'light_dark';
                                    $levelText = 'Usuário';
                                }
                                
                                if ($user->status == 'banned') {
                                    $activeBan = $bans->first();
                                    if (!$activeBan->done_at) {
                                        if ($activeBan->isTemp()) {
                                            $statusStyle = 'warning';
                                            $statusText = 'Banimento temporário';
                                        } else {
                                            $statusStyle = 'danger';
                                            $statusText = 'Banimento permanente';
                                        }
                                    } else {
                                        $statusStyle = 'secondary';
                                        $statusText = 'None';
                                    }
                                } elseif ($user->status == 'archived') {
                                    $statusStyle = 'warning';
                                    $statusText = 'Arquivado';
                                } else {
                                    if ($user->email_verified_at) {
                                        $statusStyle = 'info';
                                        $statusText = 'Verificado';
                                    } else {
                                        $statusStyle = 'light_dark';
                                        $statusText = 'Não verificado';
                                    }
                                }
                            @endphp
                            <span class="badge badge-{{ $levelStyle }} user-level">{{ $levelText }}</span>
                            <span class="badge badge-{{ $statusStyle }} user-status">{{ $statusText }}</span>
                            <span
                                class="badge badge-light user-gender">{{ $user->gender == 'male' ? 'Masculino' : 'Feminino' }}</span>

                            <p class="mb-0 user-email">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center ml-md-auto pt-2">
                    @if ($bans->count())
                        <a class="btn btn-sm btn-outline-warning mr-2 jsButtonBansList"
                            data-target="{{ route('admin.users.bans', ['id' => $user->id]) }}"
                            data-name="{{ $user->first_name . ' ' . $user->last_name }}">
                            {{ icon('list.list') }} Banimentos
                        </a>
                    @endif

                    <a class="btn btn-sm btn-outline-secondary"
                        href="{{ route('admin.users.edit', ['id' => $user->id]) }}"
                        title="Gerenciar dados de {{ $user->first_name . ' ' . $user->last_name }}">
                        {{ icon('gear.fill') }} Gerenciar
                    </a>
                </div>
            </div>
        @endforeach
    </section>

    @include('includes.pagination', ['model' => $users])
@endsection

@section('modals')
    @include('includes.admin.modal-bans-list')
@endsection
