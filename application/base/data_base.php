<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Data_base
 *
 * Lớp abstract chung của cá model
 *
 * @package data_base
 * @author Pham Trong <phamtrong204@gmail.com>
 * @version 0.0.0
 */
abstract class data_base extends CI_Model {

        // var $server_LMS = "http://payment.topmito.edu.vn/lms/webservice/rest/server.php";
        // var $wstoken_LMS = "cbb62f3072143a940d860a338427839a";
//
   var $server_LMS = "http://beta.tpe.topica.vn/webservice/rest/server.php";
   var $wstoken_LMS = "cbb62f3072143a940d860a338427839a";

    /**
     * Mảng cấu trúc schema của table
     * @var Array
     */
    var $_schema = Array();

    /**
     * Mảng cấu trúc luật các trường của table,
     * @var Array
     */
    var $_rule = Array();

    /**
     * Mảng nhãn hiển thị trong form, cũng là mảng tạo form
     * @var Array
     */
    var $_field_form = Array();

    /**
     * Mảng các trường hiển thị trên bảng quản lý
     * @var Array
     */
    var $_field_table = Array();

    /**
     * Tên bảng mà model đang tương tác
     * @var String
     */
    var $_table_name = "";

    /**
     * Trường key của model đang tương tác
     * @var String
     */
    var $_key_name = "";
    var $moodle_prefix = "mdl_";

    public function __construct() {
        parent::__construct();
        $this->setting_table();
    }

    abstract function setting_table();

    /**
     * Hàm cài đặt các trường lấy ra trong câu truy vấn get_one và get_list
     */
    function setting_select() {
        $this->db->select("m.*");
        $this->db->from($this->_table_name . " AS m");
    }

    /**
     *
     * @param Int|Array     $where  Điều kiện dạng tùy biến int hoặc Array.
     *                              <p>Nếu là Array thì điều kiện là $key=$where[$key]</p>
     *                              <p>Nếu là Int thì điều kiện là $this->key=$where</p>
     * @param Int           $limit  Số item lấy ra (LIMIT trong SQL)
     * @param Int           $post   Vị trí bắt đầu lấy ra (POST trong SQL)
     * @param String        $order  Điều kiện dạng tùy biến String dạng 'title DESC, name ASC'
     * @return Array        Mảng các object tương tự $schema
     */
    function get_list($where = NULL, $limit = 0, $post = 0, $order = NULL) {
        $this->setting_select();
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
        if ($limit) {
            $this->db->limit($limit, $post);
        }
        if ($order) {
            $this->db->order_by($order);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_list_in($where = NULL, $limit = 0, $post = 0, $order = NULL) {
        $this->setting_select();
        if (is_array($where)) {
            $this->db->where_in("m." . $this->_key_name, $where);
        } else if (intval($where) > 0) {
            $this->db->where("m." . $this->_key_name, $where);
        }
        if ($limit) {
            $this->db->limit($limit, $post);
        }
        if ($order) {
            $this->db->order_by($order);
        }
        $query = $this->db->get();
        return $query->result();
    }

    /**
     *
     * @param String        $search_text Từ khóa tìm kiếm
     * @param Array         $where  Mảng các trường cần tìm
     * @param Int           $limit  Số item lấy ra (LIMIT trong SQL)
     * @param Int           $post   Vị trí bắt đầu lấy ra (POST trong SQL)
     * @param String        $order  Điều kiện dạng tùy biến String dạng 'title DESC, name ASC'
     * @return Array        Mảng các object tương tự $schema
     */
    function get_list_table($search_text = "", $whereCondition = NULL, $limit = 0, $post = 0, $order = NULL) {
        $this->setting_select();
        $where = $this->_field_table;
        $search_text = trim($search_text);
        if (is_array($where) && strlen($search_text)) {
            $like_agr = Array();
            $like_search = $this->db->escape_like_str($search_text);
            foreach ($where as $key => $value) {
                $rule = isset($this->_rule[$key]) ? $this->_rule[$key] : FALSE;
                $x_key = $key;
                if ($rule) {
                    $temp = explode(" ", $rule); // Tách các rule
                    $list_rule = Array();
                    foreach ($temp as $rule_item) {
                        $rule_piece = explode("=", $rule_item);
                        if (sizeof($rule_piece) < 2) {
                            $list_rule[$rule_piece[0]] = NULL;
                        } else {
                            $list_rule[$rule_piece[0]] = $rule_piece[1];
                        }
                    }
                    if (isset($list_rule['type']) && $list_rule['type'] == "datetime") {
                        $x_key = " DATE_FORMAT(" . $x_key . ", '%d-%m-%Y %H:%i:%s')";
                    }
                    if (isset($list_rule['real_field'])) {/* Nếu rule có real_field thì lấy key ở real_fiel */
                        $x_key = $list_rule['real_field'];
                    }
                    if (isset($list_rule['disable_search'])) {
                        $x_key = FALSE;
                    }
                }
                if ($x_key !== FALSE) {
                    $like_agr[] = $x_key . " LIKE '%" . $like_search . "%'";
                }
            }
            if (count($like_agr)) {
                $this->db->where(" ( " . implode(" OR ", $like_agr) . " ) ", NULL, false);
            }
        }
        if (is_array($whereCondition)) {
            $this->db->where($whereCondition);
        } else if (intval($whereCondition) > 0) {
            $this->db->where("m." . $this->_key_name, $whereCondition);
        }
        if ($limit) {
            $this->db->limit($limit, $post);
        }
        if ($order && strlen($order)) {
            $this->db->order_by($order);
        } else {
            $this->db->order_by("m." . $this->_key_name, "DESC");
        }
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        return $query->result();
    }

    /**
     * Hàm lấy dữ liệu trong các select (liên kết 1-*)
     * @param String $selectString  Chuỗi select
     * @param Array         $where  Mảng các trường cần tìm
     * @param Int           $limit  Số item lấy ra (LIMIT trong SQL)
     * @param Int           $post   Vị trí bắt đầu lấy ra (POST trong SQL)
     * @param String        $order  Điều kiện dạng tùy biến String dạng 'title DESC, name ASC'
     * @return Array        Mảng các object tương tự $schema
     */
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
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Hàm lấy thông tin của 1 row với điều kiện đầu vào
     * @param Int|Array $where  Điều kiện dạng tùy biến int hoặc Array;
     *                          <p>Nếu là Array thì điều kiện là $key=$where[$key]</p>
     *                          <p>Nếu là Int thì điều kiện là $this->key=$where</p>
     * @param string    $type   Kiểu dữ liệu trả về: <b>'array'</b> hoặc <b>'object'</b>
     * @return Object   Object có cấu trúc tương tự $schema
     */
    public function get_one($where, $type = 'object') {
        $this->setting_select();
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

    /**
     * Hàm thêm một row
     * @param   Array   $data   Là mảng có cấu trúc tương tự $schema
     * @return  Int     Id của row vừa được thêm vào
     */
    public function add($data) {
        $this->db->insert($this->_table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * Hàm thêm nhiều row cùng lúc trong 1 câu truy vấn
     * @param   Array   $data   Là MẢNG CÁC MẢNG có cấu trúc tương tự $schema
     * @return  Int     Số row được thêm vào
     */
    function add_muti($data) {
        $this->db->insert_batch($this->_table_name, $data);
        return $this->db->affected_rows();
    }

    /**
     * Hàm Cập nhập thông tin một row với điều kiện và dữ liệu nhập vào
     * @param Int|Array $where  Điều kiện dạng tùy biến int hoặc Array;
     *                          <p>Nếu là Array thì điều kiện là $key=$where[$key]</p>
     *                          <p>Nếu là Int thì điều kiện là $this->key=$where</p>
     * @param Array     $data   Dữ liệu update dạng mảng tương tự mảng $schema
     * @return Int      Số row được update
     */
    public function update($where, $data) {
        if (is_array($where)) {
            $this->db->where($where);
        } else if (intval($where) > 0) {
            $this->db->where($this->_key_name, $where);
        } else if (strlen($where) > 0) {
            $this->db->where($this->_key_name, $where);
        } else {
            return false;
        }
        if ($this->db->field_exists('editable', $this->_table_name)) {
            $this->db->where('editable', '1');
        }
        $this->db->update($this->_table_name, $data);
        return $this->db->affected_rows();
    }

    public function update_list($where, $data) {
        if (is_array($where)) {
            $this->db->where_in($this->_key_name, $where);
        }
        if ($this->db->field_exists('editable', $this->_table_name)) {
            $this->db->where('editable', '1');
        }
        $this->db->update($this->_table_name, $data);
        return $this->db->affected_rows();
    }

    /**
     * Hàm xóa row với điều kiện nhập vào
     * @param Int|Array $where  Điều kiện dạng tùy biến int hoặc Array;
     *                          <p>Nếu là Array thì điều kiện là $key=$where[$key]</p>
     *                          <p>Nếu là Int thì điều kiện là $this->key=$where</p>
     * @return  Int     Số row bị xóa
     */
    public function delete_by_custom($where) {
        if (is_array($where)) {
            $this->db->where($where);
        } else if (intval($where) > 0) {
            $this->db->where($this->_key_name, $where);
        } else {
            return false;
        }
        $this->db->delete($this->_table_name);
        return $this->db->affected_rows();
    }

    /**
     * Hàm xóa row với điều kiện nhập vào
     * @param Int|Array $where  Điều kiện dạng tùy biến int hoặc Array;
     *                          <p>Nếu là Array thì điều kiện là WHERE_IN $this->key=$where</p>
     *                          <p>Nếu là Int thì điều kiện là WHERE $this->key=$where</p>
     * @return  Int     Số row bị xóa
     */
    public function delete_by_id($where) {
        if (is_array($where)) {
            $this->db->where_in($this->_key_name, $where);
        } else if (intval($where) > 0) {
            $this->db->where($this->_key_name, $where);
        } else {
            $this->db->where($this->_key_name, json_encode($where));
        }
        if ($this->db->field_exists('editable', $this->_table_name)) {
            $this->db->where('editable', '1');
        }
        $this->db->delete($this->_table_name);
        return $this->db->affected_rows();
    }

    /**
     * Hàm kiểm tra xem có thể edit hay ko với điều kiện nhập vào
     * @param Int|Array $where  Điều kiện dạng tùy biến int hoặc Array;
     *                          <p>Nếu là Array thì điều kiện là WHERE_IN $this->key=$where</p>
     *                          <p>Nếu là Int thì điều kiện là WHERE $this->key=$where</p>
     * @return  Int     1:có thể edit, 0: ko thể edit
     */
    public function is_editable($where) {
        if ($this->db->field_exists('editable', $this->_table_name)) {
            $this->db->select('editable');
            $this->db->where('editable', '1');
            if (is_array($where)) {
                $this->db->where($where);
            } else if (intval($where) > 0) {
                $this->db->where($this->_key_name, $where);
            }
            $this->db->from($this->_table_name);
            $result = $this->db->get();
            if ($result->num_rows() > 0) {
                return $result->first_row()->editable;
            } else {
                return 0;
            }
        } else {
            return 1;
        }
    }

    /**
     * Hàm kiểm tra xem đã có trường nào có giá trị sắp thêm chưa
     * @param String $key   trường cần kiểm tra
     * @param String $value Giá trị cần kiểm tra
     * @return  Int  ID của row trùng
     */
    public function check_existed($key, $value, $id = 0) {
        $this->db->select($this->_key_name);
        $this->db->from($this->_table_name);
        $this->db->where($key, $value);
        $this->db->where($this->_key_name . ' !=', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $temp = $this->_key_name;
            return $query->first_row()->$temp;
        } else {
            return false;
        }
    }

    /**
     * Hàm lấy thông tin về rule của các trường
     * @return Array Mảng các rule
     */
    function get_rule() {
        return $this->_rule;
    }

    /**
     * Hàm lấy thông tin label của các trường
     * @return Array Mảng các label tương ứng với các trường trong Schema
     */
    function get_field_form() {
        return $this->_field_form;
    }

    /**
     * Hàm lấy thông tin schema của bảng
     * @return Array Mảng schema của bảng
     */
    function get_schema() {
        return $this->_schema;
    }

    /**
     * Hàm lấy thông tin schema của bảng
     * @return Array Mảng schema của bảng
     */
    function get_field_table() {
        return $this->_field_table;
    }

    /**
     * Hàm lấy thông tin để tạo form
     * @return Array Mảng các mảng chứa thông tin để tạo form
     */
    function get_form() {
        $data = Array();
        $data["schema"] = $this->_schema;
        $data["rule"] = $this->_rule;
        $data["field_form"] = $this->_field_form;
        $data["field_table"] = $this->_field_table;
        return $data;
    }

    /**
     * Hàm lấy thông tin để tạo form
     * @return Array Mảng các mảng chứa thông tin để tạo form
     */
    function get_key_name() {
        return $this->_key_name;
    }

    /**
     * Hàm lấy thông tin để tạo form
     * @return Array Mảng các mảng chứa thông tin để tạo form
     */
    function get_table_name() {
        return $this->_table_name;
    }

}

/* End of file data_base.php */
/* Location: ./application/base/data_base.php */