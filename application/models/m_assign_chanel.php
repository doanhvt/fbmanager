<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of m_campaign
 *
 * @author Loc
 */
class m_assign_chanel extends data_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "gd_assign_chanel";
        $this->_key_name = "id";
        $this->_schema = Array(
            "id", "user_id", "chanel_id"
        );
        $this->_rule = Array(
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
        );
    }

    public function setting_select() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
    }

}
