<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class M_contact extends data_base {

    public $campaign_id = 0;
    public $check_status_filter_day = 0;
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
            'gender','marry','chanel','experience', 'language', 'age', 'line_id', 'status_filter_day', 'indonesia',
            'status_flag', 'sms_auto_flag'
        );
        $this->_rule = Array(
            "id" => "type=hidden"
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
            "m.id" => "ID",
            "m.contact_id" => "ContactID",
            "m.name" => "Name",
            "email" => "Email",
//            "country" => "Nơi ở",
//            "graduation" => "Trình độ học vấn",
//            "sector" => "Ngành đăng ký",
            "phone" => "Mobile",
//            "p_chanel_name" => "Kênh",
            "code_chanel" => "Sample",
            "datetime_submitted" => "Day Registered",
            "qc.keyword" => "Keyword",
            'm.line_id' => "Line"
            // 'm.indonesia' => "indonesia"
        );
    }

    public function setting_select() {
        $this->db->select("m.*,qc.keyword,qc.parent_id,qc.code,c.id_campaign,c.id_landingpage");
//            , camp.code as camp_code, camp.name as camp_name, land.code as land_code, land.name as land_name,
//            qc.parent_id, qc.name as qc_name,qc.keyword, parent_qc.name as p_chanel_name, parent_qc.code as p_chanel_code");
        $this->db->from($this->_table_name . " as m");


        $this->db->join("dm_chanel as qc", "qc.code = m.code_chanel", 'left');
//        $this->db->join("dm_chanel as parent_qc", "parent_qc.id = qc.parent_id", "left");
//        if ($this->campaign_id || isset($this->custom_conds["custom_where"]['camp.code']) || isset($this->custom_conds["custom_where"]['land.code'])) {
        $this->db->join("gd_campaign_landingpage as c", "m.id_camp_landingpage = c.id", 'left');
//        }
        if ($this->campaign_id) {
            $this->db->where('c.id_campaign', $this->campaign_id);
        }
        if (isset($this->custom_conds["custom_where"]['camp.code'])) {
            $this->db->join("dm_campaign as camp", "camp.id = c.id_campaign", 'left');
        }

        $land_code = NULL;
        if (isset($this->custom_conds["custom_where"]['land.code'])) {
            $this->db->join("dm_landingpage as land", "land.id = c.id_landingpage", 'left');

            /**
             * 30-01-2015
             * DucAnh : chit do khong co landingpage
             */
            if ($this->custom_conds["custom_where"]['land.code'] == "topicanative.vn" || $this->custom_conds["custom_where"]['land.code'] == "tienganh.topicanative.vn" || $this->custom_conds["custom_where"]['land.code'] == "topicanative.edu.vn") {
                $domain = "http://" . $this->custom_conds["custom_where"]['land.code'];
                $this->db->like('m.domain', $domain, "after");
                $land_code = $this->custom_conds["custom_where"]['land.code'];
                unset($this->custom_conds["custom_where"]['land.code']);
            }
            // Het DucAnh chit
        }

        if (isset($this->custom_conds["custom_where"]['datetime_submitted_unix <='])) {
            
        } else {
            $this->db->where('m.datetime_submitted_unix >', strtotime(date('Y-m-d', time())));
            $this->db->where('m.datetime_submitted_unix <=', strtotime(date('Y-m-d', time()) . ' 23:59:59'));
        }

//        var_dump($this->custom_conds["custom_where"]);exit;
        if ($this->custom_conds["custom_where"]) {
            $this->db->where($this->custom_conds["custom_where"]);
        }
        // Chit phan landing page topicanative.vn
        if ($land_code != NULL) {
            $this->custom_conds["custom_where"]['land.code'] = $land_code;
        }

        if (isset($this->custom_conds["custom_like"]["qc.keyword"])) {
            $this->db->like("qc.keyword", $this->custom_conds["custom_like"]["qc.keyword"]);
        }
        $like_array = array();
        if (count($this->custom_conds["custom_like"])) {
            $like = "( ";
            foreach ($this->custom_conds["custom_like"] as $key => $value) {
                if ($key != "qc.keyword") {
                    $like_array [] = "$key LIKE '%$value%'";
                }
            }
            $like .= implode(" OR ", $like_array);
            $like .= " )";

            if (count($like_array)) {
                $this->db->where($like, NULL, FALSE);
            }
        }
        $this->db->group_by('m.id');
        $this->db->where('(m.link_cv is NULL OR m.link_cv = "")');
        if ($this->check_status_filter_day) {
            $this->db->where('m.status_filter_day', '0');
        }
        $this->db->not_like('email', 'email');
        $this->db->not_like('email', 'example');
        $this->db->not_like('email', 'test@gmail.com');
        if ($this->session->userdata("perValue") == 1) {
//             $this->db->get();
//             echo $this->db->last_query();
        }
    }

    function check_phone_mol($where, $type = 'object') {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
        if (is_array($where)) {
            $this->db->where($where);
        } else if (intval($where) > 0) {
            $this->db->where("m." . $this->_key_name, $where);
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            if ($type == 'array' || $type == 'object') {
                return $query->first_row($type);
            } else {
                return $query->first_row();
            }
        } else {
            return null;
        }
    }

    function get_list_contact_handover() {
        $this->setting_select();
        $this->db->not_like('email', 'email');
        $this->db->not_like('email', 'example');
        $this->db->order_by('m.id', 'DESC');
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    function get_contact() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
        $this->db->where('contact_id !=', '');
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

}
