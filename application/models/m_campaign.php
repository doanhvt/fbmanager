<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of m_campaign
 *
 * @author Loc
 */
class m_campaign extends data_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "dm_campaign";
        $this->_key_name = "id";
        $this->_schema = Array(
            "id", "name", "start_time", "end_time","description",'code'
        );
        $this->_rule = Array(
            "id" => "type=hidden",
            "name" => "type=text maxlength=255 required=required",
            "code" => "type=text maxlength=255 required=required unique=true",
            "start_time" => "type=datepicker  required=required",
            "end_time" => "type=datepicker required=required",
            "description" => "type=textarea ",
            );
        $this->_field_form = Array(
            "id" => "ID",
            "code" => "Code",
            "name" => "Campaign",
            "start_time" => "Starting Time",
            "end_time" => "End Time",
            "description" => "Description",
            
        );
        $this->_field_table = Array(
            "m.id" => "ID",
            "m.name" => "Campaign",
            "m.code" => "Code",
            "m.description" => "Description",
            "m.start_time" => "Starting Time",
            "m.end_time" => "End Time",
        );
    }

    public function setting_select() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
        $this->db->where("m.deleted", 1);
    }

}
