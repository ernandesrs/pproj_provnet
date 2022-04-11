@extends('layouts.auth')

@section('title', __('auth.register.title'))

@section('content')
    <div class="shadow register-box">
        <div class="row justify-content-center">
            <div class="col-12 pt-3 text-secondary text-center">
                <div class="d-flex flex-column justify-content-center align-items-center h-100">
                    <h2 class="py-2">
                        {{ __('auth.register.title') }}
                    </h2>
                    <p class="mb-0">
                        {{ __('auth.register.wordOr') }} <a class="btn btn-sm btn-secondary"
                            href="{{ route('login') }}">{{ __('auth.register.wordLogin') }}</a>
                    </p>
                </div>
            </div>

            <div class="col-12 pt-4 pb-3 px-5">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <input id="first_name" type="text"
                                    class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                    value="{{ old('first_name') }}" autocomplete="first_name" autofocus
                                    placeholder="{{ __('auth.register.wordFirstName') }}">

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <input id="last_name" type="text"
                                    class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                    value="{{ old('last_name') }}" autocomplete="last_name" autofocus
                                    placeholder="{{ __('auth.register.wordLastName') }}">

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <input id="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{ old('username') }}" autocomplete="username" autofocus
                                    placeholder="{{ __('auth.register.wordUsername') }}">

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender"
                                    autocomplete="gender" autofocus>
                                    @foreach (\App\Models\User::ALLOWED_GENDERS as $gender)
                                        <option value="{{ $gender }}">
                                            {{ __('auth.register.wordGender' . Str::ucfirst($gender)) }}</option>
                                    @endforeach
                                </select>

                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" autocomplete="email"
                                    placeholder="{{ __('auth.register.wordMail') }}">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    autocomplete="new-password" placeholder="{{ __('auth.register.wordPassword') }}">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" autocomplete="new-password"
                                    placeholder="{{ __('auth.register.wordPasswordConfirm') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0 pt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ icon('check.lg') }}
                                    {{ __('auth.register.wordRegister') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
