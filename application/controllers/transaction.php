<?php
class transaction extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->model('property_model');
		$this->load->model('transaction_model');
/* 		$this->load->model('service_model');
		$this->load->model('production_model');
		$this->load->model('uom_model');
		$this->load->model('client_model');
		$this->load->model('supplier_model');
		$this->load->model('setting_model');
		$this->load->model('jobtitle_model'); */
		$this->load->model('user_model');
		checkPermission();
	}

	public function index($dt = FALSE)	{	
		if($dt === FALSE ) { 									// First time load
			$data['msg'] = $this->session->flashdata('msg');
			$data['nav_area'] = 'Transaction';

			$this->load->view('template/header', $data);
			$this->load->view('transaction/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$role_id = $this->session->userdata('role_id');
			if($role_id != 1) {
				
				$this->transaction_model->getdttransactions();
			
			}
			else{
				$this->transaction_model->getdttransactions_admin();
			}
			//$data['total_invoice'] = $this->transaction_model->getinvoices();	
			//$data['company'] = $this->supplier_model->get_suppliers();
			//$data['clients'] = $this->client_model->get_clients();
		}
	}

	public function view($id) {
		$data['transaction'] = $this->transaction_model->get_transactinfo($id); 
		if (empty($data['transaction'])) {
			show_404();
		}
		$data['details'] =  $this->transaction_model->getFilesbytransactionid($id);
		$this->load->view('transaction/view', $data);
	}

	public function create($submit = FALSE) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('property_id', 'Property Title', 'required');
		/* $this->form_validation->set_rules('date', 'Date', 'required');
		$this->form_validation->set_rules('client_id', 'Client', 'required');
		$this->form_validation->set_rules('sale_person_id', 'REP', 'required'); */
		
		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'new';
				$data['nav_area'] = 'transaction';
				$data['users'] = $this->user_model->getUsers();
				$data['properties'] = $this->transaction_model->get_approved_properties();
				$data['case_no'] = $this->transaction_model->getcaseno();

				$this->load->view('template/header', $data);
				$this->load->view('transaction/edit', $data);
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
			$transaction_id = $this->transaction_model->add_transaction();
			if($transaction_id) {
				//logActivity('Created', "Created New transaction  : " . $this->input->post('property_transaction_no'), $property_transaction_id);
				$this->session->set_flashdata('msg', 'Transaction Successfully Created');
				$ret = array(
					'status' => 'success',
				);	
				//$this->pv_sendEmail($property_transaction_id);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Transaction Add.'
				);
			}
			echo json_encode($ret);	
		}
	}

	public function edit($id, $submit = FALSE) {
		$data['transaction'] = $this->transaction_model->get_transactinfo($id); 
		if (empty($data['transaction'])) {
			show_404();
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

	 	$this->form_validation->set_rules('property_id', 'Property Title', 'required');


		if($this->form_validation->run() === FALSE) {
			$data['action'] = 'edit';
			$data['nav_area'] = 'transaction';
			if($submit == FALSE) { 
			 	$data['users'] = $this->user_model->getUsers();
				//$data['transaction'] = $this->transaction_model->get_transactions($id);
				$data['properties'] = $this->transaction_model->get_approved_properties();
				$data['case_no'] = $this->transaction_model->getcaseno();
				$data['details'] =  $this->transaction_model->getFilesbytransactionid($id);


				$this->load->view('template/header', $data);
				$this->load->view('transaction/edit', $data);
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
			$updated = $this->transaction_model->update_transaction($id);
			if($updated) {
				logActivity('Updated', "Updated New Transaction : " . $this->input->post('property_transaction_no'), $id);
				$this->session->set_flashdata('msg', 'Transaction Successfully Updated');
				$ret = array(
					'status' => 'success',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Transaction Update'
				);
			}	
			echo json_encode($ret);
		}
	}

	
	public function edit_status($id,$stat) {
		if($stat == 'approve'){
			$status = 'Sold/Approved';
			$msg = 'Transaction Successfully Approved';
		}
		else{
			$status = 'Rejected';
			$msg = 'This Transaction has been Rejected';
		}
		$property_id = $this->transaction_model->get_propertyid($id,$stat);
		//$id = $property_id['property_id'];
		if ( !empty($property_id) ) {
				
			
			//$deleted = $this->transaction_model->update_status($property_id,$status);  
		    if($property_id) {
		    	//logActivity('Deleted', "Deleted Transaction : " . $transaction['property_transaction_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> $msg,
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in Transaction Delete',
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
	
	
	
	
	
	public function delete($id) {
		$transaction = $this->transaction_model->get_transactions($id);
		if ( !empty($transaction) ) {
			$deleted = $this->transaction_model->delete_transaction($id);  
		    if($deleted) {
		    	//logActivity('Deleted', "Deleted Transaction : " . $transaction['property_transaction_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> 'Transaction Successfully Deleted',
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in Transaction Delete',
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

	
	
	
	
	function upload()
	{
		//$output = '';
		if(is_array($_FILES))  
		{  
		  foreach($_FILES['images']['name'] as $name => $value)  
		  {  
			   $file_name = explode(".", $_FILES['images']['name'][$name]);  
			   $allowed_extension = array("pdf","doc","docx","xls","xlsx","csv");  
			   if(in_array($file_name[1], $allowed_extension))  
			   {  
					//$new_name = rand() . '.'. $file_name[1];  
					$new_name = $file_name[0] . '_'.date('Ymdhis').'.'. $file_name[1];  
					$sourcePath = $_FILES["images"]["tmp_name"][$name];  
					$targetPath = "uploads/transaction/".$new_name;  
					move_uploaded_file($sourcePath, $targetPath); 
					
			   }
		  }  
		  $output = '<input type ="text" name ="file_path" id="file-path" value ="' . $targetPath .'" >';
		  $output .= '<input type ="text" name ="new_file_name" id="new-file-name" value ="' . $new_name .'" >';
		  echo $output;
		}  
	}
	
	
	function upload_main_img()
		{
			//$output = '';
			if(is_array($_FILES))  
			 {  
				  foreach($_FILES['main_image']['name'] as $name => $value)  
				  {  
					   $file_name = explode(".", $_FILES['main_image']['name'][$name]);  
					   $allowed_extension = array("jpg", "jpeg", "png", "gif","JPG");
						//$allowed_extension = array("jpg", "jpeg", "png", "gif","pdf","doc","docx","xls","xlsx","csv"); 
						if(in_array($file_name[1], $allowed_extension))  
						{  
							//$new_name = rand() . '.'. $file_name[1];  
							$new_name = $file_name[0] . '_'.date('Ymdhis').'.'. $file_name[1];  
							$sourcePath = $_FILES["main_image"]["tmp_name"][$name];  
							$targetPath = "uploads/transaction/img/".$new_name;  
							move_uploaded_file($sourcePath, $targetPath); 
					
							
							$output = '<input type ="hidden" name ="main_new_file_name" id="main-new-file-name" value ="' . $targetPath .'" >';
							//$output .= '<script type ="text/javascript">console.log($("main-new-file-name").val())</script>';
							echo $output;
						}

						
				  }  
			
			 }  
		}
	
	public function aj_addTransactfile() {
		$detail_id = $this->transaction_model->addFile();
		if($detail_id) {
			$transact_id = $this->input->post('hid_transact_id');
			$details = $this->transaction_model->getFilesbytransactionid($transact_id);
			//logActivity('Added', "Added Invoice Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'transaction_files'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}
		
		
		
		
		public function aj_deleteAnnouncefile($id) {
		$transact_file = $this->transaction_model->getFile($id);
		if(!empty($transact_file)) {
			$removed = $this->transaction_model->removeFile($id);
			if($removed) {
				//$this->announcement_model->getFilesbyannouncementid($id);
				$details = $this->transaction_model->getFilesbytransactionid($transact_file['transaction_id']);
				//logActivity('Removed', "Removed Invoice Detail : " . $invoice_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'announcement_files'	=> $details
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
		
		
	
	
	
	
	
	
	
	
	
	//Site Ajax //
	public function aj_getproperty_transactiondetail($id) {
		$detail = $this->transaction_model->getDetail($id);
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


	public function aj_addproperty_transactiondetail() {
		$detail_id = $this->transaction_model->addDetail();
		if($detail_id) {
			$property_transaction_id = $this->input->post('hid_property_transaction_id');
			$details = $this->transaction_model->getDetilsbyproperty_transactionid($property_transaction_id);
			logActivity('Added', "Added Transaction Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'property_transaction_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}

	public function aj_updateproperty_transactiondetail($id) {
		$updated = $this->transaction_model->updateDetail($id);
		if($updated) {
			$property_transaction_id = $this->input->post('hid_property_transaction_id');
			$details = $this->transaction_model->getDetilsbyproperty_transactionid($property_transaction_id);
			logActivity('Updated', "Updated Transaction Detail : " . $this->input->post('description'), $id);
			$ret = array(
				'status'			=> 'success',
				'property_transaction_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'Error in Transaction Detail Update'
			);
		}
		echo json_encode($ret);
	}

	public function aj_deleteproperty_transactiondetail($id) {
		$property_transaction_detail = $this->transaction_model->getDetail($id);
		if(!empty($property_transaction_detail)) {
			$removed = $this->transaction_model->removeDetail($id);
			if($removed) {
				$details = $this->transaction_model->getDetilsbyproperty_transactionid($property_transaction_detail['property_transaction_id']);
				logActivity('Removed', "Removed Transaction Detail : " . $property_transaction_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'property_transaction_detail'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in Transaction Detail remove'
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

	
	public function aj_getClientdetail($id) {
		$detail = $this->transaction_model->getClientDetail($id);
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
		$client['data'] = $this->transaction_model->getClientDetail($client_id);
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
	
	
	public function aj_getPropertyinfo($property_id) {
		//$property['data'] = $this->transaction_model->getClientDetail($client_id);
		//$client['count'] = count($client['data']);
		$property = $this->transaction_model->getPropertyinfo($property_id);
		if(!empty($property)) {
			$property['status'] = 'success';
			
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($property);
	}
	
	
	
	public function aj_hasInvoice($property_transaction_id) {
		$invoice['data'] = $this->transaction_model->getInvoice($property_transaction_id);
		$invoice['count'] = count($invoice['data']);
		
		if(!empty($invoice)) {
			$invoice['status'] = 'success';
			
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($invoice);
	}
	
	
	
	

	public function aj_getClientinfo($client_id) {
		$client = $this->transaction_model->getClientinfo($client_id);
		
		if(!empty($client)) {
			$client['status'] = 'success';
			//$client = $this->transaction_model->getClientDetail($client_id);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($client);
	}
	
	
	
	
	
	public function aj_getproperty_transactions() {
		$q = $this->input->get('q'); 
		$result = $this->transaction_model->getQoNo($q['term']);
		$ret = array(); 
	 	if ( !empty($result) ) {
	 		foreach($result as $res) {
	 			$ret[] = array(
	 				"id" 	=> $res['property_transaction_id'], 
	 				"text"	=> $res['property_transaction_no'],
	 			);
	 		}
	 	}	
	 	else {
	 		$ret[] = array("id"=>"0", "text"=>"No Results Found..");
	 	}
	 	echo json_encode($ret);	
	}

	public function aj_getRepinfo($user_id) {
		$user = $this->user_model->get_users($user_id);
		if(!empty($user)) {
			$ret = array(
				'status'				=> 'success',
				'default_commission'	=> $user['default_commission'],
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($ret);
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


	public function printproperty_transaction($id) {
		$transaction = $this->transaction_model->getproperty_transactioninfo($id);
		if (empty($transaction)) {
			show_404();
		}	
		$details =  $this->transaction_model->getDetilsbyproperty_transactionid($id);
		$note = $this->setting_model->getproperty_transactionnote();

		$this->load->library('pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$quo_title = ( $transaction['revised'] == 0 ) ? 'Transaction' : '[REVISED] Transaction';
 		$pdf->setTitle($quo_title);
		$pdf->setNote($note);
		$pdf->setproperty_transaction($transaction);
		$pdf->SetLineStyle(array('width' => 0, 'cap' => 'butt', 'join' => 0, 'color' => array(0, 0, 0)));
		// $pdf->setJobtitle($transaction['job_title'], $transaction['terms']);
		
		// add a page
		$this->pv_property_transactionHeader($pdf, $transaction);
		
        $header = array(
			'S/N.'			=> 20,
			'Description'	=> 95,
			'Quantity'		=> 20,
			'UOM'			=> 18,
			'Amount'		=> 27,
		); 
		//Content
		$detail_arr = array(); $i = 1;
		foreach($details as $dt) {
			$description_arr = explode(' ', $dt['description']);
			$description = implode('&nbsp;', $description_arr);
			$detail_arr[] = array(
				0	=> array('txt' => $dt['no'], 'align' => 'C'), 
				1	=> array('txt' => nl2br($description) , 'align' => 'L'), 
				2	=> array('txt' => $dt['qty'] , 'align' => 'R'), 
				3	=> array('txt' => $dt['unit'] , 'align' => 'L'),
				4	=> array('txt' => number_format($dt['amount'], 2, '.', ',') , 'align' => 'R'),
			);		
		}

		$footer = array(
			'sub_total'		=> '$ ' . number_format($transaction['sub_total'], 2, '.', ','), 
			'gst_percent'   => $transaction['gst'],
			'gst'			=> '$ ' . number_format($transaction['total'] - $transaction['sub_total'], 2, ".",""), 
			'grand_total'	=> '$ ' . number_format($transaction['total'], 2, '.', ','), 
		);

		$this->pdf->generateTable($pdf, $header, $detail_arr, $footer, 140, true);
        $pdf->ln(1);
		$pdf->Output('property_transaction_Order.pdf', 'I');
	}

	public function pv_property_transactionHeader(&$pdf, &$transaction) {

		//$transaction = $pdf->Transaction;
		$delivery_date = ($transaction['delivery_date'] != 0) ? date('d/m/Y', $transaction['delivery_date']) : '';
		$invoice_date = ($transaction['invoice_date'] != 0) ? date('d/m/Y', $transaction['invoice_date']) : '';
		$revised = ($transaction['revised'] == 1) ? "[ Revised ]" : '';

		$pdf->AddPage();
		$pdf->SetFont('helvetica', '', 9);
		$pdf->resetColumns();
        // $pdf->setEqualColumns(2, 90);  

        $col_arr = array(
        	0 => array( 'w' => 100, 's' => 5, 'y' => '30' ),
        	1 => array( 'w' => 75, 's' => 5, 'y' => '25' ),
        );
        $pdf->setColumnsArray($col_arr);

        $pdf->selectColumn(2);
		//$pdf->setCellPaddings(2, 2, 10, 2);
        $pdf->multiCell(25, 5, 'Client Name:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, $transaction['contact'], 0, 'L', 0 , 1);
		$pdf->multiCell(25, 5, 'Designation:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, $transaction['designation'], 0, 'L', 0 , 1);
		$pdf->multiCell(25, 5, 'Department:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, $transaction['department'], 0, 'L', 0 , 1);
		$pdf->multiCell(25, 15, 'Address:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		//$pdf->setCellPaddings(2, 2, 10, 2);
		$pdf->multiCell(60, 25, $transaction['client'] . '<br/>' . $transaction['address'], 0, 'L', 0, 1, '', '', true, 0, true, true);
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
				   		'<td width="33.33%">Date</td>'.
				   		'<td width="33.33%">Job No</td>'.
				   		'<td width="33.33%">Rep</td>'.
				   '</tr>' .
				   '<tr>' .
				   		'<td width="33.33%">'.date('d/m/Y', $transaction['date']).'</td>'.
				   		'<td width="33.33%"><b>'.$transaction['property_transaction_no'].'</b></td>'.
				   		'<td width="33.33%">'.$transaction['rep'].'</td>'.
				   '</tr>' .
			   '</table>';

		$pdf->SetFont('helvetica', '', 9);
		$pdf->writeHTML($tbl, false, false, false, false, '');

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
				   		'<td width="33.33%">P.O.No</td>'.
				   		'<td width="33.33%">ST-OIC</td>'.
				   '</tr>' .
				   '<tr>' .
				   		'<td width="33.33%">'.$delivery_date.'</td>'.
				   		'<td width="33.33%">'.$transaction['po_no'].'</td>'.
				   		'<td width="33.33%">'.$transaction['st_oic'].'</td>'.
				   '</tr>' .
			   '</table>';

		$pdf->SetFont('helvetica', '', 9);
		$pdf->writeHTML($tbl, false, false, false, false, '');

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
				   		'<td width="33.33%">Invoice Date</td>'.
				   		'<td width="33.33%">Invoice No</td>'.
				   		'<td width="33.33%">Tel No</td>'.
				   '</tr>' .
				   '<tr>' .
				   		'<td width="33.33%">'.$invoice_date.'</td>'.
				   		'<td width="33.33%">'.$transaction['invoice_no'].'</td>'.
				   		'<td width="33.33%">'.$transaction['tel_no'].'</td>'.
				   '</tr>' .
			   '</table>';

		$pdf->SetFont('helvetica', '', 9);
		$pdf->writeHTML($tbl, false, false, false, false, '');
		$pdf->ln(2);

		$col_y2 = $pdf->getY();
		
		$pdf->resetColumns();
		$y_pos = ($col_y1 > $col_y2) ? $col_y1 : $col_y2; 
		$pdf->SetY($y_pos);
	} 


	public function pv_sendEmail($property_transaction_id) {
		$property_transaction_info = $this->transaction_model->getproperty_transactioninfo($property_transaction_id);

		$message = 	"Dear Sir/Madam, <br/><br/>There is a new Transaction with the following information. <br/><br/>" .
					 "<table cellspacing='0' cellpadding='5' border='1'>" .
					 "<tr>" .
					 	"<td>Transaction No</td><td> : </td><td>" . $property_transaction_info['property_transaction_no'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Date</td><td> : </td><td>" . date('d-m-Y', $property_transaction_info['date']) . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Client</td><td> : </td><td>" . $property_transaction_info['client'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Rep</td><td> : </td><td>" . $property_transaction_info['rep'] . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Sub Total</td><td> : </td><td>" . $property_transaction_info['sub_total'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>GST</td><td> : </td><td>" . $property_transaction_info['gst'] . " %</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Grand Total</td><td> : </td><td>" . $property_transaction_info['total'] . "</td>" . 
					 "</tr>".
					 "</table> <br/><br/>  For More information, Please login to the system. <br/><br/><br/> Thanks";

		$emails = explode(', ', $this->setting_model->getEmails());  
		print_r($emails);
		echo count($emails);
		echo $message;
		//Send Email 
		foreach($emails as $email) {
			send_email('MBDPL CRM', 'dyan@mbdesign.com.sg', $email, 'New Transaction', $message);
			//echo send_email('MBDPL CRM', 'josemiguelgonzales93@gmail.com', $email, 'New Transaction', $message);
		}
	}


}