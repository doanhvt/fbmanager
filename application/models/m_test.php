<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of m_permission
 *
 * @author Loc
 */
class m_test extends data_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "c_phone";
        $this->_key_name = "id";
        $this->_schema = Array(
            "perId", "perName", "perData", "perDescreption", "perValue"
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
            "perName" => "Tên nhóm quyền",
            "perData" => "Nội dung",
            "perDescreption" => "Mô tả",
        );
        $this->_field_table = Array(
            "perId" => "ID",
            "perName" => "Nhóm quyền",
            "perDescreption" => "Mô tả",
        );
    }

    public function setting_select() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
//        $this->db->join("dm_contact as c", "c.phone = m.phone");
    }

    public function get_phone($phone) {
        $this->db->select("m.*");
        $this->db->from("dm_contact as m");
        $this->db->like('m.phone', $phone);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->first_row();
        } else {
            return null;
        }
    }

}
