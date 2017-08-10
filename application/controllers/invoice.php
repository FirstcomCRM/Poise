<?php
class invoice extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->model('invoice_model');
		$this->load->model('transaction_model');
		/* $this->load->model('production_model');
		$this->load->model('uom_model');
		$this->load->model('client_model');
		$this->load->model('quotation_model');
		$this->load->model('setting_model'); */
		$this->load->model('user_model');
		checkPermission();
	}

	public function index($dt = FALSE)	{	
		if($dt === FALSE ) { 									// First time load
			$data['msg'] = $this->session->flashdata('msg');
			$data['nav_area'] = 'invoice';

			$this->load->view('template/header', $data);
			$this->load->view('invoice/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->invoice_model->getdtinvoices_admin();	
		}
	}

	public function view($id) {
		$data['invoice'] = $this->invoice_model->getInvoiceinfo($id); 
		if (empty($data['invoice'])) {
			show_404();
		}
		$data['details'] =  $this->invoice_model->getDetilsbyinvoiceid($id);
		$this->load->view('invoice/view', $data);
	}

	public function create($quotation_id = FALSE) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('invoice_no', 'Invoice No', 'required');
		$this->form_validation->set_rules('invoice_date', 'Date', 'required');
		//$this->form_validation->set_rules('quotation_id', 'Quotation', 'required');
		
		if($this->form_validation->run() === FALSE) {
			$submitted = $this->input->post('hid_submitted');
			if(!$submitted) {
				$data['action'] = 'new';
				$data['nav_area'] = 'project';
				//$data['is_quotation'] = false;
				$data['users'] = $this->user_model->get_users();
				$data['transactions'] = $this->transaction_model->get_approved_transactions();

		
				$this->load->view('template/header', $data);
				$this->load->view('invoice/edit', $data);
				$this->load->view('template/footer', $data);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => validation_errors(),
				);	
				echo json_encode($ret);
			}
		}
		else {
			$invoice_id = $this->invoice_model->add_invoice();
			if($invoice_id) {
				logActivity('Created', "Created New Invoice  : " . $this->input->post('invoice_no'), $invoice_id);
				$this->session->set_flashdata('msg', 'Invoice Successfully Created');
				$ret = array(
					'status' => 'success',
				);	
				$this->pv_sendEmail($invoice_id);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Invoice Add'
				);
			}
			echo json_encode($ret);	
		}
	}


	public function edit($id) {
		$data['invoice'] = $this->invoice_model->getInvoiceinfo($id); 
		if (empty($data['invoice'])) {
			show_404();
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('invoice_no', 'Invoice No', 'required');
		$this->form_validation->set_rules('invoice_date', 'Date', 'required');
		//$this->form_validation->set_rules('quotation_id', 'Quotation', 'required');

		if($this->form_validation->run() === FALSE) {
			$data['action'] = 'edit';
			$data['nav_area'] = 'project';
			$submitted = $this->input->post('hid_submitted');
			if(!$submitted) {
				$data['users'] = $this->user_model->get_users();
				//$data['uoms'] = $this->uom_model->get_uoms();
				$data['transactions'] = $this->transaction_model->get_approved_transactions();
				$data['details'] = $this->invoice_model->getDetilsbyinvoiceid($id);
				

				$this->load->view('template/header', $data);
				$this->load->view('invoice/edit', $data);
				$this->load->view('template/footer', $data);		
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => validation_errors(),
				);	
				echo json_encode($ret);
			}			
		} 
		else {
			$updated = $this->invoice_model->update_invoice($id);
			if($updated) {
				logActivity('Updated', "Updated New Invoice : " . $this->input->post('invoice_no'), $id);
				$this->session->set_flashdata('msg', 'Invoice Successfully Updated');
				$ret = array(
					'status' => 'success',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Invoice Update'
				);
			}	
			echo json_encode($ret);
		}
	}

	public function delete($id) {
		$invoice = $this->invoice_model->get_invoices($id);
		if ( !empty($invoice) ) {
			$deleted = $this->invoice_model->delete_invoice($id);  
		    if($deleted) {
		    	logActivity('Deleted', "Deleted Invoice : " . $invoice['invoice_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> 'Invoice Successfully Deleted',
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in Invoice Delete',
			    );
		    }
		}
		else {
	    	$ret = array(
		      'status' 	=> 'fail',
		      'msg'  	=> 'No data',
		    );			
		}
	    echo json_encode($ret);
	}

	//Site Ajax //
	public function aj_getInvoicedetail($id) {
		$detail = $this->invoice_model->getDetail($id);
		if ( !empty($detail) ) {
			$detail['status'] = 'success';
		}
		else {
	    	$detail = array(
		      'status' 	=> 'fail',
		      'msg'  	=> 'No data',
		    );			
		}
	    echo json_encode($detail);
	}
	
	public function aj_getInvoicePaymentdetail($id) {
		$detail = $this->invoice_model->getPaymentDetail($id);
		if ( !empty($detail) ) {
			$detail['status'] = 'success';
		}
		else {
	    	$detail = array(
		      'status' 	=> 'fail',
		      'msg'  	=> 'No data',
		    );			
		}
	    echo json_encode($detail);
	}
	
	public function aj_getContactinfo($client_id) {
		$client['data'] = $this->quotation_model->getClientDetail($client_id);
		$client['count'] = count($client['data']);
		if(!empty($client)) {
			$client['status'] = 'success';
			
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($client);
	}

	public function aj_addInvoicedetail() {
		$detail_id = $this->invoice_model->addDetail();
		if($detail_id) {
			$invoice_id = $this->input->post('hid_invoice_id');
			$details = $this->invoice_model->getDetilsbyinvoiceid($invoice_id);
			//logActivity('Added', "Added Invoice Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'invoice_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}

	public function aj_addInvoicePaymentdetail() {
		$payment_detail_id = $this->invoice_model->addPaymentDetail();
		if($payment_detail_id) {
			$invoice_id = $this->input->post('hid_invoice_id');
			$payment_details = $this->invoice_model->getPaymentDetailsbyinvoiceid($invoice_id);
			//logActivity('Added', "Added Invoice Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'invoice_payment_detail'	=> $payment_details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}
	


	
	public function aj_updateInvoicedetail($id) {
		$updated = $this->invoice_model->updateDetail($id);
		if($updated) {
			$invoice_id = $this->input->post('hid_invoice_id');
			$details = $this->invoice_model->getDetilsbyinvoiceid($invoice_id);
			logActivity('Updated', "Updated Invoice Detail : " . $this->input->post('description'), $id);
			$ret = array(
				'status'			=> 'success',
				'invoice_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'Error in Invoice Detail Update'
			);
		}
		echo json_encode($ret);
	}

	public function aj_updateInvoicePaymentdetail($id) {
		$updated = $this->invoice_model->updatePaymentDetail($id);
		if($updated) {
			$invoice_id = $this->input->post('hid_invoice_id');
			$details = $this->invoice_model->getPaymentDetailsbyinvoiceid($invoice_id);
			//logActivity('Updated', "Updated Invoice Detail : " . $this->input->post('description'), $id);
			$ret = array(
				'status'			=> 'success',
				'invoice_payment_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'Error in Invoice Detail Update'
			);
		}
		echo json_encode($ret);
	}
	
	
	
	
	
	public function aj_deleteInvoicedetail($id) {
		$invoice_detail = $this->invoice_model->getDetail($id);
		if(!empty($invoice_detail)) {
			$removed = $this->invoice_model->removeDetail($id);
			if($removed) {
				$details = $this->invoice_model->getDetilsbyinvoiceid($invoice_detail['invoice_id']);
				logActivity('Removed', "Removed Invoice Detail : " . $invoice_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'invoice_detail'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in Invoice Detail remove'
				);	
			}			
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Data',
			);	
		}
		echo json_encode($ret);
	}

	
	public function aj_deleteInvoicePaymentdetail($id) {
		$invoice_detail = $this->invoice_model->getPaymentDetail($id);
		if(!empty($invoice_detail)) {
			$removed = $this->invoice_model->removePaymentDetail($id);
			if($removed) {
				$details = $this->invoice_model->getPaymentDetailsbyinvoiceid($invoice_detail['invoice_id']);
				//logActivity('Removed', "Removed Invoice Detail : " . $invoice_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'invoice_payment_detail'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in Invoice Detail remove'
				);	
			}			
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Data',
			);	
		}
		echo json_encode($ret);
	}
	
	
	
	
	
	public function aj_getQuotationinfo($quotation_id) {
		$quotation = $this->quotation_model->getQuotationinfo($quotation_id);
		if(!empty($quotation)) {
			$quotation['status'] = 'success';
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($quotation);
	}

	public function pv_getNamefromarray($arr, $ret = FALSE) {
		if($ret == FALSE) {
			$ret = array();
		}
		foreach($arr as $ar) {
			$ret[] = $ar['name'];
		}
		return $ret;
	}

	public function printInvoice($id) {
		$invoice = $this->invoice_model->getInvoiceinfo($id);
		if (empty($invoice)) {
			show_404();
		}	
		$details =  $this->invoice_model->getDetilsbyinvoiceid($id);
		//$note = $this->setting_model->getInvoicenote();

		$this->load->library('pdf');
		//$this->load->library('tcpdf');
		//$pageLayout = array($width, $height);
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->setTitle('Tax Invoice');
		//$pdf->setNote($note);
		$pdf->setInvoice($invoice);

		//$delivery_date = ($invoice['delivery_date'] != 0) ? date('d/m/Y', $invoice['delivery_date']) : '';
		//$invoice_date = ($invoice['invoice_date'] != 0) ? date('d/m/Y', $invoice['invoice_date']) : '';
		
		$this->pv_invoiceHeader($pdf, $invoice);
		
        $header = array(
			'Description'	=> 140,
			'Amount'		=> 40,
		); 
		//Content
		$detail_arr = array(); $i = 1;
		foreach($details as $dt) {
			$description_arr = explode(' ', $dt['description']);
			$description = implode('&nbsp;', $description_arr);
		if($dt['amount'] ==0.00 || $dt['amount'] =="0.00" || $dt['amount'] ==0 || $dt['amount'] =="0" ){
			$dt['amount']='';
		} 
			$detail_arr[] = array(
				0	=> array('txt' => $dt['description'], 'align' => 'L'), 
				1	=> array('txt' => $dt['amount'] , 'align' => 'R'), 
			);		
		}

		$footer = array(
			'sub_total'		=> '$ '.$invoice['sub_total'] , 2, '.', ',', 
			'gst_percent'   => '$ '.$invoice['add_gst'],
			//'gst_p'			=> '$ '.$invoice['gst'], 2, ".","", 
			'grand_total'	=> '$ '.$invoice['total'] , 2, '.', ',', 
		);

		// $footer = array(
		// 	'SUBTOTAL'							=>  array('txt' => '$' . number_format($invoice['sub_total'], 2, '.', ','), 'align' => 'R'),
		// 	'GST ' . $invoice['gst'] . ' % '	=>  array('txt' => '$' . number_format($invoice['total'] - $invoice['sub_total'], 2, ".",""), 'align' => 'R'),  
		// 	'GRAND TOTAL'						=>	array('txt' => '$' . number_format($invoice['total'], 2, '.', ','), 'align' => 'R'),					
		// );

		$this->pdf->generateTable($pdf, $header, $detail_arr, $footer, 115, true);
        $pdf->ln(1);
		$pdf->Output('Invoice.pdf', 'I');
	}


	public function pv_invoiceHeader(&$pdf, &$invoice) {

		$invoice = $pdf->invoice;
		//$delivery_date = ($invoice['delivery_date'] != 0) ? date('d/m/Y', $invoice['delivery_date']) : '';
		//$invoice_date = ($invoice['invoice_date'] != 0) ? date('d/m/Y', $invoice['invoice_date']) : '';
		//$revised = ($invoice['revised'] == 1) ? "[ Revised ]" : '';

		$pdf->AddPage();
		$pdf->SetFont('helvetica', '', 9);
		$pdf->resetColumns();
        // $pdf->setEqualColumns(2, 90);  

        $col_arr = array(
        	0 => array( 'w' => 100, 's' => 5, 'y' => '30' ),
        	1 => array( 'w' => 75, 's' => 5, 'y' => '25' ),
        );
        $pdf->setColumnsArray($col_arr);
		//$pdf->Image('./images/logo_boshi_navyblue.png', 150, 250, 15, '', '', '', '', false, 600, '', false, false, 0);
        $pdf->selectColumn(2);
		
        $pdf->multiCell(25, 5, 'To:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, '', 0, 'L', 0 , 1);
		$pdf->multiCell(55, 5, $invoice['deliver_to'], 0, 'L', 0 , 0);
		//$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, '', 0, 'L', 0 , 1);
		$pdf->multiCell(55, 5, $invoice['address'], 0, 'L', 0 , 1);
		//$pdf->multiCell(50, 5, '', 0, 'L', 0 , 1);
		//$pdf->multiCell(25, 1, 'ATTN:', 0, 'L', 0 , 0);
		//$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(70, 5, 'ATTN: '.$invoice['attention'], 0, 'L', 0 , 0);
		//$pdf->multiCell(50, 5, 'variable here' . '<br/>' . $invoice['address'], 0, 'L', 0, 1, '', '', true, 0, true, true);
		$col_y1 = $pdf->getY();

		$pdf->selectColumn(1); 

		$pdf->multiCell(35, 5, 'INVOICE NO.', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->multiCell(30, 5, $invoice['invoice_no'], 0, 'L', 0 , 1);
		$pdf->multiCell(35, 5, 'INVOICE DATE', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->multiCell(30, 5, $invoice['invoice_date'], 0, 'L', 0 , 1);
		$pdf->multiCell(35, 5, 'TRANSACTION NO', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->multiCell(30, 5, $invoice['case_no'], 0, 'L', 0 , 1);
		$pdf->multiCell(35, 5, 'ENTRY NO.', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->multiCell(30, 5, $invoice['entry_no'], 0, 'L', 0 , 1);
		$pdf->multiCell(35, 5, 'CUSTOMER NO.', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->multiCell(30, 5, $invoice['customer_no'], 0, 'L', 0 , 1);
		$pdf->multiCell(35, 5, 'OUR ASSOCIATE', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->multiCell(30, 5, $invoice['associate'], 0, 'L', 0 , 1);
		
		//$pdf->multiCell(50, 5, $invoice['entry_no'] . '<br/>' . $invoice['address'], 0, 'L', 0, 1, '', '', true, 0, true, true);
		$col_y1 = $pdf->getY();

		$pdf->selectColumn(1); 
		
		
		$tbl = '<style> ' . 
			   ' table { ' .
			   '	border-top-width:0.05px solid;' .
			   '	border-left-width:0.05px solid;' .
			   '	border-right-width:0.05px solid;' .
			   ' } ' .
			   ' td {' .
			   '	border-right-width:0.05px solid;' .
			   '	border-bottom-width:0.05px solid;' .
			   '    text-align:center;' .
			   ' }'. 
			   '</style>' .

				'<table cellspacing="0" cellpadding="4" width="100%">' .
				   '<tr>' .
				   		'<td width="33.33%">Invoice No.</td>'.
				   		'<td width="33.33%">'.$invoice['invoice_no'].'</td>'.
				   '</tr>' .
				   '<tr>' .
				   		'<td width="33.33%">Invoice Date</td>'.
				   		'<td width="33.33%">'.$invoice['invoice_date'].'</td>'.
			
				   '</tr>' .
				    '<tr>' .
				   		'<td width="33.33%">Invoice Date</td>'.
				   		'<td width="33.33%">'.$invoice['invoice_date'].'</td>'.
			
				   '</tr>' . '<tr>' .
				   		'<td width="33.33%">Invoice Date</td>'.
				   		'<td width="33.33%">'.$invoice['invoice_date'].'</td>'.
			
				   '</tr>' . '<tr>' .
				   		'<td width="33.33%">Invoice Date</td>'.
				   		'<td width="33.33%">'.$invoice['invoice_date'].'</td>'.
			
				   '</tr>' . '<tr>' .
				   		'<td width="33.33%">Invoice Date</td>'.
				   		'<td width="33.33%">'.$invoice['invoice_date'].'</td>'.
			
				   '</tr>' .
			   '</table>';

		$pdf->SetFont('helvetica', '', 9);
		//$pdf->writeHTML($tbl, false, false, false, false, '');

		$pdf->ln(2);

		$tbl = '<style> ' . 
			   ' table { ' .
			   '	border-top-width:0.05px solid;' .
			   '	border-left-width:0.05px solid;' .
			   '	border-right-width:0.05px solid;' .
			   ' } ' .
			   ' td {' .
			   '	border-right-width:0.05px solid;' .
			   '	border-bottom-width:0.05px solid;' .
			   '    text-align:center;' .
			   ' }'. 
			   '</style>' .

				'<table cellspacing="0" cellpadding="4" width="100%">' .
				   '<tr>' .
				   		'<td width="33.33%">Delivery Date</td>'.
				   		'<td width="33.33%">Job No</td>'.
				   		'<td width="33.33%">P.O.No</td>'.
				   '</tr>' .
				   '<tr>' .
				   		'<td width="33.33%">variable here</td>'.
				   		'<td width="33.33%">variable here</td>'.
				   		'<td width="33.33%">variable here</td>'.
				   '</tr>' .
			   '</table>';

		$pdf->SetFont('helvetica', '', 9);
		//$pdf->writeHTML($tbl, false, false, false, false, '');

		$pdf->ln(2);

		// $tbl = '<style> ' . 
		// 	   ' table { ' .
		// 	   '	border-top-width:0.05px solid;' .
		// 	   '	border-left-width:0.05px solid;' .
		// 	   '	border-right-width:0.05px solid;' .
		// 	   ' } ' .
		// 	   ' td {' .
		// 	   '	border-right-width:0.05px solid;' .
		// 	   '	border-bottom-width:0.05px solid;' .
		// 	   '    text-align:center;' .
		// 	   ' }'. 
		// 	   '</style>' .

		// 		'<table cellspacing="0" cellpadding="4" width="100%">' .
		// 		   '<tr>' .
		// 		   		'<td width="33.33%">Invoice Date</td>'.
		// 		   		'<td width="33.33%">Invoice No</td>'.
		// 		   		'<td width="33.33%">Tel No</td>'.
		// 		   '</tr>' .
		// 		   '<tr>' .
		// 		   		'<td width="33.33%">'.$invoice_date.'</td>'.
		// 		   		'<td width="33.33%">'.$invoice['invoice_no'].'</td>'.
		// 		   		'<td width="33.33%">'.$invoice['tel_no'].'</td>'.
		// 		   '</tr>' .
		// 	   '</table>';

		// $pdf->SetFont('helvetica', '', 7);
		// $pdf->writeHTML($tbl, false, false, false, false, '');
		// $pdf->ln(2);

		$col_y2 = $pdf->getY();
		
		$pdf->resetColumns();
		$y_pos = ($col_y1 > $col_y2) ? $col_y1 : $col_y2; 
		$pdf->SetY($y_pos);
	} 
	
	public function pv_sendEmail($invoice_id) {
		$invoice_info = $this->invoice_model->getInvoiceinfo($invoice_id);

		$message = 	"Dear Sir/Madam, <br/><br/>There is a new Invoice with the following information. <br/><br/>" .
					 "<table cellspacing='0' cellpadding='5' border='1'>" .
					 "<tr>" .
					 	"<td>Invoice No</td><td> : </td><td>" . $invoice_info['invoice_no'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Date</td><td> : </td><td>" . date('d-m-Y', $invoice_info['invoice_date']) . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Delivery Date</td><td> : </td><td>" . $invoice_info['delivery_date'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Company</td><td> : </td><td>" . $invoice_info['job_title'] . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Contact</td><td> : </td><td>" . $invoice_info['contact'] . "</td>" . 
					 "</tr>".
					 "</table> <br/><br/>  For More information, Please login to the system. <br/><br/><br/> Thanks";

		$emails = explode(', ', $this->setting_model->getEmails());  
		print_r($emails);
		echo count($emails);
		echo $message;
		//Send Email 
		foreach($emails as $email) {
			//send_email('MBDPL CRM', 'dyan@mbdesign.com.sg', $email, 'New Quotation', $message);
			//echo send_email('MBDPL CRM', 'josemiguelgonzales93@gmail.com', $email, 'New Quotation', $message);
		}
	}
	
	
	
	
	
	

}