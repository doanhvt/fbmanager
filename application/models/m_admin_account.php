<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * M_user
 * 
 * Description...
 * 
 * @package M_adminuser
 * @author Pham Trong <phamtrong204@gmail.com>
 * @version 0.0.0
 */
class M_admin_account extends data_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "admin_account";
        $this->_key_name = "userId";
        $this->_schema = Array(
            "userId", "userName", "userDisplayName", "userPassword",
            "userEmail", "userBithday", "userPhone", "userPermission",
        );
        $this->_rule = Array(
            "userId" => "type=hidden",
            "userName" => "type=text maxlength=255 required=required unique=true",
            "userDisplayName" => "type=text maxlength=255 required=required",
            "userPassword" => "type=password maxlength=255 minlength=6 required=required",
            "_userPassword" => "type=password maxlength=255 minlength=6 required=required recheck=userPassword",
            "userEmail" => "type=text maxlength=255 required=required is_email=1",
            "userPhone" => "type=text",
            "userPermission" => "type=select allow_null=true target_model=m_permission target_value=perId target_display=perName",
        );
        $this->_field_form = Array(
            "userId" => "ID",
            "userName" => "Login Name",
            "userDisplayName" => "Display Name",
            "userPassword" => "Password",
            "_userPassword" => "Verify password",
            "userEmail" => "Email",
            "userPhone" => "Mobile",
            "userPermission" => "Type of account",
        );
        $this->_field_table = Array(
            "m.userId" => "ID",
            "m.userName" => "Login Name",
            "m.userDisplayName" => "Display Name",
            "p.perName" => "Type of account",
            "m.userEmail" => "Email",
            "m.userPhone" => "Mobile",
        );
    }

    public function setting_select() {
        $this->db->select("m.*,p.perName,p.perData,p.perValue");
        $this->db->from($this->_table_name . " as m");
        $this->db->join("permission as p", "p.perId = m.userPermission", "left");
    }

    public function check_login($user_name, $password) {
        $this->setting_select();
        $this->db->where("userName", $user_name);
        $this->db->where("userPassword", $password);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->first_row();
        } else {
            return NULL;
        }
    }

    public function set_field_form($form = array()){
        $this->_field_form = $form;
    }
    
}