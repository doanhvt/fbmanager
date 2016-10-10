<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * User
 * 
 * Description...
 * 
 * @package user
 * @author Pham Trong <phamtrong204@gmail.com>
 * @version 0.0.0
 */
class Admin_account extends manager_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "admin_account",
            "view" => "admin_account",
            "model" => "m_admin_account",
            "object" => "account"
        );
    }

    protected function _validate_form_data($data, $id = 0) {
        if ($id && ($data["userPassword"] != $data["_userPassword"])) {
            if (!isset($data["_userPassword"]) || strlen($data["_userPassword"]) == 0) {
                unset($data["userPassword"]);
                unset($data["_userPassword"]);
            } else {
                $data_return = Array();
                $data_return["state"] = FALSE; /* state = 0 : dữ liệu không hợp lệ */
                $data_return["error"]["_userPassword"] = "Mật khẩu nhập lại không chính xác";
                return $data_return;
            }
        }
        return parent::_validate_form_data($data, $id);
    }

    public function ajax_list_data($data = array()) {
        parent::ajax_list_data($data);
    }

    public function edit($id = 0, $data = array()) {
        $data['is_edit'] = 1;
        parent::edit($id, $data);
    }
    
    public function view_chanel($id = 0, $data = array()) {
        // Neu la admin thi an truong thay doi mat khau
        if($id && $id != $this->session->userdata("id") && $this->session->userdata("perId") == 2){
            $field_form = $this->data->get_field_form();
            unset($field_form["userPassword"]);
            unset($field_form["_userPassword"]);
            $this->data->set_field_form($field_form);
            $this->load->model("m_chanel");
            $this->load->model("m_assign_chanel");
            $array_chanel = $this->m_assign_chanel->get_list(array("user_id" => $id));
            $array_chanel_id = array();
            foreach ($array_chanel as $value) {
                $array_chanel_id[]=$value->chanel_id;
            }
            
            $data["list_chanel"] = array();
            if(count($array_chanel_id)){
                 $data["list_chanel"] = $this->m_chanel->get_list_in($array_chanel_id);
            }
           
        }
        parent::view($id, $data);
    }
    
    public function _process_data_table($record) {
        if (!$record) {
            $record = array();
            return $record;
        }
        $key_table = $this->data->get_key_name();
        /* Tùy biến dữ liệu các cột */
        if (is_array($record)) {
            foreach ($record as $key => $valueRecord) {
                $record[$key] = $this->_process_data_table($record[$key]);
            }
        } else {
            $record = parent::_process_data_table($record);
            if($record->userPermission == 3){
                $record->custom_action = '<div class="action">
                <a class="detail e_ajax_link icon16 i-direction " per="1" href="' . site_url('admin_account/view_chanel/' . $record->$key_table) . '" title="Danh sách kênh được phân"></a>
		<a class="detail e_ajax_link icon16 i-eye-3 " per="1" href="' . site_url($this->url["view"] . $record->$key_table) . '" title="Xem"></a>';
            }  else {
                $record->custom_action = '<div class="action">
		<a class="detail e_ajax_link icon16 i-eye-3 " per="1" href="' . site_url($this->url["view"] . $record->$key_table) . '" title="Xem"></a>';
            }
            if (!isset($record->editable) || (isset($record->editable) && $record->editable)) {
                $record->custom_action .= '<a class="edit e_ajax_link icon16 i-pencil" per="1" href="' . site_url($this->url["edit"] . $record->$key_table) . '" title="Sửa"></i></a>
			<a class="delete e_ajax_confirm e_ajax_link icon16 i-remove" per="1" href="' . site_url($this->url["delete"] . $record->$key_table) . '" title="Xóa"></a></div>';
            }
            $record->custom_check = "<input type='checkbox' name='_e_check_all' data-id='" . $record->$key_table . "' />";
        }
        
        return $record;
        
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */