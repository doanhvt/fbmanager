<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class m_cronjob extends data_base {

    var $custom_conds = array(
        "custom_where" => array(),
        "custom_like" => array()
    );

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "dm_contact";
        $this->_key_name = "id";
        $this->_schema = Array(
            "id", "name", "email", "phone", 'brithday', "school", "sector", "score",
            "keyword_id", "country", "keyword", "graduation", "ip",
            "id_camp_landingpage", "code_chanel", "datetime_submitted", 'datetime_submitted_unix', 'http_referer',
            'domain', 'http_referer', 'link_cv', 'company', 'position', 'status', 'status_mol',
            'gender', 'marry', 'chanel', 'experience', 'language', 'age', 'line_id', 'status_filter_day', 'indonesia',
            'status_flag', 'sms_auto_flag'
        );
        $this->_rule = Array(
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
        );
    }

}
