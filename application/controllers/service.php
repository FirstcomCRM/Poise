<?php
class service extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('service_model');
		$this->load->library('session');
		$this->load->library("pagination");
		checkPermission();
	}

	public function index($dt = FALSE) {	
		if($dt === FALSE ) { 									// First time load
			$data['nav_area'] = 'service';
			$this->load->view('template/header', $data);
			$this->load->view('service/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->service_model->getdtServices();	
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
			$service_id = $this->service_model->add_service(); 
			if($service_id) {
				logActivity('Created', "Created New Service : " . $this->input->post('name'), $service_id);
				$ret = array(
					'status' => 'success',
					'msg'	 => 'Service Successfully Added',
				);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Service Create',
				);
			}
		}
		echo json_encode($ret);
	}

	public function aj_edit($id) {
		$service = $this->service_model->get_services($id);	
		if($service) {
			$service['status'] = 'success';
		}
		else {
			$service = array(
				'status' => 'fail',
				'msg' 	 => 'No Data',
			);
		}
		echo json_encode($service);
	}

	public function edit($id) {
		$data['service'] = $this->service_model->get_services($id);
		if ( !empty($data['service']) ) {
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
				$updated = $this->service_model->update_service($id);
				if($updated) {
					logActivity('Updated', "Updated Service : " . $this->input->post('name'), $id);
					$ret = array(
						'status' => 'success',
						'msg'	 => 'Service Successfully Updated',
					);
				}
				else {
					$ret = array(
						'status' => 'fail',
						'msg'	 => 'Error in service Update',
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
		$service = $this->service_model->get_services($id);
		if ( !empty($service) ) {
			$deleted = $this->service_model->delete_service($id);	
			if($deleted) {
				logActivity('Deleted', "Deleted Service : " . $service['name'], $id);
				$ret = array(
					'status' => 'success',
					'msg'	 => 'Service Successfully Deleted',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Service Delete',
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

	public function aj_getServices() {
		$q = $this->input->get('q'); 
		$result = $this->service_model->getService($q['term']);
		$ret = array(); 
	 	if ( !empty($result) ) {
	 		foreach($result as $res) {
	 			$ret[] = array(
	 				"id" 	=> $res['service_id'], 
	 				"text"	=> $res['name'],
	 			);
	 		}
	 	}	
	 	else {
	 		$ret[] = array("id"=>"0", "text"=>"No Results Found..");
	 	}
	 	echo json_encode($ret);
	}

	public function aj_getDescription($service_id) {
		$service = $this->service_model->get_services($service_id);
		if( !empty($service) ) {
			$ret = array(
				'status' 		=> 'success',
				'description'	=> $service['name'] . "\n" . $service['description']
			);
		}
		else {
			$ret = array(
				'status' 		=> 'fail',
				'msg'			=> 'No Service'
			);	
		}
		echo json_encode($ret);
	}

}