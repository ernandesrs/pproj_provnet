/**
 * 
 * Plugin para formulário: adiciona e remove mensagem de erros nos campos do formulário com Bootstrap
 * Dependências:
 * * jQuery
 * * Bootstrap
 * 
 */
(function ($) {

    $.fn.addErrors = function (errors, tooltip = false) {
        let form = $(this);
        let inputs = form.find("input, textarea, select");

        $.each(inputs, function (key, inpt) {
            let input = $(inpt);
            let name = input.attr("name");

            if (errors[name]) {
                input.addClass("is-invalid");
                input.parent().css("position", "relative").append(`
                    <div id="${name}-feedback" class="${tooltip ? "invalid-tooltip" : "invalid-feedback"}">
                        ${errors[name][0]}
                    </div>
                `);
            } else {
                errorRemove(input, name);
            }
        });
    };

    $.fn.removeErrors = function () {
        let form = $(this);
        let inputs = form.find(".is-invalid");

        $.each(inputs, function (key, inpt) {
            let input = $(inpt);
            let name = input.attr("name");

            errorRemove(input, name);
        });
    };

    function errorRemove(input, name) {
        if (input.hasClass("is-invalid")) {
            input.removeClass("is-invalid");
            input.parent().find("#" + name + "-feedback").remove();
        }
    }

}(jQuery));