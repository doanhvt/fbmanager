<div class="container-fluid"> 
    <div class="row-fluid"> 
        <div class="span12"> 
            <div class="widget e_widget">
                <div class="widget-title">
                    <div class="icon"><i class="icon20 i-table"></i></div>
                    <h4>Checking as channel</h4>
                    <div class="actions_content e_actions_content">
                        <a href="<?php echo $view_click_hour_url; ?>" class="btn  i-alarm-2 btn-info"> By time</a>
                        <a href="<?php echo $view_click_chanel_url; ?>" class="btn i-direction btn-info"> By chanel</a>
                        <a href="<?php echo $export_excel; ?>" class="btn i-file-excel btn-info"> Export</a>
                    </div>
                    <a href="#" class="minimize"></a>
                </div>

                <div class="widget-content e_widget_content"  data-loading_img="<?php echo $this->path_theme_file; ?>images/preloaders/loading-spiral.gif">
                    <form action="<?php echo $action_url; ?>" method="post" class="e_ajax_form">
                        <div class="row-fluid1 filter_form">
                            <div class="e_toogle_next_div" style="margin:10px 0px 20px 30px;display: inline-block;font-weight: bold">Filter</div>
                            <a href="#" title='Ẩn/hiện' class="toggle_block minimize e_toogle_next_div"></a>
                            <div class="e_form_search"> 
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="span3" style="width: 265px">
                                            <label style="margin-bottom:0px">
                                                <span>Campaign: 
                                                    <select name="id_campaign" type="text" class="select2 e_btn_submit e_change_camp" data-url="<?php echo $link_change_camp; ?>">
                                                        <option value="0">All</option>
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
                                                        <option value="0">All</option>
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
                                                <span>Chanel: 
                                                    <select name="code_chanel" type="text" class="select2 e_btn_submit e_ass_chanel">
                                                        <option value="0">All</option>
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
                                                <span>From: 
                                                    <input style="width: 100%;" type="datepicker" name="time_begin" class="e_btn_submit e_check time_begin" value="<?php echo $time_begin; ?>" />

                                                    <input name="order" type="hidden" value="" id="i_sort" />
                                                </span> 
                                            </label>
                                        </div>
                                        <div class="span3" style="width: 265px">
                                            <label style="margin-bottom:0px">
                                                <span>To: 
                                                    <input style="width: 100%;" type="datepicker" name="time_end" class="e_btn_submit e_check time_end" value="<?php echo $time_end; ?>" />
                                                </span> 
                                            </label>
                                        </div>
                                    </div>
                                    <div class="span12" style="margin-left: -17px;text-align: right">
                                        <button class="e_btn_search e_btn_search_report btn btn-info add_button" type="submit"> Search </button>
                                        <button class="btn btn-info add_button" type="reset"> Refresh </button>
                                    </div>
                                    <div class="clear" ></div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover  e_table_data">
                        <thead>
                            <tr sort_url="<?php echo site_url("report/view_click_chanel") ?>">
                                <th >No</th>
                                <th >Channel</th>
                                <th >Ads</th>
                                <th class="<?php echo $order_class['number_c2'] ?> sort" order_pos="" field_name="number_c2" order="">Click</br>(C2)</th>
                                <th class="<?php echo $order_class['number_c3'] ?> sort" order_pos="" field_name="number_c3" order="">Submit</br>(C3)</th>
                                <th class="<?php echo $order_class['c3perc2'] ?> sort" order_pos="" field_name="c3perc2" order="">C3/C2 </br>(%)-CB</th>
                            </tr>
                        </thead>
                        <?php if (sizeof($records)) { ?>
                            <tbody>
                                <?php
                                foreach ($records as $key_chanel => $item_record) {
                                    $count_ads = count($item_record->list_ads);
                                    if ($count_ads) {
                                        $i = 0;
                                        foreach ($item_record->list_ads as $key => $item) {
                                            $i++;
                                            ?>
                                            <?php if (count($item_record->list_ads) > 1) { ?>
                                                <?php if ($key == "_sum_"): ?>
                                                    <tr class="gradeX" id="i_<?php echo $key_chanel; ?>" style="">
                                                        <td class="e_chosse_day e_minimize" date='<?php echo $key_chanel; ?>' style="white-space: nowrap;cursor: pointer;background: none"><?php echo $item_record->stt; ?></td>
                                                        <td class="e_chosse_day e_minimize" date='<?php echo $key_chanel; ?>' style="white-space: nowrap;cursor: pointer;background: none"><?php echo $item_record->name; ?></td>
                                                    <?php else: ?>
                                                    <tr class="gradeX e_tr_<?php echo $key_chanel; ?>" style="display: none">  
                                                    <?php endif; ?>  
                                                    <?php if ($i == 1) { ?>
                                                        <td class="e_chosse_day e_maximize" date='<?php echo $key_chanel; ?>' style="white-space: nowrap;cursor: pointer;background: none" rowspan="<?php echo $count_ads; ?>"><?php echo $item_record->stt; ?></td>
                                                        <td class="e_chosse_day e_maximize" date='<?php echo $key_chanel; ?>' style="white-space: nowrap;cursor: pointer;background: none" rowspan="<?php echo $count_ads; ?>"><?php echo $item_record->name; ?></td> 
                                                    <?php } ?>                              
                                                    <td style="border-left: 1px solid #ccc"><?php echo $item->name_chanel; ?></td>
                                                    <td style="text-align: center"><?php echo $item->number_c2; ?></td>
                                                    <td style="text-align: center"><?php echo $item->number_c3; ?></td>
                                                    <td style="text-align: center"><?php echo $item->c3perc2 . '%'; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <?php if ($key_chanel == "_total_sum_"): ?>
                                            <tr class="gradeX" style="font-weight: bold">   
                                            <?php else: ?>
                                            <tr class="gradeX">  
                                            <?php endif; ?>   
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
                        <p class="no_record">No data available</p>
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

            </div>
        </div>
    </div>
</div>