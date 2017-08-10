<?php
class role_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_roles($id = FALSE) {
		if ($id === FALSE) {
			$this->db->where('status !=', 1);
			$query = $this->db->get('role');
			return $query->result_array();
		}

		$query = $this->db->get_where('role', array('role_id' => $id));
		return $query->row_array();
	}

	//Without Engineer role
	public function getRoles() {
		$this->db->where('status !=', 1);
		$query = $this->db->get('role');
		return $query->result_array();
	}	

	public function add_role() {
		$data = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
		);
		$this->db->insert('role', $data);
		return $this->db->insert_id();
	}

	public function update_role($id) {
		if($id == 1 || $id == 2 || $id == 3 || $id == 4) {
			$data = array(
				'description' => $this->input->post('description'),
	        );	
		}
		else{
			$data = array(
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
	        );	
		}

		$this->db->where('role_id', $id);
		return $this->db->update('role', $data); 
	}

	public function delete_role($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('role_id', $id);
		return $this->db->update('role', $data); 
	}

	public function getdtRoles() {
        $this->datatables->select('role_id, name, description');
        $this->datatables->from('role');
		$this->datatables->where('status !=', 1);

		$this->datatables->add_column('no', '');
		$this->datatables->add_column('action', '<a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="role/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="role/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'role_id');

		echo $this->datatables->generate();
	}

	public function addRole() {
		$data = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
		);
		$this->db->insert('role', $data);	
		return $this->db->insert_id();
	}

	public function getRolename($role_id) {
		$this->db->select('name');
        $this->db->from('role');
        $this->db->where('role_id', $role_id);
        $query = $this->db->get();
		$result = $query->row_array();
		return $result['name'];
	}

	public function getpermRoles() {
		$this->db->select('*');
		$this->db->from('role');
		$this->db->where('status !=', 1);
		$this->db->where('role_id !=', 1);
		$this->db->where("`role_id` NOT IN (SELECT DISTINCT `role_id` FROM `permission` WHERE status != 1)", NULL, FALSE);
		$query = $this->db->get();
		return $query->result_array();
	}
}