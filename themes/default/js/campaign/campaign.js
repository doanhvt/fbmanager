$(document).ready(function () {
    $(document).on('click', '.e_add_landingpage', function () {
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
            success: function (data) {
                var a = $("<tr class='e_row'>");
                var addString = "<td><select class='select2 e_change_value e_landingpage id_landingpage e_change' name='id_landingpage[]'>";
                $.each(data.list_landingpage, function (i) {
                    addString += "<option url='" + data.list_landingpage[i]['url'] + "/' value='" + data.list_landingpage[i]['id'] + "'>" + data.list_landingpage[i]['name'] + "</option>";
                });
                addString += "</select></td>";
                addString += "<td><select url_mau_qc='" + $("#e_url_mau_qc").val() + "?parent_id=" + "' class='select2 e_change_value e_code_chanel'>";
                $.each(data.list_chanel_parent, function (i) {
                    addString += "<option id='" + data.list_chanel_parent[i]['id'] + "' value='" + data.list_chanel_parent[i]['code'] + "'>" + data.list_chanel_parent[i]['name'] + "</option>";
                });
                addString += "</select></td>";
                //===================================
                addString += "<td class='e_code_mau_qc'><div class='controls-row'><select required='required' class='select2 e_change_value e_change e_chanel_ads' name='code_chanel[]'>";
                $.each(data.list_chanel_ads, function (i) {
                    if (data.list_chanel_parent[0]['id'] == data.list_chanel_ads[i]['parent_id']) {
                        addString += "<option keyword='" + data.list_chanel_ads[i]['keyword'] + "' url='?code_chanel=" + data.list_chanel_ads[i]['code'] + "' value='" + data.list_chanel_ads[i]['code'] + "'>" + data.list_chanel_ads[i]['name'] + "</option>";
                    }
                });
                addString += "</select><label class='error'></label></div></td>";
                //===================================
                if ((data.list_chanel_ads.length)) {
                    $.each(data.list_chanel_ads, function (i) {
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

                addString += '<td>\n\
                <a class="delete icon16 i-remove e_del_row" per="1" href="#" title="Xóa"></a>\n\
                <a class="icon16 i-checkmark-3 e_save_row" per="1" href="#" title="Lưu"></a>\n\
                </td>';
                a.html(addString);
                field_action.before(a);
                a.find(".select2").select2();
            },
            error: function (a, b, c) {
                alert(a + b + c);
            },
            complete: function () {
                obj.addClass("e_add_landingpage");
                obj.text(content);
            }
        });
    });

    $(document).on('click', '.e_chanel_plus_bak', function () {
        var obj = $(this);
        var info_campaign = $(this).parents('.info_campaign');
        var e_data_campaign = obj.parents('.e_data_campaign');
        var id = obj.attr('id');
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
            success: function (data) {
                if (data.list_campaign_landingpage) {

                    $.each(data.list_campaign_landingpage, function (i) {

                        if (data.list_campaign_landingpage[i]['info_campain_landingpage']) {
                            var status = 'disabled';
                            var icon = 'i-lock-5';
                            var bgr = 'display:block';
                            if (parseInt(data.list_campaign_landingpage[i]['info_campain_landingpage']['status'])) {
                                status = '';
                                icon = 'i-unlocked-2';
                                bgr = '';
                            }
                            var a = $("<tr class='e_row' id='" + data.list_campaign_landingpage[i]['parent_id'] + "'>");
                            //===================================
                            var addString = "<td>";
                            addString += "<select " + status + " class='select2 e_change_value e_landingpage edit_id_landingpage e_change e_input' name='edit_id_landingpage[]'>";
                            $.each(data.list_landingpage, function (x) {
                                if (data.list_campaign_landingpage[i]['info_campain_landingpage']['id_landingpage'] == data.list_landingpage[x]['id']) {
                                    addString += "<option  selected url='" + data.list_landingpage[x]['url'] + "/' value='" + data.list_landingpage[x]['id'] + "'>" + data.list_landingpage[x]['name'] + "</option>";
                                } else {
                                    addString += "<option url='" + data.list_landingpage[x]['url'] + "/' value='" + data.list_landingpage[x]['id'] + "'>" + data.list_landingpage[x]['name'] + "</option>";
                                }

                            });
                            addString += "</select>";
                            addString += "</td>";

                            addString += "<td colspan='2' class='e_code_mau_qc'>";
                            addString += "<input " + status + "  url='?code_chanel=" + data.list_campaign_landingpage[i]['info_campain_landingpage']['code_chanel'] + "' class='e_change_value e_change e_chanel_ads e_input' style='margin-bottom: 0px;min-width: 100px;' readonly='' type='text' value='" + data.list_campaign_landingpage[i]['info_campain_landingpage']['chanel_name'] + "'/>";
                            addString += "<input " + status + " name='edit_code_chanel[]' type='hidden'  class='e_input edit_code_chanel' type='hidden' value='" + data.list_campaign_landingpage[i]['info_campain_landingpage']['code_chanel'] + "'/>";
                            addString += "</td>";
                            //===================================
                            addString += "<td>";
                            addString += "<input class='e_keyword' style='margin-bottom: 0px;min-width: 100px;' name='' readonly='' type='text' value='" + data.list_campaign_landingpage[i]['info_campain_landingpage']['keyword'] + "'/>";
                            addString += "</td>";
                            //===================================
                            addString += "<td width='300px'>";
                            addString += "<a style='font-size: 12px;padding-left: 5px;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;width: 380px;' class='e_link_url' href='" + data.list_campaign_landingpage[i]['info_campain_landingpage']['url_landingpage'] + "' target='_blank'>" + data.list_campaign_landingpage[i]['info_campain_landingpage']['url_landingpage'] + "</a>";
                            addString += '<input ' + status + ' type="hidden" class="e_input e_id_campaign_landingpage" name="edit_id[]" value="' + data.list_campaign_landingpage[i]['info_campain_landingpage']['id'] + '"/>';
                            addString += '<input ' + status + ' type="hidden" id="e_status" class="e_input e_status_check" name="edit_status[]" value="' + data.list_campaign_landingpage[i]['info_campain_landingpage']['status'] + '"/>';
                            addString += '<input ' + status + ' type="hidden" class="e_input e_url" name="edit_url_landingpage[]" value="' + data.list_campaign_landingpage[i]['info_campain_landingpage']['url_landingpage'] + '"/>';
                            addString += "</td>";
                            //===================================
                            addString += '<td class="field_action">';
                            addString += '<span class="delete icon16 ' + icon + ' e_del_row" id="' + data.list_campaign_landingpage[i]['info_campain_landingpage']['id'] + '" per="1" title="Xóa"></span>';
                            addString += '<span class="icon16 i-checkmark-3 e_save_row"  style="cursor: pointer" title="Lưu"></span>';
                            addString += '<div class="e_lock_flield"><div style="' + bgr + '" class="e_lock"></div></div>';
                            addString += '</td>';
                            //===================================
                            a.html(addString);
                            info_campaign.siblings('.e_title').each(function () {
                                if ($(this).attr('id') == id) {
                                    $(this).show();
                                    $(this).after(a);
                                }
                            });


                            a.find(".select2").select2();
                        } else {
                            var status = 'disabled';
                            var icon = 'i-lock-5';
                            var bgr = 'display:block';
                            var a = $("<tr class='e_row' id='" + data.list_campaign_landingpage[i]['parent_id'] + "'>");
                            //===================================
                            var addString = "<td>";
                            addString += "<select " + status + " class='select2 e_change_value e_landingpage id_landingpage e_change e_input' name='id_landingpage[]'>";
                            $.each(data.list_landingpage, function (x) {
                                addString += "<option url='" + data.list_landingpage[x]['url'] + "/' value='" + data.list_landingpage[x]['id'] + "'>" + data.list_landingpage[x]['name'] + "</option>";
                            });
                            addString += "</select>";
                            addString += "</td>";

                            addString += "<td colspan='2' class='e_code_mau_qc'>";
                            addString += "<input " + status + " url='?code_chanel=" + data.list_campaign_landingpage[i]['code'] + "' class='e_change_value e_change e_chanel_ads e_input' style='margin-bottom: 0px;min-width: 100px;' readonly='' type='text' value='" + data.list_campaign_landingpage[i]['name'] + "'/>";
                            addString += "<input " + status + " name='code_chanel[]' type='hidden'  class='e_input code_chanel' value='" + data.list_campaign_landingpage[i]['code'] + "'/>";
                            addString += "</td>";
                            //===================================
                            addString += "<td>";
                            addString += "<input " + status + " class='e_keyword' style='margin-bottom: 0px;min-width: 100px;' name='' readonly='' type='text' value='" + data.list_campaign_landingpage[i]['keyword'] + "'/>";
                            addString += "</td>";
                            //===================================
                            addString += "<td width='300px'>";
                            addString += '<input ' + status + ' class="e_url e_input" name="url_landingpage[]" readonly="" type="text" value="" />';
                            addString += "</td>";
                            //===================================
                            addString += '<td class="field_action">';
                            addString += '<span class="delete icon16 ' + icon + ' e_del_row"  per="1" title="Xóa"></span>';
                            addString += '<span class="icon16 i-checkmark-3 e_save_row" style="cursor: pointer"  title="Lưu"></span>';
                            addString += '<div class="e_lock_flield"><div style="' + bgr + '" class="e_lock"></div></div>';
                            addString += '</td>';
                            //===================================
                            a.html(addString);
                            info_campaign.siblings('.e_title').each(function () {
                                if ($(this).attr('id') == id) {
                                    $(this).show();
                                    $(this).after(a);
                                }
                            });


                            a.find(".select2").select2();
                        }

                    });
                }
            },
            error: function (a, b, c) {
                alert(a + b + c);
            },
            complete: function () {
                e_data_campaign.find('.e_row').each(function () {
                    if ($(this).attr('id') == id) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                e_data_campaign.find('.e_title').each(function () {
                    if ($(this).attr('id') == id) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                e_data_campaign.find('.e_chanel_hide').each(function () {
                    $(this).removeClass('e_chanel_hide');
                    $(this).addClass('e_chanel_show');
                    $(this).find('span').text('+ ');
                    $(this).attr('style', '');

                });

                obj.addClass("e_chanel_hide");
                obj.find('span').text('- ');
                obj.css('font-weight', 'bold');
            }
        });
    });

    $(document).on('click', '.e_chanel_plus', function () {
        var obj = $(this);
        var info_campaign = $(this).parents('.info_campaign');
        var e_data_campaign = obj.parents('.e_data_campaign');
        var id = obj.attr('id');
        obj.removeClass("e_chanel_plus");
        var url = obj.attr("data-url");
        //Xóa các kết quả cũ
        info_campaign.parent().find('.e_data_mauqc').each(function () {
            $(this).remove();
        });

        $.ajax({
            url: url,
            type: "post",
            async: false,
            dataType: "html",
            data: {
                'ajax': 1
            },
            success: function (data) {
                info_campaign.after(data);
                //$(".select2").select2();
            },
            error: function (a, b, c) {
                alert(a + b + c);
            },
            complete: function () {
                e_data_campaign.find('.e_chanel_hide').each(function () {
                    $(this).removeClass('e_chanel_hide');
                    $(this).addClass('e_chanel_plus');
                    $(this).find('span').text('+ ');
                    $(this).attr('style', '');
                });

                obj.addClass("e_chanel_hide");
                obj.find('span').text('- ');
                obj.css('font-weight', 'bold');
            }

        });
    });

    $(document).on('click', '.e_chanel_hide', function () {
        var obj = $(this);
        obj.removeClass('e_chanel_hide');
        obj.addClass('e_chanel_plus');
        obj.find('span').text('+ ');
        obj.attr('style', '');
        var id = obj.attr('id');
        var e_data_campaign = obj.parents('.e_data_campaign');
        e_data_campaign.find('.e_row').each(function () {
            if ($(this).attr('id') == id) {
                $(this).remove();
            }
        });
        e_data_campaign.find('.e_title').each(function () {
            if ($(this).attr('id') == id) {
                $(this).remove();
            }
        });

    });

    $(document).on('click', '.e_save_row_bak', function () {
        var url = $('.e_ajax_submit').attr('action');

        var e_row = $(this).parents('.e_row');
        var id_landingpage = e_row.find('select.id_landingpage').val();
        var edit_id_landingpage = e_row.find('select.edit_id_landingpage').val();
        var edit_code_chanel = e_row.find('input.edit_code_chanel').attr('value');
        var edit_id = e_row.find('input.e_id_campaign_landingpage').val();
        var id_campaign = $('input[name=id_campaign]').val();
        var url_landingpage = e_row.find('input.e_url').val();
        var code_chanel = e_row.find('input.code_chanel').attr('value');
        var edit_status = e_row.find('input.e_status_check').val();
        var data_post = {};
        if (id_landingpage != undefined) {
            data_post.id_landingpage = [id_landingpage];
        }
        if (edit_id_landingpage != undefined) {
            data_post.edit_id_landingpage = [edit_id_landingpage];
        }
        if (edit_code_chanel != undefined) {
            data_post.edit_code_chanel = [edit_code_chanel];
        }
        if (edit_id != undefined) {
            data_post.edit_id = [edit_id];
        }
        if (id_campaign != undefined) {
            data_post.id_campaign = id_campaign;
        }
        if (url_landingpage != undefined) {
            data_post.url_landingpage = [url_landingpage];
        }
        if (code_chanel != undefined) {
            data_post.code_chanel = [code_chanel];
        }
        if (edit_status != undefined) {
            data_post.edit_status = [edit_status];
        }


        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: data_post,
            success: function (data) {
                $.jGrowl("<i class='icon16 i-checkmark-3'></i> " + data.msg, {
                    group: "success",
                    position: 'top-right',
                    sticky: false,
                    closeTemplate: '<i class="icon16 i-close-2"></i>',
                    animateOpen: {
                        width: 'show',
                        height: 'show'
                    }
                });
            }
        });
    });

    $(document).on('click', '.e_save_row', function () {
        var url = $('.e_ajax_submit').attr('action');
        var obj = $(this);
        var e_row = obj.parents(".e_row");
        var id = e_row.find("input.e_id_campaign_landingpage").val();
        var url_landingpage = e_row.find("p.e_data_url").attr("url");
        var id_landingpage = url_landingpage.split('=').pop();
        var code_chanel = e_row.find("p.e_data_chanel").attr("code_chanel");
        var id_campaign = e_row.find("p.e_data_chanel").attr("id_campaign");
        var status = e_row.find("input.e_status_check").val();
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: {
                'id' : id,
                'url_landingpage' : url_landingpage,
                'id_landingpage' : id_landingpage,
                'code_chanel' : code_chanel,
                'id_campaign' : id_campaign,
                'status' : status
            },
            success: function (data) {
                if (data.state == 1) {
                    var group = "success";
                } else {
                    var group = "error";
                }
                $.jGrowl("<i class='icon16 i-checkmark-3'></i> " + data.msg, {
                    group: group,
                    position: 'top-right',
                    sticky: false,
                    closeTemplate: '<i class="icon16 i-close-2"></i>',
                    animateOpen: {
                        width: 'show',
                        height: 'show'
                    }
                });
                if (data.id != undefined) {
                    var link = e_row.find("a.e_link_url");
                    var href = link.attr("href");
                    link.attr("href", href + data.id);
                    e_row.find("input.e_url").val(href + data.id);
                    e_row.find("input.e_id_campaign_landingpage").val(data.id);
                }
            }
        });
    });

    $(document).on('click', '.e_chanel_show', function () {
        var obj = $(this);

        var id = obj.attr('id');
        var e_data_campaign = obj.parents('.e_data_campaign');
        e_data_campaign.find('.e_row').each(function () {
            if ($(this).attr('id') == id) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        e_data_campaign.find('.e_title').each(function () {
            if ($(this).attr('id') == id) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        e_data_campaign.find('.e_chanel_hide').each(function () {
            if ($(this).attr('id') != id) {
                $(this).removeClass('e_chanel_hide');
                $(this).addClass('e_chanel_show');
                $(this).find('span').text('+ ');
                $(this).attr('style', '');
            }
        });
        obj.removeClass('e_chanel_show');
        obj.addClass('e_chanel_hide');
        obj.find('span').text('- ');
        obj.css('font-weight', 'bold');

    });

    $(document).on('click', '.e_del_row_bak', function () {
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
                e_input.each(function () {
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
                $(this).parents('.e_row').find('.e_input').each(function () {
                    $(this).removeAttr('disabled');
                });
                $(this).attr('title', 'Mở');
            }

        } else {
            if ($(this).hasClass("i-unlocked-2")) {
                $(this).removeClass("i-unlocked-2");
                $(this).addClass("i-lock-5");
                var e_row = $(this).parents('.e_row');
                e_row.find('.e_lock').show();
                var e_input = e_row.find('.e_input');
                e_input.each(function () {
                    $(this).attr('disabled', '');
                });
                $(this).attr('title', 'Đóng');

                e_row.find('.e_url').val('');
                e_row.find('.e_link_url').html('block');
                e_row.find('.e_link_url').attr('href', '');

            } else {
                $(this).removeClass("i-lock-5");
                $(this).addClass("i-unlocked-2");
                var e_row = $(this).parents('.e_row');
                e_row.find('.e_lock').hide();
                e_row.find('#e_status').val(1);
                e_row.find('.e_input').each(function () {
                    $(this).removeAttr('disabled');
                });
                $(this).attr('title', 'Mở');

                var url_string = '';
                var landingpage;
                var id_campaign = $('input[name="id_campaign"]').val();
                var e_landingpage = e_row.find('select.e_landingpage');
                landingpage = e_landingpage.val();
                url_string += e_landingpage.find(":selected").attr('url');
                url_string += e_row.find('input.e_chanel_ads').attr('url');
                url_string += '&id_landingpage=' + landingpage + '&id_campaign=' + id_campaign;

                e_row.find('.e_url').val(url_string);
                e_row.find('.e_link_url').html(url_string);
                e_row.find('.e_link_url').attr('href', url_string);
            }

        }

    });

    $(document).on('click', '.e_del_row', function () {
        if ($(this).hasClass("i-remove")) {
            $(this).parents('.e_row').remove();
            return;
        }
        if ($(this).hasClass("i-unlocked-2")) {
            $(this).removeClass("i-unlocked-2");
            $(this).addClass("i-lock-5");
            var e_row = $(this).parents('.e_row');
            e_row.find('.e_lock').show();
            var e_input = e_row.find('.e_input');
            e_input.each(function () {
                $(this).attr('disabled', '');
            });
            $(this).attr('title', 'Đóng');
            e_row.find("span.e_select_page").css("display", "none");
            //e_row.find('.e_url').val('');
            e_row.find('.e_link_url').html('');
            e_row.find('.e_link_url').attr('href', '');
            e_row.find('.e_status_check').val(0);
        } else {
            $(this).removeClass("i-lock-5");
            $(this).addClass("i-unlocked-2");
            var e_row = $(this).parents('.e_row');
            e_row.find('.e_lock').hide();
            e_row.find('.e_status_check').val(1);
            $(this).attr('title', 'Mở');
            e_row.find("span.e_select_page").css("display", "block");
            change_url(e_row);
        }
    });

    $(document).on('click', '.e_act_row', function () {
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

    $(document).on('change', '.e_change_value', function () {
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
                success: function (data) {
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
        var id_campaign = $('input[name="id_campaign"]').val();
        var e_landingpage = e_row.find('select.e_landingpage');
        landingpage = e_landingpage.val();
        url += e_landingpage.find(":selected").attr('url');
        url += e_row.find('input.e_chanel_ads').attr('url');
        if (id_campaign_landingpage.val() == undefined) {
            url += '&id_landingpage=' + landingpage + '&id_campaign=' + id_campaign;
        } else {
            url += '&id_landingpage=' + landingpage + '&id_campaign=' + id_campaign + '&id=' + id_campaign_landingpage.val();
        }

        e_row.find('.e_url').val(url);
        e_row.find('.e_link_url').html(url);
        e_row.find('.e_link_url').attr('href', url);
    });

    $(document).on('click', '.e_link_url', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
//        window.open(url + '&preview=preview_mode', '_blank');
        window.open(url, '_blank');
    });

    $(document).on('click', '.modal-content', function () {
        var list_error = $(this).find('label.error');
        list_error.each(function () {
            $(this).hide();
        });
    });

    $(document).on("click", ".e_data_url", function () {
        var obj = $(this);
        get_data_select(obj);
    });

    $(document).on("change", "select.select2", function () {
        var url = $(this).val();
        var e_row = $(this).parents('.e_row');
        var tag_p = e_row.find("p.e_data_url");
        tag_p.attr("url", url);
        change_url(e_row);
    });
});

function get_data_select(obj) {
    var id = obj.attr("id-select");
    $.ajax({
        url: obj.attr("url_action"),
        type: "POST",
        data: {
            "id_landingpage": obj.attr('id_landingpage'),
            "id-select": obj.attr('id-select')
        },
        success: function (result) {
            obj.after(result);
            $("#" + id).select2();
            $("#" + id).select2('open');
        }
    });
}

function change_url(obj) {
    var url_string = '';
    var data_url = obj.find("p.e_data_url");
    var data_chanel = obj.find("p.e_data_chanel");
    url_string += data_url.attr("url");
    url_string += "&code_chanel=" + data_chanel.attr('code_chanel') + "&id_campaign=" + data_chanel.attr("id_campaign") + "&id=" + data_chanel.attr("id_gd");

    obj.find('.e_link_url').html(url_string);
    obj.find('.e_link_url').attr('href', url_string);
}