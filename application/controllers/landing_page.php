<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Name: Anhld
 * Date: 13/05/2014
 * Desc: Quan ly Landing Page
 */
class Landing_page extends manager_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "landing_page",
            "view" => "landing_page",
            "model" => "m_landing_page",
            "object" => "landing page"
        );
    }

    public function add_save($data = array(), $data_return = array(), $re_validate = true) {
        $data = $this->input->post();
        $data_return["callback"] = "save_form_add_response";

        $data["url"] = "";
        if (isset($_FILES["zipfile"])) {
            $upload_data = $this->uploadLandingPage($data);
            if ($upload_data["state"] == 0) {
                $data_return["state"] = 0;
                $data_return["msg"] = $upload_data["msg"];
                echo json_encode($data_return);
                exit();
            }
            $data["url"] = $upload_data["url"];
            $data["soure_url"] = $upload_data["soure_url"];
        }
//        if (strpos($data["sub_domain"], "http://") == FALSE) {
//            $data["sub_domain"] = 'http://' . $data["sub_domain"];
//        }
        $data["sub_domain"] = preg_replace("{/$}", "", $data["sub_domain"]);
        if (strpos($data["sub_domain"], "mol.topmito.edu.vn") == FALSE) {
            $data["url"] = $data["sub_domain"];
            $data["sub_domain"] = $data["sub_domain"];
        } else {
            if (isset($upload_data)) {
                $data["url"] = $data["sub_domain"] . '/' . $upload_data["url"];
            }
        }
        unset($data["zipfile"]);
        parent::add_save($data, $data_return, $re_validate);
    }

    public function edit($id = 0, $data = array()) {
        $data["landingpage_info"] = $this->data->get_one($id);
        parent::edit($id, $data);
    }

    public function edit_save($id = 0, $data = array(), $data_return = array(), $re_validate = true) {
        $data = $this->input->post();

        if (isset($_FILES["zipfile"])) {
            $this->load->model("m_landing_page", "landing_page");
            $currLandingPage = $this->landing_page->get_one($id);
            if (is_dir($currLandingPage->url)) {
                delete_files($currLandingPage->url, true);
                rmdir($currLandingPage->url);
            }
            $upload_data = $this->uploadLandingPage($data);
            if ($upload_data["state"] == 0) {
                $data_return["state"] = 0;
                $data_return["msg"] = $upload_data["msg"];
                echo json_encode($data_return);
                exit();
            }
            $data["url"] = $upload_data["url"];
            $data["soure_url"] = $upload_data["soure_url"];
        }
//        if (strpos($data["sub_domain"], "http://") == FALSE) {
//            $data["sub_domain"] = 'http://' . $data["sub_domain"];
//        }
        $data["sub_domain"] = preg_replace("{/$}", "", $data["sub_domain"]);
        if (strpos($data["sub_domain"], "mol.topmito.edu.vn") == FALSE) {
            $data["url"] = $data["sub_domain"];
            $data["sub_domain"] = $data["sub_domain"];
        } else {
            if (isset($upload_data)) {
                $data["url"] = $data["sub_domain"] . '/' . $upload_data["url"];
            }
        }
        unset($data["zipfile"]);
        unset($data["code"]);
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
            if (isset($data["list_id"]) && sizeof($data["list_id"])) {
                $list_id = $data["list_id"];
            } else {
                if ($this->input->post() && $id == "0") {
                    $list_id = $this->input->post("list_id");
                } elseif ($id > 0) {
                    $list_id = Array($id);
                }
            }
            $data_update = array(
                'status' => '0'
            );
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

    protected function uploadLandingPage($data = array()) {
        if (sizeof($data) == 0) {
            $data = $this->input->post();
        }
        $data_return = array("state" => 1, "msg" => "", "url" => "");

        $path ='landingpage/'.$data["code"];
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        
        $config['upload_path'] = 'landingpage/'.$data["code"].'/';
        $config['allowed_types'] = 'zip';

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("zipfile")) {
            $upload_data = $this->upload->data();
//            $zip = new ZipArchive;
//            if ($zip->open($upload_data["full_path"]) === TRUE) {
//                $zip->extractTo('landingpage/' . $data["code"]);
//                $zip->close();
                //unlink($upload_data["full_path"]);
                $data_return["soure_url"] = "landingpage/" . $data["code"].'/'.$upload_data["file_name"];
                $data_return["url"] = "landingpage/" . $data["code"];
//            } else {
//                $data_return["state"] = 0;
//                $data_return["msg"] = "Không giải nén được file";
//            }
        } else {
            $data_return["state"] = 0;
            $data_return["msg"] = "Không upload được file";
        }

        return $data_return;
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
            if ($record->sub_domain) {
                $record->url = "<a target='_blank' href='" . $record->url . "?preview=preview_mode'>Preview</a>";
            }
        }

        return $record;
    }

}