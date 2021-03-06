<?php
class tier_commission extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tier_commission_model');
		$this->load->model('user_model');
		$this->load->model('team_model');
		$this->load->library('session');
		$this->load->library("pagination");
		checkPermission();
	}

	public function index($dt = FALSE) {	
		if($dt === FALSE ) { 									// First time load
			$data['nav_area'] = 'tier_commission';
			$data['users'] = $this->user_model->get_tc_users();
			$data['teams'] = $this->team_model->get_teams();
			$this->load->view('template/header', $data);
			$this->load->view('tier_commission/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$data['users'] = $this->user_model->get_users();
			$this->load->library("Datatables");
			$this->tier_commission_model->getdttier_commissions();	
		}
	}

	public function create() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('team_id', 'Team', 'required');

		if($this->form_validation->run() === FALSE) {
			$ret = array(
				'status' => 'fail',
				'msg'	 => validation_errors(),
			);	
		}
		else {
			$tier_commission_id = $this->tier_commission_model->add_tier_commission(); 
			if($tier_commission_id) {
				logActivity('Created', "Created New tier_commission : " . $this->input->post('name'), $tier_commission_id);
				$ret = array(
					'status' => 'success',
					'msg'	 => 'Tier Commission Successfully Added',
				);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Tier Commission Create',
				);
			}
		}
		echo json_encode($ret);
	}

	public function aj_edit($id) {
		$tier_commission = $this->tier_commission_model->get_tier_commissions($id);	
		if($tier_commission) {
			$tier_commission['status'] = 'success';
		}
		else {
			$tier_commission = array(
				'status' => 'fail',
				'msg' 	 => 'No Data',
			);
		}
		echo json_encode($tier_commission);
	}

	public function edit($id) {
		$data['users'] = $this->user_model->get_users();
		$data['tier_commission'] = $this->tier_commission_model->get_tier_commissions($id);
		if ( !empty($data['tier_commission']) ) {
			$this->load->helper('form');
			$this->load->library('form_validation');

			$this->form_validation->set_rules('team_id', 'Team', 'required');

			if($this->form_validation->run() === FALSE) {	
				$ret = array(
					'status' => 'fail',
					'msg'	 => validation_errors(),
				);		
			}
			else {
				$updated = $this->tier_commission_model->update_tier_commission($id);
				if($updated) {
					logActivity('Updated', "Updated Tier Commission : " . $this->input->post('name'), $id);
					$ret = array(
						'status' => 'success',
						'msg'	 => 'Tier Commission Successfully Updated',
					);
				}
				else {
					$ret = array(
						'status' => 'fail',
						'msg'	 => 'Error in Tier Commission Update',
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
		$tier_commission = $this->tier_commission_model->get_tier_commissions($id);
		if ( !empty($tier_commission) ) {
			$deleted = $this->tier_commission_model->delete_tier_commission($id);	
			if($deleted) {
				logActivity('Deleted', "Deleted tier_commission : " . $tier_commission['name'], $id);
				$ret = array(
					'status' => 'success',
					'msg'	 => 'Tier Commission Successfully Deleted',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Tier Commission Delete',
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

	
	public function aj_getTeamLevels($team_id) {
		$team['data'] = $this->tier_commission_model->getTeamLevels($team_id);
		$team['count'] = count($team['data']);
		if(!empty($team)) {
			$team['status'] = 'success';
			//$client['designation']	=$client['designation'];
			
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($team);
	}
	
	
	
	
	public function aj_gettier_commission() {
	    $term = $this->input->get('term');
	    if (isset($term)){
	      $q = $term; //strtolower($term);
	      $this->tier_commission_model->getTitles($q);
	    }
	}

}