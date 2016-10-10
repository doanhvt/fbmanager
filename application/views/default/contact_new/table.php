<script type="text/javascript">
    $("#i_from_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#i_to_date").datepicker("option", "minDate", selectedDate);
        }
    });//cam giao dien kieu date time cho the thoi gian
    $("#i_to_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#i_from_date").datepicker("option", "maxDate", selectedDate);
        }
    });//cam giao dien kieu date time cho the thoi gian
    
    
    //$(document).on("change", "#code_chanel", get_mauqc_by_chanel);
    $("#code_chanel").change(get_mauqc_by_chanel);
    function get_mauqc_by_chanel(){
        // Day la kenh cha, thuc hien lay cac con cua no
        var obj = $("#code_chanel");
        var select_mauqc = $("#code_mauqc");
        var url = obj.attr("get_mauqc_url");
        $.ajax({
            url: url,
            dataType: "text",
            async: false,
            data:{
                "parent_id": obj.val()
            },
            success: function(data){
                select_mauqc.html(data);
                select_mauqc.select2("val", '');
            }
        });
    }
    
//    $(document).on("change", "#code_mauqc", get_chanel_by_mauqc);
    $("#code_mauqc").change(get_chanel_by_mauqc);
    function get_chanel_by_mauqc(){
        // Day la kenh con, thuc hien lay kenh cha cua no
        var obj = $("#code_mauqc");
        var select_chanel = $("#code_chanel");
        var url = obj.attr("get_chanel_url");
        if(obj.val() != ''){
            $.ajax({
                url: url,
                dataType: "json",
                async: false,
                data:{
                    "code_mauqc": obj.val()
                },
                success: function(data){
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
        <div class="e_toogle_next_div" style="margin:10px 0px 20px 30px;display: inline-block;font-weight: bold">Filter</div>
        <a href="#" title='Ẩn/hiện' class="toggle_block minimize e_toogle_next_div"></a>
        <div class="e_form_search"> 
            <div class="uniform"> 

            </div>

            <div class="span3">
                <div class="form-group">
                    <label for="code_campaign">Campaign </label>
                    <select name="code_campaign" class="form-control" id="code_campaign" >
                        <option value="">All</option>
                        <?php foreach ($list_campaign as $campaign): ?>
                            <?php $selected = isset($form_conds["custom_where"]["camp.code"]) && $campaign->code == $form_conds["custom_where"]["camp.code"] ? 'selected' : "" ?>
                            <option value="<?php echo $campaign->code ?>" <?php echo "$selected"; ?>><?php echo $campaign->name ?></option>
                        <?php endforeach; ?>
                        <option value="-100" <?php echo isset($form_conds["custom_where"]["m.id_camp_landingpage <="]) ? "selected" : "" ?>>Không xác định</option>    
                    </select>
                </div> 
            </div>

            <div class="span3">
                <div class="form-group">
                    <label for="code_landingpage">Landing Page </label>
                    <select name="code_landingpage" class="form-control" id="code_landingpage" >
                        <option value="">All</option>
                        <?php foreach ($list_landingpage as $landingpage): ?>
                            <?php $selected = isset($form_conds["custom_where"]["land.code"]) && $landingpage->code == $form_conds["custom_where"]["land.code"] ? 'selected' : "" ?>
                            <option value="<?php echo $landingpage->code ?>" <?php echo "$selected"; ?>><?php echo $landingpage->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> 
            </div> 

            <div class="span3">
                <div class="form-group">
                    <label for="code_chanel">Chanel </label>
                    <select  name="parent_id" class="form-control" id="code_chanel" get_mauqc_url="<?php echo site_url("chanel/get_mauqc_by_id_chanel") ?>">
                        <option value="">All</option>
                        <?php foreach ($list_chanel as $chanel): ?>
                            <?php $selected = isset($form_conds["custom_where"]["qc.parent_id"]) && $chanel->id == $form_conds["custom_where"]["qc.parent_id"] ? 'selected' : "" ?>
                            <option value="<?php echo $chanel->id ?>" <?php echo "$selected"; ?>><?php echo $chanel->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> 
            </div> 

            <div class="span3">
                <div class="form-group">
                    <label for="code_mauqc">Ads Sample </label>
                    <select lock='<?php echo $lock?>' name="code_mauqc" class="form-control" id="code_mauqc" get_chanel_url="<?php echo site_url("chanel/get_chanel_by_parent_mauqc") ?>">
                        <option value="">All</option>
                        <?php foreach ($list_mauqc as $mauqc): ?>
                            <?php $selected = isset($form_conds["custom_where"]["m.code_chanel"]) && $mauqc->code == $form_conds["custom_where"]["m.code_chanel"] ? 'selected' : "" ?>
                            <option value="<?php echo $mauqc->code ?>" <?php echo "$selected"; ?>><?php echo $mauqc->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> 
            </div> 

            <div class="span3">
                <div class="form-group">
                    <label for="name">Name/Mobile/Email</label>
                    <input name="name" type="text" class="form-control" id="name" value="<?php
                        if (isset($form_conds["custom_like"]["m.name"])) {
                            echo $form_conds["custom_like"]["m.name"];
                        }
                        ?>">		
                </div>
            </div>
            <div class="span3">
                <div class="form-group">
                    <label for="keyword">Keyword</label>
                    <input name="keyword" type="text" class="form-control" id="keyword" value="<?php
                           if (isset($form_conds["custom_like"]["qc.keyword"])) {
                               echo $form_conds["custom_like"]["qc.keyword"];
                           }
                        ?>">		
                </div>
            </div>
            <div class="span3">
                <div class="form-group">
                    <label for="i_from_date">From</label>
                    <input name="from_date" class="form-control" id="i_from_date" autocomplete="off" type="datetime" value="<?php
                           if (isset($form_conds["custom_where"]["datetime_submitted_unix >="])) {
                               echo date('Y-m-d',$form_conds["custom_where"]["datetime_submitted_unix >="]);
                           }  else {
                               echo date('Y-m-d',time());
                            }
                        ?>">
                </div>
            </div>
            <div class="span3">
                <div class="form-group">
                    <label for="i_to_date">To</label>
                    <input name="to_date" class="form-control" id="i_to_date" autocomplete="off" type="datetime" value="<?php
                           if (isset($form_conds["custom_where"]["datetime_submitted_unix <="])) {
                               echo date('Y-m-d',$form_conds["custom_where"]["datetime_submitted_unix <="]);
                           }  else {
                                echo date('Y-m-d',time());
                            }
                        ?>">
                </div>
            </div>
            <div class="span3">
                <div class="form-group">
                    <label for="code_chanel">Type of contact </label>
                    <select name="link_cv" id="link_cv">
                        <option value="">All</option>
                        <option value="1" <?php echo (isset($form_conds["link_cv"]) && $form_conds["link_cv"] == 1) ? 'selected' : '' ?>>Học viên</option>
                        <option value="2" <?php echo (isset($form_conds["link_cv"])  && $form_conds["link_cv"] == 2) ? 'selected' : '' ?>>Giáo viên</option>
                    </select>
                </div> 
            </div> 
            <div class="span3">
                <div class="form-group">
                    <label for='status'>Status</label>
                    <select name="status" id="status">
                        <option value="">C3</option>
                        <option value="1" <?php echo (isset($form_conds["status"]) && $form_conds["status"] == 1) ? 'selected' : '' ?>>C3.1</option>
                        <option value="2" <?php echo (isset($form_conds["status"])  && $form_conds["status"] == 2) ? 'selected' : '' ?>>L1</option>
                        <option value="3" <?php echo (isset($form_conds["status"])  && $form_conds["status"] == 3) ? 'selected' : '' ?>>Trùng CRM</option>
                    </select>
                </div> 
            </div> 
             
            <?php if (isset($_GET["id"])): ?>
                <input type="hidden" name="id" value="<?php echo($_GET["id"]) ?>" />
            <?php endif; ?>
            <div class="span12" style="margin-left: -40px;text-align: right">
                <button class="e_btn_search btn btn-info add_button" id="btn_baocao_filter_submit"> Search </button>
                <button class="btn btn-info add_button" type="reset"> Refresh </button>
            </div>
        </div>
    </div>
</form>
<div class="row-fluid">
    <div class="span6">
        <div id="dataTable_length" class="dataTables_length">
            <label>
                <span>
                    Show <input name="post" type="text" class="changer_number_record e_changer_number_record" value="<?php echo $limit ? $limit : "all"; ?>"> records per page
                </span>
            </label>
        </div>
    </div>
    <div class="span6">
        <div>
            <label style="margin-bottom: 0px;margin-top: 10px;text-align: right;font-size: 18px">
                <b>C3</b>: <?php echo $total_c3; ?> | <b>C3.1</b>: <?php echo $total_c3_1; ?>| <b>L1</b>: <?php echo $total_l1; ?>
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
    <p class="no_record">No data</p>
<?php } else { ?>
    <div class="row-fluid no-magin">
        <div class="span6">
            <?php if ($to) { ?>
                <div class="dataTables_info" id="dataTable_info">Show from <?php echo $from . " to " . $to . " per " . $total; ?> record</div>
            <?php } else { ?>
                <div class="dataTables_info" id="dataTable_info">Show all <?php echo $total; ?> records</div>
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