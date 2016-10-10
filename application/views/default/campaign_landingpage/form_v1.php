<div class="span6 resizable uniform modal-content" style="width:1200px">
    <form class="form-horizontal e_ajax_submit" action="<?php echo $save_link; ?>" enctype="multipart/form-data" method="POST">
        <div class="modal-header"> 
            <span type="button" class="close" data-dismiss="modal"><i class="icon16 i-close-2"></i></span>
            <h3><?php echo 'Thiết lập chiến dịch'; ?></h3>
        </div>
        <div class="modal-body bgwhite">
            <div class="control-group ">
                <table class="data_campaign">
                    <tr class="title_head">
                        <td colspan="6" style="font-weight: bold;text-transform: uppercase">Thông tin chiến dịch</td>
                    </tr>
                    <tr class="info_campaign">
                        <td colspan="3"><b>Mã chiến dịch:</b> <?php echo $info_campaign->code;  ?></td>
                        <td colspan="3"><b>Tên chiến dịch:</b> <?php echo $info_campaign->name; ?></td>
                    </tr>
                    <tr class="info_campaign">
                        <td colspan="3" ><b>Thời gian bắt đầu:</b>  <?php echo $info_campaign->start_time; ?></td>
                        <td colspan="3" ><b>Thời gian kết thúc:</b> <?php echo $info_campaign->end_time; ?></td>
                    </tr>
                    <tr class="title_head">
                        <td colspan="6" style="font-weight: bold;text-transform: uppercase">Thông tin landingpage</td>
                    </tr>
                    <tr class="info_landingpage">
                        <td style="font-weight: bold;">Tên landingpage</td>
                        <td style="width: 150px;font-weight: bold">Tên kênh</td>
                        <td style="font-weight: bold;">Mẫu quảng cáo</td>
                        <td style="font-weight: bold;">Từ khóa</td>
                        <td style="width: 380px;font-weight: bold">Link chạy quảng cáo</td>
                        <td style="width: 20px;font-weight: bold"></td>
                    </tr>
                    <?php if(isset($list_campaign_landingpage)){
                        foreach ($list_campaign_landingpage as $item) { ?>
                        <tr class="e_row">
                            <?php if(isset($array_chanel[$item->parent_id])){ ?>
                                <td>
                                    <select <?php if($item->status){ echo '';}else{ echo 'disabled';} ?> class="e_input select2 e_change_value e_landingpage e_change" name="edit_id_landingpage[]">
                                    <?php foreach ($list_landingpage as $tmp) {
                                        if($tmp->id == $item->id_landingpage){ ?>
                                            <option value="<?php echo $tmp->id;?>" selected="" url='<?php echo $tmp->url; ?>/'><?php echo $tmp->name ?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo $tmp->id;?>" url='<?php echo $tmp->url; ?>/'><?php echo $tmp->name ?></option>
                                        <?php }?>
                                     <?php } ?>
                                </select>
                                </td>
                                <td>
                                    <select <?php if($item->status){ echo '';}else{ echo 'disabled';} ?>  class="e_input select2 e_code_chanel e_change_value" url_mau_qc='<?php echo site_url("chanel/get_list_mau_qc").'?parent_id='; ?>'>
                                        <?php foreach ($list_chanel_parent as $tmp) {
                                            if($item->parent_id == $tmp->id){ ?>
                                                <option id="<?php echo $tmp->id;?>" selected="" value="<?php echo $tmp->id;?>"><?php echo $tmp->name ?></option>
                                            <?php }else{ ?>
                                                <option id="<?php echo $tmp->id;?>" value="<?php echo $tmp->id;?>"><?php echo $tmp->name ?></option>
                                            <?php }?>
                                         <?php } ?>
                                    </select>
                                </td>
                                <td id="i_list_mau_qc" class='e_code_mau_qc'>
                                    <select <?php if($item->status){ echo '';}else{ echo 'disabled';} ?>  name="edit_code_chanel[]" required="" class="e_input select2 e_change_value e_chanel_ads e_change">
                                        <?php foreach ($list_chanel_ads as $tmp) {
                                            if($tmp->parent_id == $item->parent_id){
                                                if($tmp->code == $item->code_chanel){ ?>
                                                    <option keyword="<?php echo $tmp->keyword;?>" selected="" value="<?php echo $tmp->code;?>" url='?code_chanel=<?php echo $tmp->code; ?>'><?php echo $tmp->name ?></option>
                                                <?php }  else { ?>
                                                    <option keyword="<?php echo $tmp->keyword;?>" value="<?php echo $tmp->code;?>" url='?code_chanel=<?php echo $tmp->code; ?>'><?php echo $tmp->name ?></option>
                                                <?php }
                                                } 
                                            } ?>
                                    </select>
                                </td>
                                <td>
                                    <input style="margin-bottom: 0px;min-width: 100px;" class="e_keyword" readonly="" type='text' value="<?php echo $item->keyword; ?>"/>
                                </td>
                                <td>
                                    <input <?php if($item->status){ echo '';}else{ echo 'disabled';} ?> type="hidden" class="e_input e_id_campaign_landingpage" name="edit_id[]" value="<?php echo $item->id;  ?>"/>
                                    <input <?php if($item->status){ echo '';}else{ echo 'disabled';} ?> type="hidden" id="e_status" class="e_input" name="edit_status[]" value="<?php echo $item->status;  ?>"/>
                                    <input <?php if($item->status){ echo 'readonly';}else{ echo 'disabled';} ?>  class="e_input e_url" name="edit_url_landingpage[]" type="hidden" value="<?php echo $item->url_landingpage;  ?>"/>
                                    <a style="font-size: 12px;padding-left: 5px;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;width: 380px;" class="e_link_url" href="<?php echo $item->url_landingpage;  ?>" target="_blank"><?php echo $item->url_landingpage;  ?></a>
                                </td>
                                <td class="field_action">
                                        <span class="delete icon16 <?php if($item->status){ echo 'i-unlocked-2';}else{ echo 'i-lock-5';} ?>  e_del_row" per="1" href="#" id="<?php echo $item->id;  ?>" title="Đóng"></span>
                                        <div class="e_lock_flield"><div style="<?php if(!$item->status){ echo 'display:block';}?>" class="e_lock"></div></div>
                                </td>
                            <?php }  else { ?>
                            <?php } ?>
                            
                        </tr>
                        <?php }
                    } ?>
                    <tr class="field_action">
                        <td colspan="6" style="padding: 10px 5px">
                            <input type="hidden" id="e_url_mau_qc" value="<?php echo $url_mau_qc?>"/>
                            <input type="hidden" id="e_list_id_del" name="id_del"/>
                            <input type="hidden" name="id_campaign" value="<?php echo $info_campaign->id;  ?>"/>
                            <a href="#" id_campaign="<?php echo $info_campaign->id;  ?>" data-url="<?php echo site_url('campaign_landingpage/get_data_row'); ?>" class="e_add_landingpage">+ Thêm thông tin landingpage </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="modal-footer"> 
            <button type="submit" class="b_add b_edit btn btn-primary">Lưu</button>
            <button type="button" class="b_view b_add b_edit btn" data-dismiss="modal">Hủy</button>
        </div>
    </form>
</div>