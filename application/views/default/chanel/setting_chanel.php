<div class="span6 resizable uniform modal-content" style="width:1100px">
    <form class="form-horizontal e_ajax_submit" action="<?php echo $save_link; ?>" enctype="multipart/form-data" method="POST">
        <div class="modal-header"> 
            <span type="button" class="close" data-dismiss="modal"><i class="icon16 i-close-2"></i></span>
            <h3><?php echo $title; ?></h3>
        </div>
        <div class="modal-body bgwhite">
            <div class="control-group ">
                <table class="data_campaign">
                    <tr class="title_head">
                        <td colspan="5" style="font-weight: bold;text-transform: uppercase">Thông tin kênh</td>
                    </tr>
                    <tr class="info_campaign">
                        <td colspan="1" style="min-width: 100px;width: 100px;font-weight: bold">Mã</td>
                        <td colspan="2" style="font-weight: bold">Tên kênh </td>
                        <td colspan="2" style="font-weight: bold">Mô tả kênh </td>
                    </tr>
                    <tr>
                        <td colspan="1" style="padding: 5px 10px"><?php echo $info_chanel->code; ?></td>
                        <td colspan="2" style="padding: 5px 10px"><?php echo $info_chanel->name; ?></td>
                        <td colspan="2" style="padding: 5px 10px;width: 250px;"><?php echo $info_chanel->description; ?></td>
                    </tr>
                    <tr class="title_head">
                        <td colspan="5" style="font-weight: bold;text-transform: uppercase">Các mẫu quảng cáo</td>
                    </tr>
                    <tr class="info_landingpage">
                        <td style="font-weight: bold">Mã</td>
                        <td style="font-weight: bold">Tên mẫu</td>
                        <td style="font-weight: bold">Từ khóa</td>
                        <td style="font-weight: bold">Mô tả</td>
                        <td style="width: 20px"></td>
                    </tr>
                    <?php
                    if (isset($list_mau_qc) and count($list_mau_qc)) {
                        foreach ($list_mau_qc as $item) {
                            ?>
                            <tr class="e_row">
                                <td>
                                    <input class="e_code" type="text" value="<?php echo $item->code ?>" disabled="disable"/>
                                </td>
                                <td>
                                    <input type="text" name="edit_name[]" value="<?php echo $item->name ?>" />
                                </td>
                                <td>
                                    <input type="text" name="edit_keyword[]" value="<?php echo $item->keyword ?>" />
                                </td>
                                <td>
                                    <input type="text" name="edit_description[]" value="<?php echo $item->description ?>" />
                                </td>
                                <td>
                                    <?php if ($view == false): ?>
                                        <input type="hidden"  name="edit_id[]" value="<?php echo $item->id; ?>"/>
                                        <span class="delete icon16 i-remove e_del_row" per="1" href="#" id="<?php echo $item->id; ?>" title="Xóa"></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <?php if ($view == false): ?>
                        <!--Link them mau quang cao moi-->
                        <tr class="field_action">
                            <td colspan="5" style="padding: 10px 5px">
                                <input type="hidden" id="e_list_id_del" name="id_del"/>
                                <a href="#" id_chanel="<?php echo "id_chanel"; ?>" data-url="<?php echo site_url('chanel/get_data_row'); ?>" class="e_add_mau_qc">+ Thêm mẫu quảng cáo </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <div class="modal-footer"> 
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <button type="submit" class="b_add b_edit btn btn-primary">Lưu</button>
            <button type="button" class="b_view b_add b_edit btn" data-dismiss="modal">Hủy</button>
        </div>
    </form>
</div>