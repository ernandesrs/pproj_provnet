/**
 * 
 * Plugin jQuery para controle de backdrops: adiciona e remove backdrops
 * Dependências:
 * * jQuery
 * * Bootstrap
 * 
 */
(function ($) {
    let bootstrapSpinner = `
        <div class="spinner-border spinner-border-md text-secondary_light" role="status">
            <span class="sr-only">
                Loading...
            </span>
        </div>
    `;
    let backdrop = $(`
        <div class="backdropers">

        </div>
    `);

    $.fn.addLightBackdrop = function (fixed = false, loading = true) {
        $(this).addBackdrop(fixed, loading, false);
    };

    $.fn.addDarkBackdrop = function (fixed = false, loading = true) {
        $(this).addBackdrop(fixed, loading, true);
    };

    $.fn.addBackdrop = function (fixed = false, loading = true, dark = true) {
        defPosition(fixed);
        defLoading(loading);

        if (dark)
            backdrop.css({ "background-color": "rgba(0, 0, 0, 0.5)" });
        else
            backdrop.css({ "background-color": "rgba(255, 255, 255, 0.5)" });

        $(this).prepend(backdrop.css({
            "display": "flex",
            "justify-content": "center",
            "align-items": "center",
            "width": "100%",
            "height": "100%",
            "z-index": "999",
        }).hide().show("fade", "fast"));
    };

    $.fn.removeBackdrop = function () {
        $(this).find(".backdropers").hide("fade", "fast", function () {
            $(this).remove();
        });
    };

    function defLoading(loading) {
        if (loading) {
            if (!backdrop.find(".spinner-border").length)
                backdrop.append(bootstrapSpinner);
        } else
            backdrop.find(".spinner-border").remove();
    }

    function defPosition(fixed) {
        if (fixed)
            backdrop.css({ "position": "fixed" });
        else
            backdrop.css({ "position": "absolute" });
    }
}(jQuery));