@extends('layouts.site')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/splide.min.css') }}">
@endsection

@section('content')
    <section id="contact" class="py-3 section contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 d-flex flex-column align-items-center text-center">
                    <h2 class="">
                        Entre em contato
                    </h2>
                    <p class="mb-0">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Enim eveniet quae a exercitationem
                        dignissimos ipsum minima non voluptates odit esse.
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <form class="jsFormSubmit" action="{{ route('site.contact') }}" method="post">
                        @csrf
                        <div class="message-area jsMessageArea"></div>

                        <div class="form-row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">Nome:</label>
                                    <input class="form-control" type="text" name="name" id="name">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input class="form-control" type="text" name="email" id="email">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="subject">Assunto:</label>
                                    <input class="form-control" type="text" name="subject" id="subject">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="message">Mensagem:</label>
                                    <textarea class="form-control" name="message" id="message"></textarea>
                                </div>
                            </div>

                            <div class="col-12 py-3 text-center">
                                <button class="btn btn-primary" type="submit">
                                    {{ icon('send.check') }}
                                    <span class="bnt-text">
                                        Enviar
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

