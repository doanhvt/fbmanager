<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of m_campaign
 *
 * @author Loc
 */
class m_campaign_landingpage extends data_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_table() {
        $this->_table_name = "gd_campaign_landingpage";
        $this->_key_name = "id";
        $this->_schema = Array(
            "id", "id_campaign", "id_landingpage", "code_chanel", "url_landingpage", 'status'
        );
        $this->_field_form = Array(
        );
        $this->_field_table = Array(
            "m.id" => "ID",
            "m.id_campaign" => "Chiến dịch",
            "m.id_landingpage" => "Landingpage",
            "m.code_chanel" => "Kênh",
            "m.url_landingpage" => "Đường link",
            "m.status" => "Trạng thái",
        );
    }

    public function setting_select() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " as m");
    }

    public function get_list_campaign_landingpage($id) {
        $this->db->select("m.*,chanel.code,chanel.parent_id,chanel.keyword,parent_chanel.name as parent_name");
        $this->db->from($this->_table_name . " as m");
        $this->db->join("dm_campaign as c", "c.id = m.id_campaign");
        $this->db->join("dm_chanel as chanel", "chanel.code = m.code_chanel");
        $this->db->join("dm_chanel as parent_chanel", "parent_chanel.id = chanel.parent_id");
        $this->db->join("dm_landingpage as l", "l.id = m.id_landingpage");
        $this->db->where('m.id_campaign', $id);
        $this->db->order_by('m.id','desc');
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    public function get_list_campaign_landingpage_test($condition = array(),$group_by = 0) {
        if($group_by){
            $this->db->select("m.*,chanel.code,chanel.name as chanel_name,chanel.parent_id,chanel.keyword,parent_chanel.name as parent_name,count(chanel.parent_id) as total");
        }  else {
            $this->db->select("m.*,chanel.code,chanel.name as chanel_name,chanel.parent_id,chanel.keyword,parent_chanel.name as parent_name");
        }
        
        $this->db->from($this->_table_name . " as m");
        $this->db->join("dm_campaign as c", "c.id = m.id_campaign");
        $this->db->join("dm_chanel as chanel", "chanel.code = m.code_chanel");
        $this->db->join("dm_chanel as parent_chanel", "parent_chanel.id = chanel.parent_id");
        $this->db->join("dm_landingpage as l", "l.id = m.id_landingpage");
        if($condition){
          $this->db->where($condition);  
        }
        $this->db->order_by('m.id','desc');
        if($group_by){
            $this->db->group_by('chanel.parent_id');
        }
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }
    
    function get_info_campaign($id) {
        $this->db->select("m.*");
        $this->db->from("dm_campaign as m");
        $this->db->where("m.id", $id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->first_row();
        } else {
            return NULL;
        }
    }

    function get_list_landingpage() {
        $this->db->select("m.*");
        $this->db->from("dm_landingpage as m");
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    function get_list_chanel($parentID = 0) {
        $this->db->select("m.*");
        $this->db->from("dm_chanel as m");
        if ($parentID) {
            $this->db->where("m.parent_id !=", 0);
        } else {
            $this->db->where("m.parent_id", 0);
        }

        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    function get_list_chanel_custom($parentID = 0) {
        $this->db->select("m.*");
        $this->db->from("dm_chanel as m");
        if ($parentID) {
            $this->db->where("m.parent_id !=", 0);
            $this->db->where("m.parent_id !=", -100);
            $this->db->order_by('m.parent_id');
        } else {
            $perId = $this->session->userdata('perId');
            if ($perId == 3) {
                $id = $this->session->userdata('id');
                $this->db->join("gd_assign_chanel as ass", "m.id = ass.chanel_id");
                $this->db->where("ass.user_id", $id);
            }  else {
                 $this->db->where("m.parent_id", 0);
            }
           $this->db->group_by('m.id');
        }
        
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    function get_list_chanel_parent_custom() {
        $this->db->select("m.*");
        $this->db->from("dm_chanel as m");

        $perId = $this->session->userdata('perId');
        if ($perId == 3) {
            $id = $this->session->userdata('id');
            $this->db->join("gd_assign_chanel as ass", "m.id = ass.chanel_id");
            $this->db->where("ass.user_id", $id);
        }
        $this->db->where("m.parent_id !=", 0);

        $this->db->group_by('m.parent_id');
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        } else {
            return NULL;
        }
    }
    

}
