@extends("layouts.admin")

@section('content')
    <div class="section bg-light mb-4 py-3 px-2 px-md-4 shadow-sm">
        <p class="mb-0 text-center px-0 px-md-4">
            Estes são os ícones disponíveis para serem utilizados no sistema. Basta clicar no input que o nome ou classe do
            ícone será
            copiado. Caso copie o nome do ícone, o mesmo deverá ser passado como argumento para a função
            <code>icon('iconName')</code>.
        </p>
        <div class="custom-control custom-checkbox text-center py-1">
            <input type="checkbox" class="custom-control-input" id="getIconMethod">
            <label class="custom-control-label" for="getIconMethod">Ver classe do ícone</label>
        </div>
    </div>
    <section class="section icons-list">
        <div class="row justify-content-center icons">
            @foreach (config('icons') as $key => $icon)
                @if (is_array($icon))
                    @foreach ($icon as $k => $i)
                        <div class="col-6 col-sm-4 col-md-3 mb-4 icon">
                            <div class="card">
                                <div class="shadow-sm card-body text-center">
                                    {{ icon("{$key}.{$k}") }}
                                    <input class="mt-2 form-control label jsIconLabel" value="{{ $key . '.' . $k }}"
                                        data-icon="{{ $i }}" readonly />
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-6 col-sm-4 col-md-3 mb-4 icon">
                        <div class="card">
                            <div class="shadow-sm card-body text-center">
                                {{ icon("{$key}") }}
                                <input class="mt-2 form-control label jsIconLabel" value="{{ $key }}"
                                    data-icon="{{ $icon }}" readonly />
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>
@endsection


@section('scripts')
    <script>
        $(".jsIconLabel").on("click", function() {
            $(this).select();
            document.execCommand('copy');
        });

        $("#getIconMethod").on("change", function(e) {
            let iconsList = $(".jsIconLabel");

            iconsList.each(function(k, v) {
                let current = $(v).val();
                let alt = $(v).attr("data-icon");
                $(v).val(alt).attr("data-icon", current);
            });

            if ($(this).prop("checked")) {
                $(this).parent().find("label").text("Ver nome do ícone");
            } else {
                $(this).parent().find("label").text("Ver classe do ícone");
            }
        });
    </script>
@endsection
