<div class="container-fluid"> 
    <div class="row-fluid"> 
        <div class="span12"> 
            <div class="widget e_widget">
                <div class="widget-title">
                    <div class="icon"><i class="icon20 i-table"></i></div>
                    <h4>Thống kê view - click</h4>
                    <div class="actions_content e_actions_content">
                        <a href="<?php echo $view_click_hour_url; ?>" class="btn  i-alarm-2 btn-info">Xem theo giờ</a>
                        <a href="<?php echo $view_click_chanel_url; ?>" class="btn i-direction btn-info">Xem theo kênh</a>
                        <a href="<?php echo $export_excel; ?>" class="btn i-file-excel btn-info">Export</a>
                    </div>
                    <a href="#" class="minimize"></a>
                </div>
                <form action="<?php echo $action_url; ?>" method="post" class="e_ajax_form">
                    <div class="widget-content"  data-loading_img="<?php echo $this->path_theme_file; ?>images/preloaders/loading-spiral.gif">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="span3" style="width: 265px">
                                    <label style="margin-bottom:0px">
                                        <span>Chiến dịch: 
                                            <select name="id_campaign" type="text" class="select2 e_btn_submit">
                                                <option value="0">Tất cả</option>
                                                <?php
                                                if ($list_campaign) {
                                                    foreach ($list_campaign as $item) {
                                                        if ($id_campaign == $item->id) {
                                                            ?>
                                                            <option selected="" value="<?php echo $item->id ?>"><?php echo $item->name ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $item->id ?>"><?php echo $item->name ?></option>
                                                        <?php }
                                                        ?>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </label>
                                </div>
                                <div class="span3" style="width: 265px">
                                    <label style="margin-bottom:0px">
                                        <span>Landingpage: 
                                            <select name="id_landingpage" type="text" class="select2 e_btn_submit">
                                                <option value="0">Tất cả</option>
                                                <?php
                                                if ($list_landing_page) {
                                                    foreach ($list_landing_page as $item) {
                                                        if ($id_landingpage == $item->id) {
                                                            ?>
                                                            <option selected="" value="<?php echo $item->id ?>"><?php echo $item->name; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $item->id ?>"><?php echo $item->name; ?></option>
                                                        <?php }
                                                        ?>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </label>
                                </div>
                                <div class="span3" style="width: 265px">
                                    <label style="margin-bottom:0px">
                                        <span>Kênh: 
                                            <select name="code_chanel" type="text" class="select2 e_btn_submit">
                                                <option value="0">Tất cả</option>
                                                <?php
                                                if ($list_chanel) {
                                                    foreach ($list_chanel as $item) {
                                                        if ($code_chanel == $item->code) {
                                                            ?>
                                                            <option selected="" value="<?php echo $item->code ?>"><?php echo $item->name; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $item->code ?>"><?php echo $item->name; ?></option>
                                                        <?php }
                                                        ?>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="span12" style="margin: 5px 0px 0px 0px">
                                <div class="span3" style="width: 265px">
                                    <label style="margin-bottom:0px">
                                        <span>Từ: 
                                            <input style="width: 100%;" type="datepicker" name="time_begin" class="e_btn_submit e_check time_begin" value="<?php echo $time_begin; ?>" />
                                        </span> 
                                    </label>
                                </div>
                                <div class="span3" style="width: 265px">
                                    <label style="margin-bottom:0px">
                                        <span>Đến: 
                                            <input style="width: 100%;" type="datepicker" name="time_end" class="e_btn_submit e_check time_end" value="<?php echo $time_end; ?>" />
                                        </span> 
                                    </label>
                                </div>
                            </div>
                            <div class="span12" style="margin-left: -17px;text-align: right">
                                <button class="e_btn_search btn btn-info add_button" type="submit"> Tìm kiếm </button>
                                <button class="btn btn-info add_button" type="reset"> Làm lại </button>
                            </div>
                            <div class="clear" ></div>
                        </div>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap">Thời gian</th>
                                    <th >Số click</th>
                                    <th >Số submit</th>
                                    <th >Tỷ lệ C3/C2 </br>(%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < 24; $i++) {
                                    ?>
                                    <tr class="gradeX">
                                        <td style="text-align: center"><?php echo $i . 'h'; ?></td>
                                        <td style="text-align: center"><?php echo $records[$i]->total_click; ?></td>
                                        <td style="text-align: center"><?php echo $records[$i]->total_submit; ?></td>
                                        <td style="text-align: center"><?php echo $records[$i]->c3perc2 . '%'; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="text-align: center">Tổng</td>
                                    <td style="text-align: center"><?php echo $records[$i]->total_click; ?></td>
                                    <td style="text-align: center"><?php echo $records[$i]->total_submit; ?></td>
                                    <td style="text-align: center"><?php echo $records[$i]->c3perc2 . '%'; ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>