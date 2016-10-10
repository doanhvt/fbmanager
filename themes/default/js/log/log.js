$(document).ready(function() {
    $(document).on("click", "#btn_log_filter_submit", function(e){
        e.preventDefault();
        var obj = $(".table_log");
        show_ajax_loading(obj)
        $("#contact_filter_form").ajaxSubmit({
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