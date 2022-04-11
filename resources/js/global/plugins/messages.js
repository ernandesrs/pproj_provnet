/**
 * Plugin para inserção de mensagens
 * 
 * Dependências:
 ** jQuery e jQuery-ui(effects)
 * 
 */
(function ($) {
    let alertModel = null;
    let alertHandler = null;

    $.fn.errorMessage = function (message) {
        message = toObj(message);
        let full = {};

        $.extend(full, message, {
            "style": "alert-danger",
        });

        $(this).addMessage(full);
    }

    $.fn.warningMessage = function (message) {
        message = toObj(message);
        let full = {};

        $.extend(full, message, {
            "style": "alert-warning",
        });

        $(this).addMessage(full);
    }

    $.fn.infoMessage = function (message) {
        message = toObj(message);
        let full = {};

        $.extend(full, message, {
            "style": "alert-info",
        });

        $(this).addMessage(full);
    }

    $.fn.successMessage = function (message) {
        message = toObj(message);
        let full = {};

        $.extend(full, message, {
            "style": "alert-success",
        });

        $(this).addMessage(full);
    }

    $.fn.addMessage = function (message) {
        let messageContainer = $(this);
        alertModel = $("#models .alert").clone();
        alertModel.addClass(message.style);

        if (message.fixed)
            alertModel.addClass("fixed-alert");

        if (message.title)
            alertModel.find(".alert-heading").text(message.title);
        else
            alertModel.find(".alert-heading").remove();

        alertModel.find(".alert-message").text(message.message);

        messageContainer.html(alertModel);

        alertModel.showMessage();
    };

    $.fn.showMessage = function () {
        if (!alertModel) alertModel = $(this);

        alertModel.show("blind", "fast", function () {
            alertModel.effect("bounce", "fast");
        });

        clearAlertHandler();

        if (alertModel.hasClass("fixed-alert")) {
            alertHandler = setTimeout(function () {
                hideMessage(alertModel);
            }, 7500);
        }

        alertModel.find(".jsBtnClose").on("click", function (e) {
            e.preventDefault();
            hideMessage(alertModel);
        });
    };

    function hideMessage(alert) {
        clearAlertHandler();

        if (alert.hasClass("fixed-alert")) {
            alert.effect("bounce", "slow", function () {
                $(this).hide("blind", "fast");
            });
        } else {
            alert.hide("blind", "fast");
        }
    }

    function clearAlertHandler() {
        if (alertHandler)
            clearTimeout(alertHandler);
    }

    function toObj(param) {
        if (typeof param === "object") {
            return param;
        } else {
            return {
                "title": null,
                "message": param,
                "fixed": false,
            };
        }
    }
}(jQuery));