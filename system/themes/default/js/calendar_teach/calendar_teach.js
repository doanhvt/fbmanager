$(document).ready(function() {
    $('.e_select_submit').change(function() {
        $('#contact_filter_form').submit();
    });

    $("#i_from_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#i_to_date").datepicker("option", "minDate", selectedDate);
        }
    });//cam giao dien kieu date time cho the thoi gian

    $("#i_to_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#i_from_date").datepicker("option", "maxDate", selectedDate);
        }
    });//cam giao dien kieu date time cho the thoi gian

    $('.e_Scrollbar').slimScroll({
        height: '150px',
        size: '5px',
        color: '#333',
        railColor: '#333',
        railOpacity: '0.3'
    });

    $(".e_teacher").attr("draggable", "true");

    $(document).on('click', '.e_title_weekday', function() {
        var obj = $(this);
        var day = obj.attr('day');

        $('.e_table_data').find('.e_tr').each(function() {
            if (obj.hasClass('e_active')) {
                if ($(this).attr('day') === day) {
                    $(this).hide();
                }

            } else {
                if ($(this).attr('day') === day) {
                    $(this).show();
                }
            }

        });
        if (obj.hasClass('e_active')) {
            obj.removeClass('e_active');
        } else {
            obj.addClass('e_active');
        }
    });

    $('.e_widget_content').find('.e_teacher').each(function() {
        var html = $(this).find('.e_info_teacher').html();
        $(this).tooltipster({
            content: html,
            touchDevices: false,
            contentAsHTML: true,
            interactive: true,
            trigger: 'hover'
        });
    });

    $(document).on('click', '.e_icon_del', function() {
        var obj = $(this);
        var status = 0;
        var teacher_info = obj.parent('.e_teacher');
        var id_teacher = teacher_info.attr('id');
        var e_field_info = obj.parents('.e_field_info');
        var icon_status = obj.parents('.info_class').find('.icon_status');
        if (icon_status.hasClass('icon_status_1')) {
            icon_status.removeClass('icon_status_1');
            icon_status.addClass('icon_status_2');
        }
        if (icon_status.hasClass('icon_status_2')) {
            icon_status.removeClass('icon_status_2');
            icon_status.addClass('icon_status_3');
        }

        e_field_info.find('.teacherId').val('');
        var tagert_id = e_field_info.attr('tagert_field');
        var tagert = document.getElementById(tagert_id);
        tagert.appendChild(document.getElementById(id_teacher));
        var value_teacher = teacher_info.attr('teacherId');
        var tagert_class = $('#' + tagert_id).attr('tagert_field');
        var array_class = tagert_class.split('&&');
        $('.e_table_data').find('.' + array_class[1]).each(function() {

            var teacherBackup = $(this).find('.teacherBackup');
            if (teacherBackup.length) {
                var current_val = teacherBackup.val();
                if (current_val) {
                    current_val += ';' + value_teacher;
                } else {
                    current_val += value_teacher;
                }

                teacherBackup.val(current_val);
            }

        });
        change_info_support(teacher_info.attr('teacherId'), status);

        $("#" + tagert_id).mCustomScrollbar({
            theme: "dark"
        });
    });

    $('.e_check_input').change(function() {
        if (isNaN($(this).val())) {
            alert('Dữ liệu không hợp lệ');
            $(this).val('');
            $(this).focus();
            $(this).attr('style', 'border: 1px solid red;');
        } else {
            if (parseInt($(this).val()) <= 0) {
                alert('Dữ liệu không hợp lệ');
                $(this).val('');
                $(this).focus();
                $(this).attr('style', 'border: 1px solid red;');
            }
        }
        $(this).attr('style', '');
        var tabindex = $(this).attr('tabindex');
        if (tabindex == 68 || tabindex == 133 || tabindex == 198 || tabindex == 262 || tabindex == 326 || tabindex == 390) {
            var day = $(this).parent().attr('nextDay');
            $('.e_table_data').find('.input_field').each(function() {
                $(this).find('.e_check_input').addClass('no_check');
                if ($(this).attr('day') == day) {
                    $(this).find('.e_check_input').removeClass('no_check');
                }
            });
        }
    });
    
    $('.e_check_input').click(function() {
        var day = $(this).parent().attr('day');
        $('.e_table_data').find('.input_field').each(function() {
            $(this).find('.e_check_input').addClass('no_check');
            if ($(this).attr('day') == day) {
                $(this).find('.e_check_input').removeClass('no_check');
            }
        });

    });

    $(document).on('change', '.e_add_class', function() {
        var obj = $(this);
        var week = obj.val();
        var url = obj.attr('data_url');
        var data = {
            'week': week
        };
        show_ajax_loading($('.widget-content'));
        $.ajax({
            data: data,
            url: url,
            type: "POST",
            dataType: "json",
            success: function(dataAll) {
                $('#e_unit_wrap').html(dataAll.html);
                $('#i_img_loading').remove();
            }
        });
    });

    $('.e_info_support').click(function() {
        $(this).toggle();
        $('.e_action_fixed').toggle();
    });

    $('.e_action_fixed').click(function() {
        $(this).toggle();
        $('.e_info_support').toggle();
    });

    $('.e_btn_arrange').click(function() {
        show_ajax_loading($('.e_widget_content'));
        $('.e_widget_content').find('.e_ajax_submit').each(function() {
            $(this).submit();
        });
    });

    $(document).on("click", "#btn_contact_filter_submit", function(e) {
        e.preventDefault();
        var obj = $(".table_contact");
        show_ajax_loading(obj);
        $("#arrange_filter_form").ajaxSubmit({
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
    
    $(document).on('click','.e_title_weekDay',function (){
        var e_wrap_info = $(this).siblings('.e_wrap_info');
        e_wrap_info.toggle();
        if($(this).hasClass('title_no_check')){
            $(this).removeClass('title_no_check');
            $(this).addClass('title_check');
        }else{
            $(this).addClass('title_no_check');
            $(this).removeClass('title_check');
        }
    })
});

function dragHandler(target, e) {
    e.dataTransfer.setData('Text', target.id);
    $(target).tooltipster('disable');
    setTimeout(function() {
        $(target).tooltipster('enable');
    }, 1000);
}

function dropHandler(target, e) {
    var status = 1;
    var id = e.dataTransfer.getData('Text');
    var teacher = $(target).find('.teacher');
    var teacher_id = $('#' + id).attr('teacherId');
    var tagert_class = $('#' + id).parents('.list_teacher').attr('tagert_field');
    if ($(target).hasClass(tagert_class)) {
        target.appendChild(document.getElementById(id));
        var array_class = tagert_class.split('&&');
        $('#' + id).parents('.e_table_data').find('.' + array_class[1]).each(function() {
            var teacherBackup = $(this).find('.teacherBackup');
            var current_val = teacherBackup.val();
            if (current_val) {
                var array_val = current_val.split(';');
                var text = '';
                for (var index = 0; index < array_val.length; index++) {
                    if (array_val[index] != teacher_id) {
                        if (text) {
                            text += ';' + array_val[index];
                        } else {
                            text += array_val[index];
                        }
                    }

                }
                teacherBackup.val(text);
            }

        });
        if (teacher.length) {
            var id_teacher = teacher.attr('id');
            var tagert_field_id = $(target).attr('tagert_field');
            var tagert_list_teacher = document.getElementById(tagert_field_id);
            tagert_list_teacher.appendChild(document.getElementById(id_teacher));
        }
        change_info_support(teacher_id, status);
        target.appendChild(document.getElementById(id));
        var teacherId = $(target).find('.teacherId');
        var assistantId = $(target).find('.assistantId');
        if ($(target).hasClass('e_field_info')) {
            teacherId.val(teacher_id);
            assistantId.val(teacher_id);
        }
        var icon_status = $(target).parents('.info_class').find('.icon_status');
        if (icon_status.hasClass('icon_status_3')) {
            icon_status.removeClass('icon_status_3');
            icon_status.addClass('icon_status_2');
        } else if (icon_status.hasClass('icon_status_2')) {
            icon_status.removeClass('icon_status_2');
            icon_status.addClass('icon_status_1');
        }


        $('#' + id).tooltipster('enable');
        e.preventDefault();
    }
}

function change_info_support(teacher_id, status) {
    $('#i_fixed_wrap').find('.e_row_field').each(function() {
        if ($(this).attr('teacherId') == teacher_id) {
            var field_h2 = $(this).find('.field_h2');
            var field_h1 = $(this).find('.field_h1');
            var current_val = field_h2.text();
            var field_h1_val = field_h1.text();

            if (status) {
                field_h2.text(parseInt(current_val) + 1);
                if (parseInt(current_val) + 1 > field_h1_val) {
                    $(this).removeClass('row_field_3');
                    $(this).removeClass('row_field_2');
                    $(this).removeClass('row_field_1');
                    $(this).addClass('row_field_1');
                }
                if (parseInt(current_val) + 1 <= field_h1_val) {
                    $(this).removeClass('row_field_3');
                    $(this).removeClass('row_field_2');
                    $(this).removeClass('row_field_1');
                    $(this).addClass('row_field_2');
                }
            } else {
                if ((parseInt(current_val) - 1) > field_h1_val) {
                    $(this).removeClass('row_field_3');
                    $(this).removeClass('row_field_2');
                    $(this).removeClass('row_field_1');
                    $(this).addClass('row_field_1');
                }
                if (0 < (parseInt(current_val) - 1) <= field_h1_val) {
                    $(this).removeClass('row_field_3');
                    $(this).removeClass('row_field_2');
                    $(this).removeClass('row_field_1');
                    $(this).addClass('row_field_2');
                }
                if ((parseInt(current_val) - 1) == 0) {
                    $(this).removeClass('row_field_3');
                    $(this).removeClass('row_field_2');
                    $(this).removeClass('row_field_1');
                    $(this).addClass('row_field_3');
                }
                field_h2.text(parseInt(current_val) - 1);
            }
        }
    });
}
