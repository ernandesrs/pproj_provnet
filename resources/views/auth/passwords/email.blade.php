@extends('layouts.auth')

@section('title', __('auth.passwords.email.title'))

@section('content')
    {{-- view para recuperação de senha --}}
    <div class="shadow email-box">
        <div class="row justify-content-center pt-3 pb-4 px-5">
            <div class="col-12">
                <h2 class="pb-2 text-center">
                    {{ __('auth.passwords.email.title') }}
                </h2>
            </div>
            <div class="col-12">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-12">
                            <input class="form-control text-center @error('email') is-invalid @enderror" type="email"
                                id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="{{ __('auth.passwords.email.wordEmail') }}">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">
                                {{ icon('send.check') }}
                                {{ __('auth.passwords.email.wordSendButton') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
