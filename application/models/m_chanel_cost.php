<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of m_campaign
 *
 * @author Loc
 */
class m_chanel_cost extends data_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "gd_chanel_cost";
        $this->_key_name = "id";
        $this->_schema = Array(
            "id", "code_chanel", "cost", "date", "description", 'total_c1'
        );
        $this->_rule = Array(
            "id" => "type=hidden",
            "cost" => "type=number required=required",
            "total_c1" => "type=number required=required",
            "date" => "type=datepicker  required=required",
            "description" => "type=textarea ",
        );
        $this->_field_form = Array(
            "id" => "ID",
            "date" => "Starting Time",
            "cost" => "Total Cost(VND)",
            "total_c1" => "C1",
            "description" => "Note",
        );
        $this->_field_table = Array(
            "m.id" => "ID",
            'c.name'=> 'Channel',
            "date" => "Starting Time",
            "cost" => "Total Cost(VND)",
            "total_c1" => "C1",
            "m.description" => "Note",
        );
    }

    public function setting_select() {
        $this->db->select("m.*,c.name,c.user_id");
        $this->db->from($this->_table_name . " as m");
        $this->db->join("dm_chanel as c", "c.code = m.code_chanel", "left");
        $perId = $this->session->userdata('perId');
        if ($perId == 3) {
            $id = $this->session->userdata('id');
            $this->db->join("gd_assign_chanel as ass", "ass.chanel_id = c.id");
            $this->db->where("ass.user_id", $id);
        }
    }

}
