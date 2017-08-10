<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/phpexcel/PHPExcel.php";
require_once APPPATH."/third_party/phpexcel/PHPExcel/Style/Alignment.php";
//require_once APPPATH."/third_party/PHPExcel/Writer/Excel2007.php"; 
 
class Excel extends PHPExcel { 
    public function __construct() { 
        parent::__construct(); 
    } 

    public function export($arr, $filename) {
    	// HEADER 
    	$arr_header = $arr[0]; //Take first array key for header...
		$header_col = 0;
		
			$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000'),
        'size'  => 15,
        'name'  => 'Verdana'
    ));


		
		
		foreach($arr_header as $key=>$header) {	
			$this->getActiveSheet()->setCellValueByColumnAndRow($header_col++, 1, $key);
		}
		// Freeze First Column
		$this->getActiveSheet()->freezePane('B2');

		//CONTENT
		foreach($arr as $rowKey=>$ar) {
			$col = 0; $row = $rowKey + 2;
			foreach($ar as $a) {
				$this->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $a);
			}
		}

		//COLUMN ADJUSTMENT FROM A TO Z 
		for($col = 'A'; $col !== 'Z'; $col++) {
		    $this->getActiveSheet()
		        ->getColumnDimension($col)
		        ->setAutoSize(true);
	 		 $this->getActiveSheet()->getStyle($col.'1:'.$col.'999')->getFont()->setSize(10);
			// $this->getActiveSheet()->getStyle($col.'1')->getFont()->setBold(true);
		}

		// For Excel 2003 format .xls
  		// header('Content-Type: application/vnd.ms-excel'); //mime type FOR #XCEL 2003
		// header('Content-Disposition: attachment;filename="'.$filename.'.xls"'); //tell browser what's the file name
		// header('Cache-Control: max-age=0'); //no cache
		// $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel5');  
		// $objWriter->save('php://output');

		//For Excel 2007 format .xlsx
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');

		//For Password Protected Sheet...
		// $this->getActiveSheet()->getProtection()->setSheet(true);
		// $this->getActiveSheet()->getProtection()->setSort(true);
		// $this->getActiveSheet()->getProtection()->setInsertRows(true);
		// $this->getActiveSheet()->getProtection()->setFormatCells(true);
		// $this->getActiveSheet()->getProtection()->setPassword('admin');

		$objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel2007');
		$objWriter->save('php://output');
		exit();
    }

}