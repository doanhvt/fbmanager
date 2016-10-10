<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author Loc
 */
class chanel_cost extends manager_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "chanel_cost",
            "view" => "chanel_cost",
            "model" => "m_chanel_cost",
            "object" => "Cost/C1"
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
            $record = parent::_process_data_table($record);
            $record->cost = number_format($record->cost, 0, ",", ".");
        }

        return $record;
    }

    public function edit($id = 0, $data = array()) {
        $this->load->model("m_chanel");
        $data['list_chanel'] = $this->m_chanel->get_list();

        parent::edit($id, $data);
    }

    public function add($data = array()) {
        $this->load->model("m_chanel");
        $data['list_chanel'] = $this->m_chanel->get_list();
        
        parent::add($data);
    }
    
    public function add_save($data = Array(), $data_return = Array(), $re_validate = true) {
        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        $data_return["callback"] = "save_form_add_response";
        if (sizeof($data) == 0) {
            $data = $this->input->post();
        }
        if ($re_validate) {
            $data_all = $this->_validate_form_data($data);
            if (!$data_all["state"]) {
                $data_return["data"] = $data;
                $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
                $data_return["msg"] = "Dữ liệu gửi lên không hợp lệ";
                $data_return["error"] = $data_all["error"];
                echo json_encode($data_return);
                return FALSE;
            } else {
                $data = $data_all["data"];
            }
        }
        $check = $this->data->get_one(array('m.code_chanel'=>$data['code_chanel'],'m.date'=>$data['date']));
        if($check){
            $data_return["state"] = 0; 
            $data_return["msg"] = "Ngày hôm nay bạn đã nhận chi phí cho kênh ".$check->name;
            echo json_encode($data_return);
            return exit;
        }
        $insert_id = $this->data->add($data);
        $data[$this->data->get_key_name()] = $insert_id;
        if ($insert_id) {
            $data_return["key_name"] = $this->data->get_key_name();
            $data_return["record"] = $data;
            $data_return["state"] = 1; /* state = 1 : insert thành công */
            $data_return["msg"] = "Thêm bản ghi thành công";
            $data_return["redirect"] = $this->url["manager"];
            echo json_encode($data_return);
            return $insert_id;
        } else {
            $data_return["state"] = 2; /* state = 2 : Lỗi thêm bản ghi */
            $data_return["msg"] = "Thêm bản ghi thất bại, vui lòng thử lại sau";
            echo json_encode($data_return);
            return FALSE;
        }
    }

}
