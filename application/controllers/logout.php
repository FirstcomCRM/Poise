<?php
class logout extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
	}

	public function index() {
		logActivity('Logged-out', "Logged-out", '');
		$this->session->sess_destroy();	
		redirect('/login');
	}

}