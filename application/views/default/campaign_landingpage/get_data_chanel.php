<tr class="info_landingpage e_title" id="243" style="">
    <td>Tên landingpage</td>
    <td colspan="2">Mẫu quảng cáo</td>
    <td>Từ khóa</td>
    <td style="width: 380px">Link chạy quảng cáo</td>
    <td style="width: 50px"></td>
</tr>
<?php
foreach ($list_campaign_landingpage as $campaign) {
    if ($campaign->info_campain_landingpage) {

        $status = 'disabled';
        $icon = 'i-lock-5';
        $bgr = 'display:block';
        if ($campaign->info_campain_landingpage->status) {
            $status = '';
            $icon = 'i-unlocked-2';
            $bgr = '';
        }
        ?>
        <tr class = 'e_row' id = '<?php echo $campaign->parent_id; ?>'>
            <td>
                <select <?php echo $status; ?> class='select2 e_change_value e_landingpage edit_id_landingpage e_change e_input' name='edit_id_landingpage[]'>
                    <?php foreach ($list_landingpage as $landingpage) { ?>
                        <option url="<?php echo $landingpage->url . '/'; ?>" value="<?php echo $landingpage->id ?>" 
                        <?php
//                        foreach ($list_campaign_landingpage as $campaign2) {
//                            if ($campaign2->info_campain_landingpage && $campaign2->info_campain_landingpage->id_landingpage == $landingpage->id) {
//                                echo 'selected';
//                            }
//                        }
                        ?>
                                ><?php echo $landingpage->name; ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
            <!--=========================-->
            <td colspan = '2' class = 'e_code_mau_qc'>
                <input <?php echo $status; ?> url='?code_chanel=<?php echo $campaign->info_campain_landingpage->code_chanel; ?>' class='e_change_value e_change e_chanel_ads e_input' style='margin-bottom: 0px;min-width: 100px;' readonly='' type='text' value='<?php echo $campaign->info_campain_landingpage->chanel_name; ?>'/>
                <input <?php echo $status; ?> name='edit_code_chanel[]' type='hidden'  class='e_input edit_code_chanel' type='hidden' value='<?php echo $campaign->info_campain_landingpage->code_chanel; ?>'/>
            </td>
            <!--=========================-->
            <td>
                <input class='e_keyword' style='margin-bottom: 0px;min-width: 100px;' name='' readonly='' type='text' value='<?php echo $campaign->info_campain_landingpage->keyword ?>'/>
            </td>
            <!--=========================-->
            <td width='300px'>
                <a style='font-size: 12px;padding-left: 5px;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;width: 380px;' class='e_link_url' href='<?php echo $campaign->info_campain_landingpage->url_landingpage ?>'  target='_blank'><?php echo $campaign->info_campain_landingpage->url_landingpage; ?> </a>
                <input <?php echo $status; ?> type="hidden" class="e_input e_id_campaign_landingpage" name="edit_id[]" value="<?php echo $campaign->info_campain_landingpage->id; ?>"/>
                <input <?php echo $status; ?> type="hidden" id="e_status" class="e_input e_status_check" name="edit_status[]" value="<?php echo $campaign->info_campain_landingpage->status; ?>" />
                <input <?php echo $status; ?> type="hidden" class="e_input e_url" name="edit_url_landingpage[]" value="<?php echo $campaign->info_campain_landingpage->url_landingpage; ?> "/>
            </td>
            <!--=========================-->
            <td class="field_action">
                <span class="delete icon16 <?php echo $icon; ?> e_del_row" id="<?php echo $campaign->info_campain_landingpage->id; ?>" per="1" title="Xóa"></span>
                <span class="icon16 i-checkmark-3 e_save_row"  style="cursor: pointer" title="Lưu"></span>
                <div class="e_lock_flield"><div style="<?php echo $bgr; ?>" class="e_lock"></div></div>
            </td>
            <!--=========================-->
        </tr>
        <?php
    } else {
        $status = 'disabled';
        $icon = 'i-lock-5';
        $bgr = 'display:block';
        ?>
        <tr class='e_row' id='<?php echo $campaign->parent_id ?>'>
            <!--===================================-->
            <td>
                <select <?php echo $status; ?> class='select2 e_change_value e_landingpage id_landingpage e_change e_input' name='id_landingpage[]'>
                    <?php foreach ($list_landingpage as $landingpage) { ?>
                        <option url='<?php echo $landingpage->url; ?>' value='<?php echo $landingpage->id; ?>'><?php echo $landingpage->name; ?></option>
                    <?php } ?>
                </select>
            </td>
            <!--===================================-->
            <td colspan='2' class='e_code_mau_qc'>
                <input <?php echo $status; ?>  url='?code_chanel=<?php echo $campaign->code; ?>' class='e_change_value e_change e_chanel_ads e_input' style='margin-bottom: 0px;min-width: 100px;' readonly='' type='text' value='<?php echo $campaign->name; ?>'/>
                <input <?php echo $status; ?> name='code_chanel[]' type='hidden'  class='e_input code_chanel' value='<?php echo $campaign->code; ?>'/>
            </td>
            <!--===================================-->
            <td>
                <input <?php echo $status; ?> class='e_keyword' style='margin-bottom: 0px;min-width: 100px;' name='' readonly='' type='text' value='<?php echo $campaign->keyword; ?>'/>
            </td>
            <!--//===================================-->
            <td width='300px'>
                <input <?php echo $status; ?> class="e_url e_input" name="url_landingpage[]" readonly="" type="text" value="" />
            </td>
            <!--//===================================-->
            <td class="field_action">
                <span class="delete icon16  <?php echo $icon; ?>  e_del_row"  per="1" title="Xóa"></span>
                <span class="icon16 i-checkmark-3 e_save_row" style="cursor: pointer"  title="Lưu"></span>
                <div class="e_lock_flield"><div style="<?php echo $bgr; ?> " class="e_lock"></div></div>
            </td>
        <?php } ?>

    <?php }
    ?>

