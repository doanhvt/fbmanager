$(document).ready(function() {
    $(document).on("click", "#btn_contact_filter_submit", function(e) {
        e.preventDefault();
        var obj = $(".e_data_table");
        show_ajax_loading(obj)
        $("#contact_filter_form").ajaxSubmit({
            dataType: "text",
            success: function(dataAll) {
                var temp = dataAll.split($("body").attr("data-barack"));
                var data = {};
                for (var i in temp) {
                    temp[i] = $.parseJSON(temp[i]);
                    data = $.extend({}, data, temp[i]);
                }
                if (window[data.callback]) {
                    console.log("Gọi hàm: ", data.callback);
                    window[data.callback](data, obj);
                } else {
                    console.log("Không tìm thấy hàm yêu cầu:'", data.callback, "'-->Tự động gọi hàm xử lý mặc định 'default_data_table'");
                    default_data_table(data, obj);
                }
            }
        });
    });
    
    $(document).on('click', 'div.e_widget_title', function() {
        var obj = $(this);
        var widget_content = obj.parents('.widget').find('.widget-content');
        var e_action_widget = obj.parents('.widget').find('.e_action_widget')
        widget_content.toggle();
        if (e_action_widget.hasClass('maximize')) {
            e_action_widget.removeClass('maximize');
            e_action_widget.addClass('minimize');
        } else {
            e_action_widget.addClass('maximize');
            e_action_widget.removeClass('minimize');
        }

    });

    $(document).on('click', 'input.e_check_status', function() {
        var status_value = $(this).val();
        var check = true;
        if ($(this).attr('name') == 'status_l3') {
            var check_appointment = $('.e_check_appointment').val()
            if (check_appointment == undefined || check_appointment == '0') {
                check = false;
            }
        }
        if (check) {
            if (parseInt(status_value)) {

                $('select.e_contact_level').removeAttr('disabled');
                var currentContactLevel = $('input[name="currentContactLevel"]').val();
                $('select.e_contact_level').val(parseInt(currentContactLevel) + 1);
                var temp = $('div.e_contact_level');
                temp.find('span').text('Chuyển lên level tiếp theo');
                $('select.e_contact_status').val(1);
                var temp = $('div.e_contact_status');
                temp.find('span').text('Tiếp tục chăm sóc');
            } else {
                $('select.e_contact_level').attr('disabled', 'disabled');
                $('select.e_contact_status').val(0);
                var temp = $('div.e_contact_status');
                temp.find('span').text('Ngừng chăm sóc');
                $('select.e_contact_level').val('NULL');
                var temp = $('div.e_contact_level');
                temp.find('span').text('- Lựa chọn giá trị -');
            }
        }

    });

    $(document).on("click", "#i_add_phone", add_phone);
    $(document).on("click", "#i_add_email", add_email);
});
function add_email() {
    var obj = $(this);
    var table = obj.siblings();
    var tr = table.find('.end_email');
    tr.removeAttr('class');
    tr.after('<tr class = "end_email"><td></td><td><input name = "new_email[]"></td><td></td></tr>');
}
function add_phone() {
    var obj = $(this);
    var table = obj.siblings();
    var tr = table.find('.end_phone');
    tr.removeAttr('class');
    tr.after('<tr class = "end_phone"><td></td><td><input name = "new_phone[]"></td><td></td></tr>');
}

