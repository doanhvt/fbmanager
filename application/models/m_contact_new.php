<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class M_contact_new extends data_base {

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
            "keyword_id", "country", "keyword", "graduation", "ip",
            "id_camp_landingpage", "code_chanel", "datetime_submitted", 'http_referer'
        );
        $this->_rule = Array(
            "id" => "type=hidden"
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
            "m.id" => "ID",
            "m.name" => "Name",
            "email" => "Email",
//            "country" => "Nơi ở",
//            "graduation" => "Trình độ học vấn",
//            "sector" => "Ngành đăng ký",
            "phone" => "Mobile",
//            "p_chanel_name" => "Kênh",
            "code_chanel" => "Ads simple",
            "datetime_submitted" => "Day Registered",
            "qc.keyword" => "Keyword",
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
        if (isset($this->custom_conds["custom_where"]['land.code'])) {
            $this->db->join("dm_landingpage as land", "land.id = c.id_landingpage", 'left');
        }

        if (!isset($this->custom_conds["custom_where"]['datetime_submitted_unix <='])) {
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
        $this->db->not_like('email', 'email');
        $this->db->not_like('email', 'example');
        $this->db->not_like('email', 'test@gmail.com');
    }

    function get_list_contact_handover($count = true, $where = NULL, $limit = 0, $post = 0, $order = NULL) {
        $this->db->select("m.*,qc.keyword,qc.parent_id,qc.code,c.id_campaign,c.id_landingpage");
        $this->db->from($this->_table_name . " as m");

        $this->db->join("dm_chanel as qc", "qc.code = m.code_chanel", 'left');
        $this->db->join("gd_campaign_landingpage as c", "m.id_camp_landingpage = c.id", 'left');
        $this->db->join("dm_campaign as camp", "camp.id = c.id_campaign", 'left');
        $this->db->join("dm_landingpage as land", "land.id = c.id_landingpage", 'left');
        if (is_array($where)) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    $this->db->where_in($key, $value);
                } else {
                    $this->db->where($key, $value);
                }
            }
        } else if (intval($where) > 0) {
            $this->db->where("m." . $this->_key_name, $where);
        }
        $this->db->where('m.datetime_submitted_unix >', 1415293201);

        $this->db->not_like('email', 'email');
        $this->db->not_like('email', 'example');
        $this->db->not_like('email', 'test@gmail.com');


        if ($limit) {
            $this->db->limit($limit, $post);
        }
        if ($order) {
            $this->db->order_by($order);
        } else {
            $this->db->order_by('m.id', 'DESC');
        }
        if ($count) {
            return $this->db->count_all_results();
        } else {
            $query = $this->db->get();
            if ($query) {
                return $query->result();
            } else {
                return NULL;
            }
        }
    }

    function get_contact_status($where) {
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
        if (isset($this->custom_conds["custom_where"]['land.code'])) {
            $this->db->join("dm_landingpage as land", "land.id = c.id_landingpage", 'left');
        }

        if (!isset($this->custom_conds["custom_where"]['datetime_submitted_unix <='])) {
            $this->db->where('m.datetime_submitted_unix >', strtotime(date('Y-m-d', time())));
            $this->db->where('m.datetime_submitted_unix <=', strtotime(date('Y-m-d', time()) . ' 23:59:59'));
        }

        if ($this->custom_conds["custom_where"]) {
            $arr_con = $this->custom_conds["custom_where"];
            unset($arr_con['m.status']);
            unset($arr_con['m.status_mol']);
            $this->db->where($arr_con);
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
        if (is_array($where)) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    $this->db->where_in($key, $value);
                } else {
                    $this->db->where($key, $value);
                }
            }
        }
        $this->db->not_like('email', 'email');
        $this->db->not_like('email', 'example');
        $this->db->not_like('email', 'test@gmail.com');
        $this->db->group_by('m.id');
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

}
