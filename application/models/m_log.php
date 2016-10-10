<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of m_campaign
 *
 * @author Loc
 */
class m_log extends data_base {

    var $custom_conds = array(
        "custom_where" => array(),
        "custom_like" => array()
    );

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "gd_log";
        $this->_key_name = "id";
        $this->_schema = Array(
            "id", "id_camp_landingpage", "code_chanel", "datetime", "ip", 'action'
        );
        $this->_rule = Array(
            'name_chanel' => 'real_field=c.name '
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
            "m.id" => "ID",
            "name_chanel" => "Channel",
            "m.datetime" => "Time of Click",
            "m.ip" => "IP",
            "cam_lan.url_landingpage" => "Link",
        );
    }

    public function setting_select() {
        $this->db->select("m.id,m.datetime,m.ip,c.name as name_chanel,c.id as id_campaign,cam_lan.url_landingpage");
        $this->db->from($this->_table_name . " as m");
        $this->db->join("dm_chanel as c", "c.code = m.code_chanel", 'left');
        $this->db->join("gd_campaign_landingpage as cam_lan", "cam_lan.id = m.id_camp_landingpage", 'left');

        if ($this->custom_conds["custom_where"]) {

            $this->db->where($this->custom_conds["custom_where"]);
        }
        $this->db->order_by('m.ip','DESC');
    }

    public function get_data_click($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain = 0) {
        $this->db->select("m.id,cam_lan.code_chanel,m.datetime,chanel.name as name_chanel,chanel.parent_id,,chanel.id as id_ads");
        $this->db->from($this->_table_name . " as m");
        $this->db->join("dm_chanel as chanel", "chanel.code = m.code_chanel", 'left');
        $this->db->join("gd_campaign_landingpage as cam_lan", "cam_lan.id = m.id_camp_landingpage", 'left');
        
        $this->db->where("chanel.status != ", 0);
        if ($id_campaign) {
            $this->db->where('cam_lan.id_campaign', $id_campaign);
        }
        if ($code_chanel) {
            $this->db->where('chanel.parent_id', $code_chanel);
        }
        if ($time_begin && $time_end) {
            $this->db->where('m.datetime_unix >=', $time_begin);
            $this->db->where('m.datetime_unix <=', $time_end);
        }
        if ($id_landingpage) {
            if (!$id_campaign && !$code_chanel) {
                $this->db->where('m.domain', $domain);
            } else {
                $this->db->where('cam_lan.id_landingpage', $id_landingpage);
            }
        }
        $query = $this->db->get();
//        echo $this->db->last_query();
//        exit;
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    public function get_data_submit($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain = 0) {
        $this->db->select("m.id,m.id_camp_landingpage,cam_lan.code_chanel,m.datetime_submitted,chanel.name as name_chanel,chanel.parent_id,chanel.id as id_ads");
        $this->db->from("dm_contact as m");
        $this->db->join("dm_chanel as chanel", "chanel.code = m.code_chanel", 'left');
        $this->db->join("gd_campaign_landingpage as cam_lan", "cam_lan.id = m.id_camp_landingpage", 'left');
        
        $this->db->where("chanel.status != ", 0);
        if ($id_campaign) {
            $this->db->where('cam_lan.id_campaign', $id_campaign);
        }

        if ($code_chanel) {
            $this->db->where('chanel.parent_id', $code_chanel);
        }
        if ($time_begin && $time_end) {
            $this->db->where('m.datetime_submitted_unix >=', $time_begin);
            $this->db->where('m.datetime_submitted_unix <=', $time_end);
        }
        if ($id_landingpage) {
            if (!$id_campaign && !$code_chanel) {
                $this->db->where('m.domain', $domain);
            } else {
                $this->db->where('cam_lan.id_landingpage', $id_landingpage);
            }
        }
        $this->db->group_by('m.id');
        $this->db->where('m.status_filter_day', '0');
        $this->db->not_like('m.email', 'email');
        $this->db->not_like('m.email', 'example');
        $this->db->not_like('m.email', 'test@gmail.com');
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

}
