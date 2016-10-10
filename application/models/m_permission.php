<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


/**
 * Description of m_permission
 *
 * @author Loc
 */
class m_permission extends data_base{

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "permission";
        $this->_key_name = "perId";
        $this->_schema = Array(
            "perId", "perName", "perData", "perDescreption","perValue"
        );
        $this->_rule = Array(
            "perId" => "type=hidden",
            "perName" => "type=text maxlength=255 required=required unique=true",
            "perData" => "type=textarea  required=required",
            "perDescreption" => "type=textarea required=required",
            "perValue" => "type=textarea  required=required",
        );
        $this->_field_form = Array(
            "perId" => "ID",
            "perName" => "Group Name",
            "perData" => "Content",
            "perDescreption" => "Desciption",
        );
        $this->_field_table = Array(
            "perId" => "ID",
            "perName" => "Group Name",
            "perDescreption" => "Desciption",
        );
    }

    public function setting_select() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
    }

}