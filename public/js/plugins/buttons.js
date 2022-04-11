/**
 * 
 * Plugin para botões: adiciona e remove modo carregamento
 * Dependências:
 * * jQuery
 * * Bootstrap
 * 
 */
(function ($) {
    let bootstrapSpinner = `<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>`;

    $.fn.addLoading = function () {
        $(this).find(".icon").hide();
        $(this).prepend(bootstrapSpinner).prop("disabled", true);
    };
    
    $.fn.removeLoading = function () {
        $(this).prop("disabled", false).find(".spinner-border").remove();
        $(this).find(".icon").show();
    };
}(jQuery));