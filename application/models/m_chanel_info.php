<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_chanel_info extends data_base {

    var $chanel_parent = 0;
    var $where_data = array(
        'custom_where' => array(),
        'custom_like' => array(),
    );

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "dm_chanel";
        $this->_key_name = "id";
        $this->_schema = Array(
            "id", "name", "description", 'code', "keyword", "parent_id"
        );
        $this->_rule = Array(
            "id" => "type=hidden",
            "name" => "type=text maxlength=255 required=required",
            "code" => "type=text maxlength=255 required=required unique=true",
            "description" => "type=textarea",
            "keyword" => "type=text",
        );
        $this->_field_form = Array(
            "id" => "ID",
            "code" => "Ad Code",
            "name" => "Ad Name",
            "keyword" => "Keyword",
            "description" => "Describe"
        );
        $this->_field_table = Array(
            "m.id" => "ID",
            "m.code" => "Ad Code",
            "m.name" => "Ad Name",
        );
    }

    public function setting_select() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " AS m");
        if ($this->chanel_parent) {
            $this->db->where('m.parent_id', $this->chanel_parent);
        }
        $this->db->where("m.status !=", 0);
    }
    
    public function set_rule($rule = array()){
        $this->_rule = $rule;
    }
}
