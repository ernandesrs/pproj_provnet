<div id="addBannerButtons" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="title h5 mb-0"></h5>
                <a class="close" role="button" data-dismiss="modal">x</a>
            </div>
            <div class="modal-body" style="position: relative">
                <form class="jsFormSubmit" action="" method="POST">
                    <div class="row">
                        <div class="col-12">
                            <div class="jsMessageArea"></div>
                        </div>

                        {{-- inputs --}}
                        <div class="col-12 col-lg-7 col-xl-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="text">{{ __('Texto do botão') }}:</label>
                                        <input class="form-control" type="text" name="text" id="text">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="link">{{ __('Link do botão') }}:</label>
                                        <input class="form-control" type="text" name="link" id="link">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="style">{{ __('Estilo do botão') }}:</label>
                                        <select class="form-control" name="style" id="style">
                                            @foreach (['btn-primary', 'btn-outline-primary', 'btn-secondary', 'btn-outline-secondary', 'btn-link'] as $style)
                                                <option value="{{ $style }}">{{ $style }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="target">{{ __('Abrir link') }}:</label>
                                        <select class="form-control" name="target" id="target">
                                            <option value="_self">{{ __('Na mesma janela') }}</option>
                                            <option value="_blank">{{ __('Em outra janela') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- preview --}}
                        <div class="col-12 col-lg-5 col-xl-4 d-flex justify-content-center align-items-center">
                            <div class="form-group w-100 d-flex flex-column align-items-center bg-light border pt-2 px-3 pb-3">
                                <label class="align-self-start" for="">{{ __('Preview') }}:</label>
                                <div class="btn-preview">
                                    <a id="buttonPreview" class="btn btn-primary">{{ __('Button Text') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pt-3 pb-2 text-center text-lg-right">
                            <button class="btn btn-success btn-sm">
                                {{ icon('plus.plusLg') }} {{ __('Cadastrar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
