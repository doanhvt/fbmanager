<div class="container-fluid"> 
    <div class="row-fluid"> 
        <div class="span12"> 
            <div class="widget e_widget">
                <div class="widget-title">
                    <div class="icon"><i class="icon20 i-table"></i></div>
                    <h4>Report</h4>
                    <div class="actions_content e_actions_content">
                        <a href="<?php echo $export_excel; ?>" class="btn i-file-excel btn-info"> Export</a>
                    </div>
                    <a href="#" class="minimize"></a>
                </div>
                <div class="widget-content e_widget_content"  data-loading_img="<?php echo $this->path_theme_file; ?>images/preloaders/loading-spiral.gif">
                    <form action="<?php echo $action_url; ?>" method="post" class="e_ajax_form">
                        <div class="row-fluid1 filter_form">
                            <div class="e_toogle_next_div" style="margin:10px 0px 20px 30px;display: inline-block;font-weight: bold">Filter</div>
                            <a href="#" title='Hide/show' class="toggle_block minimize e_toogle_next_div"></a>
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
                                            <label style="margin-bottom: 0px">
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
                                        <button class="e_btn_search btn btn-info add_button" type="submit"> Search </button>
                                        <button class="btn btn-info add_button" type="reset"> Refresh </button>
                                    </div>
                                    <div class="clear" ></div>
                                </div>
                            </div>
                        </div>
                    </form>    
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover e_table_data">
                        <thead>
                            <tr>
                                <th >Date</th>
                                <th >Channel</th>
                                <th >Cost per day(VND)</th>
                                <th >C1</th>
                                <th >C2</th>
                                <th >Cost of C2(VND)</th>
                                <th >C2/C1 ratio </br>(%)-CTR</th>
                                <th >C3</th>
                                <th >Cost of C3 (VND)</th>
                                <th >C3/C2 ratio</br> (%)-CR</th>
                                <th >Density of C3</br>(%)</th>
                                <th >Note</th>
                            </tr>
                        </thead>
                        <?php if (sizeof($records)) { ?>
                            <tbody>
                                <?php
                                foreach ($records as $key => $item_record) {
                                    $i = 0;
                                    $count_chanel = count($item_record->list_chanel);
                                    foreach ($item_record->list_chanel as $key_chanel => $item) {
                                        $i++;
                                        if ($count_chanel == 1) { ?>
                                            <tr class="gradeX e_tr_<?php echo $key; ?>" style="">  
                                                <?php if ($i == 1): ?>
                                                    <td class="e_chosse_day e_maximize" date='<?php echo $key; ?>' rowspan="<?php echo $count_chanel; ?>" style="white-space: nowrap;font-weight: bold;cursor: pointer"><?php echo $key; ?></td>
                                                <?php endif; ?>
                                                <td style="border-left: 1px solid #ccc;white-space: nowrap"><?php echo $item['name_chanel']; ?></td>
                                                <td style="text-align: center"><?php echo $item["cost_all"]; ?></td> <!--cost/ngay-->
                                                <td style="text-align: center"><?php echo $item['number_c1']; ?></td><!--So C1-->
                                                <td style="text-align: center"><?php echo $item['number_c2']; ?></td><!--So C2-->
                                                <td style="text-align: center"><?php echo $item['cost_c2'] ?></td><!--Gia C2-->
                                                <td style="text-align: center"><?php echo $item['c2/c1'] ?></td><!--C2/C1-->
                                                <td style="text-align: center"><?php echo $item['number_c3']; ?></td><!--So C3-->
                                                <td style="text-align: center"><?php echo $item['cost_c3'] ?></td><!--Gia C3-->
                                                <td style="text-align: center"><?php echo $item['c3/c2'] ?></td><!--C3/C2-->
                                                <td style="text-align: center"><?php echo $item['c3/total_c3'] ?></td><!--C3/C2-->
                                                <td style="text-align: center"><?php echo $item['note']; ?></td><!--C3/C2-->
                                            </tr>
                                        <?php } else { ?>
                                            <?php if ($key_chanel == "_sum_"): ?>
                                            <tr class="gradeX" id="i_<?php echo $key; ?>" style="">
                                                    <td class="e_chosse_day e_minimize" date='<?php echo $key; ?>' style="white-space: nowrap;cursor: pointer;font-weight: bold"><?php echo $key; ?></td>
                                            <?php else: ?>
                                            <tr class="gradeX e_tr_<?php echo $key; ?>" style="display: none">  
                                            <?php endif; ?>
                                                <?php if ($i == 1): ?>
                                                    <td class="e_chosse_day e_maximize" date='<?php echo $key; ?>' rowspan="<?php echo $count_chanel; ?>" style="white-space: nowrap;font-weight: bold;cursor: pointer"><?php echo $key; ?></td>
                                                <?php endif; ?>
                                                <td style="border-left: 1px solid #ccc;white-space: nowrap"><?php echo $item['name_chanel']; ?></td>
                                                <td style="text-align: center"><?php echo $item["cost_all"]; ?></td> <!--cost/ngay-->
                                                <td style="text-align: center"><?php echo $item['number_c1']; ?></td><!--So C1-->
                                                <td style="text-align: center"><?php echo $item['number_c2']; ?></td><!--So C2-->
                                                <td style="text-align: center"><?php echo $item['cost_c2'] ?></td><!--Gia C2-->
                                                <td style="text-align: center"><?php echo $item['c2/c1'] ?></td><!--C2/C1-->
                                                <td style="text-align: center"><?php echo $item['number_c3']; ?></td><!--So C3-->
                                                <td style="text-align: center"><?php echo $item['cost_c3'] ?></td><!--Gia C3-->
                                                <td style="text-align: center"><?php echo $item['c3/c2'] ?></td><!--C3/C2-->
                                                <td style="text-align: center"><?php echo $item['c3/total_c3'] ?></td><!--C3/C2-->
                                                <td style="text-align: center"><?php echo $item['note']; ?></td><!--C3/C2-->
                                            </tr>
                                        <?php }
                                        ?>

                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th >Total</th>
                                    <th ></th>
                                    <th style="text-align: center"><?php echo $records['total_sys']->cost; ?></th>
                                    <th style="text-align: center"><?php echo $records['total_sys']->total_c1; ?></th>
                                    <th style="text-align: center"><?php echo $records['total_sys']->total_c2; ?></th>
                                    <th style="text-align: center"><?php echo $records['total_sys']->cost_c2; ?></th>
                                    <th style="text-align: center"><?php echo $records['total_sys']->c2_c1; ?></th>
                                    <th style="text-align: center"><?php echo $records['total_sys']->total_c3; ?></th>
                                    <th style="text-align: center"><?php echo $records['total_sys']->cost_c3; ?></th>
                                    <th style="text-align: center"><?php echo $records['total_sys']->c3_c2; ?></th>
                                    <th ></th>
                                    <th ></th>
                                </tr>
                            </tfoot>
                        <?php } ?>
                    </table>
                    <?php if (!sizeof($records)) { ?>
                        <p class="no_record">No data available in table</p>
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