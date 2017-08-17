<?php
class receipt extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->model('receipt_model');
		$this->load->model('transaction_model');
		$this->load->model('invoice_model');
		/*$this->load->model('uom_model');
		$this->load->model('client_model');
		$this->load->model('quotation_model');
		$this->load->model('setting_model'); */
		$this->load->model('user_model');
		checkPermission();
	}

	public function index($dt = FALSE)	{	
		if($dt === FALSE ) { 									// First time load
			$data['msg'] = $this->session->flashdata('msg');
			$data['nav_area'] = 'receipt';

			$this->load->view('template/header', $data);
			$this->load->view('receipt/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->receipt_model->getdtreceipts();	
		}
	}

	public function view($id) {
		$data['receipt'] = $this->receipt_model->getreceiptinfo($id); 
		if (empty($data['receipt'])) {
			show_404();
		}
		//$data['details'] 		=  $this->receipt_model->getDetilsbyreceiptid($id);
		//$data['payment_details'] = $this->receipt_model->getPaymentDetailsbyreceiptid($id);
		$this->load->view('receipt/view', $data);
	}

	public function create($quotation_id = FALSE) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('receipt_no', 'receipt No', 'required');
		$this->form_validation->set_rules('receipt_date', 'Date', 'required');
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
				$this->load->view('receipt/edit', $data);
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
			$receipt_id = $this->receipt_model->add_receipt();
			if($receipt_id) {
				logActivity('Created', "Created New receipt  : " . $this->input->post('receipt_no'), $receipt_id);
				$this->session->set_flashdata('msg', 'receipt Successfully Created');
				$ret = array(
					'status' => 'success',
				);	
				//$this->pv_sendEmail($receipt_id);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in receipt Add'
				);
			}
			echo json_encode($ret);	
		}
	}


	public function edit($id) {
		$data['receipt'] = $this->receipt_model->getreceiptinfo($id); 
		if (empty($data['receipt'])) {
			show_404();
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('receipt_no', 'receipt No', 'required');
		$this->form_validation->set_rules('receipt_date', 'Date', 'required');
		//$this->form_validation->set_rules('quotation_id', 'Quotation', 'required');

		if($this->form_validation->run() === FALSE) {
			$data['action'] = 'edit';
			$data['nav_area'] = 'project';
			$submitted = $this->input->post('hid_submitted');
			if(!$submitted) {
				$data['users'] = $this->user_model->get_users();
				//$data['uoms'] = $this->uom_model->get_uoms();
				$data['transactions'] = $this->transaction_model->get_approved_transactions();
				//$data['details'] = $this->receipt_model->getDetilsbyreceiptid($id);
				//$data['payment_details'] = $this->receipt_model->getPaymentDetailsbyreceiptid($id);

				$this->load->view('template/header', $data);
				$this->load->view('receipt/edit', $data);
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
			$updated = $this->receipt_model->update_receipt($id);
			if($updated) {
				logActivity('Updated', "Updated New receipt : " . $this->input->post('receipt_no'), $id);
				$this->session->set_flashdata('msg', 'receipt Successfully Updated');
				$ret = array(
					'status' => 'success',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in receipt Update'
				);
			}	
			echo json_encode($ret);
		}
	}

	public function delete($id) {
		$receipt = $this->receipt_model->get_receipts($id);
		if ( !empty($receipt) ) {
			$deleted = $this->receipt_model->delete_receipt($id);  
		    if($deleted) {
		    	logActivity('Deleted', "Deleted receipt : " . $receipt['receipt_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> 'receipt Successfully Deleted',
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in receipt Delete',
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
	
	
	
	function upload_cheque()
		{
			//$output = '';
			if(is_array($_FILES))  
			{  
				foreach($_FILES['cheque']['name'] as $name => $value)  
				{  
					$file_name = explode(".", $_FILES['cheque']['name'][$name]);  
					//$allowed_extension = array("jpg", "jpeg", "png", "gif","JPG");
					$allowed_extension = array("jpg", "jpeg", "png", "gif","pdf","doc","docx","xls","xlsx","csv"); 
					if(in_array($file_name[1], $allowed_extension))  
					{  
						//$new_name = rand() . '.'. $file_name[1];  
						$new_name = $file_name[0] . '_'.date('Ymdhis').'.'. $file_name[1];  
						$sourcePath = $_FILES["cheque"]["tmp_name"][$name];  
						$targetPath = "uploads/receipt/cheques/".$new_name;  
						move_uploaded_file($sourcePath, $targetPath); 
						
					
						
						$output = '<input type ="hidden" name ="cheque_file" id="chk-new-file-name" value ="' . $targetPath .'" >';
						//$output .= '<script type ="text/javascript">console.log($("main-new-file-name").val())</script>';
						echo $output;
					}

						
				}  
			
			}  
		}
		
	
	
	
	
	
	

	//Site Ajax //
	public function aj_getreceiptdetail($id) {
		$detail = $this->receipt_model->getDetail($id);
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
	
	public function aj_getreceiptPaymentdetail($id) {
		$detail = $this->receipt_model->getPaymentDetail($id);
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

	public function aj_addreceiptdetail() {
		$detail_id = $this->receipt_model->addDetail();
		if($detail_id) {
			$receipt_id = $this->input->post('hid_receipt_id');
			$details = $this->receipt_model->getDetilsbyreceiptid($receipt_id);
			//logActivity('Added', "Added receipt Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'receipt_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}

	public function aj_addreceiptPaymentdetail() {
		$payment_detail_id = $this->receipt_model->addPaymentDetail();
		if($payment_detail_id) {
			$receipt_id = $this->input->post('hid_receipt_id');
			$payment_details = $this->receipt_model->getPaymentDetailsbyreceiptid($receipt_id);
			//logActivity('Added', "Added receipt Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'receipt_payment_detail'	=> $payment_details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}
	


	
	public function aj_updatereceiptdetail($id) {
		$updated = $this->receipt_model->updateDetail($id);
		if($updated) {
			$receipt_id = $this->input->post('hid_receipt_id');
			$details = $this->receipt_model->getDetilsbyreceiptid($receipt_id);
			logActivity('Updated', "Updated receipt Detail : " . $this->input->post('description'), $id);
			$ret = array(
				'status'			=> 'success',
				'receipt_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'Error in receipt Detail Update'
			);
		}
		echo json_encode($ret);
	}

	public function aj_updatereceiptPaymentdetail($id) {
		$updated = $this->receipt_model->updatePaymentDetail($id);
		if($updated) {
			$receipt_id = $this->input->post('hid_receipt_id');
			$details = $this->receipt_model->getPaymentDetailsbyreceiptid($receipt_id);
			//logActivity('Updated', "Updated receipt Detail : " . $this->input->post('description'), $id);
			$ret = array(
				'status'			=> 'success',
				'receipt_payment_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'Error in receipt Detail Update'
			);
		}
		echo json_encode($ret);
	}
	
	
	
	
	
	public function aj_deletereceiptdetail($id) {
		$receipt_detail = $this->receipt_model->getDetail($id);
		if(!empty($receipt_detail)) {
			$removed = $this->receipt_model->removeDetail($id);
			if($removed) {
				$details = $this->receipt_model->getDetilsbyreceiptid($receipt_detail['receipt_id']);
				logActivity('Removed', "Removed receipt Detail : " . $receipt_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'receipt_detail'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in receipt Detail remove'
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

	
	public function aj_deletereceiptPaymentdetail($id) {
		$receipt_detail = $this->receipt_model->getPaymentDetail($id);
		if(!empty($receipt_detail)) {
			$removed = $this->receipt_model->removePaymentDetail($id);
			if($removed) {
				$details = $this->receipt_model->getPaymentDetailsbyreceiptid($receipt_detail['receipt_id']);
				//logActivity('Removed', "Removed receipt Detail : " . $receipt_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'receipt_payment_detail'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in receipt Detail remove'
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

	public function printReceipt($id) {
		$receipt = $this->receipt_model->getreceiptinfo($id);
		if (empty($receipt)) {
			show_404();
		}	
		//$details =  $this->receipt_model->getDetilsbyreceiptid($id);
		//$note = $this->setting_model->getreceiptnote();

		$this->load->library('pdf');
		//$this->load->library('tcpdf');
		//$pageLayout = array($width, $height);
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->setTitle('Tax receipt');
		//$pdf->setNote($note);
		$pdf->setReceipt($receipt);

		//$delivery_date = ($receipt['delivery_date'] != 0) ? date('d/m/Y', $receipt['delivery_date']) : '';
		//$receipt_date = ($receipt['receipt_date'] != 0) ? date('d/m/Y', $receipt['receipt_date']) : '';
		
		$this->pv_receiptHeader($pdf, $receipt);
		
        $header = array(
			'Description'	=> 140,
			'Amount'		=> 40,
		); 
		//Content
		$detail_arr = array(); $i = 1;
	

		$footer = array(
			'sub_total'		=> '$ '.$receipt['sum'] , 2, '.', ',', 
			'gst_percent'   => '$ '.$receipt['add_gst'],
			//'gst_p'			=> '$ '.$receipt['gst'], 2, ".","", 
			'grand_total'	=> '$ '.$receipt['add_gst'] , 2, '.', ',', 
		);

		//$this->pdf->generateTable($pdf, $header, $detail_arr, $footer, 115, true);
        $pdf->ln(1);
		$pdf->Output('receipt.pdf', 'I');
	}


	public function pv_receiptHeader(&$pdf, &$receipt) {

		$receipt = $pdf->receipt;
	

		$pdf->AddPage();
		$pdf->SetFont('helvetica', '', 9);
		$pdf->resetColumns();
        // $pdf->setEqualColumns(2, 90);  

        $col_arr = array(
        	0 => array( 'w' => 100, 's' => 5, 'y' => '30' ),
        	1 => array( 'w' => 115, 's' => 5, 'y' => '40' ),
        );
        $pdf->setColumnsArray($col_arr);
		$pdf->ln(16);
		//$pdf->Image('./images/logo_boshi_navyblue.png', 150, 250, 15, '', '', '', '', false, 600, '', false, false, 0);
        $pdf->selectColumn(2);
		$pdf->SetFont('helvetica', 'B', 9);
        $pdf->multiCell(35, 5, 'TRANSACTION NO:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, '', 0, 'L', 0 , 1);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(55, 5, $receipt['case_no'], 0, 'L', 0 , 0);
		//$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, '', 0, 'L', 0 , 1);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->multiCell(55, 5, 'RECEIVED WITH THANKS', 0, 'L', 0 , 1);
		//$pdf->multiCell(50, 5, '', 0, 'L', 0 , 1);
		//$pdf->multiCell(25, 1, 'ATTN:', 0, 'L', 0 , 0);
		//$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0)
		$pdf->ln(10);
		$pdf->multiCell(55, 5, 'POISE REAL ESTATE PTE. LTD.', 0, 'L', 0 , 1);
		$pdf->multiCell(70, 5, 'ATTN: '.$receipt['attention'], 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, '', 0, 'L', 0 , 1);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(50, 5, $receipt['address'], 0, 'L', 0, 1, '', '', true, 0, true, true);
		
		$pdf->ln(10);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->multiCell(55, 5, 'IN PAYMENT COMMISSION FOR', 0, 'L', 0 , 1);
		//$pdf->multiCell(50, 5, '', 0, 'L', 0 , 1);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(50, 5, $receipt['payment_commission_for'], 0, 'L', 0, 1, '', '', true, 0, true, true);
		
		
		$pdf->ln(10);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->multiCell(55, 5, 'SALESPERSON', 0, 'L', 0 , 1);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(70, 5, 'test', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, '', 0, 'L', 0 , 1);
		//$pdf->multiCell(50, 5, $receipt['address'], 0, 'L', 0, 1, '', '', true, 0, true, true);
		$total_rec = $receipt['sum'] + $receipt['add_gst'];
		$pdf->ln(10);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->multiCell(35, 5, 'A SUM OF', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'R', 0 , 0);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(30, 5, $receipt['sum'], 0, 'R', 0 , 1);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->multiCell(35, 5, 'PLUS GST (7.00%)', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(30, 5, $receipt['add_gst'], 0, 'R', 0 , 1);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->multiCell(35, 5, 'TOTAL', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(30, 5, $total_rec, 0, 'R', 0 , 1);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->multiCell(35, 5, 'CASH/CHEQUE', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(30, 5, $receipt['cheque'], 0, 'R', 0 , 1);
		
		
		$col_y1 = $pdf->getY();
		//$pdf->ln(999999999999999999);
		$pdf->selectColumn(1); 
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->multiCell(35, 5, 'RECEIPT NO.', 0, 'R', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'R', 0 , 0);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(30, 5, $receipt['receipt_no'], 0, 'R', 0 , 1);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->multiCell(35, 5, 'DATE', 0, 'R', 0 , 0);
		$pdf->multiCell(5, 5, ' : ', 0, 'L', 0 , 0);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->multiCell(30, 5, $receipt['date'], 0, 'R', 0 , 1);
		
		$pdf->ln(110);
		$pdf->multiCell(75, 5, 'POISE REAL ESTATE PTE. LTD.', 0, 'R', 0 , 1);
		$pdf->multiCell(75, 5, '___________________________', 0, 'R', 0 , 1);
		$pdf->multiCell(67, 5, 'AUTHORISED SIGNATURE', 0, 'R', 0 , 1);
		
		//$pdf->multiCell(30, 5, $receipt['customer_no'], 0, 'L', 0 , 1);

		
		//$pdf->multiCell(50, 5, $receipt['entry_no'] . '<br/>' . $receipt['address'], 0, 'L', 0, 1, '', '', true, 0, true, true);
		$col_y1 = $pdf->getY();

		//$pdf->selectColumn(1); 
		
		
		

		$col_y2 = $pdf->getY();
		
		$pdf->resetColumns();
		$y_pos = ($col_y1 > $col_y2) ? $col_y1 : $col_y2; 
		$pdf->SetY($y_pos);
	} 
	
	public function pv_sendEmail($receipt_id) {
		$receipt_info = $this->receipt_model->getreceiptinfo($receipt_id);

		$message = 	"Dear Sir/Madam, <br/><br/>There is a new receipt with the following information. <br/><br/>" .
					 "<table cellspacing='0' cellpadding='5' border='1'>" .
					 "<tr>" .
					 	"<td>receipt No</td><td> : </td><td>" . $receipt_info['receipt_no'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Date</td><td> : </td><td>" . date('d-m-Y', $receipt_info['receipt_date']) . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Delivery Date</td><td> : </td><td>" . $receipt_info['delivery_date'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Company</td><td> : </td><td>" . $receipt_info['job_title'] . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Contact</td><td> : </td><td>" . $receipt_info['contact'] . "</td>" . 
					 "</tr>".
					 "</table> <br/><br/>  For More information, Please login to the system. <br/><br/><br/> Thanks";

		$emails = explode(', ', $this->setting_model->getEmails());  
		//print_r($emails);
		//echo count($emails);
		//echo $message;
		//Send Email 
		foreach($emails as $email) {
			//send_email('MBDPL CRM', 'dyan@mbdesign.com.sg', $email, 'New Quotation', $message);
			//echo send_email('MBDPL CRM', 'josemiguelgonzales93@gmail.com', $email, 'New Quotation', $message);
		}
	}
	
	
	
	
	
	

}