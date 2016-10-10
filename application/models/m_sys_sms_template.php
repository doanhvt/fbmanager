<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class m_sys_sms_template extends data_base {

    var $custom_conds = array(
        "custom_where" => array(),
        "custom_like" => array()
    );

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "sys_sms_template";
        $this->_key_name = "id";
        $this->_schema = Array(
            'id', 'code', 'name', 'subject', 'content', 'time_created', 'time_modified', 'status'
        );
        $this->_rule = Array(
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
        );
    }

}
