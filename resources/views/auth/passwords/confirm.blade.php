@extends('layouts.auth')

@section('title', __('auth.passwords.confirm.title'))

@section('content')
    {{-- view para confirmação de senha --}}
    <div class="shadow confirm-box">
        <div class="row justify-content-center pt-3 pb-4 px-5">
            <div class="col-12">
                <h2 class="pb-2 text-center">
                    {{ __('auth.passwords.confirm.title') }}
                </h2>
            </div>
            <div class="col-12">
                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="row mb-3">

                        <div class="col-12">
                            <div class="form-group">
                                <label for="password">{{ __('auth.passwords.confirm.wordPassword') }}</label>

                                <input class="form-control @error('password') is-invalid @enderror" type="password"
                                    id="password" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-12 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                {{ __('auth.passwords.confirm.wordConfirmButton') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('auth.passwords.confirm.wordForgot') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
