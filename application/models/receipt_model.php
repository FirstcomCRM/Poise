<?php
class receipt_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_receipts($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('receipt');
			return $query->result_array();
		}

		$query = $this->db->get_where('receipt', array('receipt_id' => $id));
		return $query->row_array();
	}

	public function add_receipt() {
	//	$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$receipt_date = ($this->input->post('receipt_date')) ? get_timestamp($this->input->post('receipt_date'), '/') : 0;
		$data = array(
		/* 
			 hid_submitted       		: $('#hid-submitted').val(),
                    receipt_no          		: $('#receipt-no').val(),
                    receipt_date        		: $('#receipt-date').val(),
                    transact_id      			: $('#transact-id').val(),
                    deliver_to         			: $('#deliver-to').val(),
                    attention           		: $('#attention').val(),
                    address           			: $('#address').val(),
                    payment_commmission        	: $('#payment-commission').val(),
                    user_id           			: $('#user-id').val(),
                    sub_total          			: $('#sub-total').val(),
					add_gst           			: $('#add-gst').val(),
                    cheque           			: $('#cheque').val(),
		 */
			'receipt_no'				=> $this->input->post('receipt_no'),
			'date'						=> $this->input->post('receipt_date'),
			'transaction_id' 	  		=> $this->input->post('transact_id'),
			'deliver_to'				=> $this->input->post('deliver_to'),
			'attention'					=> $this->input->post('attention'),
			'address'					=> $this->input->post('address'),
			'payment_commission_for'	=> $this->input->post('payment_commission'),
			'sales_person_id'			=> $this->input->post('user_id'),
			'sum'						=> $this->input->post('sub_total'),
			'add_gst'					=> $this->input->post('gst'),
			'cheque'					=> $this->input->post('cheque'),
			
			// 'commission'			=> $this->input->post('commission'),
			// 'mf'					=> $this->input->post('mf'),
		);
		$this->db->insert('receipt', $data);
		$receipt_id = $this->db->insert_id();
	/* 	if($receipt_id) {
			$this->addDetails($receipt_id);	
			$this->addPaymentDetails($receipt_id);	
		} */
		return $receipt_id;
	}

	/* public function addDetails($receipt_id) {
		$details = $this->input->post('detail_info');
		if($details) {
			foreach($details as $detail) {
				//$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'receipt_id'	 	=> $receipt_id,
					'service'		=> $detail['service_id'],
					'description'		=> $detail['description'],
					'qty'				=> $detail['qty'],
					'amount'			=> $detail['amount'],
				);
				$this->db->insert('receipt_detail', $data);
			}	
		}
	}

	
	public function addPaymentDetails($receipt_id) {
		$payment_details = $this->input->post('payment_detail_info');
		if($payment_details) {
			foreach($payment_details as $payment_detail) {
				//$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'receipt_id'	=> $receipt_id,
					'date'			=> $payment_detail['payment_date'],
					'bank_name'		=> $payment_detail['bank_name'],
					'amount'		=> $payment_detail['payment_amount'],
					'payment_type'	=> $payment_detail['payment_type'],
					'cheque'		=> $payment_detail['cheque_file'],
					'remarks'		=> $payment_detail['remarks'],
					
		
					
				);
				$this->db->insert('receipt_payment_detail', $data);
			}	
		}
	} */
	
	
	
	public function update_receipt($id) { 
		//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$receipt_date = ($this->input->post('receipt_date')) ? get_timestamp($this->input->post('receipt_date'), '/') : 0;
		$data = array(
			'receipt_no'				=> $this->input->post('receipt_no'),
			'date'						=> $this->input->post('receipt_date'),
			'transaction_id' 	  		=> $this->input->post('transact_id'),
			'deliver_to'				=> $this->input->post('deliver_to'),
			'attention'					=> $this->input->post('attention'),
			'address'					=> $this->input->post('address'),
			'payment_commission_for'	=> $this->input->post('payment_commission'),
			'sales_person_id'			=> $this->input->post('user_id'),
			'sum'						=> $this->input->post('sub_total'),
			'add_gst'					=> $this->input->post('gst'),
			'cheque'					=> $this->input->post('cheque'),

		);
		$this->db->where('receipt_id', $id);
		return $this->db->update('receipt', $data); 
	}

	public function delete_receipt($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('receipt_id', $id);
		return $this->db->update('receipt', $data);
	}	

	public function getDetilsbyreceiptid($receipt_id) {
		$this->db->select('d.*');
		$this->db->from('receipt_detail d');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('d.receipt_id', $receipt_id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}

	public function getPaymentDetailsbyreceiptid($receipt_id) {
		$this->db->select('pd.*');
		$this->db->from('receipt_payment_detail pd');
		$this->db->where('pd.receipt_id', $receipt_id);
		$this->db->where('pd.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}

	
	public function getdtreceipts() {
		if( $this->input->post('receipt_no') && ( $this->input->post('receipt_no') != '' ) ) {
			$this->datatables->filter('r.receipt_no LIKE "%' . $this->input->post('receipt_no') . '%"');
		}
		if( $this->input->post('transaction_no') && ( $this->input->post('transaction_no') != '' ) ) {
			$this->datatables->filter('c.case_no LIKE "%' . $this->input->post('transaction_no') . '%"');
		}
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = date('Y-m-d',strtotime($this->input->post('start_date')));
        	$this->datatables->filter('i.receipt_date >=', $start_date);
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date = date('Y-m-d',strtotime($this->input->post('end_date')));
        	$this->datatables->filter('i.receipt_date <=', $end_date);
        } 
  	
		$role_id = $this->session->userdata('role_id');
		if($role_id == 1) {
			//$edit = '/ <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="receipt/edit/$1"><i class="fa fa-edit ico"></i></a>';
			$edit = '<a class="btn btn-mtac admin-control btn-view btn-success" data-toggle="tooltip" data-placement="top" title="Edit" href="receipt/edit/$1">Edit</a>';
		}
		else{
			$edit = '';
		}
        $this->datatables->select("r.receipt_id, r.receipt_no, r.date, c.case_no, r.deliver_to, r.address, r.sum", false);
        $this->datatables->from('receipt r');
        $this->datatables->join('case_submission c', 'c.case_id = r.transaction_id', 'left');
        //$this->datatables->join('client c', 'q.client_id = c.client_id', 'left');
        $this->datatables->join('users u', 'u.user_id = r.sales_person_id', 'left');
       
		$this->datatables->where('r.status !=', 1);
		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="receipt/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="print-link" data-toggle="tooltip" data-placement="top" title="Print" href="receipt/printreceipt/$1"><i class="fa fa-print ico"></i></a> '.$edit.'/ <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="receipt/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'receipt_id');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="receipt/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="print-link" data-toggle="tooltip" data-placement="top" title="Print" href="receipt/printreceipt/$1"><i class="fa fa-print ico"></i></a> '.$edit.'/<button class="btn btn-block btn-info">Info</button> <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="receipt/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'receipt_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="receipt/view/$1">View</a>'.$edit.'<a class="btn btn-mtac admin-control btn-view btn-warning" data-toggle="tooltip" data-placement="top" title="Print" href="receipt/printReceipt/$1">Print</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1">Delete</a>', 'receipt_id');
		//$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="announcement/view/$1">View</a><a class="btn btn-mtac admin-control btn-view btn-success" data-toggle="tooltip" data-placement="top" title="Edit" href="announcement/edit/$1">Edit</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1">Delete</a>', 'announce_id');
		echo $this->datatables->generate();
	}
	
	
	
	
	
	
	
	
	
	
	public function addDetail() {
		$no = $this->input->post('no');
		$data = array(
			'receipt_id'	 	=>  $this->input->post('hid_receipt_id'),
			'service'			=>  $this->input->post('service_id'),
			'description'		=>  $this->input->post('description'),
			'qty'				=>  $this->input->post('qty'),
			'amount'			=>  $this->input->post('amount'),
			
		);
		$this->db->insert('receipt_detail', $data);
		$detail_id = $this->db->insert_id();
		return $detail_id;	
	}

	
	public function addPaymentDetail() {
		//$no = $this->input->post('no');
		//$payment_date = ($this->input->post('payment_date')) ? get_timestamp($this->input->post('payment_date'), '-') : 0;
		//'date'		=> $this->input->post('payment_date'),
		$data = array(
			'receipt_id'	 	=>  $this->input->post('hid_receipt_id'),
			'date'				=>  date('Y-m-d',strtotime($this->input->post('payment_date'))),
			'bank_name'			=>  $this->input->post('bank_name'),
			'amount'			=>  $this->input->post('payment_amount'),
			'payment_type'		=>  $this->input->post('payment_type'),
			'cheque'			=>  $this->input->post('cheque_file'),
		    'remarks'			=>  $this->input->post('remarks'),
		
		
		);
		$this->db->insert('receipt_payment_detail', $data);
		$payment_detail_id = $this->db->insert_id();
		return $payment_detail_id;	
	}
	
	
	
	
	public function getDetail($id) {
		$this->db->select('d.*');
		$this->db->from('receipt_detail d');
	//	$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('d.receipt_detail_id', $id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get('receipt_detail');
		return $query->row_array();
	}

	
	public function getPaymentDetail($id) {
		$this->db->select('pd.*');
		$this->db->from('receipt_payment_detail pd');
		$this->db->where('pd.receipt_payment_detail_id', $id);
		$this->db->where('pd.status !=', 1);
		$query = $this->db->get('receipt_payment_detail');
		return $query->row_array();
	}
	
	
	
	public function updateDetail($id) {
		$no = $this->input->post('no');
		$data = array(
			'receipt_id'	 	=> $this->input->post('hid_receipt_id'),
			'service'	 		=> $this->input->post('service_id'),
			'description'		=> $this->input->post('description'),
			'qty'				=> $this->input->post('qty'),
			'amount'			=> $this->input->post('amount'),
		);	
		$this->db->where('receipt_detail_id', $id);
		return $this->db->update('receipt_detail', $data); 
	}

	public function updatePaymentDetail($id) {
		//$no = $this->input->post('no');
		$data = array(
			'receipt_id'	 	=>  $this->input->post('hid_receipt_id'),
			'date'				=>  date('Y-m-d',strtotime($this->input->post('payment_date'))),
			'bank_name'			=>  $this->input->post('bank_name'),
			'amount'			=>  $this->input->post('payment_amount'),
			'payment_type'		=>  $this->input->post('payment_type'),
			'cheque'			=>  $this->input->post('cheque_file'),
		    'remarks'			=>  $this->input->post('remarks'),
		);
		$this->db->where('receipt_payment_detail_id', $id);
		return $this->db->update('receipt_payment_detail', $data); 
	}
	
	
	public function removeDetail($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('receipt_detail_id', $id);
		return $this->db->update('receipt_detail', $data); 	
	}

	public function removePaymentDetail($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('receipt_payment_detail_id', $id);
		return $this->db->update('receipt_payment_detail', $data); 	
	}
	
	
	
	public function getreceiptno() {
		// 2017/900001
		// 15/00001
		$latest_no = $this->getLatestreceiptno();
		if($latest_no) {
			$year = substr($latest_no, 0, 4);
			$no = substr($latest_no, 5);
			$no_arr = explode('-', $no);
			if($year == date('y')) {
				return date('y') . '/9' . str_pad($no_arr[0] + 1, 4, "0", STR_PAD_LEFT); 
			}
			else {
				return date('y') . '/9' . str_pad(1, 4, "0", STR_PAD_LEFT);
			}
		}
		else {
			return date('y') . '/9' . str_pad(1, 4, "0", STR_PAD_LEFT);
		}
	}

	public function getLatestreceiptno() {
		$this->db->where('status !=', 1);
		$this->db->order_by("receipt_id", "desc");
		$this->db->limit(1);
		$query = $this->db->get('receipt');
		$result = $query->row_array();
		return ($result) ? $result['receipt_no'] : '';
	}

	public function getreceiptinfo($id) {
		// $this->db->select("i.*, q.quotation_id, q.quotation_no ,q.job_title, c.client_id, c.company as client, c.contact, c.designation, c.department, CONCAT_WS (' ', c.address_1, c.address_2, c.postal_code) as address, u.name as rep", false);
		$this->db->select("i.*,c.case_no", false);
		$this->db->from('receipt i');
        $this->db->join('case_submission c', 'c.case_id = i.transaction_id', 'left');
        //$this->db->join('user u', 'u.user_id = q.sale_person_id', 'left');
		$this->db->where('i.status !=', 1);
		$this->db->where('i.receipt_id', $id);
		$query = $this->db->get();	
		return $query->row_array();
	}

	public function getClientinfo($client_id) {
		$this->db->select("c.client_id, c.company, c.contact, c.designation, c.department, CONCAT_WS (' ', c.address_1, c.address_2, c.postal_code) as address", false);
		$this->db->from('client c');
		$this->db->where('c.client_id', $client_id);
		$query = $this->db->get();
		return $query->row_array();	
	}


}