<?php
class form_category_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_form_categories($id = FALSE) {
		if ($id === FALSE) {
			$this->db->where('status !=', 1);
			$query = $this->db->get('form_category');
			return $query->result_array();
		}

		$query = $this->db->get_where('form_category', array('form_category_id' => $id));
		return $query->row_array();
	}

	//Without Engineer form_category
	public function getform_categories() {
		$this->db->where('status !=', 1);
		$query = $this->db->get('form_category');
		return $query->result_array();
	}	

	public function add_form_category() {
		$data = array(
			'name' => $this->input->post('name'),
		);
		$this->db->insert('form_category', $data);
		return $this->db->insert_id();
	}

	public function update_form_category($id) {
		$data = array(
			'name' => $this->input->post('name'),
		);

		$this->db->where('form_category_id', $id);
		return $this->db->update('form_category', $data); 
	}

	public function delete_form_category($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('form_category_id', $id);
		return $this->db->update('form_category', $data); 
	}

	public function getdtform_categories() {
		if( $this->input->post('category') && ( $this->input->post('category') != '' ) ) {
			$this->datatables->filter('name LIKE "%' . $this->input->post('category') . '%"');
		}

        $this->datatables->select('form_category_id, name');
        $this->datatables->from('form_category');
		$this->datatables->where('status !=', 1);

		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="form_category/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="form_category/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'form_category_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-success edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="form_category/edit/$1">Edit</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="form_category/delete/$1">Delete</a>', 'form_category_id');

		echo $this->datatables->generate();
	}

	public function getTitles($q){
        $this->db->select('name');
        $this->db->like('name', $q);
        $this->db->where('status !=', 1);
        $query = $this->db->get('form_category');
        if($query->num_rows > 0){
          foreach ($query->result_array() as $row){
            $row_set[] = htmlentities(stripslashes($row['name'])); //build an array
          }
          echo json_encode($row_set); //format the array into json data
        }
    }

}