<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class M_landing_page extends data_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "dm_landingpage";
        $this->_key_name = "id";
        $this->_schema = Array(
            "id", "name", "url", "code", "description", "zipfile",'sub_domain','soure_url'
        );
        $this->_rule = Array(
            "id" => "type=hidden",
            "name" => "type=text maxlength=255 required=required",
            "code" => "type=text maxlength=255 required=required unique=true",
            "sub_domain" => "type=text maxlength=255 required=required unique=true",
            "description" => "type=textarea"
        );
        $this->_field_form = Array(
            "id" => "ID",
            "code" => "Code",
            "name" => "Name",
            "sub_domain" => "Domain (Default)",
            "description" => "Description"
        );
        $this->_field_table = Array(
            "id" => "ID",
            "code" => "Code",
            "sub_domain" => "Domain",
            "name" => "Name",
            "url" => "Preview",
        );
    }

    public function setting_select() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
        $this->db->where('m.status != ',0);
    }
    
    public function check_existed($key, $value, $id = 0) {
        if($key == "sub_domain" && $value == "http://mol.topmito.edu.vn"){
            return false;
        }else{
            return parent::check_existed($key, $value, $id);
        }
    }
}

/* End of file m_user.php */
/* Location: ./application/models/m_user.php */