$(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
        var clicks = $(this).data('clicks');
        if (clicks) {
            //Uncheck all checkboxes
            $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
        } else {
            //Check all checkboxes
            $(".mailbox-messages input[type='checkbox']").iCheck("check");
            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
        }
        $(this).data("clicks", !clicks);
    });

    //Handle starring
    $(".mailbox-star").click(function (e) {
        e.preventDefault();

        handleImportant([$(this).parents("tr").attr("data-mailbox-flag-id")]);
    });

    // handle starring for checked inputs
    $(".mailbox-star-all").on("click", function (e) {
        if(!checkboxCheck()) {
            return;
        }

        var checked = new Array();

        $(".check-message:checked").each(function (val) {
            checked.push($(this).parents("tr").attr("data-mailbox-flag-id"));
        });

        handleImportant(checked);
    });
});

function checkboxCheck()
{
    if($(".check-message:checked").length == 0) {
        alert("Please select at least one row to process!");

        return false;
    }

    return true;
}

function handleImportant(data)
{
    Mailbox.toggleImportant(data, function (response) {

        if(response.state == 0) {
            alert(response.msg);
        } else {
            response.updated_flags.map(function(value) {
                if(value.is_important == 1) {
                    //Switch states
                    $("tr[data-mailbox-flag-id='"+value.id+"'] td.mailbox-star").find("a > i").removeClass("fa-star-o").addClass("fa-star");
                } else {
                    //Switch states
                    $("tr[data-mailbox-flag-id='"+value.id+"'] td.mailbox-star").find("a > i").removeClass("fa-star").addClass("fa-star-o");
                }
            });

            alert(response.msg);
        }
    });
}