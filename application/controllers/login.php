<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Login
 * 
 * Description...
 * 
 * @package login
 * @author Pham Trong <your@email.com>
 * @version 0.0.0
 */
class Login extends home_base {

    public function __construct() {
        parent::__construct();
        $this->load->model("m_admin_account", "account");
    }

    public function index() {
        $data = Array();
        $data["login_url"] = site_url("login/check");
        $data["recover_url"] = site_url("login/reset_password");

        $data["form"] = $this->account->get_form();

        $content = $this->load->view($this->path_theme_view . "login/content", $data, true);
        $header_page = $this->load->view($this->path_theme_view . "login/header", $data, true); /* Dữ liệu đẩy thêm vào thẻ <head> (css, js, meta property) */
        $title = "Login the site - Topimito MOL";
        $description = NULL;
        $keywords = NULL;
        $canonical = NULL;
        $this->master_page_blank($content, $header_page, $title, $description, $keywords, $canonical);
    }

    public function check() {
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $dataReturn = Array();
            $email = $this->input->post("admin_email");
            $pass = md5($this->md5_salt . sha1($this->input->post("admin_password") . $this->md5_salt));
            $this->load->model("m_admin_account", "account");
            $login = $this->account->check_login($email, $pass);
            if ($login) {
                $this->session->set_userdata("id", $login->userId);
                $this->session->set_userdata("userName", $login->userName);
                $this->session->set_userdata("perData", $login->perData);
                $this->session->set_userdata("perValue", $login->perValue);
                $this->session->set_userdata("perId", $login->userPermission);
                $this->session->set_userdata("perList", explode(";", $login->perData));
                
                
                $dataReturn["state"] = 1;
                $dataReturn["msg"] = "Success";
                $dataReturn["redirect"] = site_url();
            } else {
                $dataReturn["state"] = 0;
                $dataReturn["msg"] = "Invalid login, please try again";
            }
            echo json_encode($dataReturn);
        } else {
            redirect();
        }
    }

    public function reset_password() {
        
    }

    protected function require_login() {
        return false;
    }

    protected function check_permission() {
        return true;
    }

    public function logout() {
        $this->session->set_userdata("id", 0);
        $this->session->set_userdata("userName", null);
        $this->session->set_userdata("userDisplayName", null);
        $this->session->set_userdata("roleData", null);
        redirect("login");
    }

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */