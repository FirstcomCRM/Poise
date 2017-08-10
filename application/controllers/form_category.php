<?php
class form_category extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('form_category_model');
		$this->load->library('session');
		$this->load->library("pagination");
		checkPermission();
	}

	public function index($dt = FALSE) {	
		if($dt === FALSE ) { 									// First time load
			$data['nav_area'] = 'form_category';
			$this->load->view('template/header', $data);
			$this->load->view('form_category/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->form_category_model->getdtform_categories();	
		}
	}

	public function create() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'required');

		if($this->form_validation->run() === FALSE) {
			$ret = array(
				'status' => 'fail',
				'msg'	 => validation_errors(),
			);	
		}
		else {
			$form_category_id = $this->form_category_model->add_form_category(); 
			if($form_category_id) {
				logActivity('Created', "Created New form_category : " . $this->input->post('name'), $form_category_id);
				$ret = array(
					'status' => 'success',
					'msg'	 => 'Job Title Successfully Added',
				);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Job Title Create',
				);
			}
		}
		echo json_encode($ret);
	}

	public function aj_edit($id) {
		$form_category = $this->form_category_model->get_form_categories($id);	
		if($form_category) {
			$form_category['status'] = 'success';
		}
		else {
			$form_category = array(
				'status' => 'fail',
				'msg' 	 => 'No Data',
			);
		}
		echo json_encode($form_category);
	}

	public function edit($id) {
		$data['form_category'] = $this->form_category_model->get_form_categories($id);
		if ( !empty($data['form_category']) ) {
			$this->load->helper('form');
			$this->load->library('form_validation');

			$this->form_validation->set_rules('name', 'Name', 'required');

			if($this->form_validation->run() === FALSE) {	
				$ret = array(
					'status' => 'fail',
					'msg'	 => validation_errors(),
				);		
			}
			else {
				$updated = $this->form_category_model->update_form_category($id);
				if($updated) {
					logActivity('Updated', "Updated form_category : " . $this->input->post('name'), $id);
					$ret = array(
						'status' => 'success',
						'msg'	 => 'Job Title Successfully Updated',
					);
				}
				else {
					$ret = array(
						'status' => 'fail',
						'msg'	 => 'Error in job title Update',
					);
				}
				
			}
		}
		else {
			$ret = array(
				'status' => 'fail',
				'msg'	 => 'No data',
			);	
		}
		echo json_encode($ret);
	}

	public function delete($id) {
		$form_category = $this->form_category_model->get_form_categories($id);
		if ( !empty($form_category) ) {
			$deleted = $this->form_category_model->delete_form_category($id);	
			if($deleted) {
				logActivity('Deleted', "Deleted form_category : " . $form_category['name'], $id);
				$ret = array(
					'status' => 'success',
					'msg'	 => 'Job Title Successfully Deleted',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Job Title Delete',
				);
			}
		}
		else {
			$ret = array(
				'status' => 'fail',
				'msg'	 => 'No Data',
			);
		}
		echo json_encode($ret);
	}

	public function aj_getform_category() {
	    $term = $this->input->get('term');
	    if (isset($term)){
	      $q = $term; //strtolower($term);
	      $this->form_category_model->getTitles($q);
	    }
	}

}