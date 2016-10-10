<div class="row-fluid filter_form">
    <legend class="e_toogle_next_div">Lọc</legend>
    <a href="#" title='Ẩn/hiện' class="toggle_block minimize e_toogle_next_div"></a>
    <div class=""> 
        <div class="span3">
            <div class="form-group">
                <label for="lastname">Họ</label><input name="lastname" type="text" class="form-control" id="lastname" value="">			
            </div>
        </div>

        <div class="span3">
            <div class="form-group">
                <label for="firstname">Tên</label><input name="firstname" type="text" class="form-control" id="firstname" value="">		
            </div>
        </div>

        <div class="span3">
            <div class="form-group">
                <label for="email">Email</label><input type="email" name="email" class="form-control" id="email" value="">		
            </div>
        </div>

        <div class="span3">
            <div class="form-group">
                <label for="id_code">Mã học viên </label>
                <input name="id_code" class="form-control" id="id_code" autocomplete="off" type="text" value="">		
            </div>
        </div>
        <div class="span3">
            <div class="form-group">
                <label for="level">Trình độ </label>
                <select name="level" class="form-control" id="level" >
                    <option value="">Tất cả</option>
                    <option value="basic">Basic</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
        </div>
        <div class="span3">
            <div class="form-group">
                <label for="usertype">Loại tài khoản </label>
                <select name="account_type" class="form-control" id="usertype">
                    <option value=""> Tất cả </option>
                    <option value="1">Dùng thử</option>
                    <option value="2">Học viên</option>
                    <option value="3">Demo</option>
                    <option value="4">Nhân viên</option>
                    <option value="5">Alpha</option>
                    <option value="6">Vip</option>
                    <option value="7">Giáo viên</option>
                    <option value="8"> HV Tùy chọn</option>
                    <option value="9">HV Thỏa thích </option>
                    <option value="10">HV GG</option>
                </select>
            </div>
        </div>
        <div class="span3">
            <div class="form-group">
                <label for="active">Trạng thái active</label>
                <select name="active" id="active">
                    <option value="">Tất cả</option>
                    <option value="1">Đã Active</option>
                    <option value="0">Chưa Active</option>
                </select>
            </div>
        </div>
        <div class="span3">
            <div class="form-group">
                <label for="lock">Trạng thái lock</label>
                <select name="lock" id="lock" style="display: none;">
                    <option value="">Tất cả</option>
                    <option value="1">Đã Khóa</option>
                    <option value="0">Chưa Khóa</option>
                </select>
            </div>
        </div>
        <div class="span3">
            <button class="btn btn-info add_button"> Tìm kiếm </button>
        </div>
    </div>
</div>

<fieldset>
    <legend>Kết quả</legend>
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
                    <span>Lọc: </span> <input type="text" name="q" class="e_search_table" value="<?php echo $search_string; ?>" />
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
</fieldset>