<?php
class team_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_teams($id = FALSE) {
		if ($id === FALSE) {
			$this->db->where('status !=', 1);
			$query = $this->db->get('team');
			return $query->result_array();
		}

		$query = $this->db->get_where('team', array('team_id' => $id));
		return $query->row_array();
	}

	//Without Engineer team
	public function getteams() {
		$this->db->where('status !=', 1);
		$query = $this->db->get('team');
		return $query->result_array();
	}	

	public function add_team() {
		$data = array(
			'name' => $this->input->post('name'),
		);
		$this->db->insert('team', $data);
		return $this->db->insert_id();
	}

	public function update_team($id) {
		$data = array(
			'name' => $this->input->post('name'),
		);

		$this->db->where('team_id', $id);
		return $this->db->update('team', $data); 
	}

	public function delete_team($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('team_id', $id);
		return $this->db->update('team', $data); 
	}

	public function getdtteams() {
        $this->datatables->select('team_id, name');
        $this->datatables->from('team');
		$this->datatables->where('status !=', 1);

		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="team/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="team/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'team_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-success edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="team/edit/$1">Edit</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="team/delete/$1">Delete</a>', 'team_id');
		echo $this->datatables->generate();
	}

	public function getTitles($q){
        $this->db->select('name');
        $this->db->like('name', $q);
        $this->db->where('status !=', 1);
        $query = $this->db->get('team');
        if($query->num_rows > 0){
          foreach ($query->result_array() as $row){
            $row_set[] = htmlentities(stripslashes($row['name'])); //build an array
          }
          echo json_encode($row_set); //format the array into json data
        }
    }

}