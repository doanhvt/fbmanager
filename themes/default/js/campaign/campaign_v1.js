$(document).ready(function() {
    $(document).on('click', '.e_add_landingpage', function() {
        var obj = $(this);
        var field_action = $(this).parents('.field_action');
        var content = obj.text();
        obj.text('Loading... ');
        obj.removeClass("e_add_landingpage");
        var id_campaign = obj.attr('id_campaign');
        var url = obj.attr("data-url");
        $.ajax({
            url: url,
            type: "post",
            async: false,
            dataType: "json",
            data: {
                'ajax': 1
            },
            success: function(data) {
                var a = $("<tr class='e_row'>");
                var addString = "<td><select class='select2 e_change_value e_landingpage e_change' name='id_landingpage[]'>";
                $.each(data.list_landingpage, function(i) {
                    addString += "<option url='" + data.list_landingpage[i]['url'] + "/' value='" + data.list_landingpage[i]['id'] + "'>" + data.list_landingpage[i]['name'] + "</option>";
                });
                addString += "</select></td>";
                addString += "<td><select url_mau_qc='" + $("#e_url_mau_qc").val() + "?parent_id=" + "' class='select2 e_change_value e_code_chanel'>";
                $.each(data.list_chanel_parent, function(i) {
                    addString += "<option id='" + data.list_chanel_parent[i]['id'] + "' value='" + data.list_chanel_parent[i]['code'] + "'>" + data.list_chanel_parent[i]['name'] + "</option>";
                });
                addString += "</select></td>";
                //===================================
                addString += "<td class='e_code_mau_qc'><div class='controls-row'><select required='required' class='select2 e_change_value e_change e_chanel_ads' name='code_chanel[]'>";
                $.each(data.list_chanel_ads, function(i) {
                    if (data.list_chanel_parent[0]['id'] == data.list_chanel_ads[i]['parent_id']) {
                        addString += "<option keyword='" + data.list_chanel_ads[i]['keyword'] + "' url='?code_chanel=" + data.list_chanel_ads[i]['code'] + "' value='" + data.list_chanel_ads[i]['code'] + "'>" + data.list_chanel_ads[i]['name'] + "</option>";
                    }
                });
                addString += "</select><label class='error'></label></div></td>";
                //===================================
                if ((data.list_chanel_ads.length)) {
                    $.each(data.list_chanel_ads, function(i) {
                        if (data.list_chanel_parent[0]['id'] == data.list_chanel_ads[i]['parent_id']) {
                            addString += "<td><input class='e_keyword' style='margin-bottom: 0px;min-width: 100px;' name='' readonly='' type='text' value='" + data.list_chanel_ads[i]['keyword'] + "'/></td>";
                            return false;
                        }
                    });
                } else {
                    addString += "<td><input class='e_keyword' style='margin-bottom: 0px;min-width: 100px;' name='' readonly='' type='text' value=''/></td>";
                }
                if (data.list_landingpage.length) {
                    addString += '<td><input class="e_url" name="url_landingpage[]" readonly="" type="text" value="' + data.list_landingpage[0]['url'] + '/?code_chanel=' + data.list_chanel_ads[0]['code'] + '&id_landingpage=' + data.list_landingpage[0]['id'] + '&id_campaign=' + id_campaign + '" /></td>';
                } else {
                    addString += '<td><input class="e_url" name="url_landingpage[]" readonly="" type="text" value="" /></td>';
                }

                addString += '<td><a class="delete icon16 i-remove e_del_row" per="1" href="#" title="Xóa"></a></td>';
                a.html(addString);
                field_action.before(a);
                a.find(".select2").select2();
            },
            error: function(a, b, c) {
                alert(a + b + c);
            },
            complete: function() {
                obj.addClass("e_add_landingpage");
                obj.text(content);
            }
        });
    });

    $(document).on('click', '.e_chanel_plus', function() {
        var obj = $(this);
        var info_campaign = $(this).parents('.info_campaign');
        obj.removeClass("e_chanel_plus");
        var url = obj.attr("data-url");
        $.ajax({
            url: url,
            type: "post",
            async: false,
            dataType: "json",
            data: {
                'ajax': 1
            },
            success: function(data) {
                if (data.list_campaign_landingpage) {
                    $.each(data.list_campaign_landingpage, function(i) {
                        var status = 'disabled';
                        var icon = 'i-lock-5';
                        var bgr = 'display:block';
                        if (parseInt(data.list_campaign_landingpage[i]['status'])) {
                            status = '';
                            icon = 'i-unlocked-2';
                            bgr = '';
                        }
                        var a = $("<tr class='e_row' id='" + data.list_campaign_landingpage[i]['parent_id'] + "'>");
                        //===================================
                        var addString = "<td>";
                        addString += "<select " + status + " class='select2 e_change_value e_landingpage e_change e_input' name='edit_id_landingpage[]'>";
                        $.each(data.list_landingpage, function(x) {
                            if (data.list_campaign_landingpage[i]['id_landingpage'] == data.list_landingpage[x]['id']) {
                                addString += "<option  selected url='" + data.list_landingpage[x]['url'] + "/' value='" + data.list_landingpage[x]['id'] + "'>" + data.list_landingpage[x]['name'] + "</option>";
                            } else {
                                addString += "<option url='" + data.list_landingpage[x]['url'] + "/' value='" + data.list_landingpage[x]['id'] + "'>" + data.list_landingpage[x]['name'] + "</option>";
                            }

                        });
                        addString += "</select>";
                        addString += "</td>";
                        //===================================
                        addString += "<td>";
                        addString += "<select " + status + " url_mau_qc='" + $("#e_url_mau_qc").val() + "?parent_id=" + "' class='select2 e_input e_change_value e_code_chanel'>";
                        $.each(data.list_chanel_parent, function(y) {
                            if (data.list_campaign_landingpage[i]['parent_id'] == data.list_chanel_parent[y]['id']) {
                                addString += "<option selected id='" + data.list_chanel_parent[y]['id'] + "' value='" + data.list_chanel_parent[y]['code'] + "'>" + data.list_chanel_parent[y]['name'] + "</option>";
                            } else {
                                addString += "<option id='" + data.list_chanel_parent[y]['id'] + "' value='" + data.list_chanel_parent[y]['code'] + "'>" + data.list_chanel_parent[y]['name'] + "</option>";
                            }
                        });
                        addString += "</select>";
                        addString += "</td>";
                        //===================================
                        addString += "<td class='e_code_mau_qc'>";
                        addString += "<div class='controls-row'>";
                        addString += "<select " + status + " class='select2 e_change_value e_change e_chanel_ads e_input' name='edit_code_chanel[]'>";
                        $.each(data.list_chanel_ads, function(n) {
                            if (data.list_campaign_landingpage[i]['parent_id'] == data.list_chanel_ads[n]['parent_id']) {
                                if (data.list_campaign_landingpage[i]['code_chanel'] == data.list_chanel_ads[n]['code']) {
                                    addString += "<option " + status + " selected keyword='" + data.list_chanel_ads[n]['keyword'] + "' url='?code_chanel=" + data.list_chanel_ads[n]['code'] + "' value='" + data.list_chanel_ads[n]['code'] + "'>" + data.list_chanel_ads[n]['name'] + "</option>";
                                } else {
                                    addString += "<option keyword='" + data.list_chanel_ads[n]['keyword'] + "' url='?code_chanel=" + data.list_chanel_ads[n]['code'] + "' value='" + data.list_chanel_ads[n]['code'] + "'>" + data.list_chanel_ads[n]['name'] + "</option>";
                                }
                            }
                        });
                        addString += "</select>";
                        addString += "<label class='error'></label>";
                        addString += "</div>";
                        addString += "</td>";
                        //===================================
                        addString += "<td>";
                        addString +=  "<input class='e_keyword' style='margin-bottom: 0px;min-width: 100px;' name='' readonly='' type='text' value='" + data.list_campaign_landingpage[i]['keyword'] + "'/>";
                        addString +=  "</td>";
                        //===================================
                        addString += "<td width='300px'>";
                        addString += "<a style='font-size: 12px;padding-left: 5px;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;width: 380px;' class='e_link_url' href='" + data.list_campaign_landingpage[i]['url_landingpage'] + "' target='_blank'>" + data.list_campaign_landingpage[i]['url_landingpage'] + "</a>";
                        addString += '<input '+ status +' type="hidden" class="e_input e_id_campaign_landingpage" name="edit_id[]" value="'+ data.list_campaign_landingpage[i]['id'] +'"/>';
                        addString += '<input '+ status +' type="hidden" id="e_status" class="e_input" name="edit_status[]" value="'+ data.list_campaign_landingpage[i]['status'] +'"/>';
                        addString += '<input '+ status +' type="hidden" class="e_input e_url" name="edit_url_landingpage[]" value="'+ data.list_campaign_landingpage[i]['url_landingpage'] +'"/>';
                        addString += "</td>";
                        //===================================
                        addString += '<td class="field_action">';
                        addString += '<span class="delete icon16 '+ icon +' e_del_row" id="'+ data.list_campaign_landingpage[i]['id'] +'" per="1" title="Xóa"></span>';
                        addString += '<div class="e_lock_flield"><div style="'+ bgr +'" class="e_lock"></div></div>';
                        addString += '</td>';
                        //===================================
                        a.html(addString);
                        info_campaign.after(a);
                        a.find(".select2").select2();
                    });
                }
            },
            error: function(a, b, c) {
                alert(a + b + c);
            },
            complete: function() {
                obj.addClass("e_chanel_hide");
                obj.find('span').text('- ');
            }
        });
    });

    $(document).on('click', '.e_chanel_hide', function() {
        var obj = $(this);
        obj.removeClass('e_chanel_hide');
        obj.addClass('e_chanel_show');
        obj.find('span').text('+ ');
        var id = obj.attr('id');
        var e_data_campaign = obj.parents('.e_data_campaign');
        e_data_campaign.find('.e_row').each(function() {
            if ($(this).attr('id') == id) {
                $(this).hide();
            }
        });

    });

    $(document).on('click', '.e_chanel_show', function() {
        var obj = $(this);
        obj.removeClass('e_chanel_show');
        obj.addClass('e_chanel_hide');
        obj.find('span').text('- ');
        var id = obj.attr('id');
        var e_data_campaign = obj.parents('.e_data_campaign');
        e_data_campaign.find('.e_row').each(function() {
            if ($(this).attr('id') == id) {
                $(this).show();
            }
        });

    });

    $(document).on('click', '.e_del_row', function() {
        if ($(this).hasClass("i-remove")) {
            $(this).parents('.e_row').remove();
            return;
        }
        var id = $(this).attr('id');
        if (id) {
            if ($(this).hasClass("i-unlocked-2")) {
                $(this).removeClass("i-unlocked-2");
                $(this).addClass("i-lock-5");
                var current_value = $('#e_list_id_del').val();
                if (current_value) {
                    current_value = current_value + ';' + id;
                } else {
                    current_value = id;
                }
                $('#e_list_id_del').val(current_value);
                $(this).parents('.e_row').find('.e_lock').show();
                $(this).parents('.e_row').find('#e_status').val(0);
                var e_input = $(this).parents('.e_row').find('.e_input');
                e_input.each(function() {
                    $(this).attr('disabled', '');
                });
                $(this).attr('title', 'Đóng');
            } else {
                $(this).removeClass("i-lock-5");
                $(this).addClass("i-unlocked-2");
                var current_value = $('#e_list_id_del').val();
                if (current_value) {
                    var arr_id = current_value.split(";");
                    var index = arr_id.indexOf(id);
                    if (index > -1) {
                        arr_id.splice(index, 1);
                    }
                    current_value = arr_id.join(";");
                } else {
                    current_value = "";
                }
                $('#e_list_id_del').val(current_value);
                $(this).parents('.e_row').find('.e_lock').hide();
                $(this).parents('.e_row').find('#e_status').val(1);
                $(this).parents('.e_row').find('.e_input').each(function() {
                    $(this).removeAttr('disabled');
                });
                $(this).attr('title', 'Mở');
            }

        }

    });

    $(document).on('click', '.e_act_row', function() {
        $id = $(this).attr('id');
        if ($id) {
            if ($(this).hasClass("i-lock-5")) {
                $(this).removeClass("i-lock-5");
                $(this).addClass("i-unlocked-2");
                var current_value = $('#e_list_id_act').val();
                if (current_value) {
                    current_value = current_value + ';' + $id;
                } else {
                    current_value = $id;
                }
                $('#e_list_id_act').val(current_value);
            } else {
                $(this).removeClass("i-unlocked-2");
                $(this).addClass("i-lock-5");
                var current_value = $('#e_list_id_act').val();
                if (current_value) {
                    var arr_id = current_value.split(";");
                    var index = arr_id.indexOf($id);
                    if (index > -1) {
                        arr_id.splice(index, 1);
                    }
                    current_value = arr_id.join(";");
                } else {
                    current_value = "";
                }
                $('#e_list_id_act').val(current_value);
            }

        }

    });

    $(document).on('change', '.e_change_value', function() {
        if ($(this).hasClass('e_code_chanel')) {
            var obj = $(this);
            var url_mau_qc = obj.attr("url_mau_qc") + obj.find("option:selected").attr("id");
            var this_parent = obj.parents(".e_row");
            var e_mau_qc = this_parent.find("td.e_code_mau_qc");
            var e_chanel_ads = this_parent.find("select.e_chanel_ads");
            $.ajax({
                url: url_mau_qc,
                type: "get",
                dataType: "text",
                success: function(data) {
                    e_chanel_ads.html(data);
                    e_mau_qc.find("select").select2();
                    e_chanel_ads.change();
                }
            });
        }
        if ($(this).hasClass('e_chanel_ads')) {
            var keyword = $(this).find("option:selected").attr("keyword");
            var this_parent = $(this).parents(".e_row");
            this_parent.find("input.e_keyword").val(keyword);
        }
        //======================================================================
        var url = '';
        var landingpage;
        var e_row = $(this).parents('.e_row');
        var id_campaign_landingpage = e_row.find('input.e_id_campaign_landingpage');
        var id_campaign = $('.e_add_landingpage').attr('id_campaign');
        var list_select = e_row.find('select.e_change');
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
        e_row.find('.e_link_url').html(url);
        e_row.find('.e_link_url').attr('href', url);
    });

    $(document).on('click', '.e_link_url', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        window.open(url + '&preview=preview_mode', '_blank');
    });

    $(document).on('click', '.modal-content', function() {
        var list_error = $(this).find('label.error');
        list_error.each(function() {
            $(this).hide();
        });
    });
});