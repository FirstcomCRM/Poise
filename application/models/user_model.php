<?php
class user_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_users($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('users');
			return $query->result_array();
		}

		$query = $this->db->get_where('users', array('user_id' => $id));
		return $query->row_array();
	}

	
	public function get_tc_users($id = FALSE) {
		if ($id === FALSE)
		{
			
			$query = $this->db->query("SELECT user_id, username, name FROM users
                               WHERE user_id not in (select user_id from tier_commission) and role_id=2");
			//$this->db->where('status !=', 1);
			//$this->db->where('status !=', 1);
			//$query = $this->db->get('users');
			return $query->result_array();
		}

		$query = $this->db->get_where('users', array('user_id' => $id));
		return $query->row_array();
	}
	
	
	public function add_user() {
		$data = array(
			'name' 	  				=> $this->input->post('name'),
			'username'				=> $this->input->post('username'),
			'password'				=> md5($this->input->post('password')),
			'role_id' 				=> $this->input->post('role_id'),
			'team_id' 				=> $this->input->post('team_id'),
			'level' 				=> $this->input->post('level'),
			'user_belong_to' 		=> $this->input->post('user_belong_to'),
			'email' 				=> $this->input->post('email'),
			'contact'				=> $this->input->post('contact'),
			'cea_no'				=> $this->input->post('cea_no'),
			'nric'					=> $this->input->post('nric'),
			'commission'			=> $this->input->post('commission'),
			'co_broke_commission'	=> $this->input->post('co_broke_commission'),
			'internal_commission'	=> $this->input->post('internal_commission'),
			'user_img'				=> $this->input->post('user_img'),
			'cv'					=> $this->input->post('cv'),
			
		);
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	public function getUsers() {
		$role_id = $this->session->userdata('role_id');
		$user_id = $this->session->userdata('user_id');
		if ($role_id == 1)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('users');
			return $query->result_array();
		}

		$query = $this->db->get_where('users', array('user_id' => $user_id));
		return $query->result_array();	
	}

	public function update_user($id) {
		//$def_com = ($this->input->post('default_commission') != '') ? $this->input->post('default_commission') : NULL;
		$data = array(
			'name' 	  				=> $this->input->post('name'),
			'username'				=> $this->input->post('username'),
			//'password'				=> md5($this->input->post('password')),
			'role_id' 				=> $this->input->post('role_id'),
			'team_id' 				=> $this->input->post('team_id'),
			'level' 				=> $this->input->post('level'),
			'user_belong_to' 		=> $this->input->post('user_belong_to'),
			'email' 				=> $this->input->post('email'),
			'contact'				=> $this->input->post('contact'),
			'cea_no'				=> $this->input->post('cea_no'),
			'nric'					=> $this->input->post('nric'),
			'commission'			=> $this->input->post('commission'),
			'co_broke_commission'	=> $this->input->post('co_broke_commission'),
			'internal_commission'	=> $this->input->post('internal_commission'),
			'user_img'				=> $this->input->post('user_img'),
			'cv'					=> $this->input->post('cv'),
		);
        if($this->input->post('password') != '') {
        	$data['password'] = md5($this->input->post('password'));
        }
		//echo "THE PASSWORD IS!!!>>>>". $this->input->post('password'); 
		//die();
		$this->db->where('user_id', $id);
		return $this->db->update('users', $data); 
	}

	public function delete_user($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('user_id', $id);
		return $this->db->update('users', $data); 
	}

	public function check_email($email) {
		$query = $this->db->get_where('users', array('status !='=>1, 'email'=>$email));
		if($query->num_rows == 1) {
			return FALSE;
		}
		return TRUE;
	}	

	public function check_username($username) {
		$query = $this->db->get_where('users', array('status !=' => 1, 'username' => $username));
		if($query->num_rows == 1) {
			return FALSE;
		}
		return TRUE;
	}	
	
	
	public function getTeamTierDetails($team_id) {
		$this->db->select('t.*,tc.*');
		$this->db->from('team t');
		$this->db->join('tier_commission tc', 'tc.team_id = t.team_id', 'left');
		$this->db->where('t.team_id', $team_id);
		$this->db->where('t.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();
		//echo json_encode($query);
	}
	public function getUserBelongto($team_id,$level) {
		$this->db->select('u.*');
		$this->db->from('users u');
		//$this->db->join('tier_commission tc', 'tc.team_id = t.team_id', 'left');
		$this->db->where('u.team_id', $team_id);
		$this->db->where('u.level', $level);
		$this->db->where('u.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();
		//echo json_encode($query);
	}

	public function getdtusers() {
        $this->datatables->select('a.user_id, a.user_img, a.name, a.username, r.name as role, t.name as team, a.level, a.email, a.contact, a.cv');
        $this->datatables->from('users a');
        $this->datatables->join('role r', 'a.role_id = r.role_id', 'left');
        $this->datatables->join('team t', 'a.team_id = t.team_id', 'left');
		$this->datatables->where('a.status !=', 1);

		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="user/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="user/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'user_id');
		$this->datatables->add_column('action', '<ul class="list-inline hidden-xs"><li class = "li-padds"><a class="btn btn-mtac admin-control btn-view btn-success edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="user/edit/$1">Edit</a></li><li class = "li-padds"><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="user/delete/$1">Delete</a></li></ul>', 'user_id');
		echo $this->datatables->generate();
	}
}