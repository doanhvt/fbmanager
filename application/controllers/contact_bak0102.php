<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author Loc
 */
class contact extends manager_base {

    public function __construct() {
        ini_set('memory_limit', '1024M');
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "contact",
            "view" => "contact",
            "model" => "m_contact",
            "object" => "Contact"
        );
    }

    public function _process_data_table($record) {
        if (!$record) {
            $record = array();
            return $record;
        }
        if (is_array($record)) {
            foreach ($record as $key => $valueRecord) {
                $record[$key] = $this->_process_data_table($record[$key]);
            }
        } else {
            $record->editable = 0;
            $record = parent::_process_data_table($record);
            if (isset($record->link_cv) && $record->link_cv) {
                $record->name = '<a target="_blank" style="color:#810c15" href="http://' . $record->link_cv . '">' . $record->name . '</a>';
            }
        }
        return $record;
    }

    protected function _add_colum_action($record) {
        $form = $this->data->get_form();
        $dataReturn = Array();
        $dataReturn["schema"] = $form["schema"];
        $dataReturn["rule"] = $form["rule"];
        $dataReturn["colum"] = $form["field_table"];
        /* Thêm cột check */
        //$dataReturn["colum"]["custom_check"] = "<input type='checkbox' class='e_check_all' />";

        $record = $this->_process_data_table($record);
        $dataReturn["record"] = $record;
        return $dataReturn;
    }

    public function export_contact() {

        $json_conds = $this->session->userdata("contact_conditions");
        $array_conds = json_decode($json_conds, true);

        $this->data->custom_conds = $array_conds;
        $data = $this->data->get_list_contact_handover();
        //Loc contact trung SDT
        $check = array();
        foreach ($data as $item) {
            $check[$item->phone] = $item;
        }
        //Phuc hoi data
        $data = array();
        foreach ($check as $key => $item) {
            $data [] = $item;
        }

        $data_camp = array();
        $data_ld = array();
        $data_chanel = array();
        $this->load->model('m_campaign');
        $this->load->model('m_landing_page');
        $this->load->model('m_chanel');
        $list_camp = $this->m_campaign->get_list();
        $list_ld = $this->m_landing_page->get_list();
        $list_chanel = $this->m_chanel->get_list_chanel_parent();
        foreach ($list_camp as $value) {
            $data_camp[$value->id] = $value;
        }
        foreach ($list_ld as $value) {
            $data_ld[$value->id] = $value;
        }
        foreach ($list_chanel as $value) {
            $data_chanel[$value->id] = $value;
        }

        $data_all = array();
        $stt = 0;
        foreach ($data as $record_item) {
            $stt++;
            $data_row = new stdClass();
            $data_row->stt = $stt;
            $data_row->name = $record_item->name;
            $data_row->email = str_replace(' ', '', $record_item->email);
            $data_row->phone = str_replace(' ', '', $record_item->phone);
            $data_row->country = $record_item->country;
            $data_row->graduation = $record_item->graduation;
            $data_row->sector = $record_item->sector;
            $data_row->datetime_submited = $record_item->datetime_submitted;
            $data_row->campaign = (isset($data_camp[$record_item->id_campaign])) ? $data_camp[$record_item->id_campaign]->code : 'Không xác định';
            $data_row->landingpage = (isset($data_ld[$record_item->id_landingpage])) ? $data_ld[$record_item->id_landingpage]->code : 'Không xác định';
            $data_row->chanel = (isset($data_chanel[$record_item->parent_id])) ? $data_chanel[$record_item->parent_id]->name : 'Không xác định';

            $data_row->mauqc = ($record_item->code_chanel == -100) ? 'Không xác định' : $record_item->code_chanel;
            $data_row->keyword = $record_item->keyword;
            $data_row->http_referer = $record_item->http_referer;
            if ($record_item->link_cv) {
            	if($record_item->link_cv == "N/A"){
            		$data_row->link_cv = "Không có link CV";
            	} else {
                	$data_row->link_cv = 'http://' . $record_item->link_cv;
            	}
            } else {
                $data_row->link_cv = '';
            }
            $data_row->contact_id = $record_item->contact_id;
            $data_row->brithday = $record_item->brithday;
            $data_row->age = $record_item->age;
            if ($data_row->graduation) {
                if ($data_row->graduation == 'HSSV') {
                    $data_row->graduation = 'Đăng là học sinh';
                }
                if ($data_row->graduation == 'THPT') {
                    $data_row->graduation = 'Đã tốt nghiệp THPT';
                }
                if ($data_row->graduation == 'TC') {
                    $data_row->graduation = 'Đã tốt nghiệp Trung cấp';
                }
                if ($data_row->graduation == 'CĐ') {
                    $data_row->graduation = 'Đã tốt nghiệp Cao Đẳng';
                }
                if ($data_row->graduation == 'ĐH') {
                    $data_row->graduation = 'Đã tốt nghiệp Đại Học';
                }
            }
            if ($data_row->sector) {
                if ($data_row->sector == 'BA') {
                    $data_row->sector = 'Quản Trị Kinh Doanh';
                }
                if ($data_row->sector == 'FA') {
                    $data_row->sector = 'Kế Toán';
                }
                if ($data_row->sector == 'IT') {
                    $data_row->sector = 'Công Nghệ Thông Tin';
                }
                if ($data_row->sector == 'FB') {
                    $data_row->sector = 'Tài Chính Ngân Hàng';
                }
                if ($data_row->sector == 'Khac') {
                    $data_row->sector = 'Khác';
                }
            }
            $data_row->line_id = (isset($record_item->line_id)) ? $record_item->line_id : 'N/A';
            $data_all [] = $data_row;
        }


        $time_begin = "date";
        $time_end = "date";

        if (isset($array_conds["custom_where"])) {
            if (isset($array_conds["custom_where"]["datetime_submitted >="])) {
                $time_begin = $array_conds["custom_where"]["datetime_submitted >="];
                $time_begin = date("d-m-Y", strtotime($time_begin));
            }
            if (isset($array_conds["custom_where"]["datetime_submitted <="])) {
                $time_end = $array_conds["custom_where"]["datetime_submitted <="];
                $time_end = date("d-m-Y", strtotime($time_end));
            }
        }

        $this->load->library('excel');
        $sheet = new PHPExcel();

        $objDrawing = new PHPExcel_Worksheet_Drawing();

        $objDrawing->setName('PHPExcel logo');
        $objDrawing->setDescription('PHPExcel logo');
        $path = 'static/images/logo_black.png';
        $objDrawing->setPath($path);
        $objDrawing->setHeight(50);
        $objDrawing->setCoordinates('A2');
        $objDrawing->setOffsetX(0);
        $objDrawing->setOffsetY(0);
        $objDrawing->setWorksheet($sheet->getActiveSheet());
        $style_title = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '810c15'),
                'size' => 28,
                'name' => 'Arial'
        ));
        $sheet->getActiveSheet()->getCell('D2')->setValue('Statistics list of contact');
        $sheet->getActiveSheet()->getCell('E3')->setValue('From ' . $time_begin . " to " . $time_end);
        $sheet->getActiveSheet()->getStyle('D2')->applyFromArray($style_title);

        $data_excel['content'] = $data_all;
        $data_excel['header'] = Array(
            0 => "No.",
            1 => "Name",
            2 => "Email",
            3 => "Phone",
            4 => "Address",
            5 => "Education Level",
            6 => "Major",
            7 => "Time",
            8 => "Campaigns",
            9 => "Landing page",
            10 => "Chanels",
            11 => "Ads",
            12 => "Keyword",
            13 => "Site source",
            14 => "Teacher Link",
            15 => "ContactID",
            16 => "Date of birth",
            17 => "Age",
            18 => 'Line ID'
        );
        $col = 0;
        // In ra header
        foreach ($data_excel['header'] as $field => $value) {
            $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, 6, $value);
            $col++;
        }
        $row = 7;
        $border_all = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_BLACK)
                )
            )
        );
        $border_head = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                )
            )
        );

        // Style cho cac dong cach
        $break_line = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_DARKRED)
                )
            )
        );

        //Fill color header
        $sheet->getActiveSheet()->getStyle('A6:S6')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '810c15')
                    )
                )
        );
        $sheet->getActiveSheet()->getStyle('A6:S6')->getFont()->setBold(true)
                ->setName('Verdana')
                ->setSize(10)
                ->getColor()->setRGB('FFFFFF');

        // Hien thi content
        foreach ($data_excel['content'] as $row_data) {
            $col = 0;
            $i = 0;
            // In ra row
            foreach ($row_data as $cell_data) {
                $sheet->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $cell_data, PHPExcel_Cell_DataType::TYPE_STRING);
                $col++;
            }
            $row++;
        }
        $sheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $sheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $sheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $sheet->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $sheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $sheet->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $sheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $sheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('R')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('S')->setWidth(20);

        $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Contact_' . date('dMy') . '.xls"');
        header('Cache-Control: max-age=0');

        $sheet_writer->save('php://output');
    }

    function get_search_condition($data = array()) {
        if (count($data) == 0) {
            $data = $this->input->get();
        }
        $condition_list = array(
            'name', "keyword", "parent_id", "code_mauqc", "code_campaign", "domain", "from_date", "to_date"
        );
        $where_data = array();
        $like_data = array();
        $link_cv = 0;
        if ($data) {
            foreach ($condition_list as $cond) {
                if (isset($data[$cond])) {
                    switch ($cond) {
                        case 'parent_id':
                            if ($data['parent_id'] != "") {
                                $where_data['qc.parent_id'] = trim($data['parent_id']);
                            }
                            break;
                        case 'code_mauqc':
                            if ($data['code_mauqc'] != "") {
                                $where_data['m.code_chanel'] = trim($data['code_mauqc']);
                            }
                            break;
                        case 'domain':
                            if ($data['domain'] != "") {
                                $where_data['m.domain'] = trim($data['domain']);
                            }
                            break;
                        case 'code_campaign':
                            if ($data['code_campaign'] != "") {
                                if ($data['code_campaign'] == -100) {
                                    $where_data['m.id_camp_landingpage <='] = 0;
                                } else {
                                    $where_data['camp.code'] = trim($data['code_campaign']);
                                }
                            }
                            break;
                        case 'name':
                            if (trim($data['name']) != "") {
                                $like_data['m.name'] = trim($data['name']);
                                $like_data['email'] = trim($data['name']);
                                $like_data['phone'] = trim($data['name']);
                            }
                            break;
                        case 'keyword':
                            if (trim($data['keyword']) != "") {
                                $like_data['qc.keyword'] = trim($data['keyword']);
                            }
                            break;
                        case 'from_date':
                            if (trim($data['from_date']) != "") {
                                $where_data['datetime_submitted_unix >='] = strtotime($data['from_date']);
                            }
                            break;
                        case 'to_date':
                            if (trim($data['to_date']) != "") {
                                $where_data['datetime_submitted_unix <='] = strtotime($data['to_date'] . ' 23:59:59');
                            }
                            break;
                    }
                }
            }
        }

        $data_return = array(
            "custom_where" => $where_data,
            "custom_like" => $like_data,
        );
        $this->session->set_userdata("contact_conditions", json_encode($data_return));
        return $data_return;
    }

    public function ajax_list_data($data = Array()) {
        $campaign_id = $this->input->get('id');
        $data_get = $this->input->get();
        $this->data->campaign_id = $campaign_id;
        $this->data->check_status_filter_day = 1;
        if ($data_get) {
            $data["form_conds"] = $this->data->custom_conds = $this->get_search_condition($data_get);
        } else {
            $json_conds = $this->session->userdata("contact_conditions");
            $data["form_conds"] = $this->data->custom_conds = json_decode($json_conds, true);
        }
        $data["form_url"] = site_url("contact/ajax_list_data");
        $this->load->model("m_chanel");
        $this->load->model("m_campaign");
        $this->load->model("m_landing_page");
        $data["list_campaign"] = $this->m_campaign->get_list();
        $data["list_landingpage"] = $this->m_landing_page->get_list();

        $data["list_chanel"] = $this->m_chanel->get_list_chanel_parent();

        $parent_id = isset($data["form_conds"]["custom_where"]["qc.parent_id"]) ?
                $data["form_conds"]["custom_where"]["qc.parent_id"] : null;
        if ($parent_id) {
            $data["list_mauqc"] = $this->m_chanel->get_list_mauqc(array("parent_id" => $parent_id));
        } else {
            $data["list_mauqc"] = $this->m_chanel->get_list_mauqc();
        }
        parent::ajax_list_data($data);
    }

    public function manager($data = Array()) {
        $data['campaign_id'] = $this->input->get('id');
        if ($data['campaign_id']) {
            $data['campaign_id'] = '?id=' . $data['campaign_id'];
        } else {
            $data['campaign_id'] = '';
        }
        $data['export_contact'] = site_url("contact/export_contact");
        parent::manager($data);
    }

    protected function get_left_content($data = Array()) {
        $data["menu_data"] = $this->_get_left_content_data();
        return $this->load->view($this->site_config->path_theme_view . "contact/left_content", $data, TRUE);
    }

    protected function master_page($content, $head_page = NULL, $title = NULL, $description = NULL, $keywords = NULL, $canonical = NULL) {
        $data = Array();
        /* Lấy thông tin phần head */
        $data["title"] = $title ? $title : "Manager - Topmito";

        $data["description"] = $description ? $description : "Manager - Topmito"; //THông tin này về sau sẽ cho vào cơ sở dữ liệu
        $data["keywords"] = "Manager - Topmito, " . $keywords;
        $data["canonical"] = $canonical ? $canonical : NULL;
        $data["icon"] = $this->site_config->favicon_link;
        /* head chung của các masterPage */ $data["header_base"] = $this->load->view($this->site_config->path_theme_view . "base_master/head", $data, TRUE);
        /* head riêng của các masterPage */
        $data["header_master_page"] = "";
        /* head riêng của các từng page */
        $data["header_page"] = $head_page ? $head_page : "";

        /* Lấy thông tin phần html */
        $data["header"] = $this->get_header();
        $data["menu_bar"] = $this->get_menu_bar();
        $data["breadcrumb"] = $this->get_breadcrumb();
        $data["content"] = $content;
        $data["left_content"] = $this->get_left_content();
        $data["right_content"] = $this->get_right_content();
        $data["footer"] = $this->get_footer();

        $this->load->view($this->site_config->path_theme_view . "contact/master_page", $data);
    }

    public function update_contact_id() {
        $this->load->model('m_contact');
        $list_contact = $this->m_contact->get_contact();
        foreach ($list_contact as $item) {
            $contact_id = date('Ymd', $item->datetime_submitted_unix) . substr(str_pad($item->id, 6, '0', STR_PAD_LEFT), -6);
            $this->m_contact->update($item->id, array('contact_id' => $contact_id));
        }
        echo 'Xong';
    }
    
    public function update_tl(){
        $this->load->model('m_test');
        $list = $this->m_test->get_list();
//        echo '<pre>';
//        var_dump($list);exit;
        foreach ($list as $value) {
            $c = $this->m_test->get_phone($value->phone);
            if($c){
                $this->m_test->update($value->id,array('contact_id' => $c->contact_id.'TL'));
                echo $value->phone .' - Cap nhat thanh cong';
            }
        }
    }

}
