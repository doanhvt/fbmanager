<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author DucAnh
 */
class contact_collection extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function add_save() {
    	$data_post = $this->input->post();
		$data_get = $this->input->get();
    	
       header("Access-Control-Allow-Origin: *");
       header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept');
       if ($data_post['hoten'] && $data_post['sdt']) {
           $data_insert = array();
           $data_insert["name"] = $data_post['hoten'];
           $data_insert["phone"] = $data_post['sdt'];
			$data_insert["email"] = $data_post['email'];
			$data_insert["datetime_submitted_unix"] = time();
           $data_insert["datetime_submitted"] = date("Y-m-d H:i:s");
           $data_insert["ip"] = $this->input->ip_address();
           // Kiem tra neu du lieu la do nguoi dung nhap lung tung thi xoa
           $this->load->model("m_campaign_landingpage");
           $check = $this->m_campaign_landingpage->get_one(array("id" => $data_get["id"]));
           if ($check) {
               $data_insert["id_camp_landingpage"] = $data_get['id'];
               $data_insert["code_chanel"] = $check->code_chanel;
           } else {
               $data_insert["id_camp_landingpage"] = '-100';
               $data_insert["code_chanel"] = '-100';
           }

           $this->load->model("m_contact");
           if ($id_insert = $this->m_contact->add($data_insert)) {
                $contact_id = date('Ymd') . substr(str_pad($id_insert, 6, '0', STR_PAD_LEFT), -6);
                $this->m_contact->update($id_insert, array('contact_id' => $contact_id));
				redirect('https://gmail.com');
		   }
               
           
       }
    }

}
