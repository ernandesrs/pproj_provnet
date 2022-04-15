/**
 * admin sidebar controller
 */
$(function () {
    const MDWIDTH = (992 - 10);

    setNotifications();
    setInterval(function () {
        setNotifications();
    }, 20000);

    sidebarStatus();
    $(window).on("resize", function () {
        sidebarStatus();
    });

    $(document).one("mouseover", ".jsNotificationsItem", function (e) {
        e.preventDefault();
        let link = $(this);
        let url = $(".jsNotifications").attr("data-action") + "&read=1";
        let ids = link.attr("data-ids");

        if (!ids || link.hasClass("read")) return;
        let data = new FormData();

        data.set("ids", ids);

        ajaxRequest(url, data, null, null, function () {
            let count = link.find(".count").text();
            let total = $(".jsNotifications").find(".notification-count").text();
            link.addClass("read").attr("data-ids", "");
            $(".jsNotifications").find(".notification-count").text(parseFloat(total) - parseInt(count));
        }, null);
    });

    $(".jsSidebarToggle").on("click", function () {
        let mobile = null;

        if (($(window).width()) > MDWIDTH)
            mobile = false;
        else {
            mobile = true;

            $(document).on("click", ".backdropers", function () {
                sidebarStatusToggle(false, mobile);
            });
        }

        if ($(".sidebar").hasClass("sidebar-visible"))
            sidebarStatusToggle(false, mobile);
        else
            sidebarStatusToggle(true, mobile);
    });

    $(".jsSidebarAccordion").on("shown.bs.collapse", function (e) {
        let target = $(e.target);
        let groupToggler = target.parent().find("a[data-target='#" + target.attr("id") + "'");
        let icon = groupToggler.find(".nav-group-indicator .icon").attr("class");
        let iconAlt = groupToggler.find(".nav-group-indicator .icon").attr("data-icon");
        groupToggler.find(".nav-group-indicator .icon").removeClass(icon).addClass(iconAlt).attr("data-icon", icon);
        groupToggler.addClass("nav-group-show");
    });

    $(".jsSidebarAccordion").on("hidden.bs.collapse", function (e) {
        let target = $(e.target);
        let groupToggler = target.parent().find("a[data-target='#" + target.attr("id") + "'");
        let icon = groupToggler.find(".nav-group-indicator .icon").attr("class");
        let iconAlt = groupToggler.find(".nav-group-indicator .icon").attr("data-icon");
        groupToggler.find(".nav-group-indicator .icon").removeClass(icon).addClass(iconAlt).attr("data-icon", icon);
        groupToggler.removeClass("nav-group-show");
    });

    function setNotifications() {
        let notificationArea = $(".jsNotifications");
        let notificationList = $(".jsNotifications .jsNotificationsList");

        ajaxRequest(notificationArea.attr("data-action"), null, null, function (response) {
            if (response.notifications.total) {
                if (notificationList.find("#noNotification").length)
                    notificationList.find("#noNotification").remove();

                notificationArea.find(".notification-count").text(response.notifications.total);
                $.each(response.notifications.list, function (k, v) {
                    if (k != "total") {
                        /**
                         * header notifications
                         */
                        let item = notificationList.find("#" + v.name);

                        if (item.length) {
                            item.find(".count").text(v.count);
                        } else {
                            item = $("#models").find(".jsNotificationsItem").clone();
                            item.attr("href", v.url);
                            item.attr("id", v.name);
                            item.find(".title").text(v.title);
                            item.find(".count").text(v.count);
                            notificationList.append(item);
                        }

                        item.attr("data-ids", v.ids);
                    }
                });
            } else {
                if (notificationList.find("#noNotification").length == 0)
                    notificationList.append(`<div class="text-center text-muted" id="noNotification">Sem notificações</div>`);
            }

            /**
             * overview
             */
            let boxes = response.boxes;
            if (boxes) {
                let boxesList = $(document).find(".jsBoxesList");

                $.each(boxes, function (bK, bV) {
                    let boxeDataList = bV.data;

                    if (boxesList.find("#" + bV.name).length) {
                        let boxe = boxesList.find("#" + bV.name);

                        $.each(boxeDataList, function (dK, dV) {
                            $(boxe.find(".jsBoxeDataItem")[(dK + 1)]).find(".boxe-data-value").text(dV.value);
                        });
                    } else {
                        let boxeClone = $("#models").find(".jsBoxe").clone();

                        boxeClone.attr("id", bV.name);
                        if (bV.url)
                            boxeClone.find(".boxe-url").attr("href", bV.url);

                        if (bV.description)
                            boxeClone.find(".boxe-url").attr("title", bV.description);

                        boxeClone.find(".boxe-title").text(bV.title);

                        $.each(boxeDataList, function (dK, dV) {
                            let boxeDataItemClone = $(boxeClone.find(".jsBoxeDataItem")[0]).clone();

                            boxeDataItemClone.removeClass("d-none");

                            if (dV.url)
                                boxeDataItemClone.find(".boxe-data-url").attr("url", dV.url);

                            if (dV.description)
                                boxeDataItemClone.find(".boxe-data-url").attr("title", dV.description);

                            boxeDataItemClone.find(".boxe-data-title").text(dV.title + ": ");
                            boxeDataItemClone.find(".boxe-data-value").text(dV.value);

                            $(boxeClone).find(".boxe-data-list").append(boxeDataItemClone.hide().fadeIn());
                        });

                        boxesList.append(boxeClone);
                    }
                });
            }
        }, null, null);
    }

    function sidebarStatus() {
        if (($(window).width()) > MDWIDTH)
            sidebarStatusToggle(true, false);
        else
            sidebarStatusToggle(false);
    }

    function sidebarStatusToggle(showSidebar, mobile = false) {
        if (showSidebar) {
            if (mobile)
                $("body").addLightBackdrop(true, false);

            // show
            $("body").removeClass("body-wosidebar").addClass("body-wsidebar");
            $(".sidebar").addClass("sidebar-visible");
        } else {
            if (mobile)
                $("body").removeBackdrop();

            // hide
            $("body").removeClass("body-wsidebar").addClass("body-wosidebar");
            $(".sidebar").removeClass("sidebar-visible");
        }
    }
});

/**
 * 
 */
$(function () {

    $(".jsButtonBan").on("click", function (e) {
        e.preventDefault();
        $("#modalUserBan").modal();
    });

    $(".jsButtonBansList").on("click", function (e) {
        e.preventDefault();
        let modal = $("#modalUserBansList");
        let target = $(this).attr("data-target");
        let name = $(this).attr("data-name");

        ajaxRequest(target, null, function () { // beforeSend
            modal.find(".name").text(name);
            modal.find(".content").addLightBackdrop();
            modal.modal();
        }, function (response) { // success
            if (response.reload) {
                window.location.reload();
                return;
            }

            if (response.message) {
                modal.find(".jsMessageArea").addMessage(response.message);
            }

            $(response.bans).each(function (index, ban) {
                let banDate = (new Date(ban.created_at));

                modal.find(".list").append(`
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p class="mb-0">
                                        <span class="font-weight-medium">Status:</span> ${ban.done_at ? "Banimento concluído" : "Banimento ativo"}
                                    </p>
                                    <p class="mb-0">
                                        <span class="font-weight-medium">Banimento:</span> ${ban.type == "temp" ? "Temporário por " + ban.days + " dias" : "Permanente"}
                                    </p>
                                    <p class="mb-0">
                                        <span class="font-weight-medium">Data:</span> ${banDate.getDay().toString().padStart(2, 0)}/${banDate.getMonth().toString().padStart(2, 0)}/${banDate.getFullYear()} ${banDate.getHours().toString().padStart(2, 0)}:${banDate.getSeconds().toString().padStart(2, 0)}
                                    </p>
                                    <p class="mb-0">
                                        <span class="font-weight-medium">Justificativa:</span> ${ban.description}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });
        }, function () { // complete
            modal.find(".content").removeBackdrop();
        }, function () { // error
            modal.find(".jsMessageArea")
                .errorMessage({ "title": "Error!", "message": "Houve um erro ao requisitar lista de banimentos." });
        });

        modal.on("hidden.bs.modal", function (e) {
            modal.find(".name").text("");
            $(this).find(".content .list").html("");
            $(this).find(".content .jsMessageArea").html("");
        });
    });

    $(".jsAddButtonsModal").on("click", function (e) {
        let modal = $("#addBannerButtons");
        let action = $(this).attr("data-action");

        modal.find(".title").html("Adicionar novo botão");
        modal.find("form").attr("action", action);

        modal.modal();
    });

    $(".jsEditButtonModal").on("click", function (e) {
        let button = $(this);
        let modal = $("#addBannerButtons");
        let action = $(this).attr("data-action");

        ajaxRequest(action, null, function () {
            button.parents().eq(3).addLightBackdrop();
        }, function (response) {
            if (response.reload) {
                window.location.reload();
                return;
            }

            if (response.button) {
                modal.find("#text").val(response.button.text);
                modal.find("#link").val(response.button.link);

                modal.find("#local option[value=" + response.button.local + "]").prop("selected", true);
                modal.find("#style option[value=" + response.button.style + "]").prop("selected", true);
                modal.find("#size option[value=" + response.button.size + "]").prop("selected", true);
                modal.find("#target option[value=" + response.button.target + "]").prop("selected", true);

                modal.find("#buttonPreview").text(response.button.text).removeClass("btn-primary").addClass(response.button.style).addClass(response.button.size);

                modal.find(".title").html("Editar botão '<strong>" + response.button.text + "</strong>'");
                modal.find("form").attr("action", response.action);
                modal.find("button[type='submit']").removeClass("btn-success").addClass("btn-info").find(".text").text("Atualizar");

                modal.modal();
            }
        }, function () {
            button.parents().eq(3).removeBackdrop();
        });
    });

    $("#addBannerButtons").on("hidden.bs.modal", function (e) {
        $(this).find("#text").val("");
        $(this).find("#link").val("");
        $(this).find(".jsMessageArea").html("");
        $(this).find("form").removeErrors();

        $($(this).find("#local option")[0]).prop("selected", true);
        $($(this).find("#style option")[0]).prop("selected", true);
        $($(this).find("#size option")[0]).prop("selected", true);
        $($(this).find("#target option")[0]).prop("selected", true);

        $(this).find("#buttonPreview").text("Button Text").attr("class", "btn btn-primary");

        $(this).find(".title").html("");
        $(this).find("form").attr("action", "");
        $(this).find("button[type='submit']").removeClass("btn-info").addClass("btn-success").find(".text").text("Cadastrar");
    });

});

