<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author Loc
 */
class campaign extends manager_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "campaign",
            "view" => "campaign",
            "model" => "m_campaign",
            "object" => " campaign"
        );
    }

    public function add($data = array()) {
        $this->load->model("m_chanel");
        $data["list_chanel"] = $this->m_chanel->get_list(array('m.parent_id' => 0));
        parent::add($data);
    }

    public function add_save($data = array(), $data_return = array(), $re_validate = true) {
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
        $insert_data = array();
        if (isset($data['list_id'])) {
            foreach ($data['list_id'] as $item) {
                $temp = array();
                $temp['chanel_id'] = $item;
                $insert_data[] = $temp;
            }
        }
        unset($data['list_id']);
        $insert_id = $this->data->add($data);
        $data[$this->data->get_key_name()] = $insert_id;
        if ($insert_id) {
            $this->load->model("m_campain_assign_chanel");
            foreach ($insert_data as $item) {
                $item['campaign_id'] = $insert_id;
                $this->m_campain_assign_chanel->add($item);
            }
            $data_return["key_name"] = $this->data->get_key_name();
            $data_return["record"] = $data;
            $data_return["state"] = 1; /* state = 1 : insert thành công */
            $data_return["msg"] = "Succes";
            $data_return["redirect"] = $this->url["manager"];
            echo json_encode($data_return);
            return $insert_id;
        } else {
            $data_return["state"] = 2; /* state = 2 : Lỗi thêm bản ghi */
            $data_return["msg"] = "Fail, please try again";
            echo json_encode($data_return);
            return FALSE;
        }
    }

    public function edit($id = 0, $data = array()) {

        $data['edit_status'] = 1;
        $this->load->model("m_chanel");
        $data["list_chanel"] = $this->m_chanel->get_list(array('m.parent_id' => 0));
        $this->load->model("m_campain_assign_chanel");
        $data['list_assign_chanel'] = array();
        $list_chanel = $this->m_campain_assign_chanel->get_list(array('m.campaign_id' => $id));
        foreach ($list_chanel as $value) {
            $data['list_assign_chanel'][$value->chanel_id] = $value;
        }
        parent::edit($id, $data);
    }
    
    public function edit_save($id = 0, $data = array(), $data_return = array(), $re_validate = true) {
          $data_return["callback"] = "save_form_edit_response";

        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        $id = intval($id);
        if (!$id) {
            $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
            $data_return["msg"] = "Bản ghi không tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
        if (!$this->data->is_editable($id)) {
            $data_return["state"] = 0;
            $data_return["msg"] = "Bản ghi không thể sửa đổi hoặc bản ghi không còn tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
        if (sizeof($data) == 0) {
            $data = $this->input->post();
        }
        if ($re_validate) {
            $data_all = $this->_validate_form_data($data, $id);

            if (!$data_all["state"]) {
                $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
                $data_return["msg"] = "Dữ liệu gửi lên không hợp lệ";
                $data_return["error"] = $data_all["error"];
                echo json_encode($data_return);
                return FALSE;
            } else {
                $data = $data_all["data"];
            }
        }
        $insert_data = array();
        if (isset($data['list_id'])) {
            foreach ($data['list_id'] as $item) {
                $temp = array();
                $temp['chanel_id'] = $item;
                $insert_data[] = $temp;
            }
        }
        unset($data['list_id']);
        $update = $this->data->update($id, $data);
        if ($id) {
            $this->load->model("m_campain_assign_chanel");
            $this->m_campain_assign_chanel->delete_by_custom(array('campaign_id' => $id));
            foreach ($insert_data as $item) {
                $item['campaign_id'] = $id;
                $this->m_campain_assign_chanel->add($item);
            }
            //==========================================
            $data_return["key_name"] = $this->data->get_key_name();
            $data_return["record"] = $this->_process_data_table($this->data->get_one($id));
            $data_return["state"] = 1; /* state = 1 : insert thành công */
            $data_return["msg"] = "Sửa bản ghi thành công.";
            $data_return["redirect"] = $this->url["manager"];
            echo json_encode($data_return);
            return TRUE;
        } else {
            $data_return["state"] = 2; /* state = 2 : Lỗi thêm bản ghi */
            $data_return["msg"] = "Thêm bản ghi thất bại, vui lòng thử lại sau.";
            echo json_encode($data_return);
            return FALSE;
        }
    }
    
    protected function _add_colum_action($record) {
        $form = $this->data->get_form();
        $dataReturn = Array();
        $dataReturn["schema"] = $form["schema"];
        $dataReturn["rule"] = $form["rule"];
        $dataReturn["colum"] = $form["field_table"];

        /* Thêm cột action */
        $dataReturn["colum"]["custom_action"] = "Action";
        /* Thêm cột check */
        //$dataReturn["colum"]["custom_check"] = "<input type='checkbox' class='e_check_all' />";

        $record = $this->_process_data_table($record);
        $dataReturn["record"] = $record;
        return $dataReturn;
    }

    protected function _process_data_table($record) {
        if (!$record) {
            $record = array();
            return $record;
        }
        $form = $this->data->get_form();
        $key_table = $this->data->get_key_name();
        /* Tùy biến dữ liệu các cột */
        if (is_array($record)) {
            foreach ($record as $key => $valueRecord) {
                $record[$key] = $this->_process_data_table($record[$key]);
            }
        } else {
            $perId = $this->session->userdata('perId');
            $record->custom_action = '<div class="action"><a class="detail e_ajax_link e_middle_btn icon16 i-cog-3 " per="1" href="' . site_url('campaign_landingpage/manager_campaign/' . $record->$key_table) . '" title="Campaign Setup"></a>';
            if ($perId < 3) {
                $record->custom_action .= '<a class="detail icon16 i-address-book-2" per="1" target="_blank"  href="' . site_url('contact/manager') . '?id=' . $record->$key_table . '" title="List contact"></a>';
                if (!isset($record->editable) || (isset($record->editable) && $record->editable)) {
                    $record->custom_action .= '<a class="edit e_ajax_link icon16 i-pencil" per="1" href="' . site_url($this->url["edit"] . $record->$key_table) . '" title="Edit"></i></a>
			<a class="delete e_ajax_confirm e_ajax_link icon16 i-remove" per="1" href="' . site_url($this->url["delete"] . $record->$key_table) . '" title="Delete"></a></div>';
                }
            }
            $record->custom_check = "<input type='checkbox' name='_e_check_all' data-id='" . $record->$key_table . "' />";

            foreach ($form["field_table"] as $keyColum => $valueColum) {
                if (isset($form["rule"][$keyColum])) {
                    if (preg_match("/type\=checkbox/i", $form["rule"][$keyColum])) {
                        if ($record->$keyColum) {
                            $record->$keyColum = "<input type='checkbox' name='" . $keyColum . "' disabled='disabled' checked='checked' />";
                        } else {
                            $record->$keyColum = "<input type='checkbox' name='" . $keyColum . "' disabled='disabled' />";
                        }
                    } elseif (preg_match("/type\=file/i", $form["rule"][$keyColum])) {
                        $record->$keyColum = "<div class='center'><img src='" . $record->$keyColum . "' /></div>";
                    } elseif (preg_match("/type\=datepicker/i", $form["rule"][$keyColum]) || preg_match("/type\=date/i", $form["rule"][$keyColum])) {
                        $temp = strtotime($record->$keyColum);
                        if (preg_match("/type\=datetime/i", $form["rule"][$keyColum])) {
                            $record->$keyColum = date("d-m-Y H:i:s", $temp);
                        } else {
                            $record->$keyColum = date("d-m-Y", $temp);
                        }
                    }
                }
            }
        }
        return $record;
    }

    public function delete($id = 0, $data = array()) {
        $data = array("deleted" => 0);
        $data_return = array();
        $this->data->update($id, $data);
        $data_return["callback"] = "delete_respone";
        $data_return["state"] = 1;
        $data_return["list_id"] = array($id);
        $data_return["msg"] = "Xóa bản ghi thành công";
        echo json_encode($data_return);
    }

}
