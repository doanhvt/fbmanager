<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class chanel_info extends manager_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "chanel_info",
            "view" => "chanel_info",
            "model" => "m_chanel_info",
            "object" => "Ad"
        );
    }

    public function manager($data = array()) {
        $chanel_parent_id = $this->input->get('chanel_parent');
        $chanel_parent = $this->data->get_one($chanel_parent_id);
        $data['title'] = $chanel_parent->name;
        $data["add_link"] = $this->url["add"] . '?chanel_parent=' . $chanel_parent_id;
        $data["ajax_data_link"] = site_url($this->name["class"] . "/ajax_list_data") . '?chanel_parent=' . $chanel_parent_id;
        parent::manager($data);
    }

    public function ajax_list_data($data = array()) {
        $this->data->chanel_parent = $this->input->get('chanel_parent');
        $data['chanel_parent'] = $this->data->chanel_parent;
        parent::ajax_list_data($data);
    }

    public function add($data = array()) {
        $data['chanel_parent'] = $this->input->get('chanel_parent');
        $chanel_parent = $this->data->get_one($data['chanel_parent']);
        $data["title"] = 'Thêm mới mẫu quảng cáo cho kênh - ' . $chanel_parent->name;
        parent::add($data);
    }

    public function edit($id = 0, $data = array()) {
        $rule = $this->data->get_rule();
        $rule['code'] .= " disabled style='background:#ccc' ";
        $this->data->set_rule($rule);
        
        $data['chanel_parent'] = $this->input->get('chanel_parent');
        $faq_info = $this->data->get_one($id);
        $data["title"] = 'Chỉnh sửa mẫu quảng cáo - ' . $faq_info->name;
        parent::edit($id, $data);
    }

    public function add_save($data = array(), $data_return = array(), $re_validate = true) {
        if (sizeof($data) == 0) {
            $data = $this->input->post();
        }
        $data['user_id'] = $this->session->userdata('id');
        parent::add_save($data, $data_return, $re_validate);
    }

    public function edit_save($id = 0, $data = array(), $data_return = array(), $re_validate = true) {
        if (sizeof($data) == 0) {
            $data = $this->input->post();
        }
        $data['user_id'] = $this->session->userdata('id');
        parent::edit_save($id, $data, $data_return, $re_validate);
    }

    public function delete($id = 0, $data = array()) {
        $id = intval($id);
        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return FALSE;
        }
        $data_return["callback"] = "delete_respone";
        if ($this->input->post() || $id > 0) {
            $update = $this->data->update($id, array('status' => 0));
            if ($update) {
                $data_return["list_id"] = Array($id);
                $data_return["state"] = 1;
                $data_return["msg"] = "Xóa bản ghi thành công";
            } else {
                $data_return["list_id"] = Array($id);
                $data_return["state"] = 0;
                $data_return["msg"] = "Bản ghi đã được xóa từ trước hoặc không thể bị xóa. Vui lòng tải lại trang!";
            }

            echo json_encode($data_return);
            return TRUE;
        } else {
            $data_return["state"] = 0;
            $data_return["msg"] = "Id không tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
    }

}
