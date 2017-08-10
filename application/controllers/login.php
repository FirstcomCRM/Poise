<?php
class login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('login_model');
		if( $this->session->userdata('validate_user') ) {
			redirect('/dashboard');
		}
	}


	public function index() {
		//Check Already login or not 
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$data = "";

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('login', $data);
		}
		else {
	        // Validate the user can login
	        $result = $this->login_model->admin_validate();
	        // Now we verify the result
	        if(! $result){
	            // If user did not validate, then show them login page again
	           $data['invalid'] = 1;
	            $this->form_validation->set_message('username', 'Username or password wrong.');
	            $this->load->view('login', $data);
	        }else{
	        	logActivity('Logged-in', "Logged-in", '');
	           	redirect('dashboard');	            
	       }       			
		}
	}

}