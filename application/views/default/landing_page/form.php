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
                            <?php if ($input->name == 'sub_domain') { ?>
                                <input placeholder="http://mol.topmito.edu.vn" value="http://mol.topmito.edu.vn" on name="<?php echo $input->name; ?>" <?php echo $input->rule; ?> id="i_<?php echo $input->name; ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'http://mol.topmito.edu.vn'"/>
                                <?php if(!isset($landingpage_info)) { ?>
                                    <div style="color: red"> Note: After compleMobiley creating a new landing page, must inform the tech-support team to activate the landing page</div>
                                <?php }?>
                                
                            <?php } elseif ($input->name == 'code' && isset($landingpage_info)) { ?>
                                <input readonly="" style="background: #d9d9d9" name="<?php echo $input->name; ?>" <?php echo $input->rule; ?> id="i_<?php echo $input->name; ?>" />          
                            <?php } else { ?>
                                <input name="<?php echo $input->name; ?>" <?php echo $input->rule; ?> id="i_<?php echo $input->name; ?>" />          
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($landingpage_info->soure_url)) {
                    if($landingpage_info->soure_url){ ?>
                        <div class="control-group">
                            <label class="control-label">Download landingpage</label>
                            <div class="controls controls-row">
                                <a style="margin-top: 5px;display: inline-block" href="<?php echo site_url().$landingpage_info->soure_url ?>">Click đây để download</a>
                            </div>
                        </div>
                    <?php }
                ?>
            <?php } ?>
            <div class="control-group">
                <label class="control-label">File Zip</label>
                <div class="controls controls-row">
                    <input type="file" accept="application/zip" name="zipfile"/>
                </div>
            </div>
        </div>
        <div class="modal-footer"> 
            <button type="submit" class="b_add b_edit btn btn-primary">Save</button>
            <button type="reset" class="b_add btn">Clear all</button>
            <button type="button" class="b_view b_add b_edit btn" data-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>