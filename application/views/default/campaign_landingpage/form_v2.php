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
                        <td colspan="6" style="font-weight: bold;text-transform: uppercase">Thiết lập thông tin</td>
                    </tr>
                </table>
                <table class="data_campaign e_data_campaign">
                    <?php if(isset($list_campaign_landingpage)){
                        foreach ($list_campaign_landingpage as $item)  { ?>
                            <tr class="info_campaign">
                                <td colspan="6" id='<?php echo $item->parent_id; ?>' class="e_chanel_plus row_chanel" data-url='<?php echo site_url('campaign_landingpage/get_data_chanel').'?id_chanel='.$item->parent_id.'&id_campaign='.$item->id_campaign; ?>'><span>+ </span><?php echo 'Kênh '. $item->parent_name. ' ('.$item->total.')' ?></td>
                            </tr>
                        <?php }
                    } ?>
                        <tr>
                            <td colspan="6" style="padding: 15px 5px"></td>
                        </tr>
                        <tr class="field_action">
                            <td colspan="6" style="padding: 10px 5px">
                                <input type="hidden" id="e_url_mau_qc" value="<?php echo $url_mau_qc?>"/>
                                <input type="hidden" id="e_list_id_del" name="id_del"/>
                                <input type="hidden" name="id_campaign" value="<?php echo $info_campaign->id;  ?>"/>
                                <a href="#" id_campaign="<?php echo $info_campaign->id;  ?>" data-url="<?php echo site_url('campaign_landingpage/get_data_row'); ?>" class="e_add_landingpage">+ Thêm thiết lập </a>
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