<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class m_care extends data_base {

    var $custom_conds = array(
        "custom_where" => array(),
        "custom_like" => array()
    );

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "care";
        $this->_key_name = "id";
        $this->_schema = Array(
            'id', 'contact_id', 'care_type_code', 'time_created', 'status', 'content_care', 'status_care', 'care_sms_email_id', 'care_type'
        );
        $this->_rule = Array(
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
        );
    }

}
