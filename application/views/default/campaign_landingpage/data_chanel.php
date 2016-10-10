
<tr class="info_landingpage e_title e_data_mauqc" id="<?php echo $id_chanel; ?>" style="">
    <td>Landingpage Name</td>
    <td colspan="2">Ads piecce</td>
    <td>Key word</td>
    <td style="width: 380px">URL Destination</td>
    <td style="width: 50px;"></td>
</tr>
<?php
$default = $list_chanel['0'];
$i = 1;
?>
<?php
foreach ($list_chanel as $chanel) {
    $active = 0;
    $status = 'disabled';
    $icon = 'i-lock-5';
    $bgr = 'display:block';
    $status = 0;
    $id_gd = "";
    if ($chanel->id_campaign == $id_campaign) {
        $id_gd = $chanel->id_gd;
    }
    if ($chanel->id_campaign == $id_campaign && $chanel->stage >= 1) {
        $active = 1;
        $status = '';
        $icon = 'i-unlocked-2';
        $bgr = '';
        $status = 1;
    }
    ?>
    <tr class = 'e_row  e_data_mauqc' id = '<?php echo $chanel->parent_id; ?>'>
        <td style="position: relative; cursor: pointer; padding: 1px 7.5px;" class='e_select_page'>
            <?php
            $url = isset($chanel->url) ? $chanel->url : $default->url;
            $id_landingpage = isset($chanel->id_landingpage) ? $chanel->id_landingpage : $default->id_landingpage;
            ?>
            <p id-select="<?php echo "e_" . $i; ?>" url_action="<?php echo site_url('campaign_landingpage/get_data_select'); ?>" class="e_data_url" url="<?php echo $url . "?id_landingpage=" . $id_landingpage; ?>" id_landingpage="<?php echo isset($chanel->id_landingpage) ? $chanel->id_landingpage : $default->id_landingpage; ?>">
                <?php echo isset($chanel->name_landingpage) ? $chanel->name_landingpage : $default->name_landingpage; ?>
                <span class="icon2 i-pencil e_select_page" style="<?php echo ($active == 0) ? "display:none" : "display:block"; ?>"></span>
            </p>
        </td>
        <!--=========================-->
        <td colspan = '2' class = 'e_code_mau_qc'>
            <p class="e_data_chanel" code_chanel="<?php echo $chanel->code; ?>" id_campaign="<?php echo $id_campaign; ?>" id_gd="<?php echo $chanel->id_gd; ?>"><?php echo $chanel->name; ?></p>
        </td>
        <!--=========================-->
        <td  style='margin-bottom: 0px;min-width: 100px;'> 
            <p><?php echo $chanel->keyword; ?></p>
        </td>
        <!--=========================-->
        <td width='300px'>
            <a style='font-size: 12px;padding-left: 5px;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;width: 380px; height: 20px;' class='e_link_url' href='<?php echo ($active == 1) ? $chanel->url_landingpage : ""; ?>'  target='_blank'>
                <?php echo ($active == 1) ? $chanel->url_landingpage : ""; ?>
            </a>
            <input  type="hidden" class="e_input e_id_campaign_landingpage" name="edit_id[]" value="<?php echo $id_gd; ?>"/>
            <input  type="hidden" class="e_input e_status_check" name="edit_status[]" value="<?php echo $status; ?>" />
            <input  type="hidden" class="e_input e_url" name="edit_url_landingpage[]" value="<?php echo $chanel->url_landingpage; ?> "/>

        </td>
        <!--=========================-->
        <td class="field_action" style="padding: 0;">
            <span class="delete icon16 <?php echo $icon; ?> e_del_row" id="" per="1" title="Delete"></span>
            <span class="icon16 i-checkmark-3 e_save_row"  style="cursor: pointer" title="Save"></span>
            <div class="e_lock_flield"><div style="<?php echo $bgr; ?>" class="e_lock"></div></div>
        </td>
        <!--=========================-->
    </tr>
    <?php
    $i++;
}
?>
