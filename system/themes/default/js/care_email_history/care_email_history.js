$(document).ready(function() {
    $(document).on("click", "#i_templateId", load_email);
});

function load_email() {
    var obj = $(this);
    var val_temp = {
        templateId: obj.val(),
    };
    var url = obj.attr("ajax-submit");
    $.ajax({
        url: url,
        type: "post",
        dataType: "text",
        data: val_temp,
        async: false,
        success: function(dataAll) {
            CKEDITOR.instances.editor1.setData(dataAll);
        },
        error: function(a, b, c) {
            alert(a + b + c);
        }
    });
//    alert(obj.val());
}