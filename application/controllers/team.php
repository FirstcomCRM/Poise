<?php
class team extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('team_model');
		$this->load->library('session');
		$this->load->library("pagination");
		checkPermission();
	}

	public function index($dt = FALSE) {	
		if($dt === FALSE ) { 									// First time load
			$data['nav_area'] = 'team';
			$this->load->view('template/header', $data);
			$this->load->view('team/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->team_model->getdtTeams();	
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
			$team_id = $this->team_model->add_team(); 
			if($team_id) {
				logActivity('Created', "Created New team : " . $this->input->post('name'), $team_id);
				$ret = array(
					'status' => 'success',
					'msg'	 => 'Team Successfully Added',
				);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Team Create',
				);
			}
		}
		echo json_encode($ret);
	}

	public function aj_edit($id) {
		$team = $this->team_model->get_teams($id);	
		if($team) {
			$team['status'] = 'success';
		}
		else {
			$team = array(
				'status' => 'fail',
				'msg' 	 => 'No Data',
			);
		}
		echo json_encode($team);
	}

	public function edit($id) {
		$data['team'] = $this->team_model->get_teams($id);
		if ( !empty($data['team']) ) {
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
				$updated = $this->team_model->update_team($id);
				if($updated) {
					logActivity('Updated', "Updated team : " . $this->input->post('name'), $id);
					$ret = array(
						'status' => 'success',
						'msg'	 => 'Team Successfully Updated',
					);
				}
				else {
					$ret = array(
						'status' => 'fail',
						'msg'	 => 'Error in Team Update',
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
		$team = $this->team_model->get_teams($id);
		if ( !empty($team) ) {
			$deleted = $this->team_model->delete_team($id);	
			if($deleted) {
				logActivity('Deleted', "Deleted team : " . $team['name'], $id);
				$ret = array(
					'status' => 'success',
					'msg'	 => 'Team Successfully Deleted',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Team Delete',
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

	public function aj_getteam() {
	    $term = $this->input->get('term');
	    if (isset($term)){
	      $q = $term; //strtolower($term);
	      $this->team_model->getTitles($q);
	    }
	}

}