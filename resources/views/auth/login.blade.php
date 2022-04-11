@extends('layouts.auth')

@section('title', __('auth.login.title'))

@section('content')
    <div class="shadow login-box bg-white">
        <div class="row justify-content-center">
            <div class="col-12 pt-3 text-secondary">
                <div class="d-flex flex-column justify-content-start align-items-center h-100">
                    <h2 class="pb-2">
                        {{ __('auth.login.title') }}
                    </h2>
                    <p class="mb-0">
                        {{ __('auth.login.wordOr') }} <a class="btn btn-sm btn-secondary"
                            href="{{ route('register') }}">{{ __('auth.login.wordRegister') }}</a>
                    </p>
                </div>
            </div>

            <div class="col-12 pt-3 pb-5 px-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">{{ __('auth.login.wordMail') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">{{ __('auth.login.wordPassword') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>

                            <label class="custom-control-label" for="remember">
                                {{ __('auth.login.wordRememberMe') }}
                            </label>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-12 col-md-5">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ icon('login') }}
                                {{ __('auth.login.wordLogin') }}
                            </button>
                        </div>

                        <div class="col-12 col-md-7 text-center pt-2 pt-md-0 text-md-right">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}"
                                    title="{{ __('auth.login.wordForgotPass') }}">
                                    {{ __('auth.login.wordForgotPass') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
