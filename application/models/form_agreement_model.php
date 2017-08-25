<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class form_agreement_model extends CI_Model {
	// private $file = 'files';  
	public function __construct() {
		$this->load->database();
	}

	public function get_forms($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('forms');
			return $query->result_array();
		}

		$query = $this->db->get_where('forms', array('form_id' => $id));
		return $query->row_array();
	}

	
	
	
	
	
	public function add_form() {
		//$form_agreement_date = get_timestamp('d/m/Y', '/');
		
	 	
		
		
		$data = array(
			'form_name'				=> $this->input->post('form_title'),
			'category_id'			=> $this->input->post('category_id'),
			//'announce_date'		=> date('Y-m-d',strtotime($this->input->post('form_agreement_date'))),
			//'form_agreement_date'					=> $this->input->post('terms'),
		);
		$this->db->insert('forms', $data);
		$form_id = $this->db->insert_id();
		if($form_id) {
			$this->add_files($form_id);	
		}
		return $form_id;
	}
	
	public function add_files($form_id) {
		$details = $this->input->post('files_info');
	
		if($details) {
			foreach($details as $detail) {
				//$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'form_id'	 	=> $form_id,
					'file_name'			=> $detail['file_name'],
					'new_file_name'		=> $detail['new_file_name'],
					'file_path'			=> $detail['file_path'],
					'date_uploaded'		=> date('Y-m-d'),
					//'file_name'		=> $this->input->post('name'),
				);
				$this->db->insert('form_files', $data);
			}	
		}
	}
	
	
	
	
	
	
	public function update_form_agreement($id) { 
		//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$form_agreement_date = ($this->input->post('form_agreement_date')) ? get_timestamp($this->input->post('form_agreement_date'), '/') : 0;
		$data = array(
			'form_name'				=> $this->input->post('form_title'),
			'category_id'			=> $this->input->post('category_id'),
			// 'mf'					=> $this->input->post('mf'),
		);
		$this->db->where('form_id', $id);
		return $this->db->update('forms', $data); 
	}

	public function delete_form_agreement($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('form_id', $id);
		return $this->db->update('forms', $data);
	}	

	
	
	
	
	public function getdtforms() {
		 if( $this->input->post('form_title') && ( $this->input->post('form_title') != '' ) ) {
			$this->datatables->filter('f.form_name LIKE "%' . $this->input->post('form_title') . '%"');
		}
		if( $this->input->post('category_id') && ( $this->input->post('category_id') != '' ) ) {
			$this->datatables->filter('f.category_id LIKE "%' . $this->input->post('category_id') . '%"');
		}
	

		$role_id = $this->session->userdata('role_id');
		if($role_id == 1) {
			$edit= '<a class="btn btn-mtac admin-control btn-view btn-success" data-toggle="tooltip" data-placement="top" title="Edit" href="form_agreement/edit/$1">Edit</a>';
		} 
		else{
			$edit ='';
		}
		
        $this->datatables->select("f.form_id,f.form_name,c.name", false);
        $this->datatables->from('forms f');
		$this->datatables->join('form_category c', 'c.form_category_id = f.category_id', 'left');
		$this->datatables->where('f.status !=', 1);
		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="view-link btn btn-mtac admin-control btn-view" data-toggle="tooltip" data-placement="top" title="View" href="form_agreement/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="print-link" data-toggle="tooltip" data-placement="top" title="Print" href="form_agreement/printform_agreement/$1"><i class="fa fa-print ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="form_agreement/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="form_agreement/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'announce_id');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="form_agreement/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="form_agreement/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="form_agreement/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'announce_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="form_agreement/view/$1">View</a>'.$edit.'<a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="form_agreement/delete/$1">Delete</a>', 'form_id');
		//$this->datatables->add_column('action', '<a class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="View" href="form_agreement/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="form_agreement/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="form_agreement/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'announce_id');
		//$this->datatables->add_column('action', '<a class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="View" href="property/view/$1"><i class="fa fa-save ico-btn"></i>View</a><a class="btn btn-mtac admin-control btn-approve" data-toggle="tooltip" data-placement="top" title="Approve" href="property/edit_status/$1"><i class="fa fa-save ico-btn"></i>Approve</a><a class="btn btn-mtac admin-control btn-reject" data-toggle="tooltip" data-placement="top" title="Reject" href="property/edit_status/$1"><i class="fa fa-save ico-btn"></i>Reject</a>', 'property_id');
		echo $this->datatables->generate();
	}


	
	
	public function addFile() {
		//$no = $this->input->post('no');
		$data = array(
			'form_id'	 			=>  $this->input->post('hid_form_id'),
			'file_name'					=>  $this->input->post('file_name'),
			'new_file_name'				=>  $this->input->post('new_file_name'),
			'file_path'					=>  $this->input->post('file_path'),
			'date_uploaded'				=>  date('Y-m-d'),
			
		);
		$this->db->insert('form_files', $data);
		$detail_id = $this->db->insert_id();
		return $detail_id;	
	}
	
	
	
	public function removeFile($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('announce_file_id', $id);
		return $this->db->update('announcement_files', $data); 	
	}
	
	
	public function getFile($id) {
		$this->db->select('f.*');
		$this->db->from('form_files f');
	//	$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('f.form_file_id', $id);
		$this->db->where('f.status !=', 1);
		$query = $this->db->get('form_files');
		return $query->row_array();
	}
	
	
	
	public function getFilesbyformid($form_id) {
		$this->db->select('f.*');
		$this->db->from('form_files f');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		
		$this->db->where('f.form_id', $form_id);
		$this->db->where('f.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}
	

	public function getform_agreementinfo($id) {
		// $this->db->select("i.*, q.quotation_id, q.quotation_no ,q.job_title, c.client_id, c.company as client, c.contact, c.designation, c.department, CONCAT_WS (' ', c.address_1, c.address_2, c.postal_code) as address, u.name as rep", false);
		$this->db->select("a.*,c.name", false);
		$this->db->from('forms a');
		$this->db->join('form_category c', 'c.form_category_id = a.category_id', 'left');
		$this->db->where('a.status !=', 1);
		$this->db->where('a.form_id', $id);
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