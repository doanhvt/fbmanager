<script type="text/javascript">
    $("#i_from_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function (selectedDate) {
            $("#i_to_date").datepicker("option", "minDate", selectedDate);
        }
    });//cam giao dien kieu date time cho the thoi gian
    $("#i_to_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function (selectedDate) {
            $("#i_from_date").datepicker("option", "maxDate", selectedDate);
        }
    });//cam giao dien kieu date time cho the thoi gian


    //$(document).on("change", "#code_chanel", get_mauqc_by_chanel);
    $("#code_chanel").change(get_mauqc_by_chanel);
    function get_mauqc_by_chanel() {
        // Day la kenh cha, thuc hien lay cac con cua no
        var obj = $("#code_chanel");
        var select_mauqc = $("#code_mauqc");
        var url = obj.attr("get_mauqc_url");
        $.ajax({
            url: url,
            dataType: "text",
            async: false,
            data: {
                "parent_id": obj.val()
            },
            success: function (data) {
                select_mauqc.html(data);
                select_mauqc.select2("val", '');
            }
        });
    }

//    $(document).on("change", "#code_mauqc", get_chanel_by_mauqc);
    $("#code_mauqc").change(get_chanel_by_mauqc);
    function get_chanel_by_mauqc() {
        // Day la kenh con, thuc hien lay kenh cha cua no
        var obj = $("#code_mauqc");
        var select_chanel = $("#code_chanel");
        var url = obj.attr("get_chanel_url");
        if (obj.val() != '') {
            $.ajax({
                url: url,
                dataType: "json",
                async: false,
                data: {
                    "code_mauqc": obj.val()
                },
                success: function (data) {
                    select_chanel.select2("val", data.parent_id);
                }
            });
        }
    }

</script>
<style>
    .filter_form label{
        font-size: 13px;
    }
</style>
<form id="baocao_filter_form" action="<?php echo $form_url ?>">
    <div class="row-fluid1 filter_form">
        <div class="e_toogle_next_div" style="margin:10px 0px 20px 30px;display: inline-block;font-weight: bold">Lọc</div>
        <a href="#" title='Ẩn/hiện' class="toggle_block minimize e_toogle_next_div"></a>
        <div class="e_form_search"> 
            <div class="uniform"> 

            </div>

            <div class="span3">
                <div class="form-group">
                    <label for="code_campaign">Campaign </label>
                    <select name="code_campaign" class="form-control" id="code_campaign" >
                        <option value="">Tất cả</option>
                        <?php foreach ($list_campaign as $campaign): ?>
                            <?php $selected = isset($form_conds["custom_where"]["camp.code"]) && $campaign->code == $form_conds["custom_where"]["camp.code"] ? 'selected' : "" ?>
                            <option value="<?php echo $campaign->code ?>" <?php echo "$selected"; ?>><?php echo $campaign->name ?></option>
                        <?php endforeach; ?>
                        <option value="-100" <?php echo isset($form_conds["custom_where"]["m.id_camp_landingpage <="]) ? "selected" : "" ?>>Không xác định</option>    
                    </select>
                </div> 
            </div>
            <div class="span3" >
                <div class="form-group">
                    <label for="domain">Landing Page </label>
                    <select name="domain" class="form-control" id="domain" >
                        <option value="">Tất cả</option>
                        <?php foreach ($list_landingpage as $landingpage): ?>
                            <?php $selected = isset($form_conds["custom_where"]["m.domain"]) && $landingpage->url == $form_conds["custom_where"]["m.domain"] ? 'selected' : "" ?>
                            <option value="<?php echo $landingpage->url ?>" <?php echo "$selected"; ?>><?php echo $landingpage->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> 
            </div> 

            <div class="span3">
                <div class="form-group">
                    <label for="code_chanel">Kênh </label>
                    <select  name="parent_id" class="form-control" id="code_chanel" get_mauqc_url="<?php echo site_url("chanel/get_mauqc_by_id_chanel") ?>">
                        <option value="">Tất cả</option>
                        <?php foreach ($list_chanel as $chanel): ?>
                            <?php $selected = isset($form_conds["custom_where"]["qc.parent_id"]) && $chanel->id == $form_conds["custom_where"]["qc.parent_id"] ? 'selected' : "" ?>
                            <option value="<?php echo $chanel->id ?>" <?php echo "$selected"; ?>><?php echo $chanel->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> 
            </div> 

            <div class="span3">
                <div class="form-group">
                    <label for="code_mauqc">Mẫu quảng cáo </label>
                    <select lock='<?php echo $lock ?>' name="code_mauqc" class="form-control" id="code_mauqc" get_chanel_url="<?php echo site_url("chanel/get_chanel_by_parent_mauqc") ?>">
                        <option value="">Tất cả</option>
                        <?php foreach ($list_mauqc as $mauqc): ?>
                            <?php $selected = isset($form_conds["custom_where"]["m.code_chanel"]) && $mauqc->code == $form_conds["custom_where"]["m.code_chanel"] ? 'selected' : "" ?>
                            <option value="<?php echo $mauqc->code ?>" <?php echo "$selected"; ?>><?php echo $mauqc->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> 
            </div> 

            <div class="span3">
                <div class="form-group">
                    <label for="name">Họ tên, email, số điện thoại</label>
                    <input name="name" type="text" class="form-control" id="name" value="<?php
                    if (isset($form_conds["custom_like"]["m.name"])) {
                        echo $form_conds["custom_like"]["m.name"];
                    }
                    ?>">		
                </div>
            </div>
            <div class="span3">
                <div class="form-group">
                    <label for="keyword">Từ khóa quảng cáo</label>
                    <input name="keyword" type="text" class="form-control" id="keyword" value="<?php
                    if (isset($form_conds["custom_like"]["qc.keyword"])) {
                        echo $form_conds["custom_like"]["qc.keyword"];
                    }
                    ?>">		
                </div>
            </div>
            <div class="span3">
                <div class="form-group">
                    <label for="i_from_date">Từ ngày</label>
                    <input name="from_date" class="form-control" id="i_from_date" autocomplete="off" type="datetime" value="<?php
                    if (isset($form_conds["custom_where"]["datetime_submitted_unix >="])) {
                        echo date('Y-m-d', $form_conds["custom_where"]["datetime_submitted_unix >="]);
                    } else {
                        echo date('Y-m-d', time());
                    }
                    ?>">
                </div>
            </div>
            <div class="span3">
                <div class="form-group">
                    <label for="i_to_date">Đến ngày</label>
                    <input name="to_date" class="form-control" id="i_to_date" autocomplete="off" type="datetime" value="<?php
                    if (isset($form_conds["custom_where"]["datetime_submitted <="])) {
                        echo date('Y-m-d', $form_conds["custom_where"]["datetime_submitted <="]);
                    } else {
                        echo date('Y-m-d', time());
                    }
                    ?>">
                </div>
            </div>
            <?php if (isset($_GET["id"])): ?>
                <input type="hidden" name="id" value="<?php echo($_GET["id"]) ?>" />
            <?php endif; ?>
            <div class="span12" style="margin-left: -40px;text-align: right">
                <button class="e_btn_search btn btn-info add_button" id="btn_baocao_filter_submit"> Tìm kiếm </button>
                <button class="btn btn-info add_button" type="reset"> Làm lại </button>
            </div>
        </div>
    </div>
</form>
<div class="row-fluid">
    <div class="span6">
        <div id="dataTable_length" class="dataTables_length">
            <label>
                <span>
                    Hiển thị <input name="post" type="text" class="changer_number_record e_changer_number_record" value="<?php echo $limit ? $limit : "tất cả"; ?>"> bản ghi trên 1 trang
                </span>
            </label>
        </div>
    </div>
    <div class="span6">
        <div class="dataTables_filter" id="dataTable_filter">
            <label>
                <!--<span>Lọc: </span> <input type="text" name="q" class="e_search_table" value="<?php // echo $search_string;          ?>" />-->
            </label>
        </div>
    </div>
    <div class="clear" ></div>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <?php
            foreach ($colum as $key => $value) {
                if (isset($order[$key])) {
                    $temp = $order[$key];
                    $class = "sorting_" . $order[$key];
                } else {
                    $temp = "";
                    $class = "sorting";
                }
                $order_post = array_search($key, array_keys($order));
                ?>
                <th class="<?php echo $class; ?>" order="<?php echo $temp; ?>" <?php echo (array_search($key, array_keys($order)) !== FALSE) ? "order_pos='" . $order_post . "'" : "" ?> field_name="<?php echo $key; ?>" ><?php echo $value; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <?php if (sizeof($record)) { ?>
        <tbody>
            <?php
            foreach ($record as $item) {
//                var_dump($item);
                ?>

                <tr class="gradeX" data-id="<?php echo $item->$key_name; ?>">
                    <?php foreach ($colum as $key => $value) { ?>
                        <td for_key="<?php echo $key; ?>"><?php echo $item->{end(explode(".", $key))}; ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    <?php } ?>
</table>
<?php if (!sizeof($record)) { ?>
    <p class="no_record">Không có bản ghi nào thỏa mãn yêu cầu</p>
<?php } else { ?>
    <div class="row-fluid no-magin">
        <div class="span6">
            <?php if ($to) { ?>
                <div class="dataTables_info" id="dataTable_info">Hiển thị từ <?php echo $from . " tới " . $to . " trên tổng số " . $total; ?> bản ghi</div>
            <?php } else { ?>
                <div class="dataTables_info" id="dataTable_info">Hiển thị tất cả <?php echo $total; ?> bản ghi</div>
            <?php } ?>

        </div>
        <div class="span6">
            <div class="dataTables_paginate paging_bootstrap pagination">
                <?php echo $pagging; ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<?php } ?>