@extends('layouts.auth')

@section('title', __('auth.passwords.reset.title'))

@section('content')
    {{-- view para atualização de senha --}}
    <div class="shadow reset-box">
        <div class="row justify-content-center pt-4 pb-5 px-5">
            <div class="col-12">
                <h2 class="pb-2 text-center">
                    {{ __('auth.passwords.reset.title') }}
                </h2>
            </div>

            <div class="col-12">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label for="email">{{ __('auth.passwords.reset.wordEmail') }}</label>

                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">{{ __('auth.passwords.reset.wordPassword') }}</label>

                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">{{ __('auth.passwords.reset.wordConfirmPassword') }}</label>

                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password">
                    </div>

                    <div class="row mb-0">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('auth.passwords.reset.wordResetButton') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
