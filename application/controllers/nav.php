<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author Loc
 */
class nav extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = $this->input->get();
        if (isset($data["redirect"]) && $data["redirect"] != "") {
            $log_data = array(
                "id_camp_landingpage" => isset($_GET["id"]) ? $_GET["id"] : "other",
                "code_chanel" => isset($_GET["code_chanel"]) ? $_GET["code_chanel"] : "other",
                "datetime" => date("Y-m-d H:i:s"),
                "action" => "Visited",
                "ip" => $_SERVER["REMOTE_ADDR"]
            );
            $preview = isset($data["preview"]) ? $data["preview"] : null;
            if ($preview != null && $preview == "preview_mode") {
                // khong lam gi
            }else{
                $this->load->model("m_log");
                $this->m_log->add($log_data);
            }
            redirect($data["redirect"] . "?id=".$log_data["id_camp_landingpage"].
                    "&code_chanel=".$log_data['code_chanel']);
        }
    }

}
