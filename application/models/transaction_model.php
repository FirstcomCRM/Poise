<?php
class transaction_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}
	
	
	public function get_transactions($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('case_submission');
			return $query->result_array();
		}

		$query = $this->db->get_where('case_submission', array('case_id' => $id));
		return $query->row_array();
	}
	
	public function get_propertyid($id,$stat) {
		
		$this->db->select('p.property_id');
		$this->db->from('case_submission p');
		$this->db->where('p.case_id', $id);
		$this->db->where('p.status !=', 1);
		$query = $this->db->get('case_submission');
		//return $query->result_array();	
		if($stat == 'approve'){
			$status = 'Sold/Approved';
			$msg = 'Transaction Successfully Approved';
		}
		else if($stat =='reject'){
			$status = 'Rejected';
			$msg = 'This Transaction has been Rejected';
		}
		
		//$q = $this->db->get('my_users_table');
		$data = $query->result_array();
		if($data){
			$this->update_status($data[0]['property_id'],$status);	
		}
		//echo();
				
		return $data;
	
	}
	
	
	

	public function get_properties($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('property');
			return $query->result_array();
		}

		$query = $this->db->get_where('property', array('property_id' => $id));
		return $query->row_array();
	}
	
	public function get_approved_properties($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			//$this->db->where('property_status =', 'Published');
		//	$this->db->where('property_status =', 'Sold');
			$where1 = 'property_status = "Published" OR property_status= "Sold"';
			$this->db->where($where1);
			$this->db->where('user_id =',  $this->session->userdata('user_id'));
			$query = $this->db->get('property');
			return $query->result_array();
		}

		$query = $this->db->get_where('property', array('property_id' => $id));
		return $query->row_array();
	}
	
	public function get_approved_transactions($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			//$this->db->where('property_status =', 'Published');
		//	$this->db->where('property_status =', 'Sold');
			$where1 = 'property_id in (select property_id from property where property_status ="Sold/Approved")';
			$this->db->where($where1);
			//$this->db->where('user_id =',  $this->session->userdata('user_id'));
			$query = $this->db->get('case_submission');
			return $query->result_array();
		}

		$query = $this->db->get_where('case_submission', array('case_id' => $id));
		return $query->row_array();
	}
	

	public function add_transaction() {
		$transact_date = $this->input->post('transact_date');
		$contract_date = $this->input->post('contract_date');
		$option_date = $this->input->post('option_date');
		$new_transact_date = str_replace('/', '-', $transact_date);
		$new_contract_date = str_replace('/', '-', $contract_date);
		$new_option_date = str_replace('/', '-', $option_date);
		
		 /* $query = $this->db->query("SELECT property_no FROM property
                               WHERE property_no = '".$this->input->post('property_no')."' and status = 0"); */
		//if($query->num_rows() == 0){
				$data = array(
					'case_no'        				=> $this->input->post('case_no'),
					'property_id'     		 		=> $this->input->post('property_id'),
					'amount'           		 	 	=> $this->input->post('anount'),
					'transact_date'  			 	=> date('Y-m-d',strtotime($new_transact_date)),
					'property_type'      			=> $this->input->post('property_type'),
					'address'          				=> $this->input->post('address'),
					'price'             			=> $this->input->post('price'),
					'gst'             				=> $this->input->post('gst'),
					'owner_name'         			=> $this->input->post('owner_name'),
					'buyer_name'         			=> $this->input->post('buyer_name'),
					'co_broke_agent'     			=> $this->input->post('co_broke_agent'),
					'co_broke_agency'    			=> $this->input->post('co_broke_agency'),
					'contract_date'      			=> date('Y-m-d',strtotime($new_contract_date)),
					'option_date'        			=> date('Y-m-d',strtotime($new_option_date)),
					'commission'         			=> $this->input->post('commission'),
					'co_broke_commission'			=> $this->input->post('co_broke_commission'),
					'internal_commission'			=> $this->input->post('internal_commission'),
					//'transact_img'					=> $this->input->post('main_new_file_name'),
					'user_id' 						=> $this->input->post('user_id'),
			);
			$this->db->insert('case_submission', $data);
			$transaction_id = $this->db->insert_id();
			if($transaction_id) {
				$this->add_files($transaction_id);	
				$this->update_status($this->input->post('property_id'),"Sold");	
			} 
			return $transaction_id;
			
		/* }
		else{
			return false;
		}
		 */

	}

	public function addDetails($property_id) {
		$details = $this->input->post('detail_info');
		if($details) {
			foreach($details as $detail) {
				$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'property_id'	 	=> $property_id,
					'no'				=> $no,
					'description'		=> $detail['description'],
					'qty'				=> $detail['qty'],
					'supplier_id'		=> $detail['supplier_id'],
					'supplier_cost'		=> $detail['supplier_cost'],
					'uom_id'			=> $detail['uom_id'],
					'amount'			=> $detail['amount'],
				);
				$this->db->insert('property_detail', $data);
			}	
		}
	}
	
	public function update_status($id,$stat) {
		//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$invoice_date = ($this->input->post('invoice_date')) ? get_timestamp($this->input->post('invoice_date'), '/') : 0;
	 	if($stat == 'Sold/Approved'){
			$status = 'Sold/Approved';
		}
		/*else{
			$status = 'Rejected';
		}
		 */
		$data = array(
				'property_status'   => $stat,
		);
		$this->db->where('property_id', $id);
		//$this->db->get_where('case_submission', array('property_id' => $id));
		$this->db->update('property', $data); 
	}
	
	
	public function updatetoSold($id) {
		//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$invoice_date = ($this->input->post('invoice_date')) ? get_timestamp($this->input->post('invoice_date'), '/') : 0;
		//if($stat == 'Published'){
		//	$status = 'Published';
		//}
		//else{
	//		$status = 'Rejected';
		//}
		
		$data = array(
				'property_status'   => 'Sold',
		);
		$this->db->where('property_id', $id);
		return $this->db->update('property', $data); 
	}
	
	
	

	public function update_transaction($id) {
		//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$invoice_date = ($this->input->post('invoice_date')) ? get_timestamp($this->input->post('invoice_date'), '/') : 0;
		$transact_date = $this->input->post('transact_date');
		$contract_date = $this->input->post('contract_date');
		$option_date = $this->input->post('option_date');
		$new_transact_date = str_replace('/', '-', $transact_date);
		$new_contract_date = str_replace('/', '-', $contract_date);
		$new_option_date = str_replace('/', '-', $option_date);
		$data = array(
				'case_no'        				=> $this->input->post('case_no'),
				'property_id'     		 		=> $this->input->post('property_id'),
				'amount'           		 	 	=> $this->input->post('anount'),
				'transact_date'  			 	=> date('Y-m-d',strtotime($new_transact_date)),
				'property_type'      			=> $this->input->post('property_type'),
				'address'          				=> $this->input->post('address'),
				'price'             			=> $this->input->post('price'),
				'gst'             				=> $this->input->post('gst'),
				'owner_name'         			=> $this->input->post('owner_name'),
				'buyer_name'         			=> $this->input->post('buyer_name'),
				'co_broke_agent'     			=> $this->input->post('co_broke_agent'),
				'co_broke_agency'    			=> $this->input->post('co_broke_agency'),
				'contract_date'      			=> date('Y-m-d',strtotime($new_contract_date)),
				'option_date'        			=> date('Y-m-d',strtotime($new_option_date)),
				'commission'         			=> $this->input->post('commission'),
				'co_broke_commission'			=> $this->input->post('co_broke_commission'),
				'internal_commission'			=> $this->input->post('internal_commission'),
				//'transact_img'					=> $this->input->post('main_new_file_name'),
				'user_id' 						=> $this->input->post('user_id'),
		);
		$this->db->where('case_id', $id);
		return $this->db->update('case_submission', $data); 
	}

	public function delete_transaction($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('case_id', $id);
		return $this->db->update('case_submission', $data);
	}	

	public function getDetilsbypropertyid($property_id) {
		$this->db->select('d.*, u.name as unit,s.company as supplier');
		$this->db->from('property_detail d');
		$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->join('supplier s', 'd.supplier_id = s.supplier_id', 'left');
		$this->db->where('d.property_id', $property_id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}

	public function add_files($transact_id) {
		$details = $this->input->post('files_info');
	
		if($details) {
			foreach($details as $detail) {
				//$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'transact_id'	 	=> $transact_id,
					'file_name'			=> $detail['file_name'],
					'new_file_name'		=> $detail['new_file_name'],
					'file_path'			=> $detail['file_path'],
					'date_uploaded'		=> date('Y-m-d'),
					//'file_name'		=> $this->input->post('name'),
				);
				$this->db->insert('transaction_files', $data);
			}	
		}
	}
	
	
	
	
	
	
	public function getdttransactions() {
		if( $this->input->post('transaction_no') && ( $this->input->post('transaction_no') != '' ) ) {
			$this->datatables->filter('p.case_no LIKE "%' . $this->input->post('transaction_no') . '%"');
		}
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = date('Y-m-d',strtotime($this->input->post('start_date')));
        	$this->datatables->filter('p.transact_date >=', $start_date);
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date = date('Y-m-d',strtotime($this->input->post('end_date')));
        	$this->datatables->filter('p.transact_date <=', $end_date);
        } 
		if( $this->input->post('property_status') && ( $this->input->post('property_status') != '' ) ) {
			$this->datatables->filter('pr.property_status', $this->input->post('property_status') );
		}
		/* $role_id = $this->session->userdata('role_id');
		if($role_id != 1) {
			$this->datatables->where('p.user_id', $this->session->userdata('user_id'));
		} */

        $this->datatables->select("p.case_id,p.case_no, pr.property_title,p.amount, DATE_FORMAT(p.transact_date, '%d/%m/%Y')as transact_date, p.property_type,p.owner_name, p.address,p.price, pr.property_status", false);
        $this->datatables->from('case_submission p');
        $this->datatables->join('users u', 'u.user_id = p.user_id', 'left');
        $this->datatables->join('property pr', 'pr.property_id = p.property_id', 'left');
		$this->datatables->where('p.status !=', 1);
		$this->datatables->where('p.user_id =', $this->session->userdata('user_id'));
		$this->datatables->where('pr.property_status ="Sold"');
		$this->datatables->add_column('no', '');
		$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="transaction/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="transaction/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="transaction/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'case_id');
		echo $this->datatables->generate();
	}
	
	
	public function getdttransactions_admin() {
		if( $this->input->post('transaction_no') && ( $this->input->post('transaction_no') != '' ) ) {
			$this->datatables->filter('p.case_no LIKE "%' . $this->input->post('transaction_no') . '%"');
		}
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = date('Y-m-d',strtotime($this->input->post('start_date')));
        	$this->datatables->filter('p.transact_date >=', $start_date);
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date = date('Y-m-d',strtotime($this->input->post('end_date')));
        	$this->datatables->filter('p.transact_date <=', $end_date);
        } 
		if( $this->input->post('property_status') && ( $this->input->post('property_status') != '' ) ) {
			$this->datatables->filter('pr.property_status', $this->input->post('property_status') );
		}
		/* if( $this->input->post('property_status') && ( $this->input->post('property_status') != '' ) ) {
			$this->datatables->filter('q.property_status', $this->input->post('property_status') );
		} */

		/* $role_id = $this->session->userdata('role_id');
		if($role_id != 1) {
			$this->datatables->where('p.user_id', $this->session->userdata('user_id'));
		} */

		$this->datatables->select("p.case_id,p.case_no, pr.property_title,p.amount, DATE_FORMAT(p.transact_date, '%d/%m/%Y')as transact_date, p.property_type, p.address,p.price, p.owner_name,p.property_id", false);
        $this->datatables->from('case_submission p');
        $this->datatables->join('users u', 'u.user_id = p.user_id', 'left');
        $this->datatables->join('property pr', 'pr.property_id = p.property_id', 'left');
		$this->datatables->where('p.status !=', 1);
		$this->datatables->where('pr.property_status =', 'Sold');
		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', ' <button type="submit" class="btn btn-mtac admin-control" id="btn-view"><i class="fa fa-save ico-btn"></i>View</button><button type="submit" class="btn btn-mtac admin-control" id="btn-approve"><i class="fa fa-save ico-btn"></i>Approve</button><button type="submit" class="btn btn-mtac admin-control" id="btn-reject"><i class="fa fa-save ico-btn"></i>Reject</button>', 'property_id');
		//$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view" data-toggle="tooltip" data-placement="top" title="View" href="transaction/view/$1"><i class="fa fa-save ico-btn"></i>View</a><a class="btn btn-mtac admin-control btn-approve" data-toggle="tooltip" data-placement="top" title="Approve" href="transaction/edit_status/$1"><i class="fa fa-save ico-btn"></i>Approve</a><a class="btn btn-mtac admin-control btn-reject" data-toggle="tooltip" data-placement="top" title="Reject" href="transaction/edit_status/$1"><i class="fa fa-save ico-btn"></i>Reject</a>', 'case_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="transaction/view/$1">View</a><a class="btn btn-mtac admin-control btn-approve btn-success" data-toggle="tooltip" data-placement="top" title="Approve" href="transaction/edit_status/$1">Approve</a><a class="btn btn-mtac btn-reject btn-danger" data-toggle="tooltip" data-placement="top" title="Rejected" href="transaction/edit_status/$1">Reject</a>', 'case_id');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="transaction/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="transaction/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="transaction/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'case_id');
		echo $this->datatables->generate();
	}


	public function addDetail() {
		$no = $this->input->post('no');
		$data = array(
			'property_id'	 	=> $this->input->post('hid_property_id'),
			'no'				=> ($no) ? $no : NULL,
			'description'		=> $this->input->post('description'),
			'supplier_id'		=> $this->input->post('supplier_id'),
			'supplier_cost'		=> $this->input->post('supplier_cost'),
			'discount'			=> $this->input->post('discount'),
			'qty'				=> $this->input->post('qty'),
			'uom_id'			=> $this->input->post('uom_id'),
			'amount'			=> $this->input->post('amount'),
		);
		$this->db->insert('property_detail', $data);
		$detail_id = $this->db->insert_id();
		return $detail_id;	
	}

	public function getDetail($id) {
		$this->db->select('d.*, u.name as unit,s.company');
		$this->db->from('property_detail d');
		$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->join('supplier s', 'd.supplier_id = s.supplier_id', 'left');
		$this->db->where('d.property_detail_id', $id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get('property_detail');
		return $query->row_array();
	}

	public function updateDetail($id) {
		$no = $this->input->post('no');
		$data = array(
			'property_id'	 	=> $this->input->post('hid_property_id'),
			'no'				=> ($no) ? $no : NULL,
			'description'		=> $this->input->post('description'),
			'supplier_id'		=> $this->input->post('supplier_id'),
			'supplier_cost'		=> $this->input->post('supplier_cost'),
			'discount'			=> $this->input->post('discount'),
			'qty'				=> $this->input->post('qty'),
			'uom_id'			=> $this->input->post('uom_id'),
			'amount'			=> $this->input->post('amount'),
		);	
		$this->db->where('property_detail_id', $id);
		return $this->db->update('property_detail', $data); 
	}
	
	
	
	public function addFile() {
		//$no = $this->input->post('no');
		$data = array(
			'transaction_id'	 		=>  $this->input->post('hid_transact_id'),
			'file_name'					=>  $this->input->post('file_name'),
			'new_file_name'				=>  $this->input->post('new_file_name'),
			'file_path'					=>  $this->input->post('file_path'),
			'date_uploaded'				=>  date('Y-m-d'),
			
		);
		$this->db->insert('transaction_files', $data);
		$detail_id = $this->db->insert_id();
		return $detail_id;	
	}
	
	
	
	public function removeFile($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('transaction_file_id', $id);
		return $this->db->update('transaction_files', $data); 	
	}
	
	
	public function getFile($id) {
		$this->db->select('f.*');
		$this->db->from('transaction_files f');
	//	$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('f.transaction_file_id', $id);
		$this->db->where('f.status !=', 1);
		$query = $this->db->get('transaction_files');
		return $query->row_array();
	}
	
	
	
	public function getFilesbytransactionid($transact_id) {
		$this->db->select('f.*');
		$this->db->from('transaction_files f');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('f.transaction_id', $transact_id);
		$this->db->where('f.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}
	


	public function removeDetail($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('property_detail_id', $id);
		return $this->db->update('property_detail', $data); 	
	}

	public function getcaseno() {
		$latest_no = $this->getLatestcaseno();
		if($latest_no) {
			$year = substr($latest_no, 0, 2);
			$no = substr($latest_no, 2);
			$no_arr = explode('-', $no);
			if($year == date('y')) {
				return $year . $no_arr[0] + 1;
			}
			else {
				return date('y') . str_pad(1, 4, "0", STR_PAD_LEFT);
			}
		}
		else {
			return date('y') . str_pad(1, 4, "0", STR_PAD_LEFT);
		}
	}

	public function getLatestcaseno() {
		$this->db->where('status !=', 1);
		$this->db->order_by("case_id", "desc");
		$this->db->limit(1);
		$query = $this->db->get('case_submission');
		$result = $query->row_array();
		return ($result) ? $result['case_no'] : '';
	}

	public function get_transactinfo($id) {
		$this->db->select("p.*,pr.property_title,u.name", false);
		$this->db->from('case_submission p');
		$this->db->join('property pr', 'pr.property_id = p.property_id', 'left');
        $this->db->join('users u', 'u.user_id = p.user_id', 'left');
		$this->db->where('p.status !=', 1);
		$this->db->where('p.case_id', $id);
		$query = $this->db->get();	
		return $query->row_array();
	}

	public function getPropertyinfo($property) {
		$this->db->select("p.*", false);
		$this->db->from('property p');
		$this->db->where('p.property_id', $property);
		$query = $this->db->get();
		return $query->row_array();	
	}
	
	
	public function getClientDetail($client_id) {
		$this->db->select('d.*');
		$this->db->from('client_detail d');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('d.client_id', $client_id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();
		//echo json_encode($query);
	}
	public function getInvoice($property_id) {
		$this->db->select('i.*');
		$this->db->from('invoice i');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('i.property_id', $property_id);
		$this->db->where('i.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();
		//echo json_encode($query);
	}
	
	
	public function get_admin_setting() {
		
		
	//$query = $this->db->select('email')->from('users')->where('role_id', 1)->where('role_id', 1)->get();
    //return $query->row()->average_score;
		/* $this->db->select('u.email');
		$this->db->from('users u');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('u.role_id =', 1);
		$this->db->where('u.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();
		//echo json_encode($query); */
		
		/* $this->db->where('role_id =', 1);
		$this->db->where('status !=', 1);
			$query = $this->db->get('users');
			return $query->result_array(); */
			
		$where = "`role_id` = 1 AND `status`!= 1";
		$this->db->where($where);
		$query = $this->db->get('users');
		//echo "ZZZZZZZZZZZZZZZZ>".$query;
		//return $query->result_array();
		$query = $this->db->get_where('users');
		return $query->row_array();
	}

	public function getQoNo($name) {
		// $this->db->like('property_no', $name);
		// $this->db->where('status !=', 1);
		// $query = $this->db->get('property');
		// return $query->result_array();

		$role_id = $this->session->userdata('role_id');
		$user_id = $this->session->userdata('user_id');

		$this->db->like('property_no', $name);
		$this->db->where('status !=', 1);

		if ($role_id == 1) {
			$query = $this->db->get('property');
			return $query->result_array();
		}
		else {
			$query = $this->db->get_where('property', array('sale_person_id' => $user_id));
			return $query->result_array();	
		}
	}
	
	public function getAdminEmails() {
		$setting = $this->get_admin_setting();
		return $setting['email'];	
	}
	

}