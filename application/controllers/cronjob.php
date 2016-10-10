<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronjob extends CI_Controller {

    public function __construct() {
        ini_set('memory_limit', '1024M');
        parent::__construct();
    }

    public function get_sms_email() {
        $this->load->model('m_cronjob');
        $this->load->model('m_care');
        $this->load->model('m_care_sms');
        $this->load->model('m_sys_sms_template', 'sms_template');
        $list_sms = $this->sms_template->get_list(array('m.status' => 1));
        $sms = array();
        foreach ($list_sms as $val) {
            $sms[$val->subject] = $val;
        }
        $list_contact = $this->m_cronjob->get_list(array('m.status_flag' => 1));
        foreach ($list_contact as $val) {
            //lọc sdt Thái, đem sang Indo hoặc VN chết đừng hỏi tại sao
            $val->phone = str_replace('+66', '0', $val->phone);
            $val->phone = str_replace(' ', '', $val->phone);
            if (!preg_match("/^0[0-9]{9}$/", $val->phone)) {
                continue;
            }
            //Gửi SMS01
            if (!is_numeric(strpos($val->sms_auto_flag, 'SMS01'))) {
                //insert vào bảng care_sms
                $send_sms = array(
                    'phone' => $val->phone,
                    'subject' => $sms['SMS01']->subject,
                    'content' => $sms['SMS01']->content,
                    'template_id' => $sms['SMS01']->id,
                );
                $insert_id = $this->m_care_sms->add($send_sms);
                if ($insert_id) {
                    //update lại status vào bảng contact
                    $this->m_cronjob->update($val->id, array('sms_auto_flag' => $val->sms_auto_flag . 'SMS01 '));
                    //insert vào bảng care
                    $care_sms = array(
                        'contact_id' => $val->contact_id,
                        'care_type' => 'SMS',
                        'care_type_code' => $sms['SMS01']->subject,
                        'care_sms_email_id' => $insert_id
                    );
                    $this->m_care->add($care_sms);
                }
            }
            //----------------
        }
        echo 'The End';
    }

    /**
     * Gui sms trong queue trong truong hop gui nhieu và nội dung tin nhắn là tĩnh
     * sms
     */
    function send_sms_queue() {
        // die();
        $this->load->model('m_care_sms');
        $this->load->model('m_config_sms');
        $list_sms_send = $this->m_care_sms->get_list(array('m.status_send' => 0), 5);
        if (count($list_sms_send) && $list_sms_send) {
            foreach ($list_sms_send as $item) {
                $data = array(
                    'list_phone' => array($item->phone),
                    'content' => $item->content
                );
                $result = $this->m_config_sms->send_multi_sms($data);
                if ($result && $result->data) {
                    foreach ($result->data as $val) {
                        $this->m_care_sms->update(array('phone' => $val->phone), array('status_send' => '1'));
                    }
                }
            }
        }
        echo "thanh cong\n";
    }

}
