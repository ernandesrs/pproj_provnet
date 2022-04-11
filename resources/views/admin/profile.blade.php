@extends("layouts.admin")

@section('content')
    <section class="section profile-page">
        <div class="section-header">
            <div class="subtitle">
                Olá {{ $user->first_name }}, veja ou atualize seus dados de usuário.
            </div>
        </div>

        <div class="section-content">
            <div class="row justify-content-center px-4 py-4">
                <div class="col-12 py-4 mb-4 mb-md-0 d-flex flex-column justify-content-center align-items-center bg-light">
                    <img class="img-thumbnail rounded-circle user-photo" src="{{ user_thumb_profile($user) }}"
                        alt="{{ $user->first_name }}">
                    <div class="d-flex justify-content-center w-100 py-3 px-4 mt-3">
                        <div class="mr-2">
                            <span class="font-weight-600">Conta: </span>
                            <span
                                class="badge badge-{{ $user->level > 1 ? 'success' : 'light' }}">{{ $user->level == 3 ? 'SUPER' : ($user->level == 2 ? 'Admin' : 'Usuário') }}</span>
                        </div>
                        <div class="mx-2">
                            <span class="font-weight-600">Registro:</span>
                            <span>{{ date('d/m/Y H:i', strtotime($user->created_at)) }}</span>
                        </div>
                        <div class="ml-3">
                            <span class="font-weight-600">Verificação:</span>
                            @if ($user->email_verified_at)
                                <span>{{ date('d/m/Y H:i', strtotime($user->email_verified_at)) }}</span>
                            @else
                                <span>Não verificado</span>
                            @endif
                        </div>
                    </div>
                    @if (!$user->email_verified_at)
                        <div class="w-100">
                            <div class="alert alert-warning mb-0 text-center">
                                <div class="alert-heading">
                                    Atenção, email não verificado
                                </div>
                                <p>
                                    Seu e-mail ainda não foi verificado e sua conta está passiva de exclusão a qualquer
                                    momento. Acesse seu email e faça a verificação. Caso não tenha recebido ou tenha
                                    excluído a mensagem acidentalmente, <a class="jsButtonConfirm" role="button"
                                        data-type="info" data-title="Solicitar link de verificação"
                                        data-message="Caso não tenha recebido um email com o link de verificação você pode solicitar um novo link! Deseja solicitar um novo link?"
                                        data-action="{{ route('verification.send') }}">solicite um novo link de
                                        verificação.</a>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-12 py-4">
                    <form class="jsFormSubmit" method="POST" action="{{ route('auth.profile.update') }}">
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
                                            <option value="{{ $user->gender }}"
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
                                        <input type="file" class="custom-file-input" id="photo" name="photo"
                                            lang="{{ config('app.locale') }}">
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
                            <div class="col-12 text-center text-md-right">
                                <button class="btn btn-success" type="submit">
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
