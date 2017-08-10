<?php
class dashboard_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function getTotalquotation() {
		$this->db->where('status !=', 1);
		$this->db->where('quotation_status !=', 'Cancelled');
		$this->db->from('quotation');
		$role_id = $this->session->userdata('role_id');
		if($role_id != 1) {
			$this->db->where('sale_person_id', $this->session->userdata('user_id'));
		}
		return $this->db->count_all_results();
	}

	public function getTotalinvoices() {
		$this->db->where('i.status !=', 1);
		$this->db->where('i.invoice_status !=', 'Cancelled');
		$this->db->from('invoice i');
		$this->db->join('quotation q', 'q.quotation_id = i.quotation_id', 'left');
		$role_id = $this->session->userdata('role_id');
		if($role_id != 1) {
			$this->db->where('q.sale_person_id', $this->session->userdata('user_id'));
		}
		return $this->db->count_all_results();
	}

	public function getTotalPO() {
		$this->db->where('p.status !=', 1);
		$this->db->where('p.purchase_status !=', 'Cancelled');
		$this->db->from('purchase p');
		$this->db->join('quotation q', 'q.quotation_id = p.quotation_id', 'left');
		$role_id = $this->session->userdata('role_id');
		if($role_id != 1) {
			$this->db->where('q.sale_person_id', $this->session->userdata('user_id'));
		}
		return $this->db->count_all_results();
	}

	public function getTotalclient() {
		$this->db->where('status !=', 1);
		$this->db->from('client');
		return $this->db->count_all_results();
	}

	// public function getCurrentmonthtotalorder() {
	// 	$start_date = get_earliest_currentmonth_timestamp();
	// 	$end_date = get_latest_currentmonth_timestamp();

 //        $this->db->where('date >=', $start_date);
 //        $this->db->where('date <=', $end_date);
 //        $this->db->where('status !=', 1);
	// 	$this->db->from('order');
	// 	return $this->db->count_all_results();
	// }

	// public function getCurrentmonthdo() {
	// 	$start_date = get_earliest_currentmonth_timestamp();
	// 	$end_date = get_latest_currentmonth_timestamp();

 //        $this->db->where('date >=', $start_date);
 //        $this->db->where('date <=', $end_date);
 //        $this->db->where('status !=', 1);
	// 	$this->db->from('delivery_order');
	// 	return $this->db->count_all_results();
	// }

	

	/**
	 * Get total order by date (m-d-Y)
	 */
	// public function getTotalorderbydate($date) {
	// 	$start_date = get_earliesttimestamp($date, '-');
	// 	$end_date = get_latesttimestamp($date, '-');
 //        $this->db->where('date >=', $start_date);
 //        $this->db->where('date <=', $end_date);
 //        $this->db->where('status !=', 1);
	// 	$this->db->from('order');
	// 	return $this->db->count_all_results();
	// }

}