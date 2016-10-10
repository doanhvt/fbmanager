<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author Loc
 */
class log extends manager_base {

    public function __construct() {
        ini_set('memory_limit', '1024M');
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "log",
            "view" => "log",
            "model" => "m_log",
            "object" => "Log Report"
        );
    }

    protected function _add_colum_action($record) {
        $form = $this->data->get_form();
        $dataReturn = Array();
        $dataReturn["schema"] = $form["schema"];
        $dataReturn["rule"] = $form["rule"];
        $dataReturn["colum"] = $form["field_table"];

        /* Thêm cột action */
        //$dataReturn["colum"]["custom_action"] = "Action";
        /* Thêm cột check */
        //$dataReturn["colum"]["custom_check"] = "<input type='checkbox' class='e_check_all' />";

        $record = $this->_process_data_table($record);
        $dataReturn["record"] = $record;
        return $dataReturn;
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
            $record = parent::_process_data_table($record);
            $record->url_landingpage = "<a style='word-break: break-all;text-align:center;display:inline-block' target='_blank' href='" . $record->url_landingpage . "&preview=preview_mode" . "'>$record->url_landingpage</a>";
        }

        return $record;
    }

    public function ajax_list_data($data = Array()) {
        date_default_timezone_set('Asia/Ho_Chi_Minh'); //setup lai timezone
        $date = date('Y-m-d', time());
        $data['from_date'] = $date;
        $data['to_date'] = $date;
        $data_get = $this->input->get();
//        if ($this->input->post("order") != "") {
//            $json_conds = $this->session->userdata("conditions_log");
//            if ($json_conds) {
//                $data["form_conds"] = $this->data->custom_conds = json_decode($json_conds, true);
//            } else {
//                $data["form_conds"] = $this->data->custom_conds = $this->get_search_condition();
//            }
//        } else {
//            $data["form_conds"] = $this->data->custom_conds = $this->get_search_condition();
//        }
        $data["form_conds"] = $this->data->custom_conds = $this->get_search_condition($data_get);

        $data["form_url"] = site_url("log/ajax_list_data");
        $this->load->model("m_chanel");
        $this->load->model("m_campaign");
        $data["list_chanel"] = $this->m_chanel->get_list();
        $data["list_campaign"] = $this->m_campaign->get_list();
        parent::ajax_list_data($data);
    }

    function get_search_condition($data_get) {
        $data = $this->input->get();
        $condition_list = array(
            "id_campaign", "code_chanel", "from_date", "to_date"
        );
        $where_data = array();
        $like_data = array();
        if ($data) {
            foreach ($condition_list as $cond) {
                if (isset($data[$cond])) {
                    switch ($cond) {
                        case 'code_chanel':
                            if ($data['code_chanel'] != "") {
                                $where_data['c.parent_id'] = trim($data['code_chanel']);
                            }
                            break;
                        case 'id_campaign':
                            if (trim($data['id_campaign']) != "") {
                                $where_data['id_campaign'] = trim($data['id_campaign']);
                            }
                            break;
                        case 'from_date':
                            if (trim($data['from_date']) != "") {
                                $time_begin_unix = strtotime($data['from_date']);
                                $where_data['datetime_unix >='] = $time_begin_unix;
                            }
                            break;
                        case 'to_date':
                            if (trim($data['to_date']) != "") {
                                $time_end_unix = strtotime($data['to_date'] . ' 23:59:59');
                                $where_data['datetime_unix <='] = $time_end_unix;
                            }
                            break;
                    }
                }
            }
        } else {
            date_default_timezone_set('Asia/Ho_Chi_Minh'); //setup lai timezone
            $date = date('Y-m-d', time());
            $time_begin_unix = strtotime($date);
            $time_end_unix = strtotime($date . ' 23:59:59');
            $where_data['datetime_unix >='] = $time_begin_unix;
            $where_data['datetime_unix <='] = $time_end_unix;
        }


        $data_return = array(
            "custom_where" => $where_data,
            "custom_like" => $like_data
        );
        $this->session->set_userdata("conditions_log", json_encode($data_return));
        return $data_return;
    }

    function check_ip() {
        $list_ip = $this->data->get_list(array(
            'm.datetime >=' => '2014-12-17 10:00:00',
            'm.datetime <=' => '2014-12-17 10:59:59',
        ));
        $arr_ip = array();
        foreach ($list_ip as $item) {
            $arr_ip[$item->ip][] = 1;
        }
        $total = 0;
        foreach ($arr_ip as $key => $item) {
            echo '<pre>';
            $total += count($item);
            var_dump($key .'- [' .count($item).']');
        }
//        var_dump(count($list_ip));exit;
        exit;
    }

}
