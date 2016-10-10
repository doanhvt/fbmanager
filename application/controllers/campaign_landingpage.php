<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author Loc
 */
class campaign_landingpage extends manager_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "campaign_landingpage",
            "view" => "campaign_landingpage",
            "model" => "m_campaign_landingpage",
            "object" => "Chiến dịch"
        );
    }

    public function manager_campaign($id = 0, $data = Array()) {
        $perId = $this->session->userdata('perId');
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

        $check_chanel = $this->data->get_list_chanel_custom();
        if (!$check_chanel) {
            $data_return["state"] = 0;
            $data_return["msg"] = "Bạn chưa được phân kênh";
            echo json_encode($data_return);
            return FALSE;
        }
        $data['array_chanel'] = array();
        foreach ($check_chanel as $value) {
            $data['array_chanel'][$value->id] = $value;
        }
        $data['info_campaign'] = $this->data->get_info_campaign($id);
        $data['list_landingpage'] = $this->data->get_list_landingpage();
        $data['list_chanel_parent'] = $this->data->get_list_chanel_custom();
        $data['list_chanel_ads'] = $this->data->get_list_chanel_custom(1);
        $data['perId'] = $perId;
        // ducanh ===================================================
        foreach ($data['list_landingpage'] as $landingpage) {
            $data['list_landingpage_id'][$landingpage->id] = $landingpage;
        }
        $list_chanel = $this->data->get_list_chanel();
        foreach ($data['list_chanel_parent'] as $chanel) {
            $data['list_chanel_id'][$chanel->id] = $chanel;
        }
        //===========================================================
        $this->load->model('m_chanel');
        $data['list_chanel_assign'] = $this->m_chanel->get_list();

        $this->load->model('m_campain_assign_chanel');
        $list_campain_assign_chanel = $this->m_campain_assign_chanel->get_list(array('m.campaign_id' => $id));
        $array_list_chanel_assign_id = array();
        foreach ($list_campain_assign_chanel as $item) {
            $array_list_chanel_assign_id[] = $item->chanel_id;
        }
        foreach ($data['list_chanel_assign'] as $key => $item) {
            if (!in_array($item->id, $array_list_chanel_assign_id)) {
                unset($data['list_chanel_assign'][$key]);
            }
        }
        if (!isset($data["save_link"])) {
            $data["save_link"] = site_url($this->name["class"] . "/manager_campaign_save/" . $id);
        }
        if (!isset($data["list_input"])) {
            $data["list_input"] = $this->_get_form($id);
        }
        $data["title"] = $title = "Sửa dữ liệu " . $this->name["object"];
        $viewFile = "base_manager/default_form";
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'form.php')) {
            $viewFile = $this->name["view"] . '/' . 'form';
        }

        // ducanh: url de lay danh sach mau qc cua kenh co id = id
        $data["url_mau_qc"] = site_url("chanel/get_list_mau_qc/");
        // =======================================================
        $content = $this->load->view($this->path_theme_view . $viewFile, $data, true);

        $data_return["record_data"] = $this->data->get_one($id);
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
        $title = "Thiết lập campaign";

        $this->master_page($content, $head_page, $title);
    }

    public function manager_campaign_save_bak($id = 0, $data = Array(), $data_return = Array()) {

        $data_return["callback"] = "";

        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        $id = intval($id);
        if (!$id) {
            $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
            $data_return["msg"] = "Chiến dịch không tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
        if (sizeof($data) == 0) {
            $data = $this->input->post();
        }
        if (isset($data['id_landingpage'])) {
            $data_insert = array();
            foreach ($data['id_landingpage'] as $key => $item) {
                $temp = array();
                $temp['id_landingpage'] = $data['id_landingpage'][$key];
                $temp['code_chanel'] = $data['code_chanel'][$key];
                $temp['url_landingpage'] = $data['url_landingpage'][$key];
                $temp['id_campaign'] = $data['id_campaign'];
                $temp['status'] = 1;
                $temp['user_id'] = $this->session->userdata('id');
                $data_insert[] = $temp;
            }
            foreach ($data_insert as $item) {
                $id_insert = $this->data->add($item);
                $data_update = array();
                $data_update['url_landingpage'] = $item['url_landingpage'] . '&id=' . $id_insert;
                $this->data->update($id_insert, $data_update);
            }
        }
        if (isset($data['edit_id'])) {
            foreach ($data['edit_id'] as $key => $item) {
                $data_update = array();
                $data_update['status'] = $data['edit_status'][$key];
                if ($data_update['status']) {
                    $data_update['id_landingpage'] = $data['edit_id_landingpage'][$key];
                    $data_update['code_chanel'] = $data['edit_code_chanel'][$key];
                    $data_update['url_landingpage'] = $data['url_landingpage'][$key];
                }
                if ($data['edit_id'][$key] != 1516 && $data['edit_id'][$key] != 1266) {
                    $this->data->update($data['edit_id'][$key], $data_update);
                }
            }
        }
        if (isset($data['id_del']) && $data['id_del']) {
            $list_id = explode(';', $data['id_del']);
            foreach ($list_id as $item) {
                $data_update = array();
                $data_update['status'] = 0;
                if ($item != 1516 && $item != 1266) {
                    $this->data->update($item, $data_update);
                }
            }
        }

        if ($id) {
            $data_return["key_name"] = $this->data->get_key_name();
            $data_return["record"] = $this->_process_data_table($this->data->get_one($id));
            $data_return["state"] = 1; /* state = 1 : insert thành công */
            $data_return["msg"] = "Thiết lập chiến dịch thành công.";
            $data_return["redirect"] = site_url('campaign');
            echo json_encode($data_return);
            return TRUE;
        } else {
            $data_return["state"] = 2; /* state = 2 : Lỗi thêm bản ghi */
            $data_return["msg"] = "Thêm bản ghi thất bại, vui lòng thử lại sau.";
            echo json_encode($data_return);
            return FALSE;
        }
    }

    public function get_data_row() {
        $list_landingpage = $this->data->get_list_landingpage();
        $list_chanel_parent = $this->data->get_list_chanel_custom();
        $list_chanel_ads = $this->data->get_list_chanel_custom(1);

        $ajax_data = array(
            "list_landingpage" => $list_landingpage,
            "list_chanel_parent" => $list_chanel_parent,
            "list_chanel_ads" => $list_chanel_ads,
        );
        echo json_encode($ajax_data);
    }

    /*  public function get_data_chanel() {
      $id_campaign = $this->input->get('id_campaign');
      $id_chanel = $this->input->get('id_chanel');
      $list_landingpage = $this->data->get_list_landingpage();
      $list_chanel_parent = $this->data->get_list_chanel_custom();
      $list_chanel_ads = $this->data->get_list_chanel_custom(1);
      $list_campaign_landingpage = $this->data->get_list_campaign_landingpage_test(array('m.id_campaign' => $id_campaign, 'chanel.parent_id' => $id_chanel));
      $array_campaign_landingpage = array();
      foreach ($list_campaign_landingpage as $item) {
      $array_campaign_landingpage[$item->code_chanel] = $item;
      }
      $this->load->model('m_chanel');
      $list_data_chanel = $this->m_chanel->get_list_mauqc(array('m.parent_id' => $id_chanel));
      foreach ($list_data_chanel as $item) {
      if (isset($array_campaign_landingpage[$item->code])) {
      $item->info_campain_landingpage = $array_campaign_landingpage[$item->code];
      } else {
      $item->info_campain_landingpage = NULL;
      }
      }
      $ajax_data = array(
      "list_landingpage" => $list_landingpage,
      "list_chanel_parent" => $list_chanel_parent,
      "list_chanel_ads" => $list_chanel_ads,
      "list_campaign_landingpage" => $list_data_chanel,
      );
      $result = $this->load->view("default/campaign_landingpage/get_data_chanel", $ajax_data, TRUE);
      //        echo json_encode($ajax_data);
      //        echo '<pre>';
      //        var_dump($ajax_data['list_campaign_landingpage']);
      echo $result;
      } */

    public function get_data_chanel() {
        $id_campaign = $this->input->get('id_campaign');
        $id_chanel = $this->input->get('id_chanel');
        $this->load->model("m_chanel", 'chanel');
        $data['list_chanel_db'] = $this->chanel->get_list_chanel($id_chanel);
        $data['id_campaign'] = $id_campaign;
        $data['id_chanel'] = $id_chanel;
//        $data['list_landingpage'] = $this->load->view("default/campaign_landingpage/list_landingpage", $data, TRUE);
        $i = 0;
        $temps = [];
        foreach ($data['list_chanel_db'] as $list_chanel) {
            if (!isset($temps[$list_chanel->code])) {
                $temps[$list_chanel->code] = $list_chanel;
            } else if ($list_chanel->id_campaign == $id_campaign) {
                $temps[$list_chanel->code] = $list_chanel;
                $i++;
            }
        }
        foreach ($temps as $temp) {
            $data['list_chanel'][] = $temp;
        }
        $result = $this->load->view("default/campaign_landingpage/data_chanel", $data, TRUE);
        echo $result;
    }

    public function get_data_select() {
        $data['id_landingpage'] = $this->input->post();
        $this->load->model("m_landing_page");
        $data['list_chanel'] = $this->m_landing_page->get_list();
        $data['list_landingpage'] = $this->load->view("default/campaign_landingpage/list_landingpage", $data, TRUE);
//        $result = $this->load->view("default/campaign_landingpage/data_chanel", $data, TRUE);;
        echo $data['list_landingpage'];
    }

    public function manager_campaign_save($id = 0, $data = array()) {
        $data_return["callback"] = "";

        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        $id = intval($id);
        if (!$id) {
            $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
            $data_return["msg"] = "Chiến dịch không tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
        if (sizeof($data) == 0) {
            $data = $this->input->post();
        }
        //Kiểm tra dữ liệu gửi lên
        if (!isset($data["url_landingpage"]) || !isset($data["id_landingpage"]) || !isset($data["code_chanel"]) || !isset($data["id_campaign"]) || !isset($data["status"])) {
            $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
            $data_return["msg"] = "Dữ liệu gửi lên không hợp lệ.";
            echo json_encode($data_return);
            return FALSE;
        }
        if ($data["url_landingpage"] == "" || $data["id_landingpage"] == "" || $data["code_chanel"] == "" || $data["id_campaign"] == "" || $data["status"] == "") {
            $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
            $data_return["msg"] = "Dữ liệu gửi lên không hợp lệ.";
            echo json_encode($data_return);
            return FALSE;
        }
        //Nếu dữ liệu đúng thì ghép url lại
        $data['url_landingpage'] .= '&code_chanel=' . $data["code_chanel"] . '&id_campaign=' . $data["id_campaign"] . '&id=';


        //Check trong database xem tồn tại chưa, nếu có thì update, ko thì insert
        $id_gd = $data['id'];
        $data['user_id'] = $this->session->userdata("id");
        if (isset($data['id']) && !empty($data['id'])) {
            $check = $this->data->get_one($id_gd);
            if ($check) {
                unset($data['id']);
                $data['url_landingpage'] .= $id_gd;
                $this->data->update($id_gd, $data);
                $data_return["state"] = 1; /* state = 0 : dữ liệu không hợp lệ */
                $data_return["msg"] = "Thiết lập chiến dịch thành công.";
                echo json_encode($data_return);
                return TRUE;
            } else {
                $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
                $data_return["msg"] = "Dữ liệu gửi lên không hợp lệ.";
                echo json_encode($data_return);
                return FALSE;
            }
        }
        // Insert vào db

        unset($data['id']);
        $data_return['id'] = $this->data->add($data);
        $data['url_landingpage'] .= $data_return['id'];
        $this->data->update($data_return['id'], $data);
        $data_return["state"] = 1; /* state = 0 : dữ liệu không hợp lệ */
        $data_return["msg"] = "Thiết lập chiến dịch thành công.";
        echo json_encode($data_return);
        return TRUE;
    }

}
