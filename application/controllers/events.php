<?php
class events extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->model('events_model');
	/* 	$this->load->model('service_model');
		$this->load->model('production_model');
		$this->load->model('uom_model');
		$this->load->model('client_model');
		$this->load->model('quotation_model'); */
		//$this->load->model('setting_model');
		$this->load->model('user_model');
		checkPermission();
	}

	public function index($dt = FALSE)	{	
		if($dt === FALSE ) { 									// First time load
			$data['msg'] = $this->session->flashdata('msg');
			$data['nav_area'] = 'events';

			$this->load->view('template/header', $data);
			$this->load->view('events/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
		//	$this->load->library("Datatables");
			$this->events_model->getcalendar();	
		}
	}

	public function view($id) {
		$data['events'] = $this->events_model->geteventsinfo($id); 
		if (empty($data['events'])) {
			show_404();
		}
		//$data['details'] =  $this->events_model->getDetailsbyeventsid($id);
		$this->load->view('events/view', $data);
	}

	public function create($submit = FALSE) {
		$this->load->helper('form');
		$this->load->library('form_validation');

	//	$this->form_validation->set_rules('events_no', 'events No', 'required');
		$this->form_validation->set_rules('events_body', 'events', 'required');
		//$this->form_validation->set_rules('quotation_id', 'Quotation', 'required');
		
		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'new';
				$data['nav_area'] = 'events';
		
				$this->load->view('template/header', $data);
				$this->load->view('events/edit', $data);
				$this->load->view('template/footer', $data);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => validation_errors(),
				);	
				echo json_encode($ret);
			}
		}
		else {
			$events_id = $this->events_model->add_events();
			if($events_id) {
				//logActivity('Created', "Created New events  : " . $this->input->post('events_no'), $events_id);
				$this->session->set_flashdata('msg', 'events Successfully Created');
				$ret = array(
					'status' => 'success',
				);	
				//$this->pv_sendEmail($events_id);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in events Add'
				);
			}
			echo json_encode($ret);	
		}
	}


	public function edit($id,$submit = FALSE) {
		$data['events'] = $this->events_model->geteventsinfo($id); 
		if (empty($data['events'])) {
			show_404();
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('events_body', 'events', 'required');

		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'edit';
				$data['nav_area'] = 'events';
				
		
				$this->load->view('template/header', $data);
				$this->load->view('events/edit', $data);
				$this->load->view('template/footer', $data);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => validation_errors(),
				);	
				echo json_encode($ret);
			}
		} 
		else {
			$updated = $this->events_model->update_events($id);
			if($updated) {
				logActivity('Updated', "Updated New events : " . $this->input->post('events_no'), $id);
				$this->session->set_flashdata('msg', 'events Successfully Updated');
				$ret = array(
					'status' => 'success',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in events Update'
				);
			}	
			echo json_encode($ret);
		}
	}

	public function delete($id) {
		$events = $this->events_model->get_eventss($id);
		if ( !empty($events) ) {
			$deleted = $this->events_model->delete_events($id);  
		    if($deleted) {
		    	//logActivity('Deleted', "Deleted events : " . $events['events_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> 'events Successfully Deleted',
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in events Delete',
			    );
		    }
		}
		else {
	    	$ret = array(
		      'status' 	=> 'fail',
		      'msg'  	=> 'No data',
		    );			
		}
	    echo json_encode($ret);
	}


	
		
		
		public function getEvents()
		{
			$result=$this->events_model->getEvents();
			echo json_encode($result);
		}
	
		public function addEvent()
		{
			$result=$this->events_model->addEvent();
			echo $result;
		}
		
		public function updateEvent()
		{
			$result=$this->events_model->updateEvent();
			echo $result;
		} 
		
		public function deleteEvent()
		{
			$result=$this->events_model->deleteEvent();
			echo $result;
		}
		public function dragUpdateEvent()
		{	

			$result=$this->events_model->dragUpdateEvent();
			echo $result;
		}
		

}