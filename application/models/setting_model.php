<?php
class setting_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_setting() {
		$query = $this->db->get_where('setting');
		return $query->row_array();
	}

	public function update_setting() {
		$data = array(
			'default_gst'		=> $this->input->post('default_gst'),
			'default_mf'		=> $this->input->post('default_mf'),
			'email'				=> $this->input->post('email'),
			'quotation_note'	=> $this->input->post('quotation_note'),
			'invoice_note' 		=> $this->input->post('invoice_note'),
			'po_note'			=> $this->input->post('po_note'),
			'do_note'			=> $this->input->post('do_note'),
		);
		return $this->db->update('setting', $data);
	}

	public function getGst() {
		$setting = $this->get_setting();
		return $setting['default_gst'];	
	}

	public function getMf() {
		$setting = $this->get_setting();
		return $setting['default_mf'];	
	}


	public function getQuotationnote() {
		$setting = $this->get_setting();
		return $setting['quotation_note'];
	}

	public function getInvoicenote() {
		$setting = $this->get_setting();
		return $setting['invoice_note'];	
	}

	public function getPOnote() {
		$setting = $this->get_setting();
		return $setting['po_note'];	
	}

	public function getDonote() {
		$setting = $this->get_setting();
		return $setting['do_note'];	
	}

	public function getEmails() {
		$setting = $this->get_setting();
		return $setting['email'];	
	}
	
}