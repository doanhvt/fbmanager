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
                        <td colspan="6">Thông tin chiến dịch</td>
                    </tr>
                    <tr class="info_campaign">
                        <td colspan="2">Mã chiến dịch: <?php echo $info_campaign->code;  ?></td>
                        <td colspan="2">Tên chiến dịch: <?php echo $info_campaign->name; ?></td>
                    </tr>
                    <tr class="info_campaign">
                        <td colspan="2" >Thời gian bắt đầu:  <?php echo $info_campaign->start_time; ?></td>
                        <td colspan="2" >Thời gian kết thúc: <?php echo $info_campaign->end_time; ?></td>
                    </tr>
                    <tr class="title_head">
                        <td colspan="6">Thông tin landingpage</td>
                    </tr>
                    <tr class="info_landingpage">
                        <td>Tên landingpage</td>
                        <td>Tên kênh</td>
                        <td>Mẫu quảng cáo</td>
                        <td>Từ khóa</td>
                        <td style="width: 380px">Link chạy quảng cáo</td>
                        <td style="width: 20px"></td>
                    </tr>
                    <?php if(isset($list_campaign_landingpage)){
                        foreach ($list_campaign_landingpage as $item) { ?>
                        <tr class="e_row">
                            <td>
                                <select class="select2 e_change_value e_landingpage" name="edit_id_landingpage[]">
                                    <?php foreach ($list_landingpage as $tmp) {
                                        if($tmp->id == $item->id_landingpage){ ?>
                                            <option value="<?php echo $tmp->id;?>" selected="" url='http://mol.topmito.edu.vn/nav/?redirect=http://mol.topmito.edu.vn/<?php echo $tmp->url; ?>/'><?php echo $tmp->name ?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo $tmp->id;?>" url='http://mol.topmito.edu.vn/nav/?redirect=http://mol.topmito.edu.vn/<?php echo $tmp->url; ?>/'><?php echo $tmp->name ?></option>
                                        <?php }?>
                                     <?php } ?>
                                </select>
                            </td>
                            <td>
                                <select class="select2 e_change_value e_code_chanel" name="edit_code_chanel[]" url_mau_qc="<?php echo $url_mau_qc."?parent_id="?>">
                                    <?php foreach ($list_chanel as $tmp) {
                                        if($tmp->code == $item->code_chanel){ ?>
                                            <option id="<?php echo $tmp->id;?>" selected="" value="<?php echo $tmp->code;?>" url='&code_chanel=<?php echo $tmp->code; ?>'><?php echo $tmp->name ?></option>
                                        <?php }else{ ?>
                                            <option id="<?php echo $tmp->id;?>" value="<?php echo $tmp->code;?>" url='&code_chanel=<?php echo $tmp->code; ?>'><?php echo $tmp->name ?></option>
                                        <?php }?>
                                     <?php } ?>
                                </select>
                            </td>
                            <td id="i_list_mau_qc" class='e_code_mau_qc'>
                                <select name="code_mau_qc[]" class="select2">
                                    <option value="">Không có mẫu nào</option>
                                </select>
                            </td>
                            <td class='e_keyword'>
                                <p>Từ khóa</p>
                            </td>
                            <td>
                                <input type="hidden" class="e_id_campaign_landingpage" name="edit_id[]" value="<?php echo $item->id;  ?>"/>
                                <input class="e_url" name="edit_url_landingpage[]" readonly="" type="hidden" value="<?php echo $item->url_landingpage;  ?>"/>
                                <a style="font-size: 12px;padding-left: 5px;display: inline-block" class="e_link_url" href="<?php echo $item->url_landingpage;  ?>" target="_blank"><?php echo $item->url_landingpage;  ?></a>
                            </td>
                            <td>
                                <a class="delete icon16 i-remove e_del_row" per="1" href="#" id="<?php echo $item->id;  ?>" title="Xóa"></a>
                            </td>
                        </tr>
                        <?php }
                    } ?>
                    <tr class="field_action">
                        <td colspan="4" style="padding: 10px 5px">
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