<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Permission
 * 
 * Description...
 * 
 * @package permission
 * @author Pham Trong <phamtrong204@gmail.com>
 * @version 0.0.0
 */
class Permission extends manager_base {

    public function __construct() {
        parent::__construct();
    }

    public function setting_class() {
        $this->name = Array(
            "class" => "permission",
            "view" => "permission",
            "model" => "m_permission",
            "object" => "Authorities"
        );
    }
    
    public function manager($data = Array()) {
        $data['per_link'] = 'https://docs.google.com/a/topica.edu.vn/spreadsheets/d/1VjPf7BM3WmnDChiZMZu2lYB_DxowjSdYZ1sFFqJ_gpg/edit#gid=0';
        parent::manager($data);
    }

    public function delete($id = 0, $data = array()) {
        
    }
    
}

/* End of file permission.php */
/* Location: ./application/controllers/permission.php */