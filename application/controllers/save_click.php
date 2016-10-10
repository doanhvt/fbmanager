<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author Loc
 */
class save_click extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
//        header("Access-Control-Allow-Origin: *");
//        header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept');
//        $data = $this->input->get();
//        
//        $array_domain = explode('?', $data["domain"]);
//        $array_domain[0] = str_replace('/index.php','',$array_domain[0]);
//        $data["domain"] = preg_replace("{/$}", "", $array_domain[0]);
//        
//        $log_data = array(
//            "id_camp_landingpage" => isset($data["id_camp_landingpage"]) ? $data["id_camp_landingpage"] : "-100",
//            "code_chanel" => isset($data["code_chanel"]) ? $data["code_chanel"] : "-100",
//            "datetime" => date("Y-m-d H:i:s"),
//            "action" => "Visited",
//            "domain" => $data["domain"],
//            "status" => $data["http_referer"] != '' ? 1 : 0,
//            "ip" => $this->input->ip_address()
//        );
//        $preview = isset($data["preview"]) ? $data["preview"] : null;
//        if (($preview != null && $preview == "preview_mode")) {
//            // khong lam gi
//        } else {
//            $this->load->model("m_log");
//            $this->m_log->add($log_data);
//        }
    }

}
