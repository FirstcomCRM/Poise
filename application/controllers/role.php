<?php
class role extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('role_model');
		$this->load->library('session');
		$this->load->library("pagination");
		checkPermission();
	}

	public function index($dt = FALSE) {	
		if($dt === FALSE ) { 									// First time load
			$data['nav_area'] = 'role';
			$this->load->view('template/header', $data);
			$this->load->view('role/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->role_model->getdtRoles();	
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
			$role_id = $this->role_model->addRole(); 
			if($role_id) {
				logActivity('Created', "Created New Role : " . $this->input->post('name'), $role_id);
				$ret = array(
					'status' => 'success',
					'msg'	 => 'Role Successfully Added',
				);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Role Create',
				);
			}
		}
		echo json_encode($ret);
	}

	public function aj_edit($id) {
		$role = $this->role_model->get_roles($id);	
		if($role) {
			$role['status'] = 'success';
		}
		else {
			$role = array(
				'status' => 'fail',
				'msg' 	 => 'No Data',
			);
		}
		echo json_encode($role);
	}

	public function edit($id) {
		$data['role'] = $this->role_model->get_roles($id);
		if ( !empty($data['role']) ) {
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
				$updated = $this->role_model->update_role($id);
				if($updated) {
					logActivity('Updated', "Updated Role : " . $this->input->post('name'), $id);
					$ret = array(
						'status' => 'success',
						'msg'	 => 'Role Successfully Updated',
					);
				}
				else {
					$ret = array(
						'status' => 'fail',
						'msg'	 => 'Error in role Update',
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
		$role = $this->role_model->get_roles($id);
		if ( !empty($role) ) {
			if($id == 1) {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Sorry, you cannot delete admin role.',
				);	
			}
			else {
				$deleted = $this->role_model->delete_role($id);	
				if($deleted) {
					logActivity('Deleted', "Deleted Role : " . $role['name'], $id);
					$ret = array(
						'status' => 'success',
						'msg'	 => 'Role Successfully Deleted',
					);	
				}
				else {
					$ret = array(
						'status' => 'fail',
						'msg'	 => 'Error in Role Delete',
					);
				}
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

}