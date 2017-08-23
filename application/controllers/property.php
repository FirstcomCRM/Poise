<?php
class property extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->model('property_model');
/* 		$this->load->model('service_model');
		$this->load->model('production_model');
		$this->load->model('uom_model');
		$this->load->model('client_model');
		$this->load->model('supplier_model');
		$this->load->model('setting_model');
		$this->load->model('jobtitle_model'); */
		$this->load->model('user_model');
		//checkPermission();
	}

	public function index($dt = FALSE)	{	
		if($dt === FALSE ) { 									// First time load
			$data['msg'] = $this->session->flashdata('msg');
			$data['nav_area'] = 'property';

			$this->load->view('template/header', $data);
			$this->load->view('property/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$role_id = $this->session->userdata('role_id');
			if($role_id != 1) {
				
				$this->property_model->getdtproperties();
			
			}
			else{
				$this->property_model->getdtproperties_admin();
			}
			//$data['total_invoice'] = $this->property_model->getinvoices();	
			//$data['company'] = $this->supplier_model->get_suppliers();
			//$data['clients'] = $this->client_model->get_clients();
		}
	}

	public function view($id) {
		$data['property'] = $this->property_model->getpropertyinfo($id); 
		if (empty($data['property'])) {
			show_404();
		}
		//$data['details'] =  $this->property_model->getDetilsbypropertyid($id);
		$this->load->view('property/view', $data);
	}

	public function create($submit = FALSE) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('property_title', 'Property Title', 'required');
		/* $this->form_validation->set_rules('date', 'Date', 'required');
		$this->form_validation->set_rules('client_id', 'Client', 'required');
		$this->form_validation->set_rules('sale_person_id', 'REP', 'required'); */
		
		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'new';
				$data['nav_area'] = 'project';
				$data['users'] = $this->user_model->getUsers();

				$this->load->view('template/header', $data);
				$this->load->view('property/edit', $data);
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
			$property_id = $this->property_model->add_property();
			if($property_id) {
				logActivity('Created', "Created New property  : " . $this->input->post('property_no'), $property_id);
				$this->session->set_flashdata('msg', 'property Successfully Created');
				$ret = array(
					'status' => 'success',
				);	
				//$this->pv_sendEmail($property_id);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in property Add.'
				);
			}
			echo json_encode($ret);	
		}
	}

	public function edit($id, $submit = FALSE) {
		$data['property'] = $this->property_model->getpropertyinfo($id); 
		if (empty($data['property'])) {
			show_404();
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

	 	$this->form_validation->set_rules('property_title', 'Property Title', 'required');


		if($this->form_validation->run() === FALSE) {
			$data['action'] = 'edit';
			$data['nav_area'] = 'project';
			if($submit == FALSE) { 
			 	$data['users'] = $this->user_model->getUsers();
				$data['details'] =  $this->property_model->getFilesbypropertyid($id);

				$this->load->view('template/header', $data);
				$this->load->view('property/edit', $data);
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
			$updated = $this->property_model->update_property($id);
			if($updated) {
				logActivity('Updated', "Updated New property : " . $this->input->post('property_no'), $id);
				$this->session->set_flashdata('msg', 'property Successfully Updated');
				$ret = array(
					'status' => 'success',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in property Update'
				);
			}	
			echo json_encode($ret);
		}
	}

	
	public function edit_status($id,$stat) {
		$property = $this->property_model->get_properties($id);
			if ( !empty($property) ) {
				if($stat == 'publish'){
					$status = 'Published';
					$msg = 'Property Successfully Approved';
				}
				else{
					$status = 'Rejected';
					$msg = 'This Property has been Rejected';
				}
			
			$deleted = $this->property_model->update_status($id,$status);  
		    if($deleted) {
		    	//logActivity('Deleted', "Deleted property : " . $property['property_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> $msg,
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in property Delete',
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
		$property = $this->property_model->get_properties($id);
		if ( !empty($property) ) {
			$deleted = $this->property_model->delete_property($id);  
		    if($deleted) {
		    	//logActivity('Deleted', "Deleted property : " . $property['property_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> 'Property Successfully Deleted',
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in property Delete',
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
					    $allowed_extension = array("jpg", "jpeg", "png", "gif","JPG"); 
					   if(in_array($file_name[1], $allowed_extension))  
					   {  
							//$new_name = rand() . '.'. $file_name[1];  
							$new_name = $file_name[0] . '_'.date('Ymdhis').'.'. $file_name[1];  
							$sourcePath = $_FILES["images"]["tmp_name"][$name];  
							$targetPath = "uploads/property/".$new_name;  
							move_uploaded_file($sourcePath, $targetPath); 


					   }

						
				  }  $output = '<input type ="text" name ="file_path" id="file-path" value ="' . $targetPath .'" >';
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
							$targetPath = "uploads/property/main_images/".$new_name;  
							move_uploaded_file($sourcePath, $targetPath); 

							$output = '<input type ="hidden" name ="main_new_file_name" id="main-new-file-name" value ="' . $targetPath .'" >';
							//$output .= '<script type ="text/javascript">console.log($("main-new-file-name").val())</script>';
							echo $output;
						}

						
				  }  
			
			 }  
		}
		
	
	
	public function aj_addPropertyfile() {
		$detail_id = $this->property_model->addFile();
		if($detail_id) {
			$property_id = $this->input->post('hid_property_id');
			$details = $this->property_model->getFilesbypropertyid($property_id);
			//logActivity('Added', "Added Invoice Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'property_files'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}
		
		
		
		
		public function aj_deletePropertyfile($id) {
		$property_file = $this->property_model->getFile($id);
		if(!empty($property_file)) {
			$removed = $this->property_model->removeFile($id);
			if($removed) {
				//$this->announcement_model->getFilesbyannouncementid($id);
				$details = $this->property_model->getFilesbypropertyid($property_file['property_id']);
				//logActivity('Removed', "Removed Invoice Detail : " . $invoice_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'property_files'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in File Remove'
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
	
	
	
	
	
	
	
	
	

	/* //Site Ajax //
	public function aj_getpropertydetail($id) {
		$detail = $this->property_model->getDetail($id);
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


	public function aj_addpropertydetail() {
		$detail_id = $this->property_model->addDetail();
		if($detail_id) {
			$property_id = $this->input->post('hid_property_id');
			$details = $this->property_model->getDetilsbypropertyid($property_id);
			logActivity('Added', "Added property Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'property_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}

	public function aj_updatepropertydetail($id) {
		$updated = $this->property_model->updateDetail($id);
		if($updated) {
			$property_id = $this->input->post('hid_property_id');
			$details = $this->property_model->getDetilsbypropertyid($property_id);
			logActivity('Updated', "Updated property Detail : " . $this->input->post('description'), $id);
			$ret = array(
				'status'			=> 'success',
				'property_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'Error in property Detail Update'
			);
		}
		echo json_encode($ret);
	}

	public function aj_deletepropertydetail($id) {
		$property_detail = $this->property_model->getDetail($id);
		if(!empty($property_detail)) {
			$removed = $this->property_model->removeDetail($id);
			if($removed) {
				$details = $this->property_model->getDetilsbypropertyid($property_detail['property_id']);
				logActivity('Removed', "Removed property Detail : " . $property_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'property_detail'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in property Detail remove'
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
	} */

	
	public function aj_getClientdetail($id) {
		$detail = $this->property_model->getClientDetail($id);
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
		$client['data'] = $this->property_model->getClientDetail($client_id);
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
	
	
	public function aj_hasInvoice($property_id) {
		$invoice['data'] = $this->property_model->getInvoice($property_id);
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
		$client = $this->property_model->getClientinfo($client_id);
		
		if(!empty($client)) {
			$client['status'] = 'success';
			//$client = $this->property_model->getClientDetail($client_id);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($client);
	}
	
	
	
	
	
	public function aj_getpropertys() {
		$q = $this->input->get('q'); 
		$result = $this->property_model->getQoNo($q['term']);
		$ret = array(); 
	 	if ( !empty($result) ) {
	 		foreach($result as $res) {
	 			$ret[] = array(
	 				"id" 	=> $res['property_id'], 
	 				"text"	=> $res['property_no'],
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


	public function printproperty($id) {
		$property = $this->property_model->getpropertyinfo($id);
		if (empty($property)) {
			show_404();
		}	
		$details =  $this->property_model->getDetilsbypropertyid($id);
		$note = $this->setting_model->getpropertynote();

		$this->load->library('pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$quo_title = ( $property['revised'] == 0 ) ? 'property' : '[REVISED] property';
 		$pdf->setTitle($quo_title);
		$pdf->setNote($note);
		$pdf->setproperty($property);
		$pdf->SetLineStyle(array('width' => 0, 'cap' => 'butt', 'join' => 0, 'color' => array(0, 0, 0)));
		// $pdf->setJobtitle($property['job_title'], $property['terms']);
		
		// add a page
		$this->pv_propertyHeader($pdf, $property);
		
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
			'sub_total'		=> '$ ' . number_format($property['sub_total'], 2, '.', ','), 
			'gst_percent'   => $property['gst'],
			'gst'			=> '$ ' . number_format($property['total'] - $property['sub_total'], 2, ".",""), 
			'grand_total'	=> '$ ' . number_format($property['total'], 2, '.', ','), 
		);

		$this->pdf->generateTable($pdf, $header, $detail_arr, $footer, 140, true);
        $pdf->ln(1);
		$pdf->Output('property_Order.pdf', 'I');
	}

	public function pv_propertyHeader(&$pdf, &$property) {

		//$property = $pdf->property;
		$delivery_date = ($property['delivery_date'] != 0) ? date('d/m/Y', $property['delivery_date']) : '';
		$invoice_date = ($property['invoice_date'] != 0) ? date('d/m/Y', $property['invoice_date']) : '';
		$revised = ($property['revised'] == 1) ? "[ Revised ]" : '';

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
		$pdf->multiCell(50, 5, $property['contact'], 0, 'L', 0 , 1);
		$pdf->multiCell(25, 5, 'Designation:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, $property['designation'], 0, 'L', 0 , 1);
		$pdf->multiCell(25, 5, 'Department:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, $property['department'], 0, 'L', 0 , 1);
		$pdf->multiCell(25, 15, 'Address:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		//$pdf->setCellPaddings(2, 2, 10, 2);
		$pdf->multiCell(60, 25, $property['client'] . '<br/>' . $property['address'], 0, 'L', 0, 1, '', '', true, 0, true, true);
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
				   		'<td width="33.33%">'.date('d/m/Y', $property['date']).'</td>'.
				   		'<td width="33.33%"><b>'.$property['property_no'].'</b></td>'.
				   		'<td width="33.33%">'.$property['rep'].'</td>'.
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
				   		'<td width="33.33%">'.$property['po_no'].'</td>'.
				   		'<td width="33.33%">'.$property['st_oic'].'</td>'.
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
				   		'<td width="33.33%">'.$property['invoice_no'].'</td>'.
				   		'<td width="33.33%">'.$property['tel_no'].'</td>'.
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


	public function pv_sendEmail($property_id) {
		$property_info = $this->property_model->getpropertyinfo($property_id);

		$message = 	"Dear Sir/Madam, <br/><br/>There is a new property with the following information. <br/><br/>" .
					 "<table cellspacing='0' cellpadding='5' border='1'>" .
					 "<tr>" .
					 	"<td>property No</td><td> : </td><td>" . $property_info['property_no'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Date</td><td> : </td><td>" . date('d-m-Y', $property_info['date']) . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Client</td><td> : </td><td>" . $property_info['client'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Rep</td><td> : </td><td>" . $property_info['rep'] . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Sub Total</td><td> : </td><td>" . $property_info['sub_total'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>GST</td><td> : </td><td>" . $property_info['gst'] . " %</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Grand Total</td><td> : </td><td>" . $property_info['total'] . "</td>" . 
					 "</tr>".
					 "</table> <br/><br/>  For More information, Please login to the system. <br/><br/><br/> Thanks";

		$emails = explode(', ', $this->setting_model->getEmails());  
		print_r($emails);
		echo count($emails);
		echo $message;
		//Send Email 
		foreach($emails as $email) {
			send_email('MBDPL CRM', 'dyan@mbdesign.com.sg', $email, 'New property', $message);
			//echo send_email('MBDPL CRM', 'josemiguelgonzales93@gmail.com', $email, 'New property', $message);
		}
	}


}