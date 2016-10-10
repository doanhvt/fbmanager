<div class="span6 resizable uniform modal-content" style="width:1000px">
    <form class="form-horizontal e_ajax_submit" action="<?php echo $ajax_link; ?>" enctype="multipart/form-data" method="POST">
        <div class="modal-header"> 
            <span type="button" class="close" data-dismiss="modal"><i class="icon16 i-close-2"></i></span>
            <h3><?php echo $title; ?></h3>
        </div>
        <div class="modal-body bgwhite">
            <div class="control-group">
                <div class="controls controls-row" style="font-size: 14px;margin-left: 30px;">
                    <b style="margin-bottom: 5px;display: block">Tổng (C3): <?php echo $total; ?></b>
                    <ul>
                        <li>Số C3.1: <b><?php echo $total_mol; ?></b></li>
                        <li>Số L1: <b><?php echo $total_crm; ?></b>
                            <ul>
                                <li>Đã bàn giao: <b><?php echo $total_crm_handover; ?></b></li>
                                <li>Chưa bàn giao: <b><?php echo $total_crm_not_handover; ?></b></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Số contact bàn giao</label>
                <div class="controls controls-row">
                    <input style="width: 2%;min-width: 75px;" type="number" name="limit_contact" required="" maxlength="<?php echo $total_crm_not_handover; ?>"/>
                </div>
            </div>
        </div>
        <div class="modal-footer"> 
            <button type="submit" class="b_add b_edit btn btn-primary">Bàn giao contact</button>
            <button type="reset" class="b_add btn">Nhập lại</button>
            <button type="button" class="b_view b_add b_edit btn" data-dismiss="modal">Hủy</button>
        </div>
    </form>
</div>