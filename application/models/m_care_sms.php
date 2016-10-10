<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class m_care_sms extends data_base {

    var $custom_conds = array(
        "custom_where" => array(),
        "custom_like" => array()
    );

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "care_sms";
        $this->_key_name = "id";
        $this->_schema = Array(
            'id', 'phone', 'subject', 'content', 'template_id', 'status_send', 'lang', 'time_created_queue'
        );
        $this->_rule = Array(
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
        );
    }

}
