<div class="container-fluid"> 
    <div class="row-fluid"> 
        <div class="span12"> 
            <div class="widget e_widget">
                <div class="widget-title">
                    <div class="icon"><i class="icon20 i-table"></i></div>
                    <h4>Manage <?php echo $title; ?></h4>
                    <div class="actions_content e_actions_content">
                        <a href="<?php echo $export_contact; ?>" class="btn i-file-excel btn-info e_ajax_link">Export contact new</a>
                        <a href="<?php echo $export_contact_old; ?>" class="btn i-file-excel btn-info">Export contact old</a>
                        <a href="<?php echo $check_crm; ?>" class="e_ajax_link btn btn-info">Check CRM</a>
                    </div>
                    <a href="#" class="minimize"></a>
                </div>
                <div class="widget-content data_table e_data_table table_baocao" data-url="<?php echo $ajax_data_link.$campaign_id; ?>" data-loading_img="<?php echo $this->path_theme_file; ?>images/preloaders/loading-spiral.gif">
                </div>
            </div>
        </div>
    </div>
</div>