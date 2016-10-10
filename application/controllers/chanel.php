<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author Loc
 */
class chanel extends manager_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "chanel",
            "view" => "chanel",
            "model" => "m_chanel",
            "object" => "chanel"
        );
    }

    public function add($data = array()) {
        $parent_id = $this->input->get("parent_id");
        if ($parent_id && is_numeric($parent_id) && $parent_id > 0) {
            $data["parent_id"] = $parent_id;
            $data["parent_info"] = $this->data->get_one(array("id" => $parent_id));
        } else {
            $data["parent_id"] = 0;
        }
        $this->load->model("m_admin_account");
        $data["list_user"] = $this->m_admin_account->get_list(array('m.userPermission' => 3));
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
        $insert_data = array();
        if (isset($data['list_id'])) {
            foreach ($data['list_id'] as $item) {
                $temp = array();
                $temp['user_id'] = $item;
                $insert_data[] = $temp;
            }
        }
        unset($data['list_id']);
        $data['code']  = str_replace(" ", "_", $data["code"]);
        $data['user_id'] = $this->session->userdata("id");
        $insert_id = $this->data->add($data);
        $data[$this->data->get_key_name()] = $insert_id;
        if ($insert_id) {
            $this->load->model("m_assign_chanel");
            //ducanh == thu gui tin nhan khi duoc assign
            $this->load->model("m_admin_account", "ad_account");
            $this->load->library("Rest_Client");
            $this->rest_client->initialize($this->config->item("sms"));
            $uri = "Sms/Add";
            foreach ($insert_data as $item) {
                $item['chanel_id'] = $insert_id;
                $this->m_assign_chanel->add($item);
                // Gui tin nhan
                $user = $this->ad_account->get_one($item["user_id"]);
                if ($user && $user->userPhone != "") {
                    $param = Array(
                        "sendTo" => $user->userPhone,
                        "content" => "Ban vua duoc nguoi Quan ly MOL phan kenh " . $data["name"] . ", truy cap vao tai khoan cua ban de xem them",
                    );
                    $result = $this->rest_client->post($uri, $param);
                }
            }

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

    public function edit($id = 0, $data = array()) {
        $data['edit_status'] = 1;
        $this->load->model("m_admin_account");
        $data["list_user"] = $this->m_admin_account->get_list(array('m.userPermission' => 3));
        $this->load->model("m_assign_chanel");
        $data['list_assign_chanel'] = array();
        $list_chanel = $this->m_assign_chanel->get_list(array('m.chanel_id' => $id));
        foreach ($list_chanel as $value) {
            $data['list_assign_chanel'][$value->user_id] = $value;
        }
        parent::edit($id, $data);
    }

    public function edit_save($id = 0, $data = Array(), $data_return = Array(), $re_validate = true) {
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
                $temp['user_id'] = $item;
                $insert_data[] = $temp;
            }
        }
        unset($data['list_id']);
        $data['user_id'] = $this->session->userdata("id");
        $update = $this->data->update($id, $data);
        if ($id) {
            $this->load->model("m_assign_chanel");

            $list_cur_assign = $this->m_assign_chanel->get_list(array('chanel_id' => $id));
            $array_user_id = array();
            foreach ($list_cur_assign as $item) {
                $array_user_id[] = $item->user_id;
            }

            $this->m_assign_chanel->delete_by_custom(array('chanel_id' => $id));
            //ducanh == thu gui tin nhan khi duoc assign
            $this->load->model("m_admin_account", "ad_account");
            $this->load->library("Rest_Client");
            $this->rest_client->initialize($this->config->item("sms"));
            $uri = "Sms/Add";
            foreach ($insert_data as $item) {
                $item['chanel_id'] = $id;
                $this->m_assign_chanel->add($item);
                if (!in_array($item["user_id"], $array_user_id)) {
                    //gui tin
                    $user = $this->ad_account->get_one($item["user_id"]);
                    if ($user && $user->userPhone != "") {
                        $param = Array(
                            "sendTo" => $user->userPhone,
                            "content" => "Ban vua duoc nguoi Quan ly MOL phan kenh " . $data["name"] . ", truy cap vao tai khoan cua ban de xem them",
                        );
                        $result = $this->rest_client->post($uri, $param);
                    }
                }
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

    public function setting_chanel($id = 0, $data = Array()) {
        // Cho phep lay ca mau quang cao
        $this->data->view_table = false;
        $data['id'] = $id;
        $data["view"] = false;
        $data['info_chanel'] = $this->data->get_one($id);
        $data['list_mau_qc'] = $this->data->get_list(array("m.parent_id" => $id));
        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        $data_return["callback"] = "";
        if (!$id) {
            $data_return["state"] = 0;
            $data_return["msg"] = "Id không tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
        if (!isset($data["save_link"])) {
            $data["save_link"] = site_url($this->name["class"] . "/setting_chanel_save/" . $id);
        }
        $data["title"] = $title = 'Thiết lập mẫu quảng cáo';
        $viewFile = "chanel/setting_chanel";
        $content = $this->load->view($this->path_theme_view . $viewFile, $data, true);

        if ($this->input->is_ajax_request()) {
            $data_return["state"] = 1;
            $data_return["html"] = $content;
            echo json_encode($data_return);
            return TRUE;
        }
        $head_page = $this->load->view($this->path_theme_view . 'base_manager/header_edit', $data, true);
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'header.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header', $data, true);
        }
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'header_edit.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header_edit', $data, true);
        }
        $title = "Thiết lập mẫu quảng cáo ";

        $this->master_page($content, $head_page, $title);
    }

    public function setting_chanel_save($id = 0, $data = array(), $data_return = array(), $re_validate = true) {
        $data = $this->input->post();

        // Xoa cac mau qc co yeu cau xoa
        if ($data["id_del"] != "") {
            $data_update = array(
                'status' => 0
            );
            $array_id = explode(";", $data["id_del"]);
            foreach ($array_id as $item) {
                $this->data->update($item,$data_update);
            }
            
        }
        unset($data["id_del"]);

        // Kiem tra trung
        $this->data->view_table = false;
        $list_chanel = $this->data->get_list();
        $list_chanel_code = array();
        foreach ($list_chanel as $chanel) {
            $list_chanel_code [] = $chanel->code;
        }

        // Insert nhung mau qc moi
        if (isset($data["code_mau_qc"])) {
            // Kiem tra trung
            
            foreach ($data["code_mau_qc"] as $code_mauqc) {
                $check_trung = 0;
                foreach ($data["code_mau_qc"] as $check_code) {
                    if($code_mauqc == $check_code){
                        $check_trung++;
                    }
                }
                if ($check_trung > 1) {
                    $data_return["callback"] = "save_form_edit_response";
                    $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
                    $data_return["msg"] = "Mã quảng cáo " . $code_mauqc . " đã có trong danh sách mẫu quảng cáo";
                    echo json_encode($data_return);
                    exit;
                }
                $code_mauqc = str_replace(" ", "_", $code_mauqc);
                if (in_array($code_mauqc, $list_chanel_code)) {
                    $data_return["callback"] = "save_form_edit_response";
                    $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
                    $data_return["msg"] = "Mã quảng cáo " . $code_mauqc . " đã có, vui lòng kiểm tra lại";
                    echo json_encode($data_return);
                    exit;
                }
            }

            $number_mau_qc = count($data["code_mau_qc"]);
            $insert_data = array();
            for ($i = 0; $i < $number_mau_qc; $i++) {
                $chanel_data = array(
//                    "code" => $data["code_mau_qc"][$i],
                    "code" => str_replace(" ", "_", $data["code_mau_qc"][$i]),
                    "name" => $data["name_mau_qc"][$i],
                    "keyword" => $data["keyword_mau_qc"][$i],
                    "description" => $data["description_mau_qc"][$i],
                    "parent_id" => $id
                );
                $insert_data [] = $chanel_data;
            }
            $this->data->add_muti($insert_data);
            unset($data["code_mau_qc"]);
            unset($data["name_mau_qc"]);
            unset($data["keyword_mau_qc"]);
            unset($data["description_mau_qc"]);
        }
        //cap nhat
        if (isset($data["edit_id"])) {
            $number_row = count($data["edit_id"]);
            for ($i = 0; $i < $number_row; $i++) {
                $chanel_data = array(
                    'id' => $data["edit_id"][$i],
                    "name" => $data["edit_name"][$i],
                    "keyword" => $data["edit_keyword"][$i],
                    "description" => $data["edit_description"][$i],
                );
                $this->data->update($chanel_data['id'], $chanel_data);
            }
            unset($data["edit_id"]);
            unset($data["edit_name"]);
            unset($data["edit_keyword"]);
            unset($data["edit_description"]);
        }
        parent::edit_save($id, $data, $data_return, $re_validate);
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
            $record->custom_action = '<div class="action"><a target="_blank" class="detail icon16 i-cog-3 " per="1" href="' . site_url('chanel_info').'?chanel_parent='. $record->$key_table . '" title="Thiết lập kênh"></a>';
            if ($perId < 3) {
                if (!isset($record->editable) || (isset($record->editable) && $record->editable)) {
                    $record->custom_action .= '<a class="edit e_ajax_link icon16 i-pencil" per="1" href="' . site_url($this->url["edit"] . $record->$key_table) . '" title="Sửa"></i></a>
			<a class="delete e_ajax_confirm e_ajax_link icon16 i-remove" per="1" href="' . site_url($this->url["delete"] . $record->$key_table) . '" title="Xóa"></a></div>';
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

    public function manager($data = array()) {
        $data['perId'] = $this->session->userdata('perId');
        parent::manager($data);
    }

    public function get_list_mau_qc() {
        $parent_id = $this->input->get("parent_id");
        $ajax_data = array();
        if ($parent_id) {
            $this->data->view_table = false;
            $ajax_data = $this->data->get_list(array("m.parent_id" => $parent_id));
        }
        if (count($ajax_data)) {
            foreach ($ajax_data as $item) {
                echo "<option keyword='" . $item->keyword . "' url='?code_chanel=" . $item->code . "' value='" . $item->code . "'>" . $item->name . "</option>";
            }
        } else {
            echo "<option keyword='' value=''>Không có mẫu</option>";
        }
    }

    public function get_chanel_by_mauqc() {
        $code_mauqc = $this->input->get("code_mauqc");
        $this->load->model("m_chanel");
        // Lay 1 mau qc
        $mauqc = $this->m_chanel->get_list_mauqc(array("code" => $code_mauqc));
        // Lay 1 chanel
        $chanel = $this->m_chanel->get_list_chanel_parent(array("id" => $mauqc[0]->parent_id));
        $data_return = array("code_chanel" => $chanel[0]->code);
        echo json_encode($data_return);
    }
    
    public function get_chanel_by_parent_mauqc() {
        $code_mauqc = $this->input->get("code_mauqc");
        $this->load->model("m_chanel");
        // Lay 1 mau qc
        $mauqc = $this->m_chanel->get_list_mauqc(array("code" => $code_mauqc));
        // Lay 1 chanel
//        $chanel = $this->m_chanel->get_list_chanel_parent(array("id" => $mauqc[0]->parent_id));
        $data_return = array("parent_id" => $mauqc[0]->parent_id);
        echo json_encode($data_return);
    }

    public function get_mauqc_by_chanel() {
        $code_chanel = $this->input->get("code_chanel");
        $this->load->model("m_chanel");
        if ($code_chanel != "") {
            // Lay 1 chanel
            $chanel = $this->m_chanel->get_list_chanel_parent(array("code" => $code_chanel));
            // Lay list mauqc
            $list_mauqc = $this->m_chanel->get_list_mauqc(array("parent_id" => $chanel[0]->id));
        } else {
            $list_mauqc = $this->m_chanel->get_list_mauqc();
        }
        echo "<option value=''>" . "Tất cả" . "</option>";
        foreach ($list_mauqc as $mauqc) {
            echo "<option value='" . $mauqc->code . "'>" . $mauqc->name . "</option>";
        }
    }
    
    public function get_mauqc_by_id_chanel() {
        $parent_id = $this->input->get("parent_id");
        $this->load->model("m_chanel");
        if ($parent_id != "") {
            // Lay list mauqc
            $list_mauqc = $this->m_chanel->get_list_mauqc(array("parent_id" => $parent_id));
        } else {
            $list_mauqc = $this->m_chanel->get_list_mauqc();
        }
        echo "<option value=''>" . "Tất cả" . "</option>";
        foreach ($list_mauqc as $mauqc) {
            echo "<option value='" . $mauqc->code . "'>" . $mauqc->name . "</option>";
        }
    }

    public function delete($id = 0, $data = array()) {
        $list_ads = $this->data->get_list_mauqc(array('m.parent_id' => $id));
        $data_update = array (
            'status' => 0
        );
        if ($list_ads) {
            foreach ($list_ads as $item) {
                $this->data->update($item->id,$data_update);
            }
        }
        $id = intval($id);
        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return FALSE;
        }
        $data_return["callback"] = "delete_respone";
        if ($this->input->post() || $id > 0) {
            if (isset($data["list_id"]) && sizeof($data["list_id"])) {
                $list_id = $data["list_id"];
            } else {
                if ($this->input->post() && $id == "0") {
                    $list_id = $this->input->post("list_id");
                } elseif ($id > 0) {
                    $list_id = Array($id);
                }
            }
            $affted_row = $this->data->update($id,$data_update);
            if ($affted_row) {
                $data_return["list_id"] = $list_id;
                $data_return["state"] = 1;
                $data_return["msg"] = "Xóa bản ghi thành công";
            } else {
                $data_return["list_id"] = $list_id;
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
    
    public function _add_colum_action($record) {
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

}
