$(document).ready(function() {

    cam_date_picker();

    $(document).on("click", ".sort", function() {
        var order = $(this).attr("field_name");
        $('#i_sort').val(order);
        var url = $(this).parent('tr').attr("sort_url");
        show_ajax_loading($('.widget-content'));
        $('.e_ajax_form').ajaxSubmit({
            url: url,
            type: "POST",
            dataType: "text",
            cache: false,
            success: function(data) {
                $(".e_widget_content").html(data);
                $(".select2").select2();
                cam_date_picker();
            }
        });
    });

    $(document).on("click", ".e_change_camp", function() {
        var campaign_id = $(this).val();
        var url = $(this).attr("data-url");
        $.ajax({
            url: url,
            type: "POST",
            data: {
                "id": campaign_id
            },
            dataType: "json",
            success: function(data) {
                var string = '';
                if (data.list_assign_chanel) {
                    string += '<option selected value="0">Tất cả</option>'
                    $.each(data.list_assign_chanel, function(i) {
                        string += '<option value="' + data.list_assign_chanel[i]['id'] + '">' + data.list_assign_chanel[i]['name'] + '</option>'
                    });
                }
                $('select.e_ass_chanel').html(string);
            }
        });
    });

    $(document).on('submit', '.e_ajax_form', function(e) {
        e.preventDefault();
        var form = $(this);
        show_ajax_loading($('.e_widget_content'));
        form.ajaxSubmit({
            type: "POST",
            dataType: "text",
            cache: false,
            success: function(dataAll) {
                $('.e_widget_content').html(dataAll);
                $('.select2').select2();
                cam_date_picker();
                $('#i_img_loading').remove();
            },
            error: function(a, b, c) {
            },
            complete: function(jqXHR, textStatus) {
            }
        });
    });
    $(document).on('click', '.e_chosse_day', function() {
        var obj = $(this);
        var date = obj.attr('date');
        var list_tr = $('.e_table_data').find('.e_tr_' + date);
        list_tr.each(function() {
            if (obj.hasClass('e_minimize')) {
                $('#i_'+date).find('.e_chosse_day').each(function (){
                   $(this).hide(); 
                });
                $(this).show();
                $('#i_'+date).attr('style','font-weight: bold');
            } else {
                $(this).hide();
                $('#i_'+date).attr('style','');
                $('#i_'+date).find('.e_chosse_day').each(function (){
                   $(this).show(); 
                });
            }
        });
    });

});
function cam_date_picker() {
    $(".time_begin").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $(".time_end").datepicker("option", "minDate", selectedDate);
        }
    });//cam giao dien kieu date time cho the thoi gian
    $(".time_end").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $(".time_begin").datepicker("option", "maxDate", selectedDate);
        }
    });//cam giao dien kieu date time cho the thoi gian
}
