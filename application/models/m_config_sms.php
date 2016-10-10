<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_config_sms extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->config->load('api_config');
    }

    public function send_sms($phone, $msg) {
        $config = $this->config->item('api_server_smsservice_th');
        $this->load->library("Rest_Client", $config);
        // Load library send sms
        $uri = "sms_send_queue/sendFastMultiSMS";

        $param = Array(
            "data" => array(
                'list_phone' => array($phone),
                'content' => $msg,
            )
        );
        $result = $this->rest_client->post($uri, $param);
        return $result;
    }

    /**
     * $data la array bao gom cac phan tu 
     * array(
     *  "sendTo" => SDT,
     *  "content" => MSG
     * )
     * @param type $data
     */
    public function send_multi_sms($data) {
        $config = $this->config->item('api_server_smsservice_th');
        $this->load->library("Rest_Client", $config);
        unset($config);
        $uri = "sms_send_queue/sendFastMultiSMS";

        $param = Array(
            // "data" => $data
            'data' => array(
                'list_phone' => $data['list_phone'],
                'content' => $data['content'],
                'username' => "TelesaleTL@0989390453",
                'password' => "topica123",
            )
        );
        $result = $this->rest_client->post($uri, $param);
        return $result;
    }

}

/* End of file m_user.php */
/* Location: ./application/models/m_user.php */