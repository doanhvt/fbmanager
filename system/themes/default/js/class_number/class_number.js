$(document).ready(function() {
    $(document).on("submit");
    $(document).on("click", "input.free", load_radio);
    $(document).on("click", "input.topica", val_topica);
    $(document).on("click", "input.sub_form_teacher", function() {
        if (!confirm('Bạn có chắc chắn lịch dậy như vậy không'))
            return false;
        var obj = $(this);
        var sib = obj.siblings('input[name="code_rand"]');
        var id = obj.siblings('input[name="teacherId"]');
        var rand = sib.val();
        var id = id.val();
        var code_rand = {
            rand: rand,
            id: id,
        };
        var url = obj.attr("ajax-submit");
        $.ajax({
            url: url,
            type: "post",
            dataType: "text",
            data: code_rand,
            async: false,
            success: function(data) {
                if (data != 1) {
                    alert('Bạn đã đăng ký hoặc hết thời gian đăng ký');
                }
            },
            error: function(a, b, c) {
                alert(a + b + c);
            }
        });
    });
});

function load_radio() {
    var obj = $(this);
    var val = (obj.val()).substring(0, 1);
    var week = (obj.val()).substring(1, (obj.val()).length);
    var parent = obj.parents('div.checker');
    var parent_left = obj.parents('div#wrapper');
    var sum_hour = parent_left.find('input#sum_hour');
    var count = sum_hour.val();
    var sib = parent.siblings();
    var radio_1 = sib.find('input.topica');
    var val_topica_week = (radio_1.val()).substring(1, (radio_1.val()).length);
    var div_radio_1 = radio_1.parent();
    div_radio_1.attr("style", "background: url(themes/default/plugins/forms/uniform/images/free_1.png) 0px 0px;");
    if (val == 0) {
        count++;
        obj.attr("value", "1" + week);
        sib.attr("style", 'width: 22px; margin-top: -32px; padding-left: 35px; display: block;');
        radio_1.attr("title", "Not Topica");
        sum_hour.val(count);
    } else {
        count = count - 1;
        obj.attr("value", "0" + week);
        parent.removeAttr("style");
        sib.hide();
        radio_1.attr("value", "0" + val_topica_week);
        sum_hour.val(count);
    }
}
function val_topica() {
    var obj = $(this);
    var val = (obj.val()).substring(0, 1);
    var week = (obj.val()).substring(1, (obj.val()).length);
    if (val == 1) {
        obj.attr("value", "0" + week);
        obj.attr("title", "Not Topica");
    } else {
        obj.attr("value", "1" + week);
        obj.attr("title", "Topica");
    }
}
