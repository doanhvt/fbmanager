$(document).ready(function() {
    $(document).on("click", "#i_campaignId", check_campaign);
    $("#i_date").datetimepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        numberOfMonths: 1,
    });
});
function check_campaign() {
    var obj = $(this);
    var parent = obj.parents('.modal-body');
    var contact = parent.find('.e_contactId');
    contact.removeAttr('style');
    var val_cam = {
        camId: obj.val(),
    };
    var url = obj.attr("ajax-submit");
    $.ajax({
        url: url,
        type: "post",
        dataType: "text",
        data: val_cam,
        async: false,
        success: function(dataAll) {
            var a = $('#i_contactId');
            a.html(dataAll);
            a.select2('val', '0');
        },
        error: function(a, b, c) {
            alert(a + b + c);
        }
    });
}