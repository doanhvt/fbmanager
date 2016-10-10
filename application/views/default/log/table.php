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
</script>
<style>
    .filter_form label{
        font-size: 11px;
    }
</style>
<form id="contact_filter_form" action="<?php echo $form_url ?>">
    <div class="row-fluid1 filter_form">
        <div class="e_toogle_next_div" style="margin:10px 0px 20px 30px;display: inline-block;font-weight: bold">Filter</div>
        <a href="#" title='Ẩn/hiện' class="toggle_block minimize e_toogle_next_div"></a>
        <div class="e_form_search"> 
            <div class="uniform"> 
                <div class="span3">
                    <div class="form-group">
                        <label for="id_campaign">Campaign </label>
                        <select name="id_campaign" class="form-control" id="" >
                            <option value="">All</option>
                            <?php foreach ($list_campaign as $campaign): ?>
                                <?php $selected = isset($form_conds["custom_where"]["id_campaign"]) && $campaign->id == $form_conds["custom_where"]["id_campaign"] ? 'selected' : "" ?>
                                <option value="<?php echo $campaign->id ?>" <?php echo "$selected"; ?>><?php echo $campaign->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div> 
                </div> 
            </div>

            <div class="span3">
                <div class="form-group">
                    <label for="code_chanel">Chanel </label>
                    <select name="code_chanel" class="form-control" id="code_chanel" >
                        <option value="">All</option>
                        <?php foreach ($list_chanel as $chanel): ?>
                            <?php $selected = isset($form_conds["custom_where"]["c.parent_id"]) && $chanel->id == $form_conds["custom_where"]["c.parent_id"] ? 'selected' : "" ?>
                            <option value="<?php echo $chanel->id ?>" <?php echo "$selected"; ?>><?php echo $chanel->name ?></option>
                        <?php endforeach; ?>
                    </select>	
                </div>
            </div>
            <div class="span3">
                <div class="form-group">
                    <label for="i_from_date">From</label>
                    <input name="from_date" class="form-control" id="i_from_date" autocomplete="off" type="datetime" value="<?php
                    if (isset($form_conds["custom_where"]["datetime_unix >="])) {
                        echo date('Y-m-d',$form_conds["custom_where"]["datetime_unix >="]);
                    } else {
                        echo $from_date;
                    }
                    ?>">
                </div>
            </div>
            <div class="span3">
                <div class="form-group">
                    <label for="i_to_date">To</label>
                    <input name="to_date" class="form-control" id="i_to_date" autocomplete="off" type="datetime" value="<?php
                    if (isset($form_conds["custom_where"]["datetime_unix <="])) {
                        echo date('Y-m-d',$form_conds["custom_where"]["datetime_unix <="]);
                    } else {
                        echo $to_date;
                    }
                    ?>">
                </div>
            </div>
            <div class="span12" style="margin-top: 15px;margin-left: -40px;text-align: right">
                <button class="e_btn_search btn btn-info add_button" id="btn_log_filter_submit"> Search </button>
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
        <div class="dataTables_filter" id="dataTable_filter">
            <label>
                <!--<span>Lọc: </span> <input type="text" name="q" class="e_search_table" value="<?php // echo $search_string;      ?>" />-->
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