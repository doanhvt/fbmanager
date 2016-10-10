<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Manager_base
 *
 * Lớp abstract quản lý thông tin bảng của hệ thống admin
 *
 * @package manager_base
 * @author Pham Trong <phamtrong204@gmail.com>
 * @version 0.0.0
 */
abstract class manager_base extends home_base {

    /**
     * Mảng config URL được dùng trong các hàm quản lý
     * Biến này được mô tả chi tiết trong lớp này <b>sau khi hàm setting_class được gọi</b>
     * Cấu trúc mảng:
     * <pre>
     * Array (
     *      "view"      => "", <i>String: Url <b>Xem chi tiết</b> bản ghi</i>
     *      "add"       => "", <i>String: Url <b>Thêm</b> bản ghi</i>
     *      "edit"      => "", <i>String: Url <b>Sửa</b> bản ghi</i>
     *      "delete"    => "", <i>String: Url <b>Xóa</b> bản ghi</i>
     *      "manager"   => "", <i>String: Url <b>Quản lý</b> các bản ghi</i>
     *      "search"    => "", <i>String: Url <b>Tìm kiếm</b> bản ghi</i>
     * )
     * </pre>
     * @var Array ()
     */
    var $url = Array(
        "view" => "",
        "add" => "",
        "edit" => "",
        "delete" => "",
        "manager" => "",
        "search" => ""
    );

    /**
     * Mảng config name được dùng trong các hàm quản lý
     * <b>Biến này được mô tả chi tiết trong các lớp kế thừa</b>
     * Cấu trúc mảng:
     * <pre>
     * Array (
     *      "class"  => "", <i>String: Tên class
     *      "view"   => "", <i>String: Tên view
     *      "model"  => "", <i>String: Tên model
     *      "object" => "", <i>String: Tên hiển thị của object
     * )
     * </pre>
     * @var Array
     */
    var $name = Array(
        "class" => "",
        "view" => "",
        "model" => "",
        "object" => ""
    );

    /**
     * Domain upload ảnh
     * @var String
     */
    var $image_domain = 'http://localhost/i1/';

    /**
     * Đường dẫn tương đối tới chỗ lưu ảnh
     * @var String
     */
    var $image_path = "../i1/";

    /**
     * folder lưu ảnh của người dùng
     * @var type
     */
    var $user_folder = 'user/';

    /**
     * folder temp
     * @var String
     */
    var $temp_folder = "/.temp/";

    /**
     * Các biến config upload file
     * @var type Array
     */
    var $fileUploadConfig = Array(
        'allowed_types' => 'gif|jpg|png',
        'max_size' => '2048',
        'encrypt_name' => false
    );

    /**
     * Kích thước thư mục của người dùng
     * @var int
     */
    var $user_folder_max_size = 10485760;

    /**
     * Số nút hiển thị ở khu vực phân trang
     * @var type
     */
    var $pagging_item_display = 7;

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
        $this->setting_class();

        $this->load->model($this->name["model"], "data");
        $this->url["add"] = site_url($this->name["class"] . "/add");
        $this->url["view"] = $this->name["class"] . "/view/";
        $this->url["edit"] = $this->name["class"] . "/edit/";
        $this->url["delete"] = $this->name["class"] . "/delete/";
        $this->url["manager"] = site_url($this->name["class"] . "/manager");
        $this->url["search"] = site_url($this->name["class"] . "/search");
    }

    /**
     * Hàm index, tự động gọi tới hàm manager
     */
    public function index() {
        $this->manager();
    }

    /**
     * Hàm cài đặt biến $name cho controller (xem trong 1 controller bất kỳ để biết chi tiết)
     */
    abstract function setting_class();

    /**
     * Hàm gọi view hiển thị form <b>thêm</b> bản ghi
     * @param Array $data Biến muốn gửi thêm để hiển thị ra view(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     * @return action trả dữ liệu về phía client (json nếu là ajax, html nếu ko)
     */
    public function add($data = Array()) {
        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        if (!isset($data["save_link"])) {
            $data["save_link"] = site_url($this->name["class"] . "/add_save");
        }
        if (!isset($data["list_input"])) {
            $data["list_input"] = $this->_get_form();
        }
        $data["title"] = $title = "Add " . $this->name["object"];

        $viewFile = "base_manager/default_form";
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'form.php')) {
            $viewFile = $this->name["view"] . '/' . 'form';
        }
        $content = $this->load->view($this->path_theme_view . $viewFile, $data, true);
        $data_return["callback"] = "get_form_add_response";
        if ($this->input->is_ajax_request()) {
            $data_return["state"] = 1;
            $data_return["html"] = $content;
            echo json_encode($data_return);
            return TRUE;
        }
        $head_page = $this->load->view($this->path_theme_view . 'base_manager/header_add', $data, true);
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'header.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header', $data, true);
        }
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'head_add.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header_add', $data, true);
        }

        $title = "Thêm " . $this->name["object"];

        $this->master_page($content, $head_page, $title);
    }

    /**
     * Hàm xử lý lưu trữ bản ghi mới
     * @param Array $data Biến muốn gửi thêm để <b>hiển thị ra view</b>(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     * @param Array $data_return Biến muốn gửi thêm <b>vào kết quả trả về</b>(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     * @param boolean $re_validate Có cần validate lại dữ liệu hay không?
     * @return action trả dữ liệu về phía client (json nếu là ajax, html nếu ko)
     */
    public function add_save($data = Array(), $data_return = Array(), $re_validate = true) {
        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        $data_return["callback"] = "save_form_add_response";
        if (sizeof($data) == 0) {
            $data = $this->input->post();
        }
        if ($re_validate) {
            $data_all = $this->_validate_form_data($data);
            if (!$data_all["state"]) {
                $data_return["data"] = $data;
                $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
                $data_return["msg"] = "Dữ liệu gửi lên không hợp lệ";
                $data_return["error"] = $data_all["error"];
                echo json_encode($data_return);
                return FALSE;
            } else {
                $data = $data_all["data"];
            }
        }
        $insert_id = $this->data->add($data);
        $data[$this->data->get_key_name()] = $insert_id;
        if ($insert_id) {
            $data_return["key_name"] = $this->data->get_key_name();
            $data_return["record"] = $data;
            $data_return["state"] = 1; /* state = 1 : insert thành công */
            $data_return["msg"] = "Thêm bản ghi thành công";
            $data_return["redirect"] = $this->url["manager"];
            echo json_encode($data_return);
            return $insert_id;
        } else {
            $data_return["state"] = 2; /* state = 2 : Lỗi thêm bản ghi */
            $data_return["msg"] = "Thêm bản ghi thất bại, vui lòng thử lại sau";
            echo json_encode($data_return);
            return FALSE;
        }
    }

    /**
     * Hàm gọi view hiển thị form <b>sửa</b> bản ghi<br>
     * Trong cơ sở dữ liệu có trường 'is_editable' = 0 thì sẽ ko chỉnh sửa được
     * @param int $id id của bản ghi cần sửa
     * @param Array $data Biến muốn gửi thêm để hiển thị ra view(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     * @return json trả dữ liệu về phía client JSON
     */
    public function edit($id = 0, $data = Array()) {
        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        $data_return["callback"] = "get_form_edit_response";
        if (!$id) {
            $data_return["state"] = 0;
            $data_return["msg"] = "Id không tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
        if (!$this->data->is_editable($id)) {
            $data_return["state"] = 0;
            $data_return["msg"] = "Bản ghi không thể sửa đổi hoặc bản ghi không còn tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
        if (!isset($data["save_link"])) {
            $data["save_link"] = site_url($this->name["class"] . "/edit_save/" . $id);
        }
        if (!isset($data["list_input"])) {
            $data["list_input"] = $this->_get_form($id);
        }
        $data["title"] = $title = "Edit " . $this->name["object"];
        $viewFile = "base_manager/default_form";
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'form.php')) {
            $viewFile = $this->name["view"] . '/' . 'form';
        }
        $content = $this->load->view($this->path_theme_view . $viewFile, $data, true);

        $data_return["record_data"] = $this->data->get_one($id);
        if ($this->input->is_ajax_request()) {
            $data_return["state"] = 1;
            $data_return["html"] = $content;
            echo json_encode($data_return);
            return TRUE;
        }
        $head_page = $this->load->view($this->path_theme_view . 'base_manager/header_edit', $data, true);
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'header.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header', $data, true);
        }
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'header_edit.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header_edit', $data, true);
        }
        $title = "Sửa " . $this->name["object"];

        $this->master_page($content, $head_page, $title);
    }

    /**
     * Hàm xử lý lưu trữ bản ghi mới
     * Trong cơ sở dữ liệu có trường 'is_editable' = 0 thì sẽ ko chỉnh sửa được
     * @param int $id id của bản ghi cần sửa
     * @param Array $data Biến muốn gửi thêm để <b>hiển thị ra view</b>(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     * @param Array $data_return Biến muốn gửi thêm <b>vào kết quả trả về</b>(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     * @param boolean $re_validate Có cần validate lại dữ liệu hay không?
     * @return json trả dữ liệu về phía client JSON
     */
    public function edit_save($id = 0, $data = Array(), $data_return = Array(), $re_validate = true) {
        $data_return["callback"] = "save_form_edit_response";

        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        $id = intval($id);
        if (!$id) {
            $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
            $data_return["msg"] = "Bản ghi không tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
        if (!$this->data->is_editable($id)) {
            $data_return["state"] = 0;
            $data_return["msg"] = "Bản ghi không thể sửa đổi hoặc bản ghi không còn tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
        if (sizeof($data) == 0) {
            $data = $this->input->post();
        }
        if ($re_validate) {
            $data_all = $this->_validate_form_data($data, $id);

            if (!$data_all["state"]) {
                $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
                $data_return["msg"] = "Dữ liệu gửi lên không hợp lệ";
                $data_return["error"] = $data_all["error"];
                echo json_encode($data_return);
                return FALSE;
            } else {
                $data = $data_all["data"];
            }
        }

        $update = $this->data->update($id, $data);
        if ($id) {
            $data_return["key_name"] = $this->data->get_key_name();
            $data_return["record"] = $this->_process_data_table($this->data->get_one($id));
            $data_return["state"] = 1; /* state = 1 : insert thành công */
            $data_return["msg"] = "Sửa bản ghi thành công.";
            $data_return["redirect"] = $this->url["manager"];
            echo json_encode($data_return);
            return TRUE;
        } else {
            $data_return["state"] = 2; /* state = 2 : Lỗi thêm bản ghi */
            $data_return["msg"] = "Thêm bản ghi thất bại, vui lòng thử lại sau.";
            echo json_encode($data_return);
            return FALSE;
        }
    }

    /**
     * Hàm xóa bản ghi, có 2 cách truyền dữ liệu, 1 là uri khi xóa 1 bản ghi hoặc post lên biến 'list_id' để xóa nhiều bản ghi cùng lúc
     * @param int  $id ID bản ghi cần xóa
     * @param Array $data Biến muốn gửi thêm để <b>hiển thị ra view</b>(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     * @return json Gửi biến json về client
     */
    public function delete($id = 0, $data = Array()) {
        $id = intval($id);
        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return FALSE;
        }
        $data_return["callback"] = "delete_respone";
        if ($this->input->post() || $id > 0) {
            if (isset($data["list_id"]) && sizeof($data["list_id"])) {
                $list_id = $data["list_id"];
            } else {
                if ($this->input->post() && $id == "0") {
                    $list_id = $this->input->post("list_id");
                } elseif ($id > 0) {
                    $list_id = Array($id);
                }
            }
            $affted_row = $this->data->delete_by_id($list_id);
            if ($affted_row) {
                $data_return["list_id"] = $list_id;
                $data_return["state"] = 1;
                $data_return["msg"] = "Xóa bản ghi thành công";
            } else {
                $data_return["list_id"] = $list_id;
                $data_return["state"] = 0;
                $data_return["msg"] = "Bản ghi đã được xóa từ trước hoặc không thể bị xóa. Vui lòng tải lại trang!";
            }

            echo json_encode($data_return);
            return TRUE;
        } else {
            $data_return["state"] = 0;
            $data_return["msg"] = "Id không tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }
    }

    /**
     * Hàm hiển thị bảng quản lý cơ sở dữ liệu
     * @param Array $data Biến muốn gửi thêm để <b>hiển thị ra view</b>(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     */
    public function manager($data = Array()) {
        $this->session->set_userdata("search_string", "");
        $data["add_link"] = isset($data["add_link"]) ? $data["add_link"] : $this->url["add"];
        $data["delete_list_link"] = isset($data["delete_list_link"]) ? $data["delete_list_link"] : site_url($this->url["delete"]);
        $data["ajax_data_link"] = isset($data["ajax_data_link"]) ? $data["ajax_data_link"] : site_url($this->name["class"] . "/ajax_list_data");
        $data["title"] = isset($data["title"]) ? $data["title"] : strtolower($this->name["object"]);
        $viewFile = "base_manager/default_manager";
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'manager.php')) {
            $viewFile = $this->name["view"] . '/' . 'manager';
        }
        $content = $this->load->view($this->path_theme_view . $viewFile, $data, true);

        $head_page = $this->load->view($this->path_theme_view . 'base_manager/header_manager', $data, true);
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'header.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header', $data, true);
        }
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'header_manager.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header_manager', $data, true);
        }
        $title = 'Manage '. $this->name["object"];
        $this->master_page($content, $head_page, $title);
    }

    /**
     * Hàm gọi view hiển thị form <b>xem</b> bản ghi<br>
     * @param int $id ID của bản ghi cần sửa
     * @param Array $data Biến muốn gửi thêm để hiển thị ra view(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     * @return json trả dữ liệu về phía client JSON, nếu ko ajax thì sẽ hiển thị html
     */
    public function view($id = 0, $data = Array()) {
        if (FALSE) { //Kiểm tra phân quyền
            redirect();
            return false;
        }
        $data_return["callback"] = "get_data_view_response";
        if (!$id) {
            $data_return["state"] = 0;
            $data_return["msg"] = "Id không tồn tại";
            echo json_encode($data_return);
            return FALSE;
        }


        if (!isset($data["save_link"])) {
            $data["save_link"] = site_url($this->name["class"] . "/edit_save");
        }

        if (!isset($data["list_input"])) {
            $data["list_input"] = $this->_get_form($id);
        }
        $data["title"] = $title = "View " . $this->name["object"];

        $viewFile = "base_manager/default_form";
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'form.php')) {
            $viewFile = $this->name["view"] . '/' . 'form';
        }
        $content = $this->load->view($this->path_theme_view . $viewFile, $data, true);

        $data_return["record_data"] = $this->data->get_one($id);
        if ($this->input->is_ajax_request()) {
            $data_return["state"] = 1;
            $data_return["html"] = $content;
            echo json_encode($data_return);
            return TRUE;
        }
        $head_page = $this->load->view($this->path_theme_view . 'base_manager/header_view', $data, true);
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'header.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header', $data, true);
        }
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'header_view.php')) {
            $head_page .= $this->load->view($this->path_theme_view . $this->name["view"] . '/header_view', $data, true);
        }
        $title = "Sửa " . $this->name["object"];

        $this->master_page($content, $head_page, $title);
    }

    /**
     * Hàm tìm kiếm một số bản ghi - dự trù - chưa viết nội dung
     * @param Array $data Biến muốn gửi thêm để hiển thị ra view(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     */
    public function search($data = Array()) {
        
    }

    /**
     * Hàm lấy dữ liệu của 1 bản ghi - dự trù - chưa viết nội dung
     * @param Array $data Biến muốn gửi thêm để hiển thị ra view(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     */
    public function ajax_item_data($data = Array()) {
        
    }

    /**
     * Hàm lấy dữ liệu của một danh sách bản ghi
     * Hàm này có cấu trúc nhận dữ liệu POST khá phức tạp bao gồm
     *      - q     => chuỗi tìm kiếm
     *      - limit => Số bản ghi muốn lấy ra
     *      - order => sắp xếp theo thứ tự nào
     *      - page  => trang đang xem
     * Mặc định các biến này được quản lý ở file form.js, chỉ cần quan tâm khi viết đè
     * @param Array $data Biến muốn gửi thêm để hiển thị ra view(dùng khi hàm khác gọi tới hoặc hàm ghi đè gọi tới)
     * @return json Gửi dữ liệu json về client
     */
    public function ajax_list_data($data = Array()) {
        if ($this->session->userdata("limit") === FALSE) {
            $this->session->set_userdata("limit", 20);
        }
        if (!$this->session->userdata("order")) {
            $this->session->set_userdata("order", NULL);
        }
        if (!$this->session->userdata("search_string")) {
            $this->session->set_userdata("search_string", "");
        }

        $condition = $this->input->post();

        $search_string = isset($condition["q"]) ? $condition["q"] : $this->session->userdata("search_string");
        $limit = intval(isset($condition["limit"]) ? $condition["limit"] : $this->session->userdata("limit"));
        $order = isset($condition["order"]) ? $condition["order"] : $this->session->userdata("order");

        $currentPage = intval(isset($condition["page"]) ? $condition["page"] : 0);
        if ($limit < 0) {
            $limit = 0;
        }

        /* Nếu thay đổi số record hiển thị trên 1 trang hoặc thay đổi từ khóa tìm kiếm thì đặt lại thành trang 1 */
        if (($limit != $this->session->userdata("limit")) || ($search_string != $this->session->userdata("search_string"))) {
            $currentPage = 1;
        }
        $post = ($currentPage - 1) * $limit;
        if ($post < 0) {
            $post = 0;
            $currentPage = 1;
        }
        $orderData = $this->_check_data_order_record($order);
        $order = $orderData["string_order"];

        $this->session->set_userdata("limit", $limit);
        $this->session->set_userdata("order", $order);
        $this->session->set_userdata("search_string", $search_string);

        $form = $this->data->get_form();

        $record = $this->data->get_list_table($search_string, Array(), $limit, $post, $order);
        
        $totalItem = count($this->data->get_list_table($search_string, Array(), 0, 0, $order));

        if ($limit != 0) {
            $total_page = (int) ($totalItem / $limit);
        } else {
            $total_page = 0;
        }
        if (($total_page * $limit) < $totalItem) {
            $total_page += 1;
        }
        $link = "#";
        $data["pagging"] = $this->_get_pagging($total_page, $currentPage, $this->pagging_item_display, $link);
        $tempData = $this->_add_colum_action($record);
        $data = array_merge($data, $tempData);

        $data["key_name"] = $this->data->get_key_name();
        $data["limit"] = $limit;
        $data["search_string"] = $search_string;
        $data["from"] = $post + 1;
        $data["to"] = $post + $limit;
        if ($data["to"] > $totalItem) {
            $data["to"] = $totalItem;
        }
        $data["total"] = $totalItem;
        $data["order"] = $orderData["array_order"];

        $viewFile = "base_manager/default_table";
        if (file_exists(APPPATH . "views/" . $this->path_theme_view . $this->name["view"] . '/' . 'table.php')) {
            $viewFile = $this->name["view"] . '/' . 'table';
        }
        if (isset($this->name["modules"]) && $this->name["modules"]) {
            if (file_exists(APPPATH . "modules/" . $this->name["modules"] . "/views/" . $this->name["view"] . '/' . 'table.php')) {
                $viewFile = $this->name["view"] . '/' . 'table';
                $content = $this->load->view($viewFile, $data, true);
            } else {
                $content = $this->load->view($this->path_theme_view . $viewFile, $data, true);
            }
        } else {
            $content = $this->load->view($this->path_theme_view . $viewFile, $data, true);
        }
        if ($this->input->is_ajax_request()) {
            $data_return["callback"] = "get_manager_data_response";
            $data_return["state"] = 1;
            $data_return["html"] = $content;
            echo json_encode($data_return);
            return TRUE;
        }
    }

    /**
     * Hàm xem trước ảnh upload - đang phát triển
     */
    public function preview_image_input() {
        $config = $this->fileUploadConfig;
        $user_id = $this->session->userdata("user_sesion") ? $this->session->user_data("user_sesion") : "0";
        $config['upload_path'] = $this->image_path . $this->user_folder . $user_id . $this->temp_folder;
        $this->load->library('Multi_upload');
        $data = $this->multi_upload->do_multi_upload($config, 'image_uploader', $this->user_folder_max_size);
        $request = Array();
        if ($data) {
            $request['status'] = true;
//            $temp = $this->renderDataRequest($data);
            $request['errorList'] = $temp['errorList'];
            $request['uploadInfo'] = $temp['uploadInfo'];
        } else {
            $request['status'] = false;
        }
        echo json_encode($request);
    }

    /**
     * Hàm kiểm tra dữ liệu update có chuẩn hay khôn
     * @param Array $order
     * @return Array{
     *      string_order => chuỗi order dùng trong query
     *      array_order  => Mảng order dùng để hiển thị bảng quản lý
     * }
     */
    protected function _check_data_order_record($order) {
        $viewArrOrder = Array();
        $temp = explode(",", $order);
        $string_order = Array();
        for ($i = 0; $i < sizeof($temp); $i++) {
            $temp[$i] = trim($temp[$i]);
            $tempPice = explode(" ", $temp[$i]);
            /* Kiểm tra xem trường order có trong schema ko và giá trị sắp xếp có là asc hoặc desc ko? */
            if (sizeof($tempPice) == 2 && (in_array($tempPice[0], $this->data->get_schema()) || array_key_exists($tempPice[0], $this->data->get_field_table())) && ($tempPice[1] == "asc" || $tempPice[1] == "desc" )) {
                $x_key = $tempPice[0];
                $form = $this->data->get_form();
                $rule = isset($form["rule"][$tempPice[0]]) ? $form["rule"][$tempPice[0]] : FALSE;
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

                    if (isset($list_rule['real_field'])) {/* Nếu rule có real_field thì lấy key ở real_fiel */
                        $x_key = $list_rule['real_field'];
                    }
                }
                $string_order[] = $x_key . " " . $tempPice[1];
                $viewArrOrder[$x_key] = $tempPice[1];
            } else {
                unset($temp[$i]);
            }
        }
        $data["string_order"] = implode(",", $string_order);
        $data["array_order"] = $viewArrOrder;
        return $data;
    }

    /**
     * Hàm thêm cột vào bản ghi trước khi đưa ra bảng quản lý
     * Mặc định hàm này sẽ thêm 2 cột là cột chứa 3 nút (thêm, sửa xóa) và cột "input"
     * @param Array $record Mảng chứa các bản ghi
     * @return type
     */
    protected function _add_colum_action($record) {
        $form = $this->data->get_form();
        $dataReturn = Array();
        $dataReturn["schema"] = $form["schema"];
        $dataReturn["rule"] = $form["rule"];
        $dataReturn["colum"] = $form["field_table"];

        /* Thêm cột action */
        $dataReturn["colum"]["custom_action"] = "Action";
        /* Thêm cột check */
        $dataReturn["colum"]["custom_check"] = "<input type='checkbox' class='e_check_all' />";

        $record = $this->_process_data_table($record);
        $dataReturn["record"] = $record;
        return $dataReturn;
    }

    /**
     * Hàm kiểm tra dữ liệu trước khi thêm hoặc sửa bản ghi
     * @param Array $data Dữ liệu cần quản lý
     * @param int $id ID bản ghi(có trong trường hợp chỉnh sửa
     * @return Array mảng gồm 3 phần tử:
     *  - state => trạng thái TRUE nếu dữ đúng và FALSE nếu dữ liệu có chỗ ko đúng
     *  - error => Mảng chứa các lỗi Mảng rỗng nếu dữ liệu đúng
     */
    protected function _validate_form_data($data, $id = 0) {
        $data_return = Array();
        $data_return["state"] = TRUE;
        $data_return["error"] = Array();
        $form = $this->data->get_form();
        //Kiểm tra các trường xem có đúng yêu cầu trong $form["rule"] ko?
        foreach ($data as $key => $value) {
            //Kiểm tra xem trường này có rule không, nếu không thì continue
            if (!isset($form["rule"][$key])) {
                continue;
            }
            //Kiểm tra xem $data[$key] có thỏa mãn $form["rule"][$key] không!!
            $temp = explode(" ", $form["rule"][$key]); // Tách các rule
            $list_rule = Array();
            foreach ($temp as $rule) {
                $rule_piece = explode("=", $rule);
                if (sizeof($rule_piece) < 2) {
                    $list_rule[$rule_piece[0]] = NULL;
                } else {
                    $list_rule[$rule_piece[0]] = $rule_piece[1];
                }
            }
            foreach ($list_rule AS $rule_key => $rule_value) {
                switch ($rule_key) {
                    case "type":
                        switch ($rule_value) {
                            case "text":
                                break;
                            case "password":
                                $data[$key] = md5($this->md5_salt . sha1($data[$key] . $this->md5_salt));
                                break;
                            case "number":
                                $data[$key] = str_replace('.', '', $data[$key]);
                                $data[$key] = intval($data[$key]);
                                break;
                            case "date":
                                $data[$key] = strtotime($data[$key]);
                                if ($data[$key] === FALSE) {
                                    $data_return["state"] = FALSE;
                                    $data_return["error"][$key] = "Ngày tháng không hợp lệ(ngày-tháng-năm)";
                                }
                                $data[$key] = date("Y-m-d", $data[$key]);
                                break;
                            case "datepicker":
                                $data[$key] = strtotime($data[$key]);
                                if ($data[$key] === FALSE && isset($list_rule["required"])) {
                                    $data_return["state"] = FALSE;
                                    $data_return["error"][$key] = "Ngày giờ không hợp lệ(ngày-tháng-năm giờ:phút:giây)";
                                }
                                if ($data[$key]) {
                                    $data[$key] = date("Y-m-d", $data[$key]);
                                } else {
                                    $data[$key] = NULL;
                                }
                                break;
                            case "datetime":
                                $data[$key] = strtotime($data[$key]);
                                if ($data[$key] === FALSE && isset($list_rule["required"])) {
                                    $data_return["state"] = FALSE;
                                    $data_return["error"][$key] = "Ngày giờ không hợp lệ(ngày-tháng-năm giờ:phút:giây)";
                                }
                                if ($data[$key]) {
                                    $data[$key] = date("Y-m-d H:s:i", $data[$key]);
                                } else {
                                    $data[$key] = NULL;
                                }
                            case "select":
                                if (isset($list_rule["target_model"]) && isset($list_rule["target_value"]) && isset($list_rule["target_display"])) {
                                    if (!isset($list_rule["allow_null"]) && $data[$key] == 0) {
                                        $data_return["state"] = FALSE;
                                        $data_return["error"][$key] = "Trường này không được bỏ trống";
                                    } else {
                                        $modelName = "option" . $key;
                                        $this->load->model($list_rule["target_model"], $modelName);
                                        $getString = $list_rule["target_value"] . " AS value, " . $list_rule["target_display"] . " AS display";
                                        $option = $this->$modelName->get_list_option($getString, $data[$key]);
                                        if (!isset($list_rule["allow_null"]) && sizeof($option) == 0) {
                                            $data_return["state"] = FALSE;
                                            $data_return["error"][$key] = "Dữ liệu không đồng bộ, vui lòng thử lại";
                                        }
                                    }
                                }
                                break;
                            case "checkbox":
                                if ($data[$key] == "on" || $data[$key] == "true" || $data[$key] == "1") {
                                    $data[$key] = 1;
                                } elseif ($data[$key] == "off" || $data[$key] == "false" || $data[$key] == "null") {
                                    $data[$key] = 0;
                                } else {
                                    $data[$key] = 0;
                                }
                                break;
                            case "file":

                                break;
                            default:
                                break;
                        }
                        break;
                    case "maxlength":
                        if (strlen($data[$key]) > $rule_value) {
                            $data_return["state"] = FALSE;
                            $data_return["error"][$key] = "Độ dài tối đa là " . $rule_value . " ký tự";
                        }
                        break;
                    case "minlength":
                        if (strlen($data[$key]) < $rule_value) {
                            $data_return["state"] = FALSE;
                            $data_return["error"][$key] = "Độ dài tối thiểu là " . $rule_value . " ký tự.";
                        }
                        break;
                    case "required":
                        if (!isset($data[$key]) || !strlen($data[$key])) {
                            $data_return["state"] = FALSE;
                            $data_return["error"][$key] = $form["field_form"][$key] . " không được bỏ trống.";
                        }
                        break;
                    case "is_email":
                        if (strlen($data[$key]) > 0) {
                            if (!$this->_is_email($data[$key])) {
                                $data_return["state"] = FALSE;
                                $data_return["error"][$key] = "Địa chỉ email không hợp lệ.";
                            }
                        }
                        break;
                    case "unique":
                        if ($this->data->check_existed($key, $data[$key], $id)) {
                            $data_return["state"] = FALSE;
                            $data_return["error"][$key] = "Dữ liệu đã có trong cơ sở dữ liệu.";
                        }
                        break;
                    default:

                        break;
                }
            }
            // Sau khi kiểm tra thì loại bỏ các trường ko có trong schema
            if (!in_array($key, $form["schema"])) {
                unset($data[$key]);
            }
        }
        $data_return["data"] = $data;
        return $data_return;
    }

    /**
     * Hàm kiểm tra có là email hay không?
     * @param String $email địa chỉ email cần kiểm tra
     * @return boolean
     */
    protected function _is_email($email) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
    }

    /**
     * Trường xử lý bản ghi trước khi hiển thị ra bảng
     * @param Array|Object $record
     * @return Array|Object
     */
    protected function _process_data_table($record) {
        if (!$record) {
            $record = array();
            return $record;
        }
        $form = $this->data->get_form();
        $key_table = $this->data->get_key_name();
        /* Tùy biến dữ liệu các cột */
        if (is_array($record)) {
            foreach ($record as $key => $valueRecord) {
                $record[$key] = $this->_process_data_table($record[$key]);
            }
        } else {
            $record->custom_action = '<div class="action">
			<a class="detail e_ajax_link icon16 i-eye-3 " per="1" href="' . site_url($this->url["view"] . $record->$key_table) . '" title="Xem"></a>';
            if (!isset($record->editable) || (isset($record->editable) && $record->editable)) {
                $record->custom_action .= '<a class="edit e_ajax_link icon16 i-pencil" per="1" href="' . site_url($this->url["edit"] . $record->$key_table) . '" title="Sửa"></i></a>
			<a class="delete e_ajax_confirm e_ajax_link icon16 i-remove" per="1" href="' . site_url($this->url["delete"] . $record->$key_table) . '" title="Xóa"></a></div>';
            }
            $record->custom_check = "<input type='checkbox' name='_e_check_all' data-id='" . $record->$key_table . "' />";

            foreach ($form["field_table"] as $keyColum => $valueColum) {
                if (isset($form["rule"][$keyColum])) {
                    if (preg_match("/type\=checkbox/i", $form["rule"][$keyColum])) {
                        if ($record->$keyColum) {
                            $record->$keyColum = "<input type='checkbox' name='" . $keyColum . "' disabled='disabled' checked='checked' />";
                        } else {
                            $record->$keyColum = "<input type='checkbox' name='" . $keyColum . "' disabled='disabled' />";
                        }
                    } elseif (preg_match("/type\=file/i", $form["rule"][$keyColum])) {
                        $record->$keyColum = "<div class='center'><img src='" . $record->$keyColum . "' /></div>";
                    } elseif (preg_match("/type\=datepicker/i", $form["rule"][$keyColum]) || preg_match("/type\=date/i", $form["rule"][$keyColum])) {
                        $temp = strtotime($record->$keyColum);
                        if (preg_match("/type\=datetime/i", $form["rule"][$keyColum])) {
                            $record->$keyColum = date("d-m-Y H:i:s", $temp);
                        } else {
                            $record->$keyColum = date("d-m-Y", $temp);
                        }
                    }
                }
            }
        }
        return $record;
    }

    /**
     * Hàm xử lý dữ liệu hiển thị
     * @return Object
     */
    protected function _get_form($id = 0) {
        $data = $this->data->get_form();
        $list_input = Array();
        foreach ($data["field_form"] as $key => $item) {
            if (!isset($data["rule"][$key]) || preg_match("/type\=invisible/i", $data["rule"][$key])) { /* Nếu trường ko có rule hoặc có kiểu là hidden */
                continue;
            }
            $temp = new stdClass();
            $temp->name = end(explode(".", $key));
            $temp->rule = $data["rule"][$key];
            $temp->label = $data["field_form"][$key];

            if (preg_match("/type\=select/i", $data["rule"][$key])) {
                //Kiểm tra xem $data[$key] có thỏa mãn $form["rule"][$key] không!!
                $temp_rule = explode(" ", $data["rule"][$key]); // Tách các rule
                $list_rule = Array();
                foreach ($temp_rule as $rule) {
                    $rule_piece = explode("=", $rule);
                    if (sizeof($rule_piece) < 2) {
                        continue;
                    }
                    $list_rule[$rule_piece[0]] = $rule_piece[1];
                }

                if (isset($list_rule["target_model"]) && isset($list_rule["target_value"]) && isset($list_rule["target_display"])) {
                    if (isset($this->optionModel)) {
                        $this->optionModel = NULL;
                    }
                    $modelName = "option" . $key;
                    $this->load->model($list_rule["target_model"], $modelName);

                    $getString = $list_rule["target_value"] . " AS value, " . $list_rule["target_display"] . " AS display";
                    $temp->option = Array();
//                    if (isset($list_rule["allow_null"])) {
                    $nullItem = new stdClass();
                    $nullItem->value = 0;
                    $nullItem->display = "- Select -";
                    $temp->option[] = $nullItem;
//                    }
                    $temp->option = array_merge($temp->option, $this->$modelName->get_list_option($getString));
                }
            } elseif (preg_match("/type\=file/i", $data["rule"][$key])) {
                $data["rule"][$key] .= " preview_url=" . site_url($this->name["class"] . "/preview_image_input");
                $temp->rule = $data["rule"][$key];
            }
            if ($id) {
                if (preg_match("/type\=password/i", $data["rule"][$key])) {
                    if (preg_match("/recheck/i", $data["rule"][$key])) {
                        $temp->rule = "type=password";
                    }
                }
            }
            $list_input[] = $temp;
        }
        return $list_input;
    }

}

/* End of file manager_base.php */
/* Location: ./application/base/manager_base.php */