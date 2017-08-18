<?php
class invoice_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_invoices($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('invoice');
			return $query->result_array();
		}

		$query = $this->db->get_where('invoice', array('invoice_id' => $id));
		return $query->row_array();
	}

	public function add_invoice() {
	//	$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$invoice_date = ($this->input->post('invoice_date')) ? get_timestamp($this->input->post('invoice_date'), '/') : 0;
		$data = array(
		/* 
			hid_submitted       : $('#hid-submitted').val(),
		    invoice_no          : $('#invoice-no').val(),
		    invoice_date        : $('#invoice-date').val(),
		    transaction_id      : $('#transaction-id').val(),
		    entry_no       		: $('#entry-no').val(),   
		    delivery_to         : $('#delivery-to').val(),
		    address           	: $('#address').val(),
		    custmomer_no        : $('#customer-no').val(),
		    sub_total           : $('#sub-total').val(),
		    associate      		: $('#associate').val(),
		    detail_info         : arr,
		 */
			'invoice_no'			=> $this->input->post('invoice_no'),
			'invoice_date'			=> $this->input->post('invoice_date'),
			'transaction_id' 	  	=> $this->input->post('transact_id'),
			'entry_no'				=> $this->input->post('entry_no'),
			'deliver_to'			=> $this->input->post('deliver_to'),
			'address'				=> $this->input->post('address'),
			'attention'				=> $this->input->post('attention'),
			'customer_no'			=> $this->input->post('customer_no'),
			'sub_total'				=> $this->input->post('sub_total'),
			'gst'					=> $this->input->post('gst'),
			'add_gst'				=> $this->input->post('add_gst'),
			'discount'				=> $this->input->post('discount'),
			'associate' 			=> $this->input->post('associate'),
			
			// 'commission'			=> $this->input->post('commission'),
			// 'mf'					=> $this->input->post('mf'),
		);
		$this->db->insert('invoice', $data);
		$invoice_id = $this->db->insert_id();
		if($invoice_id) {
			$this->addDetails($invoice_id);	
			$this->addPaymentDetails($invoice_id);	
		}
		return $invoice_id;
	}

	public function addDetails($invoice_id) {
		$details = $this->input->post('detail_info');
		if($details) {
			foreach($details as $detail) {
				//$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'invoice_id'	 	=> $invoice_id,
					'service'		=> $detail['service_id'],
					'description'		=> $detail['description'],
					'qty'				=> $detail['qty'],
					'amount'			=> $detail['amount'],
				);
				$this->db->insert('invoice_detail', $data);
			}	
		}
	}

	
	public function addPaymentDetails($invoice_id) {
		$payment_details = $this->input->post('payment_detail_info');
		if($payment_details) {
			foreach($payment_details as $payment_detail) {
				//$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'invoice_id'	=> $invoice_id,
					'date'			=> $payment_detail['payment_date'],
					'bank_name'		=> $payment_detail['bank_name'],
					'cheque_no'		=> $payment_detail['cheque_no'],
					'amount'		=> $payment_detail['payment_amount'],
					'payment_type'	=> $payment_detail['payment_type'],
					'cheque'		=> $payment_detail['cheque_file'],
					'remarks'		=> $payment_detail['remarks'],
					
		
					
				);
				$this->db->insert('invoice_payment_detail', $data);
			}	
		}
	}
	
	
	
	public function update_invoice($id) { 
		//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$invoice_date = ($this->input->post('invoice_date')) ? get_timestamp($this->input->post('invoice_date'), '/') : 0;
		$data = array(
			'invoice_no'			=> $this->input->post('invoice_no'),
			'invoice_date'			=> date('Y-m-d',strtotime($this->input->post('invoice_date'))),
			'transaction_id' 	  	=> $this->input->post('transact_id'),
			'entry_no'				=> $this->input->post('entry_no'),
			'deliver_to'			=> $this->input->post('deliver_to'),
			'address'				=> $this->input->post('address'),
			'attention'				=> $this->input->post('attention'),
			'customer_no'			=> $this->input->post('customer_no'),
			'sub_total'				=> $this->input->post('sub_total'),
			'gst'					=> $this->input->post('gst'),
			'add_gst'				=> $this->input->post('add_gst'),
			'discount'				=> $this->input->post('discount'),
			'total'					=> $this->input->post('total'),
			'associate' 			=> $this->input->post('associate'),
			
			
			// 'commission'			=> $this->input->post('commission'),
			// 'mf'					=> $this->input->post('mf'),
		);
		
		$has_cheque = $this->checkPaymentDetails($id);
		if($has_cheque) {
			//$this->addDetails($invoice_id);	
			$this->pv_sendEmail($has_cheque);	
		}
		
		
		$this->db->where('invoice_id', $id);
		return $this->db->update('invoice', $data);
		
	}

	
	public function checkPaymentDetails($invoice_id){
		
		
		$this->db->select('pd.invoice_payment_detail_id');
		$this->db->from('invoice_payment_detail pd');
		$this->db->where('pd.invoice_id', $invoice_id);
		$this->db->where('pd.status !=', 1);
		$this->db->order_by('pd.invoice_payment_detail_id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();	
	}
	
	
	
	
	public function delete_invoice($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('invoice_id', $id);
		return $this->db->update('invoice', $data);
	}	

	public function getDetilsbyinvoiceid($invoice_id) {
		$this->db->select('d.*');
		$this->db->from('invoice_detail d');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('d.invoice_id', $invoice_id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}

	public function getPaymentDetailsbyinvoiceid($invoice_id) {
		$this->db->select('pd.*');
		$this->db->from('invoice_payment_detail pd');
		$this->db->where('pd.invoice_id', $invoice_id);
		$this->db->where('pd.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}
	
	public function getInvoice_PD_info($detail_id) {
		$this->db->select('pd.*');
		$this->db->from('invoice_payment_detail pd');
		$this->db->where('pd.invoice_payment_detail_id', $detail_id);
		$this->db->where('pd.status !=', 1);
		$this->db->order_by('pd.invoice_payment_detail_id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();	
	}
	
	
	/* public function getdtinvoices() {
		if( $this->input->post('invoice_no') && ( $this->input->post('invoice_no') != '' ) ) {
			$this->datatables->filter('i.invoice_no LIKE "%' . $this->input->post('invoice_no') . '%"');
		}
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = get_earliesttimestamp($this->input->post('start_date'), '-');
        	$this->datatables->filter('i.invoice_date >=', $start_date);
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date = get_latesttimestamp($this->input->post('end_date'), '-');
        	$this->datatables->filter('i.invoice_date <=', $end_date);
        }
  		// if( $this->input->post('project_no') && ( $this->input->post('project_no') != '' ) ) {
  		//  	$this->datatables->filter('p.project_no LIKE "%' . $this->input->post('project_no') . '%"');
		// }
		// if( $this->input->post('client_id') && ( $this->input->post('client_id') != '' ) ) {
		// 	$this->datatables->filter('q.client_id', $this->input->post('client_id') );
		// }
		if( $this->input->post('invoice_status') && ( $this->input->post('invoice_status') != '' ) ) {
			$this->datatables->filter('i.invoice_status', $this->input->post('invoice_status') );
		}

		$role_id = $this->session->userdata('role_id');
		if($role_id != 1) {
			$this->datatables->where('q.sale_person_id', $this->session->userdata('user_id'));
		}
		
        $this->datatables->select("i.invoice_id, i.invoice_no, q.quotation_no, q.job_title, i.po_no, FROM_UNIXTIME(i.invoice_date, '%Y-%m-%d') as invoice_date, IF(i.delivery_date = '0', '-', FROM_UNIXTIME(i.delivery_date, '%Y-%m-%d') ) as delivery_date, IF(i.invoice_date = '0', '-', FROM_UNIXTIME(i.invoice_date, '%Y-%m-%d') ) as invoice_date, i.sub_total, TRUNCATE( i.total - i.sub_total, 2) as gst, i.total, i.invoice_status, c.company as client,u.username as user_name", false);
        $this->datatables->from('invoice i');
        $this->datatables->join('quotation q', 'q.quotation_id = i.quotation_id', 'left');
        $this->datatables->join('client c', 'q.client_id = c.client_id', 'left');
        $this->datatables->join('user u', 'u.user_id = q.sale_person_id', 'left');
       
		$this->datatables->where('i.status !=', 1);
		$this->datatables->add_column('no', '');
		$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="invoice/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="print-link" data-toggle="tooltip" data-placement="top" title="Print" href="invoice/printInvoice/$1"><i class="fa fa-print ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="invoice/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="invoice/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'invoice_id');
		echo $this->datatables->generate();
	}

	 */
	public function getdtinvoices() {
		if( $this->input->post('invoice_no') && ( $this->input->post('invoice_no') != '' ) ) {
			$this->datatables->filter('i.invoice_no LIKE "%' . $this->input->post('invoice_no') . '%"');
		}
		if( $this->input->post('transaction_no') && ( $this->input->post('transaction_no') != '' ) ) {
			$this->datatables->filter('c.case_no LIKE "%' . $this->input->post('transaction_no') . '%"');
		}
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = date('Y-m-d',strtotime($this->input->post('start_date')));
        	$this->datatables->filter('i.invoice_date >=', $start_date);
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date = date('Y-m-d',strtotime($this->input->post('end_date')));
        	$this->datatables->filter('i.invoice_date <=', $end_date);
        } 
  	
		$role_id = $this->session->userdata('role_id');
		if($role_id == 1) {
			//$edit = '/ <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="invoice/edit/$1"><i class="fa fa-edit ico"></i></a>';
			$edit = '<a class="btn btn-mtac admin-control btn-view btn-success" data-toggle="tooltip" data-placement="top" title="Edit" href="invoice/edit/$1">Edit</a>';
		}
		else{
			$edit = '';
		}
        $this->datatables->select("i.invoice_id, i.invoice_no, i.invoice_date, c.case_no, i.deliver_to, i.address, i.sub_total", false);
        $this->datatables->from('invoice i');
        $this->datatables->join('case_submission c', 'c.case_id = i.transaction_id', 'left');
        //$this->datatables->join('client c', 'q.client_id = c.client_id', 'left');
        //$this->datatables->join('user u', 'u.user_id = q.sale_person_id', 'left');
       
		$this->datatables->where('i.status !=', 1);
		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="invoice/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="print-link" data-toggle="tooltip" data-placement="top" title="Print" href="invoice/printInvoice/$1"><i class="fa fa-print ico"></i></a> '.$edit.'/ <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="invoice/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'invoice_id');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="invoice/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="print-link" data-toggle="tooltip" data-placement="top" title="Print" href="invoice/printInvoice/$1"><i class="fa fa-print ico"></i></a> '.$edit.'/<button class="btn btn-block btn-info">Info</button> <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="invoice/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'invoice_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="invoice/view/$1">View</a>'.$edit.'<a class="btn btn-mtac admin-control btn-view btn-warning" data-toggle="tooltip" data-placement="top" title="Print" href="invoice/printInvoice/$1">Print</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1">Delete</a>', 'invoice_id');
		//$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="announcement/view/$1">View</a><a class="btn btn-mtac admin-control btn-view btn-success" data-toggle="tooltip" data-placement="top" title="Edit" href="announcement/edit/$1">Edit</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1">Delete</a>', 'announce_id');
		echo $this->datatables->generate();
	}

	
	public function addDetail() {
		$no = $this->input->post('no');
		$data = array(
			'invoice_id'	 	=>  $this->input->post('hid_invoice_id'),
			'service'			=>  $this->input->post('service_id'),
			'description'		=>  $this->input->post('description'),
			'qty'				=>  $this->input->post('qty'),
			'amount'			=>  $this->input->post('amount'),
			
		);
		$this->db->insert('invoice_detail', $data);
		$detail_id = $this->db->insert_id();
		return $detail_id;	
	}

	
	public function addPaymentDetail() {
		//$no = $this->input->post('no');
		//$payment_date = ($this->input->post('payment_date')) ? get_timestamp($this->input->post('payment_date'), '-') : 0;
		//'date'		=> $this->input->post('payment_date'),
		$data = array(
			'invoice_id'	 	=>  $this->input->post('hid_invoice_id'),
			'date'				=>  date('Y-m-d',strtotime($this->input->post('payment_date'))),
			'cheque_no'			=>  $this->input->post('cheque_no'),
			'bank_name'			=>  $this->input->post('bank_name'),
			'amount'			=>  $this->input->post('payment_amount'),
			'payment_type'		=>  $this->input->post('payment_type'),
			'cheque'			=>  $this->input->post('cheque_file'),
		    'remarks'			=>  $this->input->post('remarks'),
		
		
		);
		$this->db->insert('invoice_payment_detail', $data);
		$payment_detail_id = $this->db->insert_id();
		return $payment_detail_id;	
	}
	
	
	
	
	public function getDetail($id) {
		$this->db->select('d.*');
		$this->db->from('invoice_detail d');
	//	$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('d.invoice_detail_id', $id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get('invoice_detail');
		return $query->row_array();
	}

	
	public function getPaymentDetail($id) {
		$this->db->select('pd.*');
		$this->db->from('invoice_payment_detail pd');
		$this->db->where('pd.invoice_payment_detail_id', $id);
		$this->db->where('pd.status !=', 1);
		$query = $this->db->get('invoice_payment_detail');
		return $query->row_array();
	}
	
	
	
	public function updateDetail($id) {
		$no = $this->input->post('no');
		$data = array(
			'invoice_id'	 	=> $this->input->post('hid_invoice_id'),
			'service'	 		=> $this->input->post('service_id'),
			'description'		=> $this->input->post('description'),
			'qty'				=> $this->input->post('qty'),
			'amount'			=> $this->input->post('amount'),
		);	
		$this->db->where('invoice_detail_id', $id);
		return $this->db->update('invoice_detail', $data); 
	}

	public function updatePaymentDetail($id) {
		//$no = $this->input->post('no');
		$data = array(
			'invoice_id'	 	=>  $this->input->post('hid_invoice_id'),
			'date'				=>  date('Y-m-d',strtotime($this->input->post('payment_date'))),
			'cheque_no'			=>  $this->input->post('cheque_no'),
			'bank_name'			=>  $this->input->post('bank_name'),
			'amount'			=>  $this->input->post('payment_amount'),
			'payment_type'		=>  $this->input->post('payment_type'),
			'cheque'			=>  $this->input->post('cheque_file'),
		    'remarks'			=>  $this->input->post('remarks'),
		);
		$this->db->where('invoice_payment_detail_id', $id);
		return $this->db->update('invoice_payment_detail', $data); 
	}
	
	
	public function removeDetail($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('invoice_detail_id', $id);
		return $this->db->update('invoice_detail', $data); 	
	}

	public function removePaymentDetail($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('invoice_payment_detail_id', $id);
		return $this->db->update('invoice_payment_detail', $data); 	
	}
	
	
	
	public function getInvoiceno() {
		// 2017/900001
		// 15/00001
		$latest_no = $this->getLatestinvoiceno();
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

	public function getLatestinvoiceno() {
		$this->db->where('status !=', 1);
		$this->db->order_by("invoice_id", "desc");
		$this->db->limit(1);
		$query = $this->db->get('invoice');
		$result = $query->row_array();
		return ($result) ? $result['invoice_no'] : '';
	}

	public function getInvoiceinfo($id) {
		// $this->db->select("i.*, q.quotation_id, q.quotation_no ,q.job_title, c.client_id, c.company as client, c.contact, c.designation, c.department, CONCAT_WS (' ', c.address_1, c.address_2, c.postal_code) as address, u.name as rep", false);
		$this->db->select("i.*,c.case_no", false);
		$this->db->from('invoice i');
        $this->db->join('case_submission c', 'c.case_id = i.transaction_id', 'left');
        //$this->db->join('user u', 'u.user_id = q.sale_person_id', 'left');
		$this->db->where('i.status !=', 1);
		$this->db->where('i.invoice_id', $id);
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

	
	public function pv_sendEmail($detail_id) {
		$cheque_info = $this->getInvoice_PD_info($detail_id);
		//$recipients = $this->transaction_model->getAdminEmails();
		$message = 	"Dear Sir/Madam, <br/><br/>There is a new Cheque with the following information. <br/><br/>" .
					 "<table cellspacing='0' cellpadding='5' border='1'>" .
					 "<tr>" .
					 	"<td>Cheque No</td><td> : </td><td>" . $cheque_info['invoice_no'] . "</td>" . 
					 "</tr>".
					  "<tr>" .
					 	"<td>Bank Name</td><td> : </td><td>" . $cheque_info['invoice_date'] . "</td>" . 
					 "</tr>".
					 "<tr>" .
					 	"<td>Amount</td><td> : </td><td>" . $cheque_info['entry_no'] . "</td>" . 
					 "</tr>".
					 "</table> <br/><br/>  For More information, Please login to the system. <br/><br/><br/> Thanks";
		/*  "<tr>" .
								"<td>Grand Total</td><td> : </td><td>" . $transaction_info['total'] . "</td>" . 
							 "</tr>". */
				//$emails = $this->transaction_model->getAdminEmails();
				//$emails = explode(', ', $this->transaction_model->getAdminEmails());  		
				//print_r($emails);
			//	echo count($emails);
				//echo $message;
		
		
		$query = $this->db->query("SELECT distinct email FROM users where role_id=1 and status !=1;");

		//Send Email 
		foreach ($query->result_array() as $row)
		{
			//echo $row['email'];
			send_email('POISE CRM', 'test@poise.com.sg',$row['email'], 'New Transaction', $message);
		}
				
	}
	

}