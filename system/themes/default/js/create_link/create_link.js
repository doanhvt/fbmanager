$(document).ready(function() {
    $(document).on('click', '#create_link', function() {
        var obj = $(this);
        var tr = obj.parent();
        var text = obj.siblings('p.link');
        var teacherId = tr.find('select[name="list_teacher"]').val();
        var week = tr.find('select[name="week"]').val();
        var rand = Math.floor((Math.random() * 10000));
        var val_rand = {
            codeRegister: rand,
            week: week,
            teacherId: teacherId,
        };
        var url = obj.attr("ajax-submit");
        $.ajax({
            url: url,
            type: "post",
            dataType: "text",
            data: val_rand,
            async: false,
            success: function(data) {
                text.before(data);
                text.remove();
            },
            error: function(a, b, c) {
                alert(a + b + c);
            }
        });
    });
});