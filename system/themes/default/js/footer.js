$(document).ready(function() {
    var Cookie = $.cookie('appointment');
    $(document).on('click', 'div#i_hide', function() {
        var obj = $(this);
        var sibling = obj.siblings('#float_content2');
        sibling.hide();
        obj.after('<div id="i_activate" style="float:left;">Lịch hẹn sắp tới</div>');
        obj.remove();
        $.cookie('appointment', 1);
    });
    $(document).on('click', 'td.e_appointment', function() {
        var obj = $(this);
        var parent = obj.parents('div#float_content2');
        var sibling = parent.siblings('#i_hide');
        parent.hide();
        sibling.after('<div id="i_activate" style="float:left;">Lịch hẹn sắp tới</div>');
        sibling.remove();
        $.cookie('appointment', 1);
    });
    $(document).on('click', 'div#i_activate', function() {
        var obj = $(this);
        var sibling = obj.siblings('#float_content2');
        sibling.show();
        obj.after('<div id="i_hide" style="float:left;">Lịch hẹn sắp tới</div>');
        obj.remove();
        $.cookie('appointment', 2);
    });
    if(Cookie == 1){
        $('div#float_content2').hide();
        $('div#i_hide').after('<div id="i_activate" style="float:left;">Lịch hẹn sắp tới</div>');
        $('div#i_hide').remove();
    }
});