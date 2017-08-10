<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/tcpdf/tcpdf.php";

class PDF extends TCPDF { 


	public $title;
	public $note;
	public $quotation;
	public $invoice;
	public $po;
	public $do;
	public $jobtitle;
	public $terms;

	public function __construct() { 
        parent::__construct(); 
        $this->SetMargins(15, 25, 15);
        $this->SetAutoPageBreak( TRUE, 30);
    } 

    public function setTitle($title) {
    	$this->title = $title;	
    }

    public function setQuotation($quotation) {
    	$this->quotation = $quotation;
    	$this->jobtitle = $quotation['job_title'];
    	$this->terms = $quotation['terms'];
    }

    public function setInvoice($invoice) {
    	$this->invoice = $invoice;
    	// $this->jobtitle = $invoice['job_title'];
    	// $this->terms = $invoice['terms'];
    }

    public function setPO($po) {
    	$this->po = $po;
    	$this->jobtitle = $po['company'];
    	$this->terms = $po['terms'];
    }

    public function setDO($do) {
    	$this->do = $do;
    	$this->jobtitle = $do['job_title'];
    	$this->terms = $do['terms'];
    }

    public function setNote($note) {
    	$this->note = $note;
    }

    public function setJobtitle($jobtitle, $terms) {
    	$this->jobtitle = $jobtitle;
    	$this->terms = $terms;
    }

    public function Header() { 
		if($this->title != '') {
			$this->SetFont('helvetica', 'B', 11);
			$this->ln(10);
			$this->setFontSpacing(2);
			$this->Image('./images/logo_boshi_navyblue.png', 15, '', 30, '', '', '', '', false, 600, '', false, false, 0);
			$this->multiCell(180, 3, $this->title, 0, 'R',  0, 0, '', '', true, 0, false, true, 8, 'M');
			$this->ln(5);
			$this->SetFont('helvetica', '', 8);
			$this->setFontSpacing(0);
			$this->multiCell(180, 3, 'GST Reg No. M201109442', 0, 'R',  0, 0, '', '', true, 0, false, true, 8, 'M');
		}
	}

	// Page footer
	public function Footer() {
		global $x;
		if( !empty($this->quotation) || !empty($this->invoice) || !empty($this->do) ) {
			$this->SetY(-35);
			//$this->Image('./images/logo_boshi_navyblue.png', 15, 277, 30, '', '', '', '', false, 600, '', false, false, 0);	
			//$this->Line(142, 268, 195, 268);
			$this->SetFont('helvetica', '', 5);
			//$this->multiCell(40, 4, '', 0, 'L', 0 , 0);
			//$this->multiCell(88, 4, '', 0, 'L', 0 , 0);
			//$this->multiCell(40, 4, 'Authorised Signature & Company Stamp', 0, 'L', 0 , 1);
			$this->ln(1);
			$this->SetFont('helvetica', 'B', 8);
			//$this->multiCell(37, 5, '', 0, 'L', 0 , 0);
			$this->multiCell(180, 5, 'POISE REAL ESTATE PTE LTD', 0, 'C', 0 , 1);
			$this->SetFont('helvetica', '', 7);
			$this->multiCell(180, 5, 'AGENCY LICENSE NO. L3001234567890', 0, 'C', 0 , 1);
			$this->multiCell(180, 5, '33 UBI AVE 3 #08-12 VERTEX TOWER B SINGAPORE 408868', 0, 'C', 0 , 1);
			$this->multiCell(37, 5, '', 0, 'L', 0 , 0);
			//$this->multiCell(140, 5, '14-A Yan Kit Road Singapore 088266 &nbsp;t. 65 6348 3202 f. 65 6327 4401 &nbsp;w. <b>www.mbdesign.com.sg</b>', 0, 'L', 0 , 1, '', '', true, 0, true);
		}
		// else if( !empty($this->invoice) ) {
		// 	$this->SetY(-28);
		// 	$this->Image('./images/mb-invoice.jpg', 15, 274, 50, '', '', '', '', false, 600, '', false, false, 0);
		// 	$this->Image('./images/logo_boshi_navyblue.png', 150, 250, 15, '', '', '', '', false, 600, '', false, false, 0);
		// 	$this->Image('./images/mb-dyansign.jpg', 170, 247, 18, '', '', '', '', false, 600, '', false, false, 0);		

		// 	$this->Line(142, 268, 195, 268);
		// 	$this->SetFont('helvetica', '', 5);
		// 	$this->multiCell(128, 4, '', 0, 'L', 0 , 0);
		// 	$this->multiCell(40, 4, 'for Markers & Brushes Design Pte Ltd', 0, 'L', 0 , 1);
		// 	$this->ln(3);
		// 	$this->SetFont('helvetica', 'B', 7);
		// 	$this->multiCell(55, 5, '', 0, 'L', 0 , 0);
		// 	$this->multiCell(90, 5, 'Markers & Brushes Design Pte Ltd', 0, 'L', 0 , 1);
		// 	$this->SetFont('helvetica', '', 7);
		// 	$this->multiCell(55, 5, '', 0, 'L', 0 , 0);
		// 	$this->multiCell(140, 5, '14-A Yan Kit Road Singapore 088266 &nbsp;t. 65 6348 3202 f. 65 6327 4401 &nbsp;w. <b>www.mbdesign.com.sg</b>', 0, 'L', 0 , 1, '', '', true, 0, true);
		// }
		else if( !empty($this->po) ) {
			$this->SetY(-10);
		}
		
		// if($this->quotation != '') {
		// 	$this->Image('./images/mb-logo.jpg', 15, 277, 30, '', '', '', '', false, 600, '', false, false, 0);	
		// }
		// else if($this->invoice != '') {
		// 	$this->Image('./images/mb-invoice.jpg', 15, 277, 30, '', '', '', '', false, 600, '', false, false, 0);
		// }
		// $this->Line(15, 268, 68, 268);
		
		if( empty($this->po) ) {
			$this->SetFont('helvetica', '', 5);
			$this->multiCell(185, 5, '', 0, 'L', 0 , 0);
			$this->StartTransform();
			$this->Rotate(90);
			$this->multiCell(50, 5,'CRN/GST No. 200404594H', 0, 'L', 0 , 0);
			$this->StopTransform();
		}
		$x = $this->PageNo();
		$this->ln(10);
		$this->SetFont('helvetica', '', 7);
		$this->multiCell(180, 5, 'Page '.$x.' of '.$this->getNumPages(), 0, 'R', 0 , 1);
		//echo "XXXXXXXXXXX>>>>".$x;
	}

	public function generateTable(&$pdf, $header, $content_arr, $footer, $table_height, $total_everypage = false) {
		//For Tbl Footer
		//global $x;
		end($header);
		//$x = $pdf->PageNo();
		//echo "XXXXXXXXXXX>>>>".$x;
		$last_key = key($header);			
		$original_table_height = $table_height;
		if($pdf->PageNo()==1){
	//	echo "PAGE IS>>>>".$x;
			$table_height = 143;
		}
		else{
			$table_height = $table_height;

		}
		

		$total_width = 0; $amount_width = 0;
		foreach($header as $key=>$hd) {
			if( $key != $last_key ) {
				$total_width += $hd; 
			}
			else {
				$amount_width = $hd;
			}
		}

		//For Individual Page Total
		$page_amt_total = 0;
		/* if($pdf->PageNo()==1){
	//	echo "PAGE IS>>>>".$x;
			$table_height = 141;
		}
		else{
			$table_height = $table_height;
		} */
		//$table_height = 110;  
		$tbl_start_y = $pdf->GetY();

		$pdf->ln();
		$border =  array('LR' => array('width' => 0.04, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0)); 
		$border2 =  array('LRB' => array('width' => 0.04, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0));
		$head_border =  array('LTRB' => array('width' => 0.04, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0)); 		

		$pdf->SetFont('helvetica', 'B', 9);
		//For Job Title
		//$title_height =  $this->calculateHeight(120, $pdf->jobtitle, 'LTR');
		$pdf->setCellPaddings(2, 2, 2, 2);
		// $pdf->multiCell(20, $title_height,'Job Title', 'LTR', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
		// $pdf->multiCell(115, $title_height, $pdf->jobtitle, 'LTR', 'L', 0, 0, '', '', true, 0, false, true, 8, 'M');
		// $pdf->multiCell(18, $title_height, 'Terms', 'LTR', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
		// $pdf->multiCell(27, $title_height, $pdf->terms, 'LTR', 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');
		foreach($header as $key=>$hd) {
			$header_width_arr[] = $hd; 
			//$pdf->multiCell($hd, 8, $key, $head_border, 'C', 0, 0, '', '', '', 0, true, false, 8, 'M'); 
			$pdf->multiCell($hd, 8, $key, $head_border, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
		}
		$pdf->ln();
		$pdf->SetFont('helvetica', '', 9);

		foreach($content_arr as $detail) {
			//First Loop to get Max Height
			foreach($detail as $key=>$dt) {
				$height_arr[] = $this->calculateHeight($header_width_arr[$key], $dt['txt'], $border);
			}

			$max_height = max($height_arr);
			//echo "MAX HEIGHT IS>>>>".$max_height."<br>";
			$current_y  = $pdf->GetY();
			if( ($current_y - $tbl_start_y + $max_height) > $table_height ) {
				//Go to New Page...
				/* echo "CURRENT Y IS>>>>".$current_y."<br>";
				echo "START Y IS>>>>".$tbl_start_y."<br>";
				echo "MAX HEIGHT IS>>>>".$max_height."<br>";
				echo "TABLE HEIGHT IS>>>>".$table_height."<br>"; */
				$pdf->SetFont('helvetica', 'B', 9);
				foreach($header as $key=>$hd) {
					$pdf->multiCell($hd, $table_height - ($pdf->GetY() - $tbl_start_y ), '',  $border2, '', 0, 0, '', '', true, 0, true, true); 	
				}
				//$table_height = 100;
				$table_height += ($original_table_height == $table_height) ? 25 : 0;
				$pdf->SetFont('helvetica', '', 9);
				// For every page total 
				if($footer != false) {
					//For Individual Page TOTAL
					//$pdf->ln();
					$footer_y_start = $pdf->getY();
					/* $pdf->multiCell(115, 4,'', 0, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
					$pdf->multiCell(65, 4,'SUB TOTAL', 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
					$pdf->multiCell(115, 4,'', 0, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
					$pdf->multiCell(65, 4,'Tax Total', 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
					$pdf->multiCell(115, 4,'', 0, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
					$pdf->multiCell(65, 4,'GRAND TOTAL', 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 8, 'M'); */
					$footer_y_end = $pdf->getY();
					$pdf->SetFont('helvetica', '', 7);
					$pdf->multiCell(115, 4, $pdf->note, 0, 'L', 0, 1, '', $footer_y_start, true, 0, true, true); 	
					$page_amt_total = 0;
					if( !empty($pdf->quotation) || !empty($pdf->invoice) ) {
						//$pdf->ln(2);				
						$pdf->setCellPaddings(1, 0, 0, 0);
						$y_stamp = $pdf->getY();
						//$pdf->Image('./images/logo_boshi_navyblue.png', 20, $y_stamp, 15, '', '', '', '', false, 600, '', false, false, 0);
						//$pdf->Image('./images/mb-dyansign.jpg', 40, $y_stamp , 12, '', '', '', '', false, 600, '', false, false, 0);
						$pdf->ln(13);
					//	$pdf->multiCell(60, 4, '', 'B', 'L', 0, 1, '', '', true, 0, true, true);
						//$pdf->multiCell(60, 2,'for Poise Real Estate Pte Ltd', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
						$pdf->SetFont('helvetica', '', 8);
						//$pdf->multiCell(60, 2,'Dyan Chang', 0, 'L', 0, 1);
						//$pdf->multiCell(60, 2,'Director', 0, 'L', 0, 1);
					} 
				/* 	if ( !empty($pdf->po) ) {
						$pdf->ln(25);
						$pdf->setCellPaddings(1, 0, 0, 0);
						$pdf->multiCell(60, 4, '', 'B', 'L', 0, 1, '', '', true, 0, true, true);
					//	$pdf->multiCell(60, 2,'for Poise Real Estate Pte Ltd', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');	
					}	 */
				}
				else {
					if ( !empty($pdf->do) ) {	
						$pdf->ln(10);
						$pdf->SetFont('helvetica', '', 7);
						$pdf->multiCell(185, 4, $pdf->note, 0, 'R', 0, 1, '', '', true, 0, true, true); 
					}
				}

				$pdf->AddPage();
				$pdf->ln(5);
				$pdf->SetFont('helvetica', 'B', 9);
				$tbl_start_y = $pdf->GetY();
				$pdf->setCellPaddings(2, 2, 2, 2);
				/* $pdf->multiCell(20, $title_height,'Job Title', 'LTR', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
				$pdf->multiCell(115, $title_height, $pdf->jobtitle, 'LTR', 'L', 0, 0, '', '', true, 0, false, true, 8, 'M');
				$pdf->multiCell(18, $title_height, 'Terms', 'LTR', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
				$pdf->multiCell(27, $title_height, $pdf->terms, 'LTR', 'C', 0, 1, '', '', true, 0, false, true, 8, 'M'); */
				foreach($header as $key=>$hd) {
					$header_width_arr[] = $hd;
					$pdf->multiCell($hd, 8, $key, $head_border, 'C', 0, 0, '', '', '', 0, false, false, 0, 'M'); 
				}
				$pdf->ln();
				$pdf->SetFont('helvetica', '', 9);
			}

			//Second Loop to write
			foreach($detail as $key=>$dt) { 
				$pdf->setCellPaddings(2, 2, 2, 2);
				$pdf->multiCell($header_width_arr[$key], $max_height, rtrim($dt['txt']),  $border,  $dt['align'], 0, 0, '', '', true, 0, true, true); 	
				
				//Assume Last one is amount
				if($key == count($detail) - 1) {
					$page_amt_total += $dt['txt'];
				}
			}
			$height_arr = array();
			$pdf->ln();
		}

		//For Table Closing border
		foreach($header as $key=>$hd) {
			if($pdf->PageNo()==1 ){
				$pdf->multiCell($hd, $table_height - ($pdf->GetY() - $tbl_start_y ), '',  $border2, '', 0, 0, '', '', true, 0, true, true);
			}
			else{
				$pdf->multiCell($hd, $table_height - ($pdf->GetY() - $tbl_start_y ), '',  $border, '', 0, 0, '', '', true, 0, true, true);
			}
			 	
		}
		$pdf->ln();
		
		//For Subtotal/ GST & Total Inc GST
		if($footer != '') {
		//$pdf->multiCell(140, 4,'PLEASE PAY BY THE DATE OF SIGNING THE TENANCY AGREEMENT', 'LBR', 'L', 0, 0, '', '', true, 0, false, true, 8, 'M');
		//$pdf->multiCell(40, 4,'', 'R', 'R', 0, 0, '', '', true, 0, false, true, 8, 'M');
			$footer_y_start = $pdf->getY();
			
			$pdf->multiCell(140, 4,'PLEASE PAY BY THE DATE OF SIGNING THE TENANCY AGREEMENT', 'LR', 'L', 0, 0, '', '', true, 0, false, true, 8, 'M');
			$pdf->multiCell(40, 4,'', 'R', 'R', 0, 0, '', '', true, 0, false, true, 8, 'M');
			//$pdf->multiCell(115, 4,'', 0, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
			$pdf->ln();
			$pdf->multiCell(140, 4,'SUB TOTAL', 'LR', 'R', 0, 0, '', '', true, 0, false, true, 8, 'M');
			$pdf->multiCell(40, 4, $footer['sub_total'], 'LTR', 'R', 0, 1, '', '', true, 0, false, true, 8, 'M');
			//
			
			$pdf->multiCell(140, 4,'ADD: 7% GST', 'LR', 'R', 0, 0, '', '', true, 0, false, true, 8, 'M');
			$pdf->multiCell(40, 4, $footer['gst_percent'], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 8, 'M');
			//$pdf->multiCell(115, 4,'', 0, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
			
			$pdf->multiCell(140, 4,'TOTAL PAYABLE', 'LBR', 'R', 0, 0, '', '', true, 0, false, true, 8, 'M');
			$pdf->multiCell(40, 4, $footer['grand_total'], 'LBR', 'R', 0, 1, '', '', true, 0, false, true, 8, 'M');
			$footer_y_end = $pdf->getY();
			//$pdf->ln();
			$pdf->SetFont('helvetica', '', 8);
			$pdf->multiCell(140, 4, $pdf->note, 0, 'L', 0, 1, '', $footer_y_start, true, 0, true, true); 
			if(!empty($pdf->invoice) ) {
				//$pdf->ln(2);				
				$pdf->setCellPaddings(1, 0, 0, 0);
				$y_stamp = $pdf->getY();
				//$pdf->Image('./images/logo_boshi_navyblue.png', 20, $y_stamp, 15, '', '', '', '', false, 600, '', false, false, 0);
				//$pdf->Image('./images/logo_boshi_navyblue.png', 20, $y_stamp, 15, '', '', '', '', false, 600, '', false, false, 0);
				//$pdf->Image('./images/mb-dyansign.jpg', 40, $y_stamp , 12, '', '', '', '', false, 600, '', false, false, 0);
				$pdf->ln(25);

				//$pdf->multiCell(60, 4, '', 'B', 'L', 0, 1, '', '', true, 0, true, true);
				$pdf->multiCell(110, 2,'Notes', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
				$pdf->multiCell(150, 2,'(1) All Cheques must be crossed and made payable to "POISE REAL ESTATE PTE LTD."', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
				$pdf->multiCell(150, 2,'(2) Cash payments can only be made at our Business Galleria (level one) at 33 UBI AVE 3 #08-12 VERTEX TOWER B SINGAPORE 408868.', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
				$pdf->multiCell(150, 2,'(3) The Company will not be responsible for any payment made to any other party.', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
				$pdf->multiCell(150, 2,'(4) No official receipt will be issued for cheque payment unless requested.', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
				$pdf->multiCell(150, 2,'(5) The Company reserves the right to charge interest at 1.5% per month on any overdue amount.', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
//				$pdf->multiCell(110, 2,'(1) All Cheques must be crossed and made payable to "POISE REAL ESTATE PTE LTD."', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
				$pdf->ln();
				$pdf->SetFont('helvetica', 'B', 8);
				$pdf->multiCell(110, 2,'This is a computer generated invoice and no signature is required', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
				$pdf->SetFont('helvetica', '', 8);
				$pdf->multiCell(150, 2,'Our Ref: LSH123456 ', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
				//$pdf->ln(3);
				//$pdf->SetFont('helvetica', 'B', 8);
				//$pdf->multiCell(110, 2,'This is a computer generated invoice and no signature is required', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
				/* $pdf->multiCell(60, 2,'Dyan Chang', 0, 'L', 0, 1);
				$pdf->multiCell(60, 2,'Director', 0, 'L', 0, 1); */
			} 
			if ( !empty($pdf->po) ) {
				$pdf->ln(25);
				$pdf->setCellPaddings(1, 0, 0, 0);
				$pdf->multiCell(60, 4, '', 'B', 'L', 0, 1, '', '', true, 0, true, true);
				$pdf->multiCell(60, 2,'for Poise Real Estate Pte Ltd', 0, 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');	
			}					
		}
		else {
			if ( !empty($pdf->do) ) {	
				$pdf->ln(1);
				$pdf->SetFont('helvetica', '', 7);
				$pdf->multiCell(185, 4, $pdf->note, 0, 'R', 0, 1, '', '', true, 0, true, true); 
			}
		}

	}

	//Get the heighest height of every cell
	public function calculateHeight($width, $txt, $border) {
		$pdf = new Pdf('L', 'mm', 'Letter', true, 'UTF-8', false);
		$pdf->SetPrintHeader(false);
		$pdf->AddPage();
		//Notice : Font and Size should be the same as source
		$pdf->SetFont('helvetica', '', 9);
		$y1 = $pdf->getY();
		$pdf->setCellPaddings(2, 2, 2, 2);
		$pdf->multiCell($width, 5, $txt, $border, '', 0, 0, '', '', true, 0, true, true);
		$pdf->ln();
		$y2 = $pdf->getY();
		
		$pdf->deletePage($pdf->getPage());
		return $y2 - $y1;
	}

	// public function quotationHeader(&$pdf) {

	// 	$quotation = $pdf->quotation;
	// 	$delivery_date = ($quotation['delivery_date'] != 0) ? date('d/m/Y', $quotation['delivery_date']) : '';
	// 	$invoice_date = ($quotation['invoice_date'] != 0) ? date('d/m/Y', $quotation['invoice_date']) : '';
	// 	$revised = ($quotation['revised'] == 1) ? "[ Revised ]" : '';

	// 	$pdf->AddPage();
	// 	$pdf->SetFont('helvetica', '', 9);
	// 	$pdf->resetColumns();
 //        // $pdf->setEqualColumns(2, 90);  

 //        $col_arr = array(
 //        	0 => array( 'w' => 100, 's' => 5, 'y' => '30' ),
 //        	1 => array( 'w' => 70, 's' => 5, 'y' => '25' ),
 //        );
 //        $pdf->setColumnsArray($col_arr);

 //        $pdf->selectColumn(2);
 //        $pdf->multiCell(25, 5, 'Client Name', 0, 'L', 0 , 0);
	// 	$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
	// 	$pdf->multiCell(50, 5, $quotation['contact'], 0, 'L', 0 , 1);
	// 	$pdf->multiCell(25, 5, 'Designation', 0, 'L', 0 , 0);
	// 	$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
	// 	$pdf->multiCell(50, 5, $quotation['designation'], 0, 'L', 0 , 1);
	// 	$pdf->multiCell(25, 5, 'Department', 0, 'L', 0 , 0);
	// 	$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
	// 	$pdf->multiCell(50, 5, $quotation['department'], 0, 'L', 0 , 1);
	// 	$pdf->multiCell(25, 5, 'Address', 0, 'L', 0 , 0);
	// 	$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
	// 	$pdf->multiCell(50, 5, $quotation['client'] . '<br/>' . $quotation['address'], 0, 'L', 0, 1, '', '', true, 0, true, true);
	// 	$col_y1 = $pdf->getY();

	// 	$pdf->selectColumn(1); 

	// 	$tbl = '<style> ' . 
	// 		   ' table { ' .
	// 		   '	border-top-width:0.05px solid;' .
	// 		   '	border-left-width:0.05px solid;' .
	// 		   '	border-right-width:0.05px solid;' .
	// 		   ' } ' .
	// 		   ' td {' .
	// 		   '	border-right-width:0.05px solid;' .
	// 		   '	border-bottom-width:0.05px solid;' .
	// 		   '    text-align:center;' .
	// 		   ' }'. 
	// 		   '</style>' .

	// 			'<table cellspacing="0" cellpadding="4" width="100%">' .
	// 			   '<tr>' .
	// 			   		'<td width="33.33%">Date</td>'.
	// 			   		'<td width="33.33%">Job No</td>'.
	// 			   		'<td width="33.33%">Rep</td>'.
	// 			   '</tr>' .
	// 			   '<tr>' .
	// 			   		'<td width="33.33%">'.date('d/m/Y', $quotation['date']).'</td>'.
	// 			   		'<td width="33.33%">'.$quotation['quotation_no'].'</td>'.
	// 			   		'<td width="33.33%">'.$quotation['rep'].'</td>'.
	// 			   '</tr>' .
	// 		   '</table>';

	// 	$pdf->SetFont('helvetica', '', 7);
	// 	$pdf->writeHTML($tbl, false, false, false, false, '');

	// 	$pdf->ln(2);

	// 	$tbl = '<style> ' . 
	// 		   ' table { ' .
	// 		   '	border-top-width:0.05px solid;' .
	// 		   '	border-left-width:0.05px solid;' .
	// 		   '	border-right-width:0.05px solid;' .
	// 		   ' } ' .
	// 		   ' td {' .
	// 		   '	border-right-width:0.05px solid;' .
	// 		   '	border-bottom-width:0.05px solid;' .
	// 		   '    text-align:center;' .
	// 		   ' }'. 
	// 		   '</style>' .

	// 			'<table cellspacing="0" cellpadding="4" width="100%">' .
	// 			   '<tr>' .
	// 			   		'<td width="33.33%">Delivery Date</td>'.
	// 			   		'<td width="33.33%">P.O.No</td>'.
	// 			   		'<td width="33.33%">ST-OIC</td>'.
	// 			   '</tr>' .
	// 			   '<tr>' .
	// 			   		'<td width="33.33%">'.$delivery_date.'</td>'.
	// 			   		'<td width="33.33%">'.$quotation['po_no'].'</td>'.
	// 			   		'<td width="33.33%">'.$quotation['st_oic'].'</td>'.
	// 			   '</tr>' .
	// 		   '</table>';

	// 	$pdf->SetFont('helvetica', '', 7);
	// 	$pdf->writeHTML($tbl, false, false, false, false, '');

	// 	$pdf->ln(2);

	// 	$tbl = '<style> ' . 
	// 		   ' table { ' .
	// 		   '	border-top-width:0.05px solid;' .
	// 		   '	border-left-width:0.05px solid;' .
	// 		   '	border-right-width:0.05px solid;' .
	// 		   ' } ' .
	// 		   ' td {' .
	// 		   '	border-right-width:0.05px solid;' .
	// 		   '	border-bottom-width:0.05px solid;' .
	// 		   '    text-align:center;' .
	// 		   ' }'. 
	// 		   '</style>' .

	// 			'<table cellspacing="0" cellpadding="4" width="100%">' .
	// 			   '<tr>' .
	// 			   		'<td width="33.33%">Invoice Date</td>'.
	// 			   		'<td width="33.33%">Invoice No</td>'.
	// 			   		'<td width="33.33%">Tel No</td>'.
	// 			   '</tr>' .
	// 			   '<tr>' .
	// 			   		'<td width="33.33%">'.$invoice_date.'</td>'.
	// 			   		'<td width="33.33%">'.$quotation['invoice_no'].'</td>'.
	// 			   		'<td width="33.33%">'.$quotation['tel_no'].'</td>'.
	// 			   '</tr>' .
	// 		   '</table>';

	// 	$pdf->SetFont('helvetica', '', 7);
	// 	$pdf->writeHTML($tbl, false, false, false, false, '');
	// 	$pdf->ln(2);

	// 	$col_y2 = $pdf->getY();
		
	// 	$pdf->resetColumns();
	// 	$y_pos = ($col_y1 > $col_y2) ? $col_y1 : $col_y2; 
	// 	$pdf->SetY($y_pos);
	// } 

}
