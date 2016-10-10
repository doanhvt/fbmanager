<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Excel
 *
 * @author Tùng Arsenal
 */
require_once APPPATH . "/third_party/PHPExcel.php";

class Excel extends PHPExcel {

    public function __construct() {
        parent::__construct();
    }

    public function write_excel($header, $data_export) {
        $sheet = new PHPExcel ();
        $sheet->getProperties()->setTitle('Attendance Report')->setDescription('Attendance Report');
        $sheet->setActiveSheetIndex(0);
        $col = 0;
        foreach ($header as $field => $value) {
            $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $value);
            $col++;
        }
        $row = 2;
        foreach ($data_export as $data) {
            $col = 0;
            foreach ($data as $field_val) {
                $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field_val);
                $col++;
            }
            $row++;
        }

        $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="att_report_' . date('dmY') . '.xls"');
        header('Cache-Control: max-age=0');

        $sheet_writer->save('php://output');
    }

    /**
     * marketing_productivity
     */
    public function write_excel_marketing_productivity($data_other, $data_export) {
        $sheet = new PHPExcel ();

        $startTime = $data_other['startTime'];
        $start_day = date('d', strtotime($startTime));
        $start_month = date('m', strtotime($startTime));
        $start_year = date('Y', strtotime($startTime));

        $stopTime = $data_other['stopTime'];
        $stop_day = date('d', strtotime($stopTime));
        $stop_month = date('m', strtotime($stopTime));
        $stop_year = date('Y', strtotime($stopTime));


        //style cho header cua excel: Báo cáo học lý thuyết học viên
        $styleHeader1 = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
        );
        //style cho header2 cua excel: Từ ngày …. tháng…năm... đến ngày…….  tháng……..  năm….. 
        $styleHeader2 = array(
            'font' => array(
                'italic' => true,
                'color' => array('rgb' => '000000'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
        );
        //style cho row dau tien cua bang
        $styleHeaderRow = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        //style cho content
        $styleContent = array(
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOTTED,
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),
        );
        // Set row height
//        $sheet->getActiveSheet()->getRowDimension(1)->setRowHeight(50);
//        $sheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
// Set column width
        $sheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $sheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $sheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $sheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $sheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $sheet->getProperties()->setTitle('Báo cáo NĂNG XUẤT MARKETING')->setDescription('Báo cáo NĂNG XUẤT MARKETING');
        $sheet->setActiveSheetIndex(0);
        //row 1
//        $sheet->getActiveSheet()->mergeCells('J1:I1');
//        $sheet->getActiveSheet()->setCellValue('J1', '');
        //row 3
        $sheet->getActiveSheet()->mergeCells('A3:I3');
        $sheet->getActiveSheet()->setCellValue('A3', "NĂNG XUẤT MARKETING");
        $sheet->getActiveSheet()->getStyle('A3')->applyFromArray($styleHeader1);

        //row 4
        $sheet->getActiveSheet()->mergeCells('A4:I4');
        $sheet->getActiveSheet()->setCellValue('A4', "Từ ngày $start_day tháng $start_month năm $start_year đến ngày $stop_day tháng $stop_month năm $stop_year. ");
        $sheet->getActiveSheet()->getStyle('A4:I4')->applyFromArray($styleHeader2);
        //row 5
//        $sheet->getActiveSheet()->mergeCells('A5:C5');
//        $userName = $data_other['userName'];
//        $sheet->getActiveSheet()->setCellValue('A5', "Học viên: $userName");
        //row 8
//        $sheet->getActiveSheet()->setCellValue('K8', 'ĐVT: đồng');
//        $sheet->getActiveSheet()->getStyle('A4:K8')->applyFromArray($styleHeader2);
        //row 9
        $sheet->getActiveSheet()->getStyle('A7:N7')->applyFromArray($styleHeaderRow);

        $sheet->getActiveSheet()->setCellValue('A7', 'Ngày');
        $sheet->getActiveSheet()->setCellValue('B7', 'Kênh');
        $sheet->getActiveSheet()->setCellValue('C7', 'Cost/ngày');
        $sheet->getActiveSheet()->setCellValue('D7', 'Số C1');
        $sheet->getActiveSheet()->setCellValue('E7', 'Số C2');
        $sheet->getActiveSheet()->setCellValue('F7', 'Giá C2');
        $sheet->getActiveSheet()->setCellValue('G7', 'Tỉ lệ C2/C1');
        $sheet->getActiveSheet()->setCellValue('H7', 'Số C3');
        $sheet->getActiveSheet()->setCellValue('I7', 'Giá C3');
        $sheet->getActiveSheet()->setCellValue('J7', 'Tỉ lệ C3/C2');
        $sheet->getActiveSheet()->setCellValue('K7', 'Tỉ trọng C3');
        $sheet->getActiveSheet()->setCellValue('L7', 'Tỉ lệ nghiệm thu');
        $sheet->getActiveSheet()->setCellValue('M7', 'Tỉ lệ trả lại');
        $sheet->getActiveSheet()->setCellValue('N7', 'Chú ý');


//        $row = 8;
//        if (sizeof($data_export) > 0) {
//            foreach ($data_export as $data) {
//                $merge = sizeof($data->list_lesson);
//                $sheet->getActiveSheet()->mergeCellsByColumnAndRow(0, $row, 0, $row + $merge - 1);
//                $sheet->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Unit: ' . $data->section . ' ' . $data->tpe_title);
//                for ($i = $row; $i < $row + $merge; $i++) {
//                    $sheet->getActiveSheet()->getStyleByColumnAndRow(0, $i)->applyFromArray($styleContent);
//                    $sheet->getActiveSheet()->getStyleByColumnAndRow(1, $i)->applyFromArray($styleContent);
//                    $sheet->getActiveSheet()->getStyleByColumnAndRow(2, $i)->applyFromArray($styleContent);
//                }
//
//                $sheet->getActiveSheet()->mergeCellsByColumnAndRow(1, $row, 1, $row + $merge - 1);
//                $sheet->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data->tpe_objective);
//
//                $sheet->getActiveSheet()->mergeCellsByColumnAndRow(2, $row, 2, $row + $merge - 1);
//                $sheet->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data->tpe_element);
//
//                if (is_array($data->list_lesson)) {
//                    $i = 0;
//                    foreach ($data->list_lesson as $k => $v) {
//                        $sheet->getActiveSheet()->setCellValueByColumnAndRow(3, $row + $i, $v->name . '(' . $v->tpe_code . ')');
//                        $sheet->getActiveSheet()->getStyleByColumnAndRow(3, $row + $i)->applyFromArray($styleContent);
//
//                        $sheet->getActiveSheet()->setCellValueByColumnAndRow(4, $row + $i, $v->tpe_description);
//                        $sheet->getActiveSheet()->getStyleByColumnAndRow(4, $row + $i)->applyFromArray($styleContent);
//
//                        $sheet->getActiveSheet()->setCellValueByColumnAndRow(5, $row + $i, $v->tpe_type);
//                        $sheet->getActiveSheet()->getStyleByColumnAndRow(5, $row + $i)->applyFromArray($styleContent);
//
//                        $sheet->getActiveSheet()->setCellValueByColumnAndRow(6, $row + $i, $this->processTime($v->tpe_starttime, $v->tpe_endtime));
//                        $sheet->getActiveSheet()->getStyleByColumnAndRow(6, $row + $i)->applyFromArray($styleContent);
//
//                        $sheet->getActiveSheet()->setCellValueByColumnAndRow(7, $row + $i, $v->tpe_type == 'WORKSHEET' ? 'done' : '');
//                        $sheet->getActiveSheet()->getStyleByColumnAndRow(7, $row + $i)->applyFromArray($styleContent);
//
//                        $sheet->getActiveSheet()->setCellValueByColumnAndRow(8, $row + $i, $v->tpe_type == 'WORKSHEET' ? 'n/a' : '');
//                        $sheet->getActiveSheet()->getStyleByColumnAndRow(8, $row + $i)->applyFromArray($styleContent);
//
//                        $i++;
//                    }
//                }
//                $row += $merge;
////                var_dump($row);exit;
//            }
//        }

        if(sizeof($data_export) > 0){
            foreach ($data_export as $date => $data) {
                
            }
        }
        
        $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="NS_MARKETING' . date('dmY') . '.xls"');
        header('Cache-Control: max-age=0');

        $sheet_writer->save('php://output');
    }

    public function processTime($tpe_starttime, $tpe_endtime) {
        $string_time = '';
        if ($tpe_starttime) {
            $list_starttime = explode(',', $tpe_starttime);
            $list_stoptime = explode(',', $tpe_endtime);
            $date_array = array();
            foreach ($list_starttime as $key => $item) {
                if ($item) {
                    $time = date('d/m/Y', $list_starttime[$key]);
                    if ($list_stoptime[$key] >= $list_starttime[$key]) {
                        if (isset($date_array[$time])) {
                            $date_array[$time] = $date_array[$time] + ($list_stoptime[$key] - $list_starttime[$key]);
                            unset($list_starttime[$key]);
                        } else {
                            $date_array[$time] = $list_stoptime[$key] - $list_starttime[$key];
                        }
                    }
                }
            }
            foreach ($date_array as $key => $value) {
                if ($string_time) {
                    $string_time = $string_time . ', ' . $key . '(' . $value . 's)';
                } else {
                    $string_time = $key . '(' . $value . 's)';
                }
            }
        }
        return $string_time;
    }

}
