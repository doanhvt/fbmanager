$(document).ready(function() {
    
    /**
     * Khi an them mau quang cao
     * Them 1 row
     */
    $(document).on('click', '.e_add_mau_qc', function() {
        var obj = $(this);
        var field_action = $(this).parents('.field_action');
        var a = $("<tr class='e_row'>");
        var addString = "<td><input class='e_code' type='text' required='required' class='e_change_value' name='code_mau_qc[]' /></td>";
        addString += "<td><input type='text' required='required' class=e_change_value' name='name_mau_qc[]' /></td>";
        addString += "<td><input type='text' required='required' class=e_change_value' name='keyword_mau_qc[]' /></td>";
        addString += "<td><input type='text' class=e_change_value' name='description_mau_qc[]' /></td>";
        addString += '<td><a class="delete icon16 i-remove e_del_row" per="1" href="#" title="Xóa"></a></td>';
        a.html(addString);
        field_action.before(a);
    });
    
    $(document).on('click', '.e_del_row', function() {
        $(this).parents('.e_row').remove();
        $id = $(this).attr('id');
        if ($id) {
            var current_value = $('#e_list_id_del').val();
            if (current_value) {
                current_value = current_value + ';' + $id;
            } else {
                current_value = $id;
            }
            $('#e_list_id_del').val(current_value);
        }

    });

    $(document).on('change', '.e_change_value', function() {
        var url = '';
        var landingpage;
        var e_row = $(this).parents('.e_row');
        var id_campaign_landingpage = e_row.find('input.e_id_campaign_landingpage');
        var id_campaign = $('.e_add_landingpage').attr('id_campaign');
        var list_select = e_row.find('select.e_change_value');
        list_select.each(function() {
            url += $(this).find(":selected").attr('url');
            if ($(this).hasClass('e_landingpage')) {
                landingpage = $(this).val();
            }
        });
        if (id_campaign_landingpage.val() == undefined) {
            url += '&id_landingpage=' + landingpage + '&id_campaign=' + id_campaign;
        } else {
            url += '&id_landingpage=' + landingpage + '&id_campaign=' + id_campaign + '&id=' + id_campaign_landingpage.val();
        }

        e_row.find('.e_url').val(url);
        e_row.find('.e_link_url').text(url);
    });

    $(document).on('click','.e_link_url',function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        window.open(url+'&preview=preview_mode', '_blank');
    });
});