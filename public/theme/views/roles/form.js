/**
 * Roles Form Api
 */

$(function (e) {

    $("#select_all").on("ifChanged", function (e) {
        if($(this).is(":checked")) {
            $(".permission").iCheck('check');
        } else {
            $(".permission").iCheck('uncheck');
        }
    });

});