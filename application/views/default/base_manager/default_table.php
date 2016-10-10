<div class="row-fluid">
    <div class="span6">
        <div id="dataTable_length" class="dataTables_length">
            <label>
                <span>
                    Show <input name="post" type="text" class="changer_number_record e_changer_number_record" value="<?php echo $limit ? $limit : "tất cả"; ?>"> records per page
                </span>
            </label>
        </div>
		</div>
    <div class="span6">
        <div class="dataTables_filter" id="dataTable_filter">
            <label>
                <span>Filter: </span> <input type="text" name="q" class="e_search_table" value="<?php echo $search_string; ?>" />
            </label>
        </div>
    </div>
    <div class="clear" ></div>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover e_data_table">
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
            <?php foreach ($record as $item) { 
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
    <p class="no_record">No data available in table</p>
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