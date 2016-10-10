<div class="span6 resizable uniform modal-content" style="width:1000px">
    <form class="form-horizontal e_ajax_submit" action="<?php echo $save_link; ?>" enctype="multipart/form-data" method="POST">
        <div class="modal-header"> 
            <span type="button" class="close" data-dismiss="modal"><i class="icon16 i-close-2"></i></span>
            <h3><?php echo $title; ?></h3>
        </div>
        <div class="modal-body bgwhite">
            <?php foreach ($list_input as $input) { ?>
                <div class="control-group <?php echo (preg_match("/type\=hidden/i", $input->rule)) ? "hidden" : ""; ?>">
                    <label class="control-label" for="<?php echo $input->name; ?>"><?php echo $input->label; ?></label>
                    <div class="controls controls-row">
                        <?php if (preg_match("/type\=textarea/i", $input->rule)) { ?>
                            <textarea name="<?php echo $input->name; ?>" <?php echo $input->rule; ?> id="i_<?php echo $input->name; ?>" ></textarea>
                        <?php } elseif (preg_match("/type\=select/i", $input->rule)) { ?>
                            <?php if($input->name == 'userPermission'){
                                $pre_id = $this->session->userdata('perId');
                                    if($pre_id >2){ ?>
                                        <select class="select2" name="<?php echo $input->name; ?>" id="i_<?php echo $input->name; ?>" <?php echo $input->rule; ?>>
                                        <?php foreach ($input->option as $option) { 
                                            if($option->value == $pre_id){?>
                                                <option value="<?php echo $option->value; ?>"><?php echo $option->display; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                      </select> 
                                <?php } elseif($pre_id == 2) { ?>
                                      <select class="select2" name="<?php echo $input->name; ?>" id="i_<?php echo $input->name; ?>" <?php echo $input->rule; ?>>
                                        <?php foreach ($input->option as $option) {
                                            if($option->value != 1){ ?>
                                                <option value="<?php echo $option->value; ?>"><?php echo $option->display; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                      </select>      
                                <?php } else { ?>
                                      <select class="select2" name="<?php echo $input->name; ?>" id="i_<?php echo $input->name; ?>" <?php echo $input->rule; ?>>
                                        <?php foreach ($input->option as $option) { ?>
                                            <option value="<?php echo $option->value; ?>"><?php echo $option->display; ?></option>
                                        <?php } ?>
                                      </select> 
                                <?php }?>
                            <?php }  else { ?>
                                   <select class="select2" name="<?php echo $input->name; ?>" id="i_<?php echo $input->name; ?>" <?php echo $input->rule; ?>>
                                    <?php foreach ($input->option as $option) { ?>
                                        <option value="<?php echo $option->value; ?>"><?php echo $option->display; ?></option>
                                    <?php } ?>
                                  </select>   
                            <?php }?>
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
                            <input <?php if($input->name == 'userName' && isset($is_edit)) echo 'disabled style="background: #d9d9d9"'; ?> name="<?php echo $input->name; ?>" <?php echo $input->rule; ?> id="i_<?php echo $input->name; ?>" />          
                        
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php if(isset($list_chanel)):?>
                <div class="control-group">
                    <label class="control-label">Danh sách các kênh được phân</label>
                    <div class="controls controls-row">
                        <?php if(count($list_chanel) == 0):?>
                            <br />
                            <p>Chưa được phân kênh nào</p>
                        <?php else:?>
                            <p>Đã được phân: <?php echo count($list_chanel)?> kênh</p>
                            <table border='1' style="width: 100%; text-align: center;">
                                <tr>
                                    <th>Mã kênh</th>
                                    <th>Tên kênh</th>
                                    <th>Mô tả</th>
                                </tr>
                                <?php foreach ($list_chanel as $chanel):?>
                                    <tr>
                                        <td><?php echo $chanel->code?></td>
                                        <td><?php echo $chanel->name?></td>
                                        <td><?php echo $chanel->description?></td>
                                    </tr>
                                <?php endforeach;?>
                            </table>
                        <?php endif;?>
                    </div>
                </div>
                
            <?php endif;?>
                
        </div>
        <div class="modal-footer"> 
            <button type="submit" class="b_add b_edit btn btn-primary">Save</button>
            <button type="reset" class="b_add btn">Clear all</button>
            <button type="button" class="b_view b_add b_edit btn" data-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>