$(function () {
    let alerts = $(document).find(".message-area .jsAlert");

    if (alerts.length) {
        $(alerts[0]).showMessage();
    }

    $(document).on("submit", ".jsFormSubmit", function (e) {
        e.preventDefault();
        let form = $(this);
        let button = $(e.originalEvent.submitter);
        let messageArea = form.find(".jsMessageArea");
        let data = new FormData(form[0]);
        let url = form.attr("action");

        ajaxRequest(url, data, function () { // beforeSend
            button.addLoading();
            form.addLightBackdrop();
        }, function (response) { // success
            if (response.redirect) {
                window.location.href = response.redirect;
                return;
            }

            if (response.reload) {
                window.location.reload();
                return;
            }

            if (response.message)
                messageArea.addMessage(response.message);

            form.removeErrors();
        }, function (xhr) { // complete
            button.removeLoading();
            form.removeBackdrop();
        }, function (xhr) { // error
            let response = xhr.responseJSON;

            if (!response) {
                messageArea.errorMessage({
                    "title": "Solicitação não respondida",
                    "message": "Verifique sua conexão com a internet e tente de novo.",
                });
                return;
            }

            messageArea.errorMessage(response.message);
            if (response.errors)
                form.addErrors(response.errors, true);
        });
    });

    $(".jsButtonConfirm").on("click", function (e) {
        e.preventDefault();
        let modal = $("#models .jsConfirmationModal").clone();
        modal.addClass($(this).attr("data-type"));
        modal.find(".modal-title").html($(this).attr("data-title"));
        modal.find(".modal-text").html($(this).attr("data-message"));
        modal.find(".jsFormSubmit").attr("action", $(this).attr("data-action"));
        modal.find(".jsFormSubmit").addClass("jsFormSubmit");

        $("body").append(modal.modal());

        $(".jsConfirmationModal").on("hidden.bs.modal", function () {
            $(this).remove();
        });
    });
});

function ajaxRequest(url, data = null, before = null, success = null, complete = null, error = null) {
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        contentType: false,
        processData: false,
        timeout: 5000,

        beforeSend: function () {
            if (before) {
                before();
            }
        },

        success: function (response) {
            if (success) {
                success(response);
            }
        },

        complete: function () {
            if (complete) {
                complete();
            }
        },

        error: function (xhr) {
            if (error) {
                error(xhr);
            }
        }
    });
}

