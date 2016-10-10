<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_contact_teacher extends data_base {

    public $campaign_id = 0;
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
            "id", "name", "email", "phone", "school", "sector", "score",
            "keyword_id", "country", "keyword", "graduation", "ip", 'link_cv',
            "id_camp_landingpage", "code_chanel", "datetime_submitted", 'http_referer'
        );
        $this->_rule = Array(
            "id" => "type=hidden"
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
            "m.id" => "ID",
            "m.name" => "Tên",
            "email" => "Email",
            "phone" => "Số điện thoại",
            "code_chanel" => "Mẫu QC",
            "datetime_submitted" => "Ngày đăng ký",
            "m.link_cv" => "File CV",
        );
    }

    public function setting_select() {
        $this->db->select("m.*,qc.keyword,qc.parent_id,qc.code,c.id_campaign,c.id_landingpage");
        $this->db->from($this->_table_name . " as m");


        $this->db->join("dm_chanel as qc", "qc.code = m.code_chanel", 'left');
        $this->db->join("gd_campaign_landingpage as c", "m.id_camp_landingpage = c.id", 'left');
        if ($this->campaign_id) {
            $this->db->where('c.id_campaign', $this->campaign_id);
        }
        if (isset($this->custom_conds["custom_where"]['camp.code'])) {
            $this->db->join("dm_campaign as camp", "camp.id = c.id_campaign", 'left');
        }
        if (isset($this->custom_conds["custom_where"]['datetime_submitted_unix <='])) {
            
        } else {
            $this->db->where('m.datetime_submitted_unix >', strtotime(date('Y-m-d', time())));
            $this->db->where('m.datetime_submitted_unix <=', strtotime(date('Y-m-d', time()) . ' 23:59:59'));
        }

        if ($this->custom_conds["custom_where"]) {
            $this->db->where($this->custom_conds["custom_where"]);
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
        $this->db->where('m.link_cv !=','');
        $this->db->not_like('email', 'email');
        $this->db->not_like('email', 'example');
        $this->db->not_like('email', 'test@gmail.com');
        if ($this->session->userdata("perValue") == 1) {
            // $this->db->get();
            // echo $this->db->last_query();
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
