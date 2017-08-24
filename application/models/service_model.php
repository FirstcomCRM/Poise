<?php
class service_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_services($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('service');
			return $query->result_array();
		}

		$query = $this->db->get_where('service', array('service_id' => $id));
		return $query->row_array();
	}

	public function add_service() {
		$data = array(
			'name' 	  			=> $this->input->post('name'),
			'type' 	  			=> $this->input->post('type'),
			'description' 		=> $this->input->post('description'),
		);
		$this->db->insert('service', $data);
		$service_id = $this->db->insert_id();
		return $service_id;
	}

	public function update_service($id) {
		$data = array(
			'name' 	  			=> $this->input->post('name'),
			'type' 	  			=> $this->input->post('type'),
			'description' 		=> $this->input->post('description'),
        );
     
		$this->db->where('service_id', $id);
		return $this->db->update('service', $data); 
	}

	public function delete_service($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('service_id', $id);
		return $this->db->update('service', $data); 
	}


	public function getdtservices() {
        $this->datatables->select('service_id, name, type, description');
        $this->datatables->from('service');
		$this->datatables->where('status !=', 1);

		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="service/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="service/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'service_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-success edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="service/edit/$1">Edit</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="service/delete/$1">Delete</a>', 'service_id');
		echo $this->datatables->generate();
	}

	public function getService($name) {
		$this->db->like('name', $name);
		$this->db->where('status !=', 1);
		$query = $this->db->get('service');
		return $query->result_array();
	}
}