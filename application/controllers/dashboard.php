<?php
class dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('dashboard_model');
		//$this->load->model('client_model');
		$this->load->model('announcement_model');
		$this->load->helper('url');
		$this->load->library('session');
		checkPermission();
	}

	/* Dashboard Index */
	public function index() {
		$permissions = $this->session->userdata('permissions');
		$is_admin = ( $this->session->userdata('role_id') == 1 ) ? TRUE : FALSE;
		//print_out($permissions); exit();
		$data['nav_area'] = 'dashboard';
		$data['announcements'] = $this->announcement_model->get_top_announcements();
	/* 	$data['show_total_quotations'] = ( $is_admin ) ? TRUE : ( (isset($permissions['quotation']) && strpos($permissions['quotation']['permission'], 'index' ) !== false) ? TRUE : FALSE );
		$data['show_total_invoices'] = ( $is_admin ) ? TRUE : ( (isset($permissions['invoice']) && strpos($permissions['invoice']['permission'], 'index' ) !== false) ? TRUE : FALSE );
		$data['show_total_po'] = ( $is_admin ) ? TRUE : ( (isset($permissions['purchase']) && strpos($permissions['purchase']['permission'], 'index' ) !== false) ? TRUE : FALSE );
		$data['show_total_clients'] = ( $is_admin ) ? TRUE : ( (isset($permissions['client']) && strpos($permissions['client']['permission'], 'index' ) !== false) ? TRUE : FALSE );
 */
	/* 	$data['total_quotations'] = $this->dashboard_model->getTotalquotation();
		$data['total_invoices'] = $this->dashboard_model->getTotalinvoices();
		$data['total_po'] = $this->dashboard_model->getTotalPO();
		$data['total_clients'] = $this->dashboard_model->getTotalclient();
 */
		$this->load->view('template/header', $data);
		$this->load->view('dashboard.php', $data);
		$this->load->view('template/footer', $data);
	}

}