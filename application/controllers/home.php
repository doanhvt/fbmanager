<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class home extends home_base {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
		
           $data = Array();

        date_default_timezone_set('Asia/Ho_Chi_Minh'); //setup lai timezone
        $date = date('Y-m-d', time());
        $id_campaign = $this->input->post('id_campaign');
        $id_landingpage = $this->input->post('id_landingpage');
        $code_chanel = $this->input->post('code_chanel');
        $time_begin = $this->input->post('time_begin');
        $time_end = $this->input->post('time_end');

        $this->load->model("m_landing_page");
        if ($id_landingpage) {
            $landingpage = $this->m_landing_page->get_one(array('m.id' => $id_landingpage));
            $domain = $landingpage->url;
        } else {
            $domain = 0;
        }

        $this->session->set_userdata('h_id_campaign', $id_campaign);
        $this->session->set_userdata('h_id_landingpage', $id_landingpage);
        $this->session->set_userdata('h_code_chanel', $code_chanel);
        $this->session->set_userdata('h_domain', $domain);

        $data['export_excel'] = site_url("report/export_excel_view_click_hour");
        $data['view_click_hour_url'] = site_url('report/view_click_hour');
        $data['view_click_chanel_url'] = site_url('report/view_click_chanel');
        if ($time_begin) {
            $data['time_begin'] = $time_begin;
            $data['time_end'] = $time_end;
            $this->session->set_userdata('h_time_begin', $time_begin);
            $this->session->set_userdata('h_time_end', $time_end);
        } else {
            $data['time_begin'] = $date;
            $data['time_end'] = $date;
            $this->session->set_userdata('h_time_begin', $date);
            $this->session->set_userdata('h_time_end', $date);
        }
        $data['id_campaign'] = $id_campaign;
        $data['code_chanel'] = $code_chanel;
        $data['id_landingpage'] = $id_landingpage;

        $this->load->model("m_chanel");
        $data["list_chanel"] = $this->m_chanel->get_list_chanel_parent();
        $this->load->model("m_landing_page");
        $data["list_landing_page"] = $this->m_landing_page->get_list();
        $this->load->model("m_campaign");
        $data["list_campaign"] = $this->m_campaign->get_list();
        $data['action_url'] = site_url('report/view_click_hour');

        $data['records'] = $this->get_data_view_click_hour();

        $content = $this->load->view($this->path_theme_view . "report/view_click_hour", $data, true);
        $header_page = $this->load->view($this->path_theme_view . "report/header", $data, true); /* Dữ liệu đẩy thêm vào thẻ <head> (css, js, meta property) */
        $title = 'Statistics of Click and Submit by time';
        $description = NULL;
        $keywords = NULL;
        $canonical = NULL;

        $this->master_page($content, $header_page, $title, $description, $keywords, $canonical);
    }

    public function get_data_view_click_hour() {
        $id_campaign = $this->session->userdata('h_id_campaign');
        $id_landingpage = $this->session->userdata('h_id_landingpage');
        $code_chanel = $this->session->userdata('h_code_chanel');
        $time_begin = $this->session->userdata('h_time_begin');
        $time_end = $this->session->userdata('h_time_end');
        $domain = $this->session->userdata('h_domain');

        $this->load->model("m_log");
		$time_begin_unix = strtotime($time_begin);
        $time_end_unix = strtotime($time_end . ' 23:59:59');
        $records_click = $this->m_log->get_data_click($id_campaign, $id_landingpage, $code_chanel, $time_begin_unix, $time_end_unix, $domain);

        $records_submit = $this->m_log->get_data_submit($id_campaign, $id_landingpage, $code_chanel, $time_begin_unix, $time_end_unix, $domain);
        $data = array();

        for ($i = 0; $i < 24; $i++) {
            $data[$i] = new stdClass();
            $data[$i]->total_click = 0;
            $data[$i]->total_submit = 0;
            $data[$i]->c3perc2 = 0;
            $data[$i]->cost = 0;
            $data[$i]->cost_c3 = 0;
        }

        foreach ($records_click as $item) {
            $hour = date('H', strtotime($item->datetime));
            $h = intval($hour);
            if (isset($data[$h])) {
                $data[$h]->total_click = $data[$h]->total_click + 1;
            } else {
                $item->total_click = 1;
                $item->total_submit = 0;
                $data[$h] = $item;
            }
        }
        foreach ($records_submit as $item) {
            $hour = date('H', strtotime($item->datetime_submitted));
            $h = intval($hour);
            if (isset($data[$h])) {
                $data[$h]->total_submit = $data[$h]->total_submit + 1;
            } else {
                $item->total_submit = 1;
                $item->total_click = 0;
                $data[$h] = $item;
            }
        }
        // DUCANH viet lai cho de xuat excel

        $total_row = new stdClass();
        $total_row->total_click = 0;
        $total_row->total_submit = 0;
        $total_row->c3perc2 = 0;

        foreach ($data as $hour => $row_data) {
            $data[$hour]->c3perc2 = $data[$hour]->total_click ? number_format(($data[$hour]->total_submit * 100) / $data[$hour]->total_click, "1", ".", "") : 0;
            $total_row->total_click += $data[$hour]->total_click;
            $total_row->total_submit += $data[$hour]->total_submit;
            $total_row->c3perc2 = $total_row->total_click ? number_format(($total_row->total_submit * 100) / $total_row->total_click, "1", ".", "") : 0;
            $data[$hour]->total_click = number_format($data[$hour]->total_click);
            $data[$hour]->total_submit = number_format($data[$hour]->total_submit);
        }
        // Tong
        $total_row->total_click = number_format($total_row->total_click);
        $total_row->total_submit = number_format($total_row->total_submit);
        $data[24] = $total_row;
		$this->session->set_userdata('data_view_hour',$data);
        return $data;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */