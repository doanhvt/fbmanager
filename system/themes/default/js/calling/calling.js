$(document).ready(function() {
    //    $(document).on("click", ".btn_calling", set_day_calling);
    $(document).on("click", "#i_btn_calling", make_call);
    $(document).on("click", "#i_btn_endcall", end_call);
    $(document).on("change", "#i_change_status_calling", change_status_calling);
    $(document).on("click", "#i_save_call", function(){
        $("#i_btn_endcall").click();
        $("#i_save_call_2").click();
    });
    $(document).on("click", "#btn_call_filter_submit", function(e){
        e.preventDefault();
        var obj = $(".table_call_history");
        show_ajax_loading(obj)
        $("#call_filter_form").ajaxSubmit({
            dataType: "text",
            success: function(dataAll){
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
});

function make_call(){
    var contactPhone = $("#contactPhone").val();
    var campaignId = $("#campaignId").val();
    var contactId = $("#contactId").val();
    var employeeId = $("#employeeId").val();
    var obj = $(this);
    var call = obj.hasClass("btn-primary") || obj.hasClass("btn-danger");
    if(call){
        obj.removeClass("btn-primary");
        obj.removeClass("btn-danger");
        obj.addClass("btn-warning");
        obj.html("Đang gọi...");
    }
    var url = obj.attr("call_url");
    $.ajax({
        url: url,
        type: "POST",
        data: {
            'contactId':contactId,
            'employeeId':employeeId,
            'contactPhone':contactPhone,
            'campaignId':campaignId
        },
        dataType: "json",
        success: function(dataAll) {
            if(dataAll.call_id){
                $("#i_call_id").val(dataAll.call_id);
                obj.removeClass("btn-warning");
                obj.addClass("btn-success disabled");
                obj.html("Đã kết nối");
            //                $("#i_btn_endcall").css("display", "block");
            }else{
                obj.removeClass("btn-warning");
                obj.addClass("btn-danger");
                obj.html("Thử lại");
            }
        },
        error:function(){
            obj.removeClass("btn-warning");
            obj.addClass("btn-danger");
            obj.html("Thử lại");
        }
    });
}

function end_call(){
    var student_id = $("#StudentId").val();
    var advisor_id = $("#advisorId").val();
    var call_id = $("#i_call_id").val();
    var obj = $(this);
    var url = obj.attr("end_call_url");
    $.ajax({
        url: url,
        type: "POST",
        async: false,
        data: {
            'student_id':student_id,
            'advisor_id':advisor_id,
            'call_id':call_id
        },
        dataType: "json",
        success: function(dataAll) {
            $("#i_audio_link").val(dataAll.AudioFile);
            $("#i_btn_calling").hide();
            $("#i_btn_endcall").hide();
        }
    });
}

function set_day_calling() {
    var obj = $(this);
    var call = obj.hasClass("btn-success");
    if(call){
        obj.removeClass("btn-success");
        obj.addClass("btn-danger");
        obj.html("Đang gọi...");
    }
    var day_calling = $("#i_callDate");
    var d = new Date();
    var curr_date = d.getDate();
    var curr_month = d.getMonth() + 1; //Months are zero based
    var curr_year = d.getFullYear();
    day_calling.val(curr_year + "-" + curr_month + "-" + curr_date);
}

function change_status_calling(e, source_obj) {
    e.preventDefault();
    var obj;
    if (source_obj) {
        obj = source_obj;
    } else {
        obj = $(this);
    }
    var url = obj.attr("href");
    var data = obj.attr("data");
    if (data) {
        data = JSON.parse(obj.attr("data"));
    }
    if (obj.hasClass("e_ajax_confirm")) {
        $("<div title='Bạn chắc chắn?'>").html("Đổi trạng thái hoàn thành ?").dialog({
            resizable: false,
            height: 180,
            modal: true,
            buttons: {
                "Chắc chắn": function() {
                    //                    call_ajax_link(url, data, obj);
                    $.jGrowl("<i class='icon16 i-checkmark-3'></i> " + "Sửa thành công", {
                        group: "success",
                        position: 'top-right',
                        sticky: false,
                        closeTemplate: '<i class="icon16 i-close-2"></i>',
                        animateOpen: {
                            width: 'show',
                            height: 'show'
                        }
                    });
                    $(this).dialog("destroy");
                },
                "Thôi": function() {
                    window.location = window.location.href;
                    $(this).dialog("destroy");
                }
            },
            close: function() {
                $(this).dialog("destroy");
            }
        });
    } else {
        call_ajax_link(url, data, obj);
    }
}




