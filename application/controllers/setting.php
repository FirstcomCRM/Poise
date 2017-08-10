<?php
class setting extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('setting_model');
		$this->load->library('session');
		checkPermission();
	}

	public function edit($dt = FALSE)	{	
		$data['msg'] = $this->session->flashdata('msg');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('hid_submit', 'Submit', 'required');

		if($this->form_validation->run() === FALSE) {
			$data['action'] = 'edit';
			$data['nav_area'] = 'setting';
			$data['setting'] = $this->setting_model->get_setting(); 
			$this->load->view('template/header', $data);
			$this->load->view('setting/edit', $data);
			$this->load->view('template/footer', $data);
		}
		else{
			$updated = $this->setting_model->update_setting();
			if($updated) {
				logActivity('Updated', "Updated System Setting", '');
				$this->session->set_flashdata('msg', 'System Setting Successfully Updated');
				redirect(base_url().'setting/edit');
			}
		}
	}	

}