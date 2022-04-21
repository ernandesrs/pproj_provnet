@extends("layouts.admin")

@section('content')

    <section class="section user-edit-page">

        <div class="section-content">
            <div class="row justify-content-center px-4 py-4">
                <div
                    class="col-12 col-md-4 py-4 mb-4 mb-md-0 border shadow d-flex flex-column justify-content-start align-items-center">
                    <img class="img-thumbnail rounded-circle user-photo" src="{{ user_thumb_profile($user) }}"
                        alt="{{ $user->first_name }}">

                    <div class="w-100 bg-light py-2 px-4 mt-3" style="font-size: 0.875rem">
                        <div>
                            <span class="font-weight-600">Conta: </span>
                            <span
                                class="badge badge-{{ $user->level > 1 ? 'success' : 'light' }}">{{ $user->level == 3 ? 'SUPER' : ($user->level == 2 ? 'Admin' : 'Usuário') }}</span>
                        </div>
                        <div>
                            <span class="font-weight-600">Registro:</span>
                            <span>{{ date('d/m/Y H:i', strtotime($user->created_at)) }}</span>
                        </div>
                        @php
                            $statusStyle = null;
                            $statusText = null;
                            if ($user->status == 'banned') {
                                $statusStyle = 'danger';
                                $statusText = 'Banido';
                            } elseif ($user->status == 'archived') {
                                $statusStyle = 'warning';
                                $statusText = 'Arquivado';
                            } else {
                                if ($user->email_verified_at) {
                                    $statusStyle = 'info';
                                    $statusText = 'Verificado em  ' . date('d/m/Y H:i', strtotime($user->email_verified_at));
                                } else {
                                    $statusStyle = 'light_dark';
                                    $statusText = 'Não verificado';
                                }
                            }
                        @endphp
                        <div>
                            <span class="font-weight-600">Status:</span>
                            <span class="badge badge-{{ $statusStyle }}">{{ $statusText }}</span>
                        </div>
                    </div>

                    @if ($user->level != 3)
                        {{-- promote, demote, ban, unban --}}
                        <div class="py-2 mt-2 text-center">
                            @if ($user->level == 1)
                                <a class="btn btn-sm btn-outline-success mb-3 jsButtonConfirm" href="" data-type="warning"
                                    data-title="Promoção de usuário"
                                    data-message="Você está promovendo este usuário para administrador. Continuar?"
                                    data-action="{{ route('admin.users.promote', ['id' => $user->id]) }}">
                                    {{ icon('user.check') }}
                                    Tornar administrador
                                </a>
                            @else
                                <a class="btn btn-sm btn-outline-danger mb-3 jsButtonConfirm" href="" data-type="warning"
                                    data-title="Promoção de usuário"
                                    data-message="Você está rebaixando este administrador a usuário. Continuar?"
                                    data-action="{{ route('admin.users.demote', ['id' => $user->id]) }}">
                                    {{ icon('user.x') }}
                                    Tornar usuário
                                </a>
                            @endif

                            @if ($user->status == 'banned')
                                <a class="btn btn-sm btn-outline-warning mb-3 jsButtonConfirm" href="" data-type="warning"
                                    data-title="Desbanimento de usuário"
                                    data-message="Você está desbanido este usuário, deseja continuar?"
                                    data-action="{{ route('admin.users.unban', ['id' => $user->id]) }}">
                                    {{ icon('user.x') }}
                                    Desbanir
                                </a>

                                <a class="btn btn-sm btn-outline-warning jsButtonBansList"
                                    data-target="{{ route('admin.users.bans', ['id' => $user->id]) }}"
                                    data-name="{{ $user->first_name . ' ' . $user->last_name }}">
                                    {{ icon('list.list') }} Banimentos
                                </a>
                            @else
                                <a class="btn btn-sm btn-outline-warning mb-3 jsButtonBan" href="" data-type="warning"
                                    data-title="Banimento de usuário"
                                    data-message="Você está banindo este usuário, deseja continuar?"
                                    data-action="{{ route('admin.users.ban', ['id' => $user->id]) }}">
                                    {{ icon('user.x') }}
                                    Banir
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="col-12 col-md-8">
                    @if (auth()->user()->id == $user->id)
                        <div class="alert alert-secondary text-center" role="alert">
                            <small>
                                <div class="font-weight-medium">
                                    Atenção!
                                </div>
                                <p class="mb-0">
                                    Você não pode editar seus dados de usuário por aqui, para isso você deve acessar seu
                                    perfil.
                                </p>
                            </small>
                        </div>
                    @endif
                    <form class="jsFormSubmit" method="POST"
                        action="{{ route('admin.users.update', ['id' => $user->id]) }}" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-12">
                                <div class="message-area jsMessageArea"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="first_name">Nome:</label>
                                    <input class="form-control" type="text" name="first_name" id="first_name"
                                        value="{{ $user->first_name }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Sobrenome:</label>
                                    <input class="form-control" type="text" name="last_name" id="last_name"
                                        value="{{ $user->last_name }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="username">Usuário:</label>
                                    <input class="form-control" type="text" name="username" id="username"
                                        value="{{ $user->username }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="gender">Gênero:</label>
                                    <select class="form-control" name="gender" id="gender">
                                        @foreach (\App\Models\User::ALLOWED_GENDERS as $gender)
                                            <option value="{{ $gender }}"
                                                {{ $user->gender == $gender ? 'selected' : null }}>
                                                {{ $gender == 'male' ? 'Masculino' : 'Feminino' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4 col-lg-3 mb-3">
                                <div class="border photo-preview"
                                    style="width: 100%; height: 100%; max-width: 100px; max-height: 100px;"></div>
                            </div>

                            <div class="col-12 col-sm-8 col-lg-9 mb-3">
                                <div class="form-group">
                                    <label for="photo">Foto:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="photo"
                                            lang="{{ config('app.locale') }}" name="photo">
                                        <label class="custom-file-label"
                                            for="photo">{{ $user->photo ? 'Atualizar foto' : 'Enviar foto' }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input class="form-control" type="text" name="email" id="email"
                                        value="{{ $user->email }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="password">Senha:</label>
                                    <input class="form-control" type="password" name="password" id="password"
                                        autocomplete="new-password">
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar:</label>
                                    <input class="form-control" type="password" name="password_confirmation"
                                        id="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-12 d-flex">
                                <button class="btn btn-outline-danger mr-auto jsButtonConfirm" type="button"
                                    data-type="danger" data-title="Exclusão de usuário"
                                    data-message="Você está excluindo um usuário permanentemente e isso não pode ser desfeito. Continuar?"
                                    data-action="{{ route('admin.users.destroy', ['id' => $user->id]) }}">
                                    {{ icon('trash') }} Excluir
                                </button>

                                <button class="btn btn-info" type="submit">
                                    {{ icon('spinner.spinner') }}
                                    Atualizar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('modals')
    @if ($user->status != \App\Models\User::STATUS_BANNED)
        @include('includes.admin.modal-ban')
    @endif
    @include('includes.admin.modal-bans-list')
@endsection

@section('scripts')
    <script>
        $("#photo").on("change", function() {
            let img = $("<img />");
            img.attr("src", URL.createObjectURL($(this)[0].files[0]));
            img.css({
                "width": "100%",
                "height": "100%",
            });

            $(this).parents().eq(3).find(".photo-preview").html(img[0]);
        });
    </script>
@endsection
