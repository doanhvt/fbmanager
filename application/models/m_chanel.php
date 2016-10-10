<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of m_campaign
 *
 * @author Loc
 */
class m_chanel extends data_base {

    var $view_table = true;

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "dm_chanel";
        $this->_key_name = "id";
        $this->_schema = Array(
            "id", "name", "description", 'code', "keyword", "parent_id"
        );
        $this->_rule = Array(
            "id" => "type=hidden",
            "name" => "type=text maxlength=255 required=required",
            "code" => "type=text maxlength=255 required=required unique=true",
            "description" => "type=textarea",
            "keyword" => "type=text",
            "ass_name" => "real_field=account.userDisplayName"
        );
        $this->_field_form = Array(
            "id" => "ID",
            "code" => "Code",
            "name" => "name",
            "keyword" => "Keyword",
            "description" => "Description"
        );
        $this->_field_table = Array(
            "m.id" => "ID",
            "m.code" => "Code",
            "m.name" => "Name of Channel",
            "ass_name" => "Administrator"
        );
    }

    public function setting_select() {
        $this->db->select("m.*,
        (SELECT GROUP_CONCAT(account.userDisplayName SEPARATOR ',')
            FROM gd_assign_chanel AS ass 
            LEFT JOIN  admin_account AS account ON ass.user_id = account.userId
            WHERE ass.chanel_id = m.id 
        )AS ass_name
        ", FALSE);
        $this->db->from($this->_table_name . " as m");
        $perId = $this->session->userdata('perId');
        if ($perId == 3) {
            $id = $this->session->userdata('id');
            if ($this->view_table == true) {
                $this->db->join("gd_assign_chanel as ass", "m.id = ass.chanel_id");
                $this->db->where("ass.user_id", $id);
            }
        } else {
            if ($this->view_table == true) {
                $this->db->where("m.parent_id", 0);
                $this->db->where("m.id !=", -100);
            }
        }
        $this->db->where("m.status !=", 0);
    }

    public function get_list_chanel_parent($condition = array()) {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
        $this->db->where("m.status != ", 0);
        if ($condition) {
            $this->db->where($condition);
        } else {
            $this->db->where("m.parent_id", 0);
            $this->db->or_where("m.parent_id", -100);
        }

        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    public function get_list_chanel_parent_test($condition = array()) {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
        if ($condition) {
            $this->db->where($condition);
        } else {
            $this->db->where("m.parent_id", 0);
            $this->db->or_where("m.parent_id", -100);
        }
        $this->db->where("m.status !=", 0);
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    public function get_list_mauqc($condition = array()) {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
        if ($condition) {
            $this->db->where($condition);
        } else {
            $this->db->where("m.parent_id !=", 0);
            $this->db->where("m.parent_id !=", -100);
        }
        $this->db->where("m.status !=", 0);
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    public function get_data_report($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain = 0) {
        if ($time_begin && $time_end) {
            if ($id_campaign) {
                if ($id_landingpage) {
                    $this->db->select("m.*,
                    (SELECT(count(log.code_chanel))
                        FROM gd_log AS log
                        LEFT JOIN gd_campaign_landingpage AS cam_lan ON cam_lan.id = log.id_camp_landingpage
                        WHERE log.code_chanel = m.code 
                            AND log.datetime >= " . "'" . $time_begin . "'" . " 
                            AND log.datetime <= " . "'" . $time_end . "23:59:59'" . "  
                            AND cam_lan.id_campaign = " . "'" . $id_campaign . "'" . " 
                            AND cam_lan.id_landingpage = " . "'" . $id_landingpage . "'" . "

                    )AS total_click,
                    (SELECT(count(contact.code_chanel))
                        FROM dm_contact AS contact
                        LEFT JOIN gd_campaign_landingpage AS cam_lan ON cam_lan.id = contact.id_camp_landingpage
                        WHERE contact.code_chanel = m.code 
                            AND contact.datetime_submitted >= " . "'" . $time_begin . "'" . " 
                            AND contact.datetime_submitted <= " . "'" . $time_end . "23:59:59'" . " 
                            AND cam_lan.id_campaign = " . "'" . $id_campaign . "'" . " 
                            AND cam_lan.id_landingpage = " . "'" . $id_landingpage . "'" . "
                                
                    )AS total_submit
                    ");
                } else {
                    $this->db->select("m.*,
                    (SELECT(count(log.code_chanel))
                        FROM gd_log AS log
                        LEFT JOIN gd_campaign_landingpage AS cam_lan ON cam_lan.id = log.id_camp_landingpage
                        WHERE log.code_chanel = m.code 
                            AND log.datetime >= " . "'" . $time_begin . "'" . " 
                            AND log.datetime <= " . "'" . $time_end . "23:59:59'" . "  
                            AND cam_lan.id_campaign = " . "'" . $id_campaign . "'" . "

                    )AS total_click,
                    (SELECT(count(contact.code_chanel))
                        FROM dm_contact AS contact
                        LEFT JOIN gd_campaign_landingpage AS cam_lan ON cam_lan.id = contact.id_camp_landingpage 
                        WHERE contact.code_chanel = m.code 
                            AND contact.datetime_submitted >= " . "'" . $time_begin . "'" . " 
                            AND contact.datetime_submitted <= " . "'" . $time_end . "23:59:59'" . " 
                            AND cam_lan.id_campaign = " . "'" . $id_campaign . "'" . "
                    )AS total_submit
                    ");
                }
            } else {
                if ($id_landingpage) {
                    $this->db->select("m.*,
                    (SELECT(count(log.code_chanel))
                        FROM gd_log AS log
                        LEFT JOIN gd_campaign_landingpage AS cam_lan ON cam_lan.id = log.id_camp_landingpage
                        WHERE log.code_chanel = m.code 
                            AND log.datetime >= " . "'" . $time_begin . "'" . " 
                            AND log.datetime <= " . "'" . $time_end . "23:59:59'" . " 
                            AND cam_lan.id_landingpage = " . "'" . $id_landingpage . "'" . "

                    )AS total_click,
                    (SELECT(count(contact.code_chanel))
                        FROM dm_contact AS contact
                        LEFT JOIN gd_campaign_landingpage AS cam_lan ON cam_lan.id = contact.id_camp_landingpage 
                        WHERE contact.code_chanel = m.code 
                            AND contact.datetime_submitted >= " . "'" . $time_begin . "'" . " 
                            AND contact.datetime_submitted <= " . "'" . $time_end . "23:59:59'" . " 
                            AND cam_lan.id_landingpage = " . "'" . $id_landingpage . "'" . "
                    )AS total_submit
                    ");
                } else {
                    $this->db->select("m.*,
                    (SELECT(count(log.code_chanel))
                        FROM gd_log AS log
                        LEFT JOIN gd_campaign_landingpage AS cam_lan ON cam_lan.id = log.id_camp_landingpage
                        WHERE log.code_chanel = m.code 
                            AND log.datetime >= " . "'" . $time_begin . "'" . " 
                            AND log.datetime <= " . "'" . $time_end . "23:59:59'" . "  

                    )AS total_click,
                    (SELECT(count(contact.code_chanel))
                        FROM dm_contact AS contact
                        LEFT JOIN gd_campaign_landingpage AS cam_lan ON cam_lan.id = contact.id_camp_landingpage 
                        WHERE contact.code_chanel = m.code 
                            AND contact.datetime_submitted >= " . "'" . $time_begin . "'" . " 
                            AND contact.datetime_submitted <= " . "'" . $time_end . "23:59:59'" . " 
                    )AS total_submit
                    ");
                }
            }
        }
        $this->db->from($this->_table_name . " as m");
        $this->db->where('m.parent_id !=', 0);
        if ($code_chanel) {
            $this->db->join("dm_chanel as parent_chanel", "parent_chanel.id = m.parent_id", 'left');
            $this->db->where('parent_chanel.code', $code_chanel);
        }
        $this->db->where("m.status !=", 0);
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    function get_list_option($selectString = NULL, $where = Array(), $limit = 0, $post = 0, $order = NULL) {
        if ($selectString && strlen($selectString)) {
            $this->db->select($selectString);
        } else {
            return Array();
        }
        if (is_array($where)) {
            $this->db->where($where);
        } else if (intval($where) > 0) {
            $this->db->where($this->_key_name, $where);
        }
        if ($limit) {
            $this->db->limit($limit, $post);
        }
        if ($order) {
            $this->db->order_by($order);
        }
        $this->db->from($this->_table_name . " as m");
        $this->db->where("m.parent_id", 0);
        $this->db->where("m.status !=", 0);
        $query = $this->db->get();
        return $query->result();
    }

    function get_one_chanel() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
        $this->db->where("m.id", -100);
        $this->db->where("m.status !=", 0);
        $query = $this->db->get();
        if ($query) {
            return $query->first_row();
        } else {
            return NULL;
        }
    }

    public function get_list_chanel($parent) {
        $this->db->select("m.*, cl.id_campaign, cl.status as stage, cl.id_landingpage, l.name name_landingpage, cl.url_landingpage, l.url, cl.id id_gd");
        $this->db->from("dm_chanel m");
        $this->db->join("gd_campaign_landingpage cl", "m.code = cl.code_chanel" , "left");
        $this->db->join("dm_landingpage l", "cl.id_landingpage = l.id", "left");
        $this->db->where("m.parent_id", $parent);
//        $this->db->group_by("m.code");
        $this->db->order_by("m.id", "DESC");
        $result = $this->db->get();
        return $result->result();
    }

}
