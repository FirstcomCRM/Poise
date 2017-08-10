<?php
class permission extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('permission_model');
		$this->load->model('role_model');
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->library('ControllerList');
		checkPermission();
	}

	public function index($dt = FALSE)	{	
		if($dt === FALSE ) { 									// First time load
			$data['msg'] = $this->session->flashdata('msg');
			$data['nav_area'] = 'permission';

			$this->load->view('template/header', $data);
			$this->load->view('permission/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->permission_model->getdtPermission();
		}
	}

	public function create() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('role_id', 'Role', 'required');

		$data['controllers'] = $this->controllerlist->getControllers(); //$this->pv_rearrangeControllers($this->controllerlist->getControllers());
		$data['roles']	= $this->role_model->getpermRoles();
		//print_out($data['controllers']);exit();

		if($this->form_validation->run() === FALSE) {
			$data['action'] = 'new';
			$data['nav_area'] = 'user';
			$this->load->view('template/header', $data);
			$this->load->view('permission/edit', $data);
			$this->load->view('template/footer', $data);
		}
		else{	
			$role_id = $this->permission_model->add_permission();
			if($role_id) {
				$role = $this->role_model->get_roles($role_id);
				logActivity('Created', "Created Permission for Role : " . $role['name'], $role_id);
				$this->session->set_flashdata('msg', 'Permission Successfully Created');
				redirect(base_url().'permission');
			}
		}		
	}

	public function edit($role_id) {
		$data['permission'] = $this->permission_model->getPermisssionbyrole($role_id);
		if (empty($data['permission'])) {
			show_404();
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('role_id', 'Role', 'required');

		$data['role_id'] = $role_id;
		$data['controllers'] = $this->controllerlist->getControllers();  //$data['controllers'] = $this->pv_rearrangeControllers($this->controllerlist->getControllers());
		$new_roles = $this->role_model->getpermRoles();
		$old_roles = $this->role_model->get_roles($data['permission'][0]['role_id']);
		array_unshift($new_roles, $old_roles);
		$data['roles'] = $new_roles;

		if($this->form_validation->run() === FALSE) {
			$data['action'] = 'edit';
			$data['nav_area'] = 'user';
			$this->load->view('template/header', $data);
			$this->load->view('permission/edit', $data);
			$this->load->view('template/footer', $data);	
		} 
		else {
			$updated = $this->permission_model->update_permission($role_id);
			if($updated) {
				$role = $this->role_model->get_roles($role_id);
				logActivity('Updated', "Updated Permission for Role : " . $role['name'], $role_id);
				$this->session->set_flashdata('msg', 'Permission Successfully Updated');
				redirect(base_url().'permission');
			}
		}
	}

	public function delete($permission_id) {
		$permission = $this->permission_model->get_permissions($permission_id);
		$deleted = $this->permission_model->remove_permission($permission_id);
		if($deleted) {
			$role = $this->role_model->get_roles($permission['role_id']);
			logActivity('Deleted', "Deleted Permission for Role : " . $role['name'], $permission_id);
			$ret = array(
				'status' => 'success',
				'msg'	 => 'Permission Successfully Deleted',
			);
		}
		else {
			$ret = array(
				'status' => 'fail',
				'msg'	 => 'Error in Permission Delete',
			);
		}
		
		echo json_encode($ret);
	}

	public function view($role_id) {
		$data['role'] = $this->role_model->getRolename($role_id);
		$data['permission'] = $this->permission_model->getPermisssionbyrole($role_id);
		if (empty($data['permission'])) {
			show_404();
		}
		$this->load->view('permission/view', $data);
	}

	public function pv_rearrangeControllers($controllerList) {
		$newList = array(
			'program'		=> $controllerList['program'],
			'pricing'		=> $controllerList['pricing'],
			'registration'	=> $controllerList['registration'],
			'announcement'	=> $controllerList['announcement'],
			'payment'		=> $controllerList['payment'],
			'refund'		=> $controllerList['refund'],
			'student'		=> $controllerList['student'],
			'lecturer'		=> $controllerList['lecturer'],
			'lecturermc'	=> $controllerList['lecturermc'],
			'admin'			=> $controllerList['admin'],
			'role'			=> $controllerList['role'],
			'permission'	=> $controllerList['permission'],
			'level'			=> $controllerList['level'],
			'subject'		=> $controllerList['subject'],
			'lesson'		=> $controllerList['lesson'],
			'classroom'		=> $controllerList['classroom'],
			'dashboard'		=> $controllerList['dashboard'],
			'report'		=> $controllerList['report'],
			'holiday'		=> $controllerList['holiday'],
			'setting'		=> $controllerList['setting'],
		);
		return $newList;

	}

}	
