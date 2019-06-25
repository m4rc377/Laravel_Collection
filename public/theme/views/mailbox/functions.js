/**
 * Mailbox main core functions
 *
 * These functions used only to send the request and deliver the response to a callback
 * so it's not the responsibility of these functions for example to update the UI
 *
 * @type {{toggleImportant: Mailbox.toggleImportant, trash: Mailbox.trash, remove: Mailbox.remove, send: Mailbox.send, reply: Mailbox.reply, forward: Mailbox.forward}}
 */

var Mailbox = {
    toggleImportant: function toggleImportant(ids, cb) {

        $.ajax({
           url: BASE_URL + "/admin/mailbox-toggle-important",
           method: "PUT",
           data: {mailbox_flag_ids: ids, method: "PUT", _token: $("meta[name='csrf_token']").attr("content")},
           dataType: "json",
           success: function (response) {
                cb(response);
           }
        });
    },
    trash: function trash(ids, cb) {                    // move to the trash folder

        $.ajax({
            url: BASE_URL + "/admin/mailbox-trash",
            method: "DELETE",
            data: {mailbox_user_folder_ids: ids, method: "DELETE", _token: $("meta[name='csrf_token']").attr("content")},
            dataType: "json",
            success: function (response) {
                cb(response);
            }
        });
    },
    send: function send() {

    },
    reply: function reply() {

    },
    forward: function forward() {

    }
};