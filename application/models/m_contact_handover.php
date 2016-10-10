<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class m_contact_handover extends data_base {

    var $custom_conds = array(
        "custom_where" => array(),
        "custom_like" => array()
    );

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "contact_handover";
        $this->_key_name = "contact_id";
        $this->_schema = Array(
            "contact_id", "user_id", "time"
        );
        $this->_rule = Array(
            "id" => "type=hidden"
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
            "m.contact_id" => "ID",
            "c.name" => "Tên",
            "email" => "Email",
//            "country" => "Nơi ở",
//            "graduation" => "Trình độ học vấn",
//            "sector" => "Ngành đăng ký",
            "phone" => "Số điện thoại",
//            "p_chanel_name" => "Kênh",
            "code_chanel" => "Mẫu QC",
            "datetime_submitted" => "Ngày đăng ký",
            "qc.keyword" => "Từ khóa",
        );
    }

    public function setting_select() {
        $this->db->select("m.*,contact.*,qc.keyword,qc.parent_id,qc.code,c.id_campaign,c.id_landingpage");
//            , camp.code as camp_code, camp.name as camp_name, land.code as land_code, land.name as land_name,
//            qc.parent_id, qc.name as qc_name,qc.keyword, parent_qc.name as p_chanel_name, parent_qc.code as p_chanel_code");
        $this->db->from($this->_table_name . " as m");

        $this->db->join("dm_contact as contact", "contact.id = m.contact_id");
        $this->db->join("dm_chanel as qc", "qc.code = contact.code_chanel", 'left');
//        $this->db->join("dm_chanel as parent_qc", "parent_qc.id = qc.parent_id", "left");
//        if ($this->campaign_id || isset($this->custom_conds["custom_where"]['camp.code']) || isset($this->custom_conds["custom_where"]['land.code'])) {
        $this->db->join("gd_campaign_landingpage as c", "contact.id_camp_landingpage = c.id", 'left');
//        }
        if ($this->campaign_id) {
            $this->db->where('c.id_campaign', $this->campaign_id);
        }
        if (isset($this->custom_conds["custom_where"]['camp.code'])) {
            $this->db->join("dm_campaign as camp", "camp.id = c.id_campaign", 'left');
        }
        if (isset($this->custom_conds["custom_where"]['land.code'])) {
            $this->db->join("dm_landingpage as land", "land.id = c.id_landingpage", 'left');
        }

        if (isset($this->custom_conds["custom_where"]['m.time <='])) {
            
        } else {
            $this->db->where('m.time >', strtotime(date('Y-m-d', time())));
            $this->db->where('m.time <=', strtotime(date('Y-m-d', time()) . ' 23:59:59'));
        }

//        var_dump($this->custom_conds["custom_where"]);exit;
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
    }
}
