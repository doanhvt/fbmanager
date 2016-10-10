<?php

/**
 * Description of contact_collection_api
 *
 * @author Loc
 */
class Contact_collection_api extends REST_Controller {

    public function __construct() {
        date_default_timezone_set('Asia/Ho_Chi_Minh'); //setup lai timezone
        parent::__construct();
    }

    public function add_post() {
        $data = $this->post();
        if (isset($data["domain"])) {
            $array_domain = explode('?', $data["domain"]);
            $array_domain[0] = str_replace('/index.php', '', $array_domain[0]);
            $data["domain"] = preg_replace("{/$}", "", $array_domain[0]);
        } else {
            $data["domain"] = '';
        }


        $log_data = array(
            "id_camp_landingpage" => isset($data["id_camp_landingpage"]) ? $data["id_camp_landingpage"] : "-100",
            "code_chanel" => isset($data["code_chanel"]) ? $data["code_chanel"] : "-100",
            "datetime" => date("Y-m-d H:i:s"),
            "datetime_unix" => time(),
            "action" => "Visited",
            "domain" => $data["domain"],
            "status" => (isset($data["http_referer"]) && $data["http_referer"]) ? 1 : 0,
            "http_referer" => (isset($data["http_referer"]) && $data["http_referer"]) ? $data["http_referer"] : '',
            "ip" => $data["ip"]
        );
        $preview = isset($data["preview"]) ? $data["preview"] : null;
        if (($preview != null && $preview == "preview_mode")) {
            // khong lam gi
        } else {
            $this->load->model("m_log");
            $this->m_log->add($log_data);
        }
        $response = array(
            'status' => true,
            'data' => $data,
            'msg' => 'đã kết nối thành công'
        );
        $this->response($response);
    }

    public function add_contact_post() {
        $data = $this->input->post();
        //Kiem tra so dien thoai
        $phone = $data['phone'];
        $first_symbol = $phone[0];
        $count_phone = strlen($phone);
        if ($count_phone != 10 || $first_symbol != '0') {
            $response = array(
                'status' => false,
                'msg' => 'Bạn đã thực hiện test landingpage thành công!',
                'redirect' => $data["domain"] . '/thank-you.php'
            );

            $this->response($response);
            return false;
        }

        if (is_array($data) && $data['name'] && $data['phone'] && $data['email']) {
            $this->load->model("m_contact");
            $form = $this->m_contact->get_form();
            /**
             * Loc bo du lieu khong can thiet
             */
            foreach ($data as $key => $value) {
                if (!in_array($key, $form["schema"])) {
                    unset($data[$key]);
                } else {
                    $data[$key] = trim($value);
                }
            }
            $data["datetime_submitted"] = date("Y-m-d H:i:s");
            $data["datetime_submitted_unix"] = time();

            /**
             *  Kiem tra neu du lieu la do nguoi dung nhap lung tung thi xoa
             */
            $this->load->model("m_campaign_landingpage");
            $check = $this->m_campaign_landingpage->get_one(array("id" => $data["id_camp_landingpage"]));
            if ($check) {
                $data["id_camp_landingpage"] = $data['id_camp_landingpage'];
                $data["code_chanel"] = $check->code_chanel;
            } else {
                $data["id_camp_landingpage"] = '-100';
                $data["code_chanel"] = '-100';
            }

            /**
             * Xu li du lieu ve domain
             */
            $array_domain = explode('?', $data["domain"]);
            $temp = explode('/', $array_domain[0]);
            if (count($temp) > 3) {
                $n = $temp[count($temp) - 1];
                $data["domain"] = preg_replace("{" . $n . "}", "", $array_domain[0]);
                $data["domain"] = preg_replace("{/$}", "", $data["domain"]);
            } else {
                $data["domain"] = preg_replace("{/$}", "", $array_domain[0]);
            }


            /**
             * Check trung phone trong MOL
             */
            if ($this->m_contact->check_phone_mol(array('phone' => $data['phone']))) {
                $data['status_mol'] = 2;
            }
            /**
             * Check trung contact trong ngay tai he thong MOL
             */
            if ($this->m_contact->check_phone_mol(array(
                        'phone' => trim($data['phone']),
                        'email' => trim($data['email']),
                        'name' => trim($data['name']),
                        'datetime_submitted_unix >=' => strtotime(date('Y-m-d')),
                        'datetime_submitted_unix <=' => strtotime(date('Y-m-d 23:59:59')),
                    ))) {
                $data['status_filter_day'] = 1; //0: không trùng trong ngày; 1: trùng trong ngày
            }

            /**
             * Check trung phone ben CRM
              $this->load->library("Rest_Client");
              $config_checkphone = array(
              'server' => 'http://cctpe.topica.vn/services/CheckPhone.aspx',
              );
              $this->rest_client->initialize($config_checkphone);
              $uri = "";
              $param = array(
              'PhoneCheck' => $data_insert["phone"],
              'MailCheck' => $data_insert["email"],
              );
              $check_crm = $this->rest_client->get($uri, $param);
              $arr_check_crm = json_decode($check_crm);
              if($arr_check_crm) {
              if (isset($arr_check_crm->status) && ($arr_check_crm->status == 'True')) {
              $data_insert['status'] = 2;
              }
              }  else {
              $data_insert['status'] = 3;
              }
             */
            $data['status'] = 3;

            if ($id_insert = $this->m_contact->add($data)) {
                $contact_id = date('Ymd') . substr(str_pad($id_insert, 6, '0', STR_PAD_LEFT), -6) . 'TL';
                $this->m_contact->update($id_insert, array('contact_id' => $contact_id));
                $this->load->library("Rest_Client");
                $config = array(
                    'server' => 'http://cloud.sms.dev.topica.vn/',
                    'api_key' => 'SSeKfm7RXCJZxnFUleFsPf63o2ymZ93fWuCmvCjq',
                    'api_name' => 'key',
                    'http_user' => 'admin',
                    'http_pass' => 'admin',
                    'http_auth' => 'basic'
                );
                $this->rest_client->initialize($config);
                $uri = "contact_collection_api/send_email";
                $param = array(
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'name' => $data['name'],
                );
                $result = $this->rest_client->post($uri, $param);
                /* Gửi sms */
                if (isset($data['status_flag']) && $data['status_flag'] == 1) {
                    $config_sms = array(
                        'server' => "http://smsservice.topicanative.asia",
                        'api_key' => 'RIsXdogqZxcyhOifjTJotuc3oJtMMO6GPYU1hxGD',
                        'api_name' => 'key',
                        'http_user' => 'marketing.smsth',
                        'http_pass' => 'marketing.smsth@topica.',
                        'http_auth' => 'basic',
                    );
                    $this->rest_client->initialize($config_sms);
                    $uri_sms = "sms_send_queue/sendFastMultiSMS";
                    $param_sms = array(
                        'data' => array(
                            'list_phone' => array($data['phone']),
                            'content' => "TOPICA Native อยากขอขอบคุณที่คุณสนใจในโปรแกรมของเรา เราจะติดต่อกลับหาคุณภายใน 2 วัน ด้วยหมายเลข 021054257/0600035989 เพื่อให้ข้อมูลเพิ่มเติมกับคุณ ขอให้คุณมีวันที่ดีค่ะ",
                            'username' => "TelesaleTL@0989390453",
                            'password' => "topica123",
                        )
                    );
                    $this->rest_client->post($uri_sms, $param_sms);
                }
            }
        }
        if (($data['email'] == 'test@gmail.com')) {
            $response = array(
                'status' => true,
                'msg' => 'Bạn đã thực hiện test landingpage thành công!',
                'redirect' => $data["domain"] . '/thank-you.php'
            );
        } else {
            $response = array(
                'status' => true,
                'msg' => 'Cám ơn bạn đã đăng ký. Chúng tôi sẽ liên hệ tư vấn sớm nhất tới bạn!',
                'redirect' => $data["domain"] . '/thank-you.php'
            );
        }

        $this->response($response);
    }

}
