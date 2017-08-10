<?php
class project extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->model('project_model');
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
			$data['nav_area'] = 'project';

			$this->load->view('template/header', $data);
			$this->load->view('project/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			//$role_id = $this->session->userdata('role_id');
			//if($role_id != 1) {
				
				$this->project_model->getdtprojects();
			
			// }
			// else{
				// $this->project_model->getdtproperties_admin();
			// }
			//$data['total_invoice'] = $this->project_model->getinvoices();	
			//$data['company'] = $this->supplier_model->get_suppliers();
			//$data['clients'] = $this->client_model->get_clients();
		}
	}

	public function view($id) {
		$data['project'] = $this->project_model->getprojectinfo($id); 
		if (empty($data['project'])) {
			show_404();
		}
		//$data['details'] =  $this->project_model->getDetilsbyprojectid($id);
		$this->load->view('project/view', $data);
	}

	public function create($submit = FALSE) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('description', 'Description', 'required');
		/* $this->form_validation->set_rules('date', 'Date', 'required');
		$this->form_validation->set_rules('client_id', 'Client', 'required');
		$this->form_validation->set_rules('sale_person_id', 'REP', 'required'); */
		
		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'new';
				$data['nav_area'] = 'project';
				$data['users'] = $this->user_model->getUsers();
				$data['project_code'] = $this->project_model->getprojectcode();
				$this->load->view('template/header', $data);
				$this->load->view('project/edit', $data);
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
			$project_id = $this->project_model->add_project();
			if($project_id) {
				//logActivity('Created', "Created New project  : " . $this->input->post('project_no'), $project_id);
				$this->session->set_flashdata('msg', 'Project Successfully Created');
				$ret = array(
					'status' => 'success',
				);	
				//$this->pv_sendEmail($project_id);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in project Add.'
				);
			}
			echo json_encode($ret);	
		}
	}

	public function edit($id, $submit = FALSE) {
		$data['project'] = $this->project_model->getprojectinfo($id); 
		if (empty($data['project'])) {
			show_404();
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

	 		$this->form_validation->set_rules('description', 'Description', 'required');


		if($this->form_validation->run() === FALSE) {
			$data['action'] = 'edit';
			$data['nav_area'] = 'project';
			if($submit == FALSE) { 
			 	$data['users'] = $this->user_model->getUsers();


				$this->load->view('template/header', $data);
				$this->load->view('project/edit', $data);
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
			$updated = $this->project_model->update_project($id);
			if($updated) {
				logActivity('Updated', "Updated New project : " . $this->input->post('project_no'), $id);
				$this->session->set_flashdata('msg', 'project Successfully Updated');
				$ret = array(
					'status' => 'success',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in project Update'
				);
			}	
			echo json_encode($ret);
		}
	}

	
	public function edit_status($id,$stat) {
		$project = $this->project_model->get_properties($id);
		if ( !empty($project) ) {
				if($stat == 'publish'){
					$status = 'Published';
					$msg = 'project Successfully Approved';
				}
				else{
					$status = 'Rejected';
					$msg = 'This project has been Rejected';
				}
			
			$deleted = $this->project_model->update_status($id,$status);  
		    if($deleted) {
		    	//logActivity('Deleted', "Deleted project : " . $project['project_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> $msg,
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in project Delete',
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
		$project = $this->project_model->get_projects($id);
		if ( !empty($project) ) {
			$deleted = $this->project_model->delete_project($id);  
		    if($deleted) {
		    	//logActivity('Deleted', "Deleted project : " . $project['project_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> 'project Successfully Deleted',
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in project Delete',
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
	public function aj_getprojectdetail($id) {
		$detail = $this->project_model->getDetail($id);
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


	public function aj_addprojectdetail() {
		$detail_id = $this->project_model->addDetail();
		if($detail_id) {
			$project_id = $this->input->post('hid_project_id');
			$details = $this->project_model->getDetilsbyprojectid($project_id);
			logActivity('Added', "Added project Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'project_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}

	public function aj_updateprojectdetail($id) {
		$updated = $this->project_model->updateDetail($id);
		if($updated) {
			$project_id = $this->input->post('hid_project_id');
			$details = $this->project_model->getDetilsbyprojectid($project_id);
			logActivity('Updated', "Updated project Detail : " . $this->input->post('description'), $id);
			$ret = array(
				'status'			=> 'success',
				'project_detail'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'Error in project Detail Update'
			);
		}
		echo json_encode($ret);
	}

	public function aj_deleteprojectdetail($id) {
		$project_detail = $this->project_model->getDetail($id);
		if(!empty($project_detail)) {
			$removed = $this->project_model->removeDetail($id);
			if($removed) {
				$details = $this->project_model->getDetilsbyprojectid($project_detail['project_id']);
				logActivity('Removed', "Removed project Detail : " . $project_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'project_detail'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in project Detail remove'
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
		$detail = $this->project_model->getClientDetail($id);
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
		$client['data'] = $this->project_model->getClientDetail($client_id);
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
	
	
	public function aj_hasInvoice($project_id) {
		$invoice['data'] = $this->project_model->getInvoice($project_id);
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
		$client = $this->project_model->getClientinfo($client_id);
		
		if(!empty($client)) {
			$client['status'] = 'success';
			//$client = $this->project_model->getClientDetail($client_id);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($client);
	}
	
	
	
	
	
	public function aj_getprojects() {
		$q = $this->input->get('q'); 
		$result = $this->project_model->getQoNo($q['term']);
		$ret = array(); 
	 	if ( !empty($result) ) {
	 		foreach($result as $res) {
	 			$ret[] = array(
	 				"id" 	=> $res['project_id'], 
	 				"text"	=> $res['project_no'],
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


	public function printproject($id) {
		$project = $this->project_model->getprojectinfo($id);
		if (empty($project)) {
			show_404();
		}	
		$details =  $this->project_model->getDetilsbyprojectid($id);
		$note = $this->setting_model->getprojectnote();

		$this->load->library('pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$quo_title = ( $project['revised'] == 0 ) ? 'project' : '[REVISED] project';
 		$pdf->setTitle($quo_title);
		$pdf->setNote($note);
		$pdf->setproject($project);
		$pdf->SetLineStyle(array('width' => 0, 'cap' => 'butt', 'join' => 0, 'color' => array(0, 0, 0)));
		// $pdf->setJobtitle($project['job_title'], $project['terms']);
		
		// add a page
		$this->pv_projectHeader($pdf, $project);
		
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
			'sub_total'		=> '$ ' . number_format($project['sub_total'], 2, '.', ','), 
			'gst_percent'   => $project['gst'],
			'gst'			=> '$ ' . number_format($project['total'] - $project['sub_total'], 2, ".",""), 
			'grand_total'	=> '$ ' . number_format($project['total'], 2, '.', ','), 
		);

		$this->pdf->generateTable($pdf, $header, $detail_arr, $footer, 140, true);
        $pdf->ln(1);
		$pdf->Output('project_Order.pdf', 'I');
	}

	public function pv_projectHeader(&$pdf, &$project) {

		//$project = $pdf->project;
		$delivery_date = ($project['delivery_date'] != 0) ? date('d/m/Y', $project['delivery_date']) : '';
		$invoice_date = ($project['invoice_date'] != 0) ? date('d/m/Y', $project['invoice_date']) : '';
		$revised = ($project['revised'] == 1) ? "[ Revised ]" : '';

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
		$pdf->multiCell(50, 5, $project['contact'], 0, 'L', 0 , 1);
		$pdf->multiCell(25, 5, 'Designation:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, $project['designation'], 0, 'L', 0 , 1);
		$pdf->multiCell(25, 5, 'Department:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		$pdf->multiCell(50, 5, $project['department'], 0, 'L', 0 , 1);
		$pdf->multiCell(25, 15, 'Address:', 0, 'L', 0 , 0);
		$pdf->multiCell(5, 5, '  ', 0, 'L', 0 , 0);
		//$pdf->setCellPaddings(2, 2, 10, 2);
		$pdf->multiCell(60, 25, $project['client'] . '<br/>' . $project['address'], 0, 'L', 0, 1, '', '', true, 0, true, true);
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
				   		'<td width="33.33%">'.date('d/m/Y', $project['date']).'</td>'.
				   		'<td width="33.33%"><b>'.$project['project_no'].'</b></td>'.
				   		'<td width="33.33%">'.$project['rep'].'</td>'.
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
				   		'<td width="33.33%">'.$project['po_no'].'</td>'.
				   		'<td width="33.33%">'.$project['st_oic'].'</td>'.
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
				   		'<td width="33.33%">'.$project['invoice_no'].'</td>'.
				   		'<td width="33.33%">'.$project['tel_no'].'</td>'.
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


	public function pv_sendEmail($project_id) {
		$project_info = $this->project_model->getprojectinfo($project_id);

		$message = 	"Dear Sir/Madam, <br/><br/>There is a new project with the following information. <br/><br/>" .
					 "<table cellspacing='0' cellpadding='5' border='1'>" .
					 "<tr>" .
					 	"<td>project No</td><td> : </td><td>" . $project_info['project_no'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Date</td><td> : </td><td>" . date('d-m-Y', $project_info['date']) . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Client</td><td> : </td><td>" . $project_info['client'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Rep</td><td> : </td><td>" . $project_info['rep'] . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Sub Total</td><td> : </td><td>" . $project_info['sub_total'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>GST</td><td> : </td><td>" . $project_info['gst'] . " %</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Grand Total</td><td> : </td><td>" . $project_info['total'] . "</td>" . 
					 "</tr>".
					 "</table> <br/><br/>  For More information, Please login to the system. <br/><br/><br/> Thanks";

		$emails = explode(', ', $this->setting_model->getEmails());  
		print_r($emails);
		echo count($emails);
		echo $message;
		//Send Email 
		foreach($emails as $email) {
			send_email('MBDPL CRM', 'dyan@mbdesign.com.sg', $email, 'New project', $message);
			//echo send_email('MBDPL CRM', 'josemiguelgonzales93@gmail.com', $email, 'New project', $message);
		}
	}


}