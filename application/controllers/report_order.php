<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of campaign
 *
 * @author Loc
 */
class report_order extends home_base {

    public function __construct() {
        parent::__construct();
    }

    public function view_click_chanel() {
        $this->set_view_click_chanel_order();
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

        $this->session->set_userdata('c_id_campaign', $id_campaign);
        $this->session->set_userdata('c_id_landingpage', $id_landingpage);
        $this->session->set_userdata('c_code_chanel', $code_chanel);
        $this->session->set_userdata('c_domain', $domain);

        $data['export_excel'] = site_url("report_order/export_excel_view_click_chanel");
        $data['view_click_hour_url'] = site_url('report_order/view_click_hour');
        $data['view_click_chanel_url'] = site_url('report_order/view_click_chanel');
        if ($time_begin) {
            $data['time_begin'] = $time_begin;
            $data['time_end'] = $time_end;
            $this->session->set_userdata('c_time_begin', $time_begin);
            $this->session->set_userdata('c_time_end', $time_end);
        } else {
            $data['time_begin'] = $date;
            $data['time_end'] = $date;
            $this->session->set_userdata('c_time_begin', $date);
            $this->session->set_userdata('c_time_end', $date);
        }
        $data['id_campaign'] = $id_campaign;
        $data['id_landingpage'] = $id_landingpage;
        $data['code_chanel'] = $code_chanel;
        $data['action_url'] = site_url('report_order/view_click_chanel');
        $this->load->model("m_chanel");
        $data["list_chanel"] = $this->m_chanel->get_list_chanel_parent();
        $this->load->model("m_campaign");
        $data["list_campaign"] = $this->m_campaign->get_list();
        $this->load->model("m_landing_page");
        $data["list_landing_page"] = $this->m_landing_page->get_list();

        $data['records'] = $this->get_data_view_click_chanel();

        $content = $this->load->view($this->path_theme_view . "report/view_click_chanel_test", $data, true);

        if ($this->input->is_ajax_request()) {
            echo $content;
            exit;
        }

        $header_page = $this->load->view($this->path_theme_view . "report/header", $data, true); /* Dữ liệu đẩy thêm vào thẻ <head> (css, js, meta property) */
        $title = 'Thống kê view - click theo kênh';
        $description = NULL;
        $keywords = NULL;
        $canonical = NULL;

        $this->master_page($content, $header_page, $title, $description, $keywords, $canonical);
    }

    public function view_click_hour() {
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
        $title = 'Thống kê view - click theo giờ';
        $description = NULL;
        $keywords = NULL;
        $canonical = NULL;

        $this->master_page($content, $header_page, $title, $description, $keywords, $canonical);
    }

    public function marketing_productivity() {
        $data = Array();

        date_default_timezone_set('Asia/Ho_Chi_Minh'); //setup lai timezone
        $date = date('Y-m-d', time());
        $id_campaign = $this->input->post('id_campaign');
        $id_landingpage = $this->input->post('id_landingpage');
        $code_chanel = $this->input->post('code_chanel');
        $code_mauqc = $this->input->post('code_mauqc');
        $time_begin = $this->input->post('time_begin');
        $time_end = $this->input->post('time_end');

        $this->load->model("m_landing_page");
        if ($id_landingpage) {
            $landingpage = $this->m_landing_page->get_one(array('m.id' => $id_landingpage));
            $domain = $landingpage->url;
        } else {
            $domain = 0;
        }

        $this->session->set_userdata('id_campaign', $id_campaign);
        $this->session->set_userdata('id_landingpage', $id_landingpage);
        $this->session->set_userdata('code_chanel', $code_chanel);
        $this->session->set_userdata('code_mauqc', $code_mauqc);
        $this->session->set_userdata('domain', $domain);

        $data['export_excel'] = site_url("report/export_excel_marketing_productivity");
        if ($time_begin) {
            $data['time_begin'] = $time_begin;
            $data['time_end'] = $time_end;
            $this->session->set_userdata('time_begin', $time_begin);
            $this->session->set_userdata('time_end', $time_end);
        } else {
            $data['time_begin'] = $date;
            $data['time_end'] = $date;
            $this->session->set_userdata('time_begin', $date);
            $this->session->set_userdata('time_end', $date);
        }
        $data['id_campaign'] = $id_campaign;
        $data['id_landingpage'] = $id_landingpage;
        $data['code_chanel'] = $code_chanel;

        $data['action_url'] = site_url('report/marketing_productivity');
        $this->load->model("m_chanel");
        $data["list_chanel"] = $this->m_chanel->get_list_chanel_parent();
        $data["list_mauqc"] = $this->m_chanel->get_list_mauqc();
        $this->load->model("m_landing_page");
        $data["list_landing_page"] = $this->m_landing_page->get_list();
        $this->load->model("m_campaign");
        $data["list_campaign"] = $this->m_campaign->get_list();

        $data['records'] = $this->get_data_marketing_productivity();

        $content = $this->load->view($this->path_theme_view . "report/marketing_productivity", $data, true);
        $header_page = $this->load->view($this->path_theme_view . "report/header", $data, true); /* Dữ liệu đẩy thêm vào thẻ <head> (css, js, meta property) */
        $title = 'Báo cáo năng suất marketing';
        $description = NULL;
        $keywords = NULL;
        $canonical = NULL;

        $this->master_page($content, $header_page, $title, $description, $keywords, $canonical);
    }

    public function get_data_view_click_chanel() {
        $id_campaign = $this->session->userdata('c_id_campaign');
        $id_landingpage = $this->session->userdata('c_id_landingpage');
        $code_chanel = $this->session->userdata('c_code_chanel');
        $time_begin = $this->session->userdata('c_time_begin');
        $time_end = $this->session->userdata('c_time_end');
        $domain = $this->session->userdata('c_domain');

        $this->load->model("m_chanel");
        if ($code_chanel) {
            $list_chanel_parent = $this->m_chanel->get_list_chanel_parent_test(array('m.id' => $code_chanel));
            $list_chanel_ads = $this->m_chanel->get_list_mauqc(array('m.parent_id' => $code_chanel));
        } else {
            $list_chanel_parent = $this->m_chanel->get_list_chanel_parent_test();
            $list_chanel_ads = $this->m_chanel->get_list_mauqc();
        }

        $this->load->model("m_log");
        $records_click = $this->m_log->get_data_click($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain);
        $records_submit = $this->m_log->get_data_submit($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain);

        $stt = 0;
        $data = array();
        $n = count($list_chanel_parent);
        $array_chanel_ads = array();
        foreach ($list_chanel_ads as $item) {
            $array_chanel_ads[$item->parent_id][] = $item;
        }
        foreach ($list_chanel_parent as $item) {
            if ($item->id != -100) {
                $item->stt = ++$stt;
                $item->list_ads = array();
                if (isset($array_chanel_ads[$item->id])) {
                    foreach ($array_chanel_ads[$item->id] as $value) {
                        $array_info_ads = new stdClass();
                        $array_info_ads->name_chanel = $value->name;
                        $array_info_ads->number_c2 = 0;
                        $array_info_ads->number_c3 = 0;
                        $array_info_ads->c3perc2 = 0;
                        $item->list_ads[$value->id] = $array_info_ads;
                    }
                }
                $data[$item->id] = $item;
            }
        }
        if (!$code_chanel) {
            $chanel_other = $this->m_chanel->get_one_chanel();
            $chanel_other->list_ads = array();
            $chanel_other->stt = $n;
            $chanel_other->name = 'Không xác định';
            $chanel_other->number_c2 = 0;
            $chanel_other->number_c3 = 0;
            $chanel_other->c3perc2 = 0;
            $data[-100] = $chanel_other;
        }

        $total_click = 0;
        $total_submit = 0;
        foreach ($records_click as $item) {
            if ($item->parent_id == -100) {
                $data[$item->parent_id]->number_c2 += 1;
                $total_click += 1;
            } else {
                if (isset($data[$item->parent_id]->list_ads[$item->id_ads])) {
                    $data[$item->parent_id]->list_ads[$item->id_ads]->number_c2 += 1;
                    $total_click += 1;
                }
            }
        }
        foreach ($records_submit as $item) {
            if ($item->parent_id == -100) {
                $data[$item->parent_id]->number_c3 += 1;
                $data[$item->parent_id]->c3perc2 = $data[$item->parent_id]->number_c2 ? number_format(($data[$item->parent_id]->number_c3 * 100) / $data[$item->parent_id]->number_c2, "1", ".", "") : 0;
            } else {
                if (isset($data[$item->parent_id]->list_ads[$item->id_ads])) {
                    $data[$item->parent_id]->list_ads[$item->id_ads]->number_c3 += 1;
                    $data[$item->parent_id]->list_ads[$item->id_ads]->c3perc2 = $data[$item->parent_id]->list_ads[$item->id_ads]->number_c2 ? number_format(($data[$item->parent_id]->list_ads[$item->id_ads]->number_c3 * 100) / $data[$item->parent_id]->list_ads[$item->id_ads]->number_c2, "1", ".", "") : 0;
                    $total_submit += 1;
                }
            }
        }


// ducanh ==================test sap xep c2. c3. ...===================
        $order = $this->input->post('order');
        if ($order) {
            $this->session->set_userdata('order', $order);
        } else {
            $order = $this->session->userdata("order");
        }
// gia tri cua order = number_c2, number_c3, c3perc2
        if ($order) {
            if ($this->session->userdata("order_type") == 0) {
                $this->session->set_userdata("order_type", 1);
            } else {
                $this->session->set_userdata("order_type", 0);
            }
            $order_type = $this->session->userdata("order_type");
            if (is_numeric($order_type)) {
                foreach ($data as $key => $item) {
                    if ($order_type) {
                        uasort($item->list_ads, array($this, "sort_list_ads_desc"));
                    } else {
                        uasort($item->list_ads, array($this, "sort_list_ads_asc"));
                    }
                    $data[$key] = $item;
                }
            }
        }


//        echo "<pre>";
//        var_dump($data);
//        exit;

        $total_row = new stdClass();
        $total_row->stt = "";
        $total_row->name = "Tổng";
        $total_row->list_ads = array();
        $total_row->c3perc2 = $total_click ? number_format(($total_submit * 100) / $total_click, "1", ".", "") : 0;
        $total_row->number_c2 = number_format($total_click);
        $total_row->number_c3 = number_format($total_submit);
        $data['tổng'] = $total_row;

        return $data;
    }

    public function sort_list_ads_desc($a, $b) {
        $order = $this->session->userdata("order");
        if ($a->$order == $b->$order) {
            return 0;
        }
        return ($a->$order < $b->$order) ? -1 : 1;
    }

    public function sort_list_ads_asc($a, $b) {
        $order = $this->session->userdata("order");
        if ($a->$order == $b->$order) {
            return 0;
        }
        return ($a->$order > $b->$order) ? -1 : 1;
    }

// Dung de set sap xep number_c2, number_c3, c3perc2
    public function set_view_click_chanel_order() {
        $condition = $this->input->post();
        if (!isset($condition["order"])) {
            $this->session->set_userdata("order", NULL);
        } else {
            $this->session->set_userdata("order", $condition["order"]);
        }
    }

    public function get_data_view_click_hour() {
        $id_campaign = $this->session->userdata('h_id_campaign');
        $id_landingpage = $this->session->userdata('h_id_landingpage');
        $code_chanel = $this->session->userdata('h_code_chanel');
        $time_begin = $this->session->userdata('h_time_begin');
        $time_end = $this->session->userdata('h_time_end');
        $domain = $this->session->userdata('h_domain');

        $this->load->model("m_log");
        $records_click = $this->m_log->get_data_click($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain);

        $records_submit = $this->m_log->get_data_submit($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain);
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
        return $data;
    }

    public function get_data_marketing_productivity() {
        $id_campaign = $this->session->userdata('id_campaign');
        $id_landingpage = $this->session->userdata('id_landingpage');
        $code_chanel = $this->session->userdata('code_chanel');
//        $code_mauqc = $this->session->userdata('code_mauqc');
        $time_begin = $this->session->userdata('time_begin');
        $time_end = $this->session->userdata('time_end');
        $domain = $this->session->userdata('domain');

        $this->load->model("m_log");
        $this->load->model("m_landing_page");
        $this->load->model("m_chanel");
        $list_chanel = $this->m_chanel->get_list_chanel_parent();

        $array_chanel = array();
        foreach ($list_chanel as $item) {
            if ($item->id != -100) {
                $tmp = array();
                $tmp['name_chanel'] = $item->name;
                $tmp['code_chanel'] = $item->code;
                $tmp['total_click'] = 0;
                $tmp['total_submit'] = 0;
                $tmp['list_ads'] = array();
                $array_chanel[$item->id] = $tmp;
            }
        }
        $array_chanel_other = array();
        $chanel_other = $this->m_chanel->get_one_chanel();
        $array_chanel_other['name_chanel'] = $chanel_other->name;
        $array_chanel_other['code_chanel'] = $chanel_other->code;
        $array_chanel_other['total_click'] = 0;
        $array_chanel_other['total_submit'] = 0;
        $array_chanel_other['list_ads'] = array();
        $array_chanel[$chanel_other->id] = $array_chanel_other;

        $tmp = array();
        $tmp['name_chanel'] = 'Total';
        $tmp['code_chanel'] = '';
        $tmp['cost'] = 0;
        $tmp['total_c1'] = 0;
        $tmp['total_click'] = 0;
        $tmp['total_submit'] = 0;
        $tmp['note'] = '';
        $tmp['list_ads'] = array();
        $array_chanel['Total'] = $tmp;

        $records_click = $this->m_log->get_data_click($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain);

        $records_submit = $this->m_log->get_data_submit($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain);

        $val_time_begin = strtotime($time_begin);
        $val_time_end = strtotime($time_end . ' 23:59:59');
        $n = intval(($val_time_end - $val_time_begin) / (24 * 60 * 60 - 1));
        $data = array();
        for ($i = 0; $i < $n; $i++) {
            $val_date = $val_time_begin + 24 * 60 * 60 * $i;
            $date = date('Y-m-d', $val_date);
            $data[$date] = new stdClass();
            $data[$date]->list_chanel = $array_chanel;
        }
        foreach ($records_click as $item) {
            $h = date('Y-m-d', strtotime($item->datetime));
            if (isset($data[$h])) {
                if (isset($data[$h]->list_chanel[$item->parent_id])) {
                    $data[$h]->list_chanel[$item->parent_id]['total_click'] += 1;
                    $data[$h]->list_chanel['Total']['total_click'] += 1;
                }
            }
        }
        foreach ($records_submit as $item) {
            $h = date('Y-m-d', strtotime($item->datetime_submitted));
            if (isset($data[$h])) {
                if ($item->parent_id == -100) {
                    if (isset($data[$h]->list_chanel[$item->parent_id])) {
                        $data[$h]->list_chanel[$item->parent_id]['total_submit'] += 1;
                        $data[$h]->list_chanel['Total']['total_submit'] += 1;
                    }
                } else {
                    if (isset($data[$h]->list_chanel[$item->parent_id])) {
                        $data[$h]->list_chanel[$item->parent_id]['total_submit'] += 1;
                        $data[$h]->list_chanel['Total']['total_submit'] += 1;
                    }
                }
            }
        }
// ducanh: them tong chi phi theo ngay cho tung
        $this->load->model("m_chanel_cost", "chanel_cost");
        foreach ($data as $date => $item) {
// Tong cost/ngay
            foreach ($item->list_chanel as $key => $value) {
                if ($key != "Total") {
                    $chanel_date_code = $this->chanel_cost->get_one(array(
                        "code_chanel" => $value['code_chanel'],
                        "cast(date as DATE) = " => $date
                            ));
                    if ($chanel_date_code != NULL) {
                        $data[$date]->list_chanel[$key]["cost"] = $chanel_date_code->cost;
                        $data[$date]->list_chanel[$key]["total_c1"] = $chanel_date_code->total_c1;
                        $data[$date]->list_chanel[$key]["note"] = $chanel_date_code->description;
                    } else {
                        $data[$date]->list_chanel[$key]["cost"] = 0;
                        $data[$date]->list_chanel[$key]["total_c1"] = 0;
                        $data[$date]->list_chanel[$key]["note"] = '';
                    }
                    if ($data[$date]->list_chanel[$key]["total_c1"]) {
                        $data[$date]->list_chanel[$key]["rate_click"] = number_format(($value['total_click'] * 100) / $data[$date]->list_chanel[$key]["total_c1"], "0", ".", "");
                        ;
                    } else {
                        $data[$date]->list_chanel[$key]["rate_click"] = 0;
                    }
// + vao cost/ngay
                    $data[$date]->list_chanel["Total"]["cost"] += intval($data[$date]->list_chanel[$key]["cost"]);
                    $data[$date]->list_chanel["Total"]["total_c1"] += intval($data[$date]->list_chanel[$key]["total_c1"]);
                }

// So C2
                $total_click = $data[$date]->list_chanel[$key]['total_click'];
                if ($total_click == 0) {
                    $data[$date]->list_chanel[$key]["cost_c2"] = 0;
                } else {
                    $data[$date]->list_chanel[$key]["cost_c2"] = round($data[$date]->list_chanel[$key]["cost"] / $total_click);
                }

// So C3
                $total_submit = $data[$date]->list_chanel[$key]['total_submit'];
                if ($total_submit == 0) {
                    $data[$date]->list_chanel[$key]["cost_c3"] = 0;
                } else {
                    $data[$date]->list_chanel[$key]["cost_c3"] =
                            round($data[$date]->list_chanel[$key]["cost"] / $total_submit);
                }
// range
                if ($value['total_click']) {
                    $data[$date]->list_chanel[$key]["rate_submit"] = number_format(($value['total_submit'] * 100) / $value['total_click'], "2", ".", "");
                } else {
                    $data[$date]->list_chanel[$key]["rate_submit"] = 0;
                }
                if ($data[$date]->list_chanel[$key]["total_c1"]) {
                    $data[$date]->list_chanel[$key]["rate_click"] = number_format(($data[$date]->list_chanel[$key]['total_click'] * 100) / $data[$date]->list_chanel[$key]["total_c1"], "2", ".", "");
                } else {
                    $data[$date]->list_chanel[$key]["rate_click"] = 0;
                }
            }
        }
        $data_return = array();
        $total_c1_sys = 0;
        $total_cost = 0;
        $total_c2_sys = 0;
        $total_c3_sys = 0;
        $total_cost_c2_sys = 0;
        $total_cost_c3_sys = 0;
        $total_c2_c1_sys = 0;
        $total_c3_c2_sys = 0;
        foreach ($data as $key => $item_record) {
            $data_return[$key] = new stdClass();
            $data_return[$key]->list_chanel = array();
            if ($code_chanel) {
                $data_return[$key]->list_chanel[$code_chanel] = array();
                $data_return[$key]->list_chanel[$code_chanel]['name_chanel'] = $item_record->list_chanel[$code_chanel]['name_chanel'];
                $data_return[$key]->list_chanel[$code_chanel]['cost_all'] = number_format($item_record->list_chanel[$code_chanel]['cost']);
                $data_return[$key]->list_chanel[$code_chanel]['number_c1'] = number_format($item_record->list_chanel[$code_chanel]['total_c1']);
                $data_return[$key]->list_chanel[$code_chanel]['number_c2'] = number_format($item_record->list_chanel[$code_chanel]['total_click']);
                $data_return[$key]->list_chanel[$code_chanel]['cost_c2'] = number_format($item_record->list_chanel[$code_chanel]['cost_c2']);
                $data_return[$key]->list_chanel[$code_chanel]['c2/c1'] = $item_record->list_chanel[$code_chanel]['rate_click'] . "%";
                $data_return[$key]->list_chanel[$code_chanel]['number_c3'] = $item_record->list_chanel[$code_chanel]['total_submit'];
                $data_return[$key]->list_chanel[$code_chanel]['cost_c3'] = number_format($item_record->list_chanel[$code_chanel]['cost_c3']);
                $data_return[$key]->list_chanel[$code_chanel]['c3/c2'] = $item_record->list_chanel[$code_chanel]['rate_submit'] . "%";
                if ($item_record->list_chanel[$code_chanel]['total_submit'] == 0) {
                    $data_return[$key]->list_chanel[$code_chanel]['c3/total_c3'] = "0%";
                } else {
                    $data_return[$key]->list_chanel[$code_chanel]['c3/total_c3'] = number_format(($item_record->list_chanel[$code_chanel]['total_submit'] * 100) / $item_record->list_chanel['Total']['total_submit'], "2", ".", "") . "%";
                }
                $data_return[$key]->list_chanel[$code_chanel]['tile1'] = '';
                $data_return[$key]->list_chanel[$code_chanel]['tile2'] = '';
                $data_return[$key]->list_chanel[$code_chanel]['note'] = $item_record->list_chanel[$code_chanel]['note'];


                $data_return[$key]->list_chanel['Total'] = array();
                $data_return[$key]->list_chanel['Total']['name_chanel'] = $item_record->list_chanel['Total']['name_chanel'];
                $data_return[$key]->list_chanel['Total']['cost_all'] = number_format($item_record->list_chanel['Total']['cost']);
                $data_return[$key]->list_chanel['Total']['number_c1'] = number_format($item_record->list_chanel['Total']['total_c1']);
                $data_return[$key]->list_chanel['Total']['number_c2'] = number_format($item_record->list_chanel['Total']['total_click']);
                $data_return[$key]->list_chanel['Total']['cost_c2'] = number_format($item_record->list_chanel['Total']['cost_c2']);
                $data_return[$key]->list_chanel['Total']['c2/c1'] = $item_record->list_chanel['Total']['rate_click'] . "%";
                $data_return[$key]->list_chanel['Total']['number_c3'] = $item_record->list_chanel['Total']['total_submit'];
                $data_return[$key]->list_chanel['Total']['cost_c3'] = number_format($item_record->list_chanel['Total']['cost_c3']);
                $data_return[$key]->list_chanel['Total']['c3/c2'] = $item_record->list_chanel['Total']['rate_submit'] . "%";
                $data_return[$key]->list_chanel['Total']['c3/total_c3'] = '';
                $data_return[$key]->list_chanel['Total']['tile1'] = '';
                $data_return[$key]->list_chanel['Total']['tile1'] = '';
                $data_return[$key]->list_chanel['Total']['note'] = $item_record->list_chanel['Total']['note'];
            } else {
                foreach ($item_record->list_chanel as $chanel => $item) {
                    $data_return[$key]->list_chanel[$chanel] = array();
                    $data_return[$key]->list_chanel[$chanel]['name_chanel'] = $item['name_chanel'];
                    $data_return[$key]->list_chanel[$chanel]['cost_all'] = number_format($item['cost']);
                    $data_return[$key]->list_chanel[$chanel]['number_c1'] = number_format($item['total_c1']);
                    $data_return[$key]->list_chanel[$chanel]['number_c2'] = number_format($item['total_click']);
                    $data_return[$key]->list_chanel[$chanel]['cost_c2'] = number_format($item['cost_c2']);
                    $data_return[$key]->list_chanel[$chanel]['c2/c1'] = $item['rate_click'] . "%";
                    $data_return[$key]->list_chanel[$chanel]['number_c3'] = $item['total_submit'];
                    $data_return[$key]->list_chanel[$chanel]['cost_c3'] = number_format($item['cost_c3']);
                    $data_return[$key]->list_chanel[$chanel]['c3/c2'] = $item['rate_submit'] . "%";
                    if ($chanel != 'Total') {
                        if ($item_record->list_chanel['Total']['total_submit'] == 0) {
                            $data_return[$key]->list_chanel[$chanel]['c3/total_c3'] = '0%';
                        } else {
                            $data_return[$key]->list_chanel[$chanel]['c3/total_c3'] = number_format(($item['total_submit'] * 100) / $item_record->list_chanel['Total']['total_submit'], "2", ".", "") . "%";
                        }
                    } else {
                        $total_cost += $item['cost'];
                        $total_c1_sys += $item['total_c1'];
                        $total_c2_sys += $item['total_click'];
                        $total_c3_sys += $item['total_submit'];
                        $total_cost_c2_sys += $item['cost_c2'];
                        $total_cost_c3_sys += $item['cost_c3'];
                        $total_c2_c1_sys = $total_c1_sys != 0 ? ($total_c2_sys * 100 / $total_c1_sys) : 0;
                        $total_c3_c2_sys = $total_c2_sys != 0 ? ($total_c3_sys * 100 / $total_c2_sys) : 0;
                        $data_return[$key]->list_chanel[$chanel]['c3/total_c3'] = '';
                    }

                    $data_return[$key]->list_chanel[$chanel]['tile1'] = '';
                    $data_return[$key]->list_chanel[$chanel]['tile2'] = '';
                    $data_return[$key]->list_chanel[$chanel]['note'] = $item['note'];
                }
            }
        }
        $data_return['total_sys'] = new stdClass();
        $data_return['total_sys']->cost = number_format($total_cost);
        $data_return['total_sys']->total_c1 = number_format($total_c1_sys);
        $data_return['total_sys']->total_c2 = number_format($total_c2_sys);
        $data_return['total_sys']->cost_c2 = number_format($total_cost_c2_sys);
        $data_return['total_sys']->c2_c1 = number_format($total_c2_c1_sys, "2", ".", "") . '%';
        $data_return['total_sys']->total_c3 = number_format($total_c3_sys);
        $data_return['total_sys']->cost_c3 = number_format($total_cost_c3_sys);
        $data_return['total_sys']->c3_c2 = number_format($total_c3_c2_sys, "2", ".", "") . '%';
        $data_return['total_sys']->list_chanel = array();
        return $data_return;
    }

    public function get_chart_view_click_hour() {
        $data = $this->get_data_view_click_hour();
        echo json_encode($data);
    }

    public function export_excel_view_click_chanel() {

        $data_all = $this->get_data_view_click_chanel();

        $time_begin = $this->session->userdata('c_time_begin');
        $time_end = $this->session->userdata('c_time_end');

        $this->load->library('excel');
        $sheet = new PHPExcel();

        $objDrawing = new PHPExcel_Worksheet_Drawing();

        $objDrawing->setName('PHPExcel logo');
        $objDrawing->setDescription('PHPExcel logo');
        $path = 'static/images/logo_black.png';
        $objDrawing->setPath($path);
        $objDrawing->setHeight(50);
        $objDrawing->setCoordinates('A2');
        $objDrawing->setOffsetX(0);
        $objDrawing->setOffsetY(0);
        $objDrawing->setWorksheet($sheet->getActiveSheet());
        $style_title = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '810c15'),
                'size' => 28,
                'name' => 'Arial'
                ));
        $sheet->getActiveSheet()->getCell('D2')->setValue('Báo cáo View Click theo kênh');
        $sheet->getActiveSheet()->getCell('E3')->setValue('Từ ngày ' . $time_begin . " đến ngày " . $time_end);
        $sheet->getActiveSheet()->getStyle('D2')->applyFromArray($style_title);

        $data_excel['content'] = $data_all;
        $data_excel['header'] = Array(
            0 => "STT",
            1 => "Tên kênh",
            2 => "Mẫu quảng cáo",
            3 => "Số click (C2)",
            4 => "Số submit (C3)",
            5 => "Tỉ lệ C3/C2 %",
        );
        $col = 0;
// In ra header
        foreach ($data_excel['header'] as $field => $value) {
            $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, 6, $value);
            $col++;
        }
        $row = 7;
        $border_all = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_BLACK)
                )
            )
        );
        $border_head = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                )
            )
        );

// Style cho cac dong cach
        $break_line = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_DARKRED)
                )
            )
        );

//Fill color header
        $sheet->getActiveSheet()->getStyle('A6:N6')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '810c15')
                    )
                )
        );
        $sheet->getActiveSheet()->getStyle('A6:N6')->getFont()->setBold(true)
                ->setName('Verdana')
                ->setSize(10)
                ->getColor()->setRGB('FFFFFF');

// Hien thi content
        foreach ($data_excel['content'] as $key => $row_data) {
            $col = 0;
            $i = 0;
            $sheet->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $row_data->stt);
            $sheet->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
// Tên kênh
            $sheet->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $row_data->name);
            $sheet->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            if (count($row_data->list_ads)) {
                $sheet->getActiveSheet()->mergeCells('A' . $row . ':A' . ($row + count($row_data->list_ads) - 1));
                $sheet->getActiveSheet()->mergeCells('B' . $row . ':B' . ($row + count($row_data->list_ads) - 1));
            }

// In ra row
            if ($key != "tổng" && $key != "-100") {
                foreach ($row_data->list_ads as $sub_row_data) {
                    $tmp_coll = $col; // Dong con ben trong kenh to
                    $sheet->getActiveSheet()->setCellValueByColumnAndRow($tmp_coll++, $row, $sub_row_data->name_chanel);
                    $sheet->getActiveSheet()->setCellValueByColumnAndRow($tmp_coll++, $row, $sub_row_data->number_c2);
                    $sheet->getActiveSheet()->setCellValueByColumnAndRow($tmp_coll++, $row, $sub_row_data->number_c3);
                    $sheet->getActiveSheet()->setCellValueByColumnAndRow($tmp_coll++, $row, $sub_row_data->c3perc2);
                    $row++;
                }
            } else {
                $tmp_coll = $col;
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($tmp_coll++, $row, ""); // Khong co kenh con
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($tmp_coll++, $row, $row_data->number_c2);
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($tmp_coll++, $row, $row_data->number_c3);
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($tmp_coll++, $row, $row_data->c3perc2);
            }

            $row++;
        }
        $row_temp = $row - 1;
        $sheet->getActiveSheet()->getStyle('A6:N6' . $row_temp)->applyFromArray($border_all);
        $sheet->getActiveSheet()->getStyle('A1:N5')->applyFromArray($border_head);
        $sheet->getActiveSheet()->getDefaultStyle()->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'rotation' => 0,
                    'wrap' => false
                )
        );
        $sheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $sheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $sheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $sheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);

        $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ViewClick_Chanel_' . date('dMy') . '.xls"');
        header('Cache-Control: max-age=0');

        $sheet_writer->save('php://output');
    }

    public function export_excel_view_click_hour() {
        $data_all = $this->get_data_view_click_hour();

        $time_begin = $this->session->userdata('h_time_begin');
        $time_end = $this->session->userdata('h_time_end');

        $this->load->library('excel');
        $sheet = new PHPExcel();

        $objDrawing = new PHPExcel_Worksheet_Drawing();

        $objDrawing->setName('PHPExcel logo');
        $objDrawing->setDescription('PHPExcel logo');
        $path = 'static/images/logo_black.png';
        $objDrawing->setPath($path);
        $objDrawing->setHeight(50);
        $objDrawing->setCoordinates('A2');
        $objDrawing->setOffsetX(0);
        $objDrawing->setOffsetY(0);
        $objDrawing->setWorksheet($sheet->getActiveSheet());
        $style_title = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '810c15'),
                'size' => 28,
                'name' => 'Arial'
                ));
        $sheet->getActiveSheet()->getCell('D2')->setValue('Báo cáo View Click theo giờ');
        $sheet->getActiveSheet()->getCell('E3')->setValue('Từ ngày ' . $time_begin . " đến ngày " . $time_end);
        $sheet->getActiveSheet()->getStyle('D2')->applyFromArray($style_title);

        $data_excel['content'] = $data_all;
        $data_excel['header'] = Array(
            0 => "Thời gian",
            1 => "Số click",
            2 => "Số submit",
            3 => "Tỉ lệ C3 / C2 %"
        );
        $col = 0;
// In ra header
        foreach ($data_excel['header'] as $field => $value) {
            $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, 6, $value);
            $col++;
        }
        $row = 7;
        $border_all = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_BLACK)
                )
            )
        );
        $border_head = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                )
            )
        );

// Style cho cac dong cach
        $break_line = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_DARKRED)
                )
            )
        );

//Fill color header
        $sheet->getActiveSheet()->getStyle('A6:N6')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '810c15')
                    )
                )
        );
        $sheet->getActiveSheet()->getStyle('A6:N6')->getFont()->setBold(true)
                ->setName('Verdana')
                ->setSize(10)
                ->getColor()->setRGB('FFFFFF');

// Hien thi content
        foreach ($data_excel['content'] as $hour => $row_data) {
            $col = 0;
            $i = 0;

// In ra gio
            if ($hour == 24) {
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "Tổng");
            } else {
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hour . 'h');
            }

//            }

            $col++;
// In ra row
            foreach ($row_data as $cell_data) {
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $cell_data);
                $col++;
            }
            $row++;
        }
        $row_temp = $row - 1;
        $sheet->getActiveSheet()->getStyle('A6:N6' . $row_temp)->applyFromArray($border_all);
        $sheet->getActiveSheet()->getStyle('A1:N5')->applyFromArray($border_head);
        $sheet->getActiveSheet()->getDefaultStyle()->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'rotation' => 0,
                    'wrap' => false
                )
        );
        $sheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $sheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $sheet->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $sheet->getActiveSheet()->getColumnDimension('D')->setWidth(18);

        $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ViewClick_Hour_' . date('dMy') . '.xls"');
        header('Cache-Control: max-age=0');

        $sheet_writer->save('php://output');
    }

    public function export_excel_marketing_productivity() {

        $data_all = $this->get_data_marketing_productivity();
        $time_begin = $this->session->userdata('time_begin');
        $time_end = $this->session->userdata('time_end');


        $this->load->library('excel');
        $sheet = new PHPExcel();

        $objDrawing = new PHPExcel_Worksheet_Drawing();

        $objDrawing->setName('PHPExcel logo');
        $objDrawing->setDescription('PHPExcel logo');
        $path = 'static/images/logo_black.png';
        $objDrawing->setPath($path);
        $objDrawing->setHeight(50);
        $objDrawing->setCoordinates('A2');
        $objDrawing->setOffsetX(0);
        $objDrawing->setOffsetY(0);
        $objDrawing->setWorksheet($sheet->getActiveSheet());
        $style_title = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '810c15'),
                'size' => 28,
                'name' => 'Arial'
                ));
        $sheet->getActiveSheet()->getCell('D2')->setValue('Báo cáo Năng suất Marketing');
        $sheet->getActiveSheet()->getCell('E3')->setValue('Từ ngày ' . $time_begin . " đến ngày " . $time_end);
        $sheet->getActiveSheet()->getStyle('D2')->applyFromArray($style_title);

        $data_excel['content'] = $data_all;
        $data_excel['header'] = Array(
            0 => "Ngày",
            1 => "Kênh",
            2 => "Cost/Ngày (VNĐ)",
            3 => "Số C1",
            4 => "Số C2",
            5 => "Giá C2 (VNĐ)",
            6 => "Tỉ lệ C2/C1",
            7 => "Số C3",
            8 => "Giá C3 (VNĐ)",
            9 => "Tỉ lệ C3/C2",
            10 => "Tỉ trọng C3",
            11 => "Tỉ lệ nghiệm thu",
            12 => "Tỉ lệ trả lại",
            13 => "Chú ý"
        );
        $col = 0;
// In ra header
        foreach ($data_excel['header'] as $field => $value) {
            $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, 6, $value);
            $col++;
        }
        $row = 7;
        $border_all = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_BLACK)
                )
            )
        );
        $border_head = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                )
            )
        );

// Style cho cac dong cach
        $break_line = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => PHPExcel_Style_Color::COLOR_DARKRED)
                )
            )
        );

//Fill color header
        $sheet->getActiveSheet()->getStyle('A6:N6')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '810c15')
                    )
                )
        );
        $sheet->getActiveSheet()->getStyle('A6:N6')->getFont()->setBold(true)
                ->setName('Verdana')
                ->setSize(10)
                ->getColor()->setRGB('FFFFFF');

// Hien thi content
        foreach ($data_excel['content'] as $date => $data) {
            $col = 0;
            $i = 0;
// In ra ngay thang nam
            if ($date != 'total_sys') {
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, date("d-m-Y", strtotime($date)));
                $sheet->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $sheet->getActiveSheet()->mergeCells('A' . $row . ':A' . ($row + count($data->list_chanel) - 1));
// In ra danh sach cac kenh va thong so
                foreach ($data->list_chanel as $data_chanel) {
                    $col = 1;
                    foreach ($data_chanel as $value) {
                        $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                        $sheet->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $col++;
                    }
                    $row++;
                }
                $row++;
            } else {
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Tổng');
                $col += 2;
                unset($data->list_chanel);
                foreach ($data as $value) {
                    $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                    $sheet->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $col++;
                }
            }
        }
        $row_temp = $row - 1;
        $sheet->getActiveSheet()->getStyle('A6:N6' . $row_temp)->applyFromArray($border_all);
        $sheet->getActiveSheet()->getStyle('A1:N5')->applyFromArray($border_head);
        $sheet->getActiveSheet()->getDefaultStyle()->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'rotation' => 0,
                    'wrap' => false
                )
        );
        $sheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $sheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $sheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $sheet->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $sheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('N')->setWidth(25);

        $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="NS_MARKETING_' . date('dMy') . '.xls"');
        header('Cache-Control: max-age=0');

        $sheet_writer->save('php://output');
    }

    /**
      //    public function view_click_chanel() {
      //        $data = Array();
      //
      //        date_default_timezone_set('Asia/Ho_Chi_Minh'); //setup lai timezone
      //        $date = date('Y-m-d', time());
      //        $id_campaign = $this->input->post('id_campaign');
      //        $id_landingpage = $this->input->post('id_landingpage');
      //        $code_chanel = $this->input->post('code_chanel');
      //        $time_begin = $this->input->post('time_begin');
      //        $time_end = $this->input->post('time_end');
      //
      //        $this->load->model("m_landing_page");
      //        if ($id_landingpage) {
      //            $landingpage = $this->m_landing_page->get_one(array('m.id' => $id_landingpage));
      //            $domain = $landingpage->url;
      //        } else {
      //            $domain = 0;
      //        }
      //
      //        $this->session->set_userdata('c_id_campaign', $id_campaign);
      //        $this->session->set_userdata('c_id_landingpage', $id_landingpage);
      //        $this->session->set_userdata('c_code_chanel', $code_chanel);
      //        $this->session->set_userdata('c_domain', $domain);
      //
      //        $data['export_excel'] = site_url("report/export_excel_view_click_chanel");
      //        $data['view_click_hour_url'] = site_url('report/view_click_hour');
      //        $data['view_click_chanel_url'] = site_url('report/view_click_chanel');
      //        if ($time_begin) {
      //            $data['time_begin'] = $time_begin;
      //            $data['time_end'] = $time_end;
      //            $this->session->set_userdata('c_time_begin', $time_begin);
      //            $this->session->set_userdata('c_time_end', $time_end);
      //        } else {
      //            $data['time_begin'] = $date;
      //            $data['time_end'] = $date;
      //            $this->session->set_userdata('c_time_begin', $date);
      //            $this->session->set_userdata('c_time_end', $date);
      //        }
      //        $data['id_campaign'] = $id_campaign;
      //        $data['id_landingpage'] = $id_landingpage;
      //        $data['code_chanel'] = $code_chanel;
      //        $data['action_url'] = site_url('report/view_click_chanel');
      //        $this->load->model("m_chanel");
      //        $data["list_chanel"] = $this->m_chanel->get_list_chanel_parent();
      //        $this->load->model("m_campaign");
      //        $data["list_campaign"] = $this->m_campaign->get_list();
      //        $this->load->model("m_landing_page");
      //        $data["list_landing_page"] = $this->m_landing_page->get_list();
      //
      //        $data['records'] = $this->get_data_view_click_chanel();
      //
      //        $content = $this->load->view($this->path_theme_view . "report/view_click_chanel", $data, true);
      //        $header_page = $this->load->view($this->path_theme_view . "report/header", $data, true);
      //        $title = 'Thống kê view - click';
      //        $description = NULL;
      //        $keywords = NULL;
      //        $canonical = NULL;
      //
      //        $this->master_page($content, $header_page, $title, $description, $keywords, $canonical);
      //    }
     */
    /**
      //    public function get_data_view_click_chanel() {
      //        $id_campaign = $this->session->userdata('c_id_campaign');
      //        $id_landingpage = $this->session->userdata('c_id_landingpage');
      //        $code_chanel = $this->session->userdata('c_code_chanel');
      //        $time_begin = $this->session->userdata('c_time_begin');
      //        $time_end = $this->session->userdata('c_time_end');
      //        $domain = $this->session->userdata('c_domain');
      //
      //        $this->load->model("m_chanel");
      //        $records = $this->m_chanel->get_data_report($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain);
      //        if ($code_chanel) {
      //            $list_chanel_parent = $this->m_chanel->get_list_chanel_parent(array('m.code' => $code_chanel));
      //        } else {
      //            $list_chanel_parent = $this->m_chanel->get_list_chanel_parent();
      //        }
      //        $this->load->model("m_log");
      //        $records_click = $this->m_log->get_data_click($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain);
      //
      //        $records_submit = $this->m_log->get_data_submit($id_campaign, $id_landingpage, $code_chanel, $time_begin, $time_end, $domain);
      //
      //        $stt = 0;
      //        $data = array();
      //        $n = count($list_chanel_parent);
      //
      //        foreach ($list_chanel_parent as $item) {
      //            if ($item->id != -100) {
      //                $item->stt = ++$stt;
      //                $item->list_ads = array();
      //                $item->number_c2 = 0;
      //                $item->number_c3 = 0;
      //                $data[$item->id] = $item;
      //            }
      //        }
      //        if (!$code_chanel) {
      //            $chanel_other = $this->m_chanel->get_one_chanel();
      //            $chanel_other->list_ads = array();
      //            $chanel_other->stt = $n;
      //            $chanel_other->total_click = 0;
      //            $chanel_other->total_submit = 0;
      //            $data[-100] = $chanel_other;
      //        }
      //
      //        $total_click = 0;
      //        $total_submit = 0;
      //
      //
      //
      //        foreach ($records as $record_item) {
      //            if (isset($data[$record_item->parent_id])) {
      //                $data_row = new stdClass();
      //                $data_row->stt = $stt++;
      //                $data_row->name_chanel = $record_item->name;
      //                $data_row->number_c2 = $record_item->total_click;
      //                $data_row->number_c3 = $record_item->total_submit;
      //                $data_row->c3perc2 = $record_item->total_click ? number_format(($record_item->total_submit * 100) / $record_item->total_click, "1", ".", "") : 0;
      //
      //                $data[$record_item->parent_id]->list_ads[] = $data_row;
      //
      //                $total_click += $record_item->total_click;
      //                $total_submit += $record_item->total_submit;
      //            }
      //        }
      //        $total_row = new stdClass();
      //        $total_row->stt = "";
      //        $total_row->name = "Tổng";
      //        $total_row->number_c2 = $total_click;
      //        $total_row->number_c3 = $total_submit;
      //        $total_row->c3perc2 = 0;
      //        $total_row->list_ads = array();
      //        $total_row->c3perc2 = $total_row->number_c2 ? number_format(($total_row->number_c3 * 100) / $total_row->number_c2, "1", ".", "") : 0;
      //        $data['tổng'] = $total_row;
      //        return $data;
      //    }
     */
}

