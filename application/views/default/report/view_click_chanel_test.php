<div class="container-fluid"> 
    <div class="row-fluid"> 
        <div class="span12"> 
            <div class="widget e_widget">
                <div class="widget-title">
                    <div class="icon"><i class="icon20 i-table"></i></div>
                    <h4>Thống kê view - click theo kênh</h4>
                    <div class="actions_content e_actions_content">
                        <a href="<?php echo $view_click_hour_url; ?>" class="btn  i-alarm-2 btn-info"> Xem theo giờ</a>
                        <a href="<?php echo $view_click_chanel_url; ?>" class="btn i-direction btn-info"> Xem theo kênh</a>
                        <a href="<?php echo $export_excel; ?>" class="btn i-file-excel btn-info"> Export</a>
                    </div>
                    <a href="#" class="minimize"></a>
                </div>
                <form action="<?php echo $action_url; ?>" method="post" class="e_ajax_form">
                    <div class="widget-content"  data-loading_img="<?php echo $this->path_theme_file; ?>images/preloaders/loading-spiral.gif">
                        <div class="row-fluid1 filter_form">
                            <div class="e_toogle_next_div" style="margin:10px 0px 20px 30px;display: inline-block;font-weight: bold">Lọc</div>
                            <a href="#" title='Ẩn/hiện' class="toggle_block minimize e_toogle_next_div"></a>
                            <div class="e_form_search"> 
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
                                                                if ($code_chanel == $item->id) {
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
                            </div>
                        </div>

                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr sort_url="<?php echo site_url("report_order/view_click_chanel")?>">
                                    <th >STT</th>
                                    <th >Tên kênh</th>
                                    <th >Mẫu quảng cáo</th>
                                    <th class="sorting" order_pos="" field_name="number_c2" order="">Số click</br>(C2)</th>
                                    <th class="sorting" order_pos="" field_name="number_c3" order="">Số submit</br>(C3)</th>
                                    <th class="sorting" order_pos="" field_name="c3perc2" order="">Tỷ lệ C3/C2 </br>(%)</th>
                                </tr>
                            </thead>
                            <?php if (sizeof($records)) { ?>
                                <tbody>
                                    <?php
                                    foreach ($records as $item_record) {
                                        $count_ads = count($item_record->list_ads);
                                        if ($count_ads) {
                                            $i = 0;
                                            foreach ($item_record->list_ads as $item) {
                                                $i++;
                                                ?>
                                                <tr class="gradeX">  
                                                    <?php if ($i == 1) { ?>
                                                        <td rowspan="<?php echo $count_ads; ?>"><?php echo $item_record->stt; ?></td>
                                                        <td rowspan="<?php echo $count_ads; ?>"><?php echo $item_record->name; ?></td> 
                                                    <?php } ?>                              
                                                    <td style="border-left: 1px solid #ccc"><?php echo $item->name_chanel; ?></td>
                                                    <td style="text-align: center"><?php echo $item->number_c2; ?></td>
                                                    <td style="text-align: center"><?php echo $item->number_c3; ?></td>
                                                    <td style="text-align: center"><?php echo $item->c3perc2 . '%'; ?></td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr class="gradeX">  
                                                <td><?php echo $item_record->stt; ?></td>
                                                <td><?php echo $item_record->name; ?></td>            
                                                <td><?php echo ''; ?></td>
                                                <td style="text-align: center"><?php echo isset($item_record->number_c2) ? $item_record->number_c2 : 0; ?></td>
                                                <td style="text-align: center"><?php echo isset($item_record->number_c3) ? $item_record->number_c3 : 0; ?></td>
                                                <td style="text-align: center"><?php echo isset($item_record->c3perc2) ? $item_record->c3perc2 : 0; ?>%</td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                        <?php } ?>
                        </table>
                        <?php if (!sizeof($records)) { ?>
                            <p class="no_record">Không có bản ghi nào thỏa mãn yêu cầu</p>
<?php } else { ?>
                            <div class="row-fluid no-magin">
                                <div class="span6">
                                </div>
                                <div class="span6">
                                    <div class="dataTables_paginate paging_bootstrap pagination">
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
<?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>