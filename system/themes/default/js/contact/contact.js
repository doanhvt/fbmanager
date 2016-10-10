$(document).ready(function() {
    $("#excel_file").change(function() {
        $("#excel_file_submit").click();
    });
    $(document).on('change','select.e_change_campaign',ajax_change_campaign);
});

function get_form_forward_contact(data, obj) {
    a = data;
    if (data.state == 1) {
        if (data.html) {
            var $modal = $("<div class='modal fade out modal_content'>");
            $modal.html(data.html);
            $modal.find("button:not(.b_add)").remove();
            $modal.modal();
        }
    } else {
        if (data.redirect) {
            window.location = data.redirect;
        }
    }
}

function save_form_forward_contact(data, form, button) {
    button.removeAttr('disabled');
    var jgrow = "error";
    if (data.state == 1) { /* Thành công */
        button.removeClass('btn-danger');
        button.addClass('btn-success');
        button.html('Thành công ...');
        jgrow = "success";
        $.jGrowl("<i class='icon16 i-checkmark-3'></i> " + data.msg, {
            group: jgrow,
            position: 'top-right',
            sticky: false,
            closeTemplate: '<i class="icon16 i-close-2"></i>',
            animateOpen: {
                width: 'show',
                height: 'show'
            }
        });
        window.location = data.redirect;
    } else if (data.state == 0) { /* Lỗi dữ liệu không hợp lệ */
        button.addClass('btn-danger');
        button.removeClass('btn-success');
        button.html('Thất bại ...');
    } else if (data.state == 2) { /* Lỗi phía server */
        button.addClass('btn-danger');
        button.removeClass('btn-success');
        button.html('Thất bại ...');
        location.reload();
    } else {
        button.addClass('btn-danger');
        button.removeClass('btn-success');
        button.html('Không rõ kết quả');
        location.reload();
    }
    if (data.error) {
        show_error(data.error);
    }

    $.jGrowl("<i class='icon16 i-checkmark-3'></i> " + data.msg, {
        group: jgrow,
        position: 'top-right',
        sticky: false,
        closeTemplate: '<i class="icon16 i-close-2"></i>',
        animateOpen: {
            width: 'show',
            height: 'show'
        }
    });
}

function ajax_change_campaign(){
    var obj = $(this);
    var url = obj.attr('data-url');
    var data = {
        'id_campaign' : obj.val()
    };
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: "json",
        success: function(dataAll) {
        },
        error: function(a, b, c) {
            alert(a + b + c);
//            window.location = url;
        },
    });
}
