<?php if (isset($id)): ?>
    <!--Truong hop view hoac edit kenh-->
    <div class="span6 resizable uniform modal-content" style="width:1000px">
        <form class="form-horizontal e_ajax_submit" action="<?php echo $save_link; ?>" enctype="multipart/form-data" method="POST">
            <div class="modal-header"> 
                <span type="button" class="close" data-dismiss="modal"><i class="icon16 i-close-2"></i></span>
                <h3><?php echo $title; ?></h3>
            </div>
            <div class="modal-body bgwhite">
                <div class="control-group ">
                    <table class="data_campaign">
                        <tr class="title_head">
                            <td colspan="5">Thông tin kênh</td>
                        </tr>
                        <tr class="info_campaign">
                            <td colspan="1" style="min-width: 100px;width: 100px">Mã</td>
                            <td colspan="2">Tên kênh </td>
                            <td colspan="2">Mô tả kênh </td>
                        </tr>
                        <tr>
                            <td colspan="1"><input class="e_code" disabled="disable" type="text" name="code" value="<?php echo $info_chanel->code; ?>"/></td>
                            <td colspan="2"><input type="text" name="name" required="" value="<?php echo $info_chanel->name; ?>"/></td>
                            <td colspan="2"><input type="text" name="description" required="" value="<?php echo $info_chanel->description; ?>"/></td>
                        </tr>
                        <tr class="title_head">
                            <td colspan="5">Các mẫu quảng cáo</td>
                        </tr>
                        <tr class="info_landingpage">
                            <td>Mã</td>
                            <td>Tên mẫu</td>
                            <td>Từ khóa</td>
                            <td>Mô tả</td>
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
                                            <input type="hidden"  name="edit_id[]" value="<?php echo $item->id;  ?>"/>
                                            <a class="delete icon16 i-remove e_del_row" per="1" href="#" id="<?php echo $item->id; ?>" title="Xóa"></a>
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
<?php else: ?>
    <!--Truong hop them kenh moi-->
    <div class="span6 resizable uniform modal-content" style="width:1000px">
        <form class="form-horizontal e_ajax_submit" action="<?php echo $save_link; ?>" enctype="multipart/form-data" method="POST">
            <div class="modal-header"> 
                <span type="button" class="close" data-dismiss="modal"><i class="icon16 i-close-2"></i></span>
                <h3><?php echo $title; ?></h3>
            </div>
            <div class="modal-body bgwhite">
                <input type="hidden" name="parent_id" value="<?php echo isset($parent_id) ? $parent_id : '' ?>"/>
                <?php foreach ($list_input as $input) { ?>
                    <div class="control-group <?php echo (preg_match("/type\=hidden/i", $input->rule)) ? "hidden" : ""; ?>">
                        <label class="control-label" for="<?php echo $input->name; ?>"><?php echo $input->label; ?></label>
                        <div class="controls controls-row">
                            <?php if (preg_match("/type\=textarea/i", $input->rule)) { ?>
                                <textarea name="<?php echo $input->name; ?>" <?php echo $input->rule; ?> id="i_<?php echo $input->name; ?>" ></textarea>
                            <?php } elseif (preg_match("/type\=select/i", $input->rule)) { ?>
                                <select class="select2" name="<?php echo $input->name; ?>" id="i_<?php echo $input->name; ?>" <?php echo $input->rule; ?>>
                                    <?php foreach ($input->option as $option) { ?>
                                        <option value="<?php echo $option->value; ?>"><?php echo $option->display; ?></option>
                                    <?php } ?>
                                </select>
                            <?php } elseif (preg_match("/type\=file/i", $input->rule)) { ?>
                                <?php if (preg_match("/crop\=true/i", $input->rule)) { ?>
                                    <input name="<?php echo $input->name; ?>" id="i_<?php echo $input->name; ?>" type="hidden" />
                                    <div class="file_input_content e_file_input_content">
                                        <p class="image_preview"><img class="main_img" src="#" /></p>
                                        <input data-name="image_uploader[]" <?php echo $input->rule; ?> />
                                    </div>
                                    <div class="image_crop_content">
                                        <div class="trigger_crop" title="Ấn để crop">
                                            <span class="i-arrow-right-18 c_icon arr_icon"></span><span class="i-crop c_icon crop_icon"></span>
                                        </div><div class="crop_value" title="Ấn để crop">
                                            <img class="croped_img" src="#" />
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="file_input_content e_file_input_content">
                                        <p class="image_preview"><img class="main_img" src="#" /></p>
                                        <input <?php echo $input->rule; ?> />
                                    </div>
                                <?php } ?>
                            <?php } elseif (preg_match("/type\=rich_editor/i", $input->rule)) { ?>
                                <textarea class="ckeditor" data-ckfinder="<?php echo base_url("ckfinder") ?>" name="<?php echo $input->name; ?>" <?php echo $input->rule; ?> ></textarea>
                            <?php } else { ?>
                                <input <?php if ($input->name == 'code' && isset($id_chanel)) echo 'disabled style="background: #d9d9d9"'; ?>name="<?php echo $input->name; ?>" <?php echo $input->rule; ?> id="i_<?php echo $input->name; ?>" />
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="modal-footer"> 
                <button type="submit" class="b_add b_edit btn btn-primary">Lưu</button>
                <button type="reset" class="b_add btn">Nhập lại</button>
                <button type="button" class="b_view b_add b_edit btn" data-dismiss="modal">Hủy</button>
            </div>
        </form>
    </div>
<?php endif; ?>