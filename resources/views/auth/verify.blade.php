@extends('layouts.auth')

@section('title', __('auth.verify.title'))

@section('content')
    <div class="shadow verify-box">
        <div class="row justify-content-center">
            <div class="col-12 px-4 py-3">
                <h2 class="text-center">
                    {{ __('auth.verify.title') }}
                </h2>
                <div class="text-center pb-3">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('auth.verify.msgVerificationResent') }}
                        </div>
                    @endif

                    <p class="py-2 px-md-4">
                        {{ __('auth.verify.msgVerifyYourEmail') }}
                    </p>
                    <a class="btn btn-outline-secondary mb-3 mb-sm-0" href="{{ route('site.index') }}">Página inicial</a>
                    <a class="btn btn-outline-secondary mb-3 mb-sm-0" href="{{ route('auth.account.profile') }}">Perfil</a>
                </div>
            </div>
        </div>
    </div>
@endsection
