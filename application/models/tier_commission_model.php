<?php
class tier_commission_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_tier_commissions($id = FALSE) {
		if ($id === FALSE) {
			$this->db->where('status !=', 1);
			$query = $this->db->get('tier_commission');
			return $query->result_array();
		}

		$query = $this->db->get_where('tier_commission', array('tier_commission_id' => $id));
		return $query->row_array();
	}

	//Without Engineer tier_commission
	public function gettier_commissions() {
		$this->db->where('status !=', 1);
		$query = $this->db->get('tier_commission');
		return $query->result_array();
	}	

	public function add_tier_commission() {
		$data = array(
			'user_id' => $this->input->post('user_id'),
			'level' => $this->input->post('level'),
		);
		$this->db->insert('tier_commission', $data);
		return $this->db->insert_id();
	}

	public function update_tier_commission($id) {
		$data = array(
			'name' => $this->input->post('name'),
		);

		$this->db->where('tier_commission_id', $id);
		return $this->db->update('tier_commission', $data); 
	}

	public function delete_tier_commission($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('tier_commission_id', $id);
		return $this->db->update('tier_commission', $data); 
	}

	public function getdttier_commissions() {
		if( $this->input->post('category') && ( $this->input->post('category') != '' ) ) {
			$this->datatables->filter('name LIKE "%' . $this->input->post('category') . '%"');
		}

        $this->datatables->select('t.tier_commission_id, u.username, t.level');
        $this->datatables->from('tier_commission t');
		$this->datatables->join('users u', 'u.user_id = t.user_id', 'left');
		$this->datatables->where('t.status !=', 1);

		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="tier_commission/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="tier_commission/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'tier_commission_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-success edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="tier_commission/edit/$1">Edit</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="tier_commission/delete/$1">Delete</a>', 'tier_commission_id');

		echo $this->datatables->generate();
	}

	public function getTitles($q){
        $this->db->select('name');
        $this->db->like('name', $q);
        $this->db->where('status !=', 1);
        $query = $this->db->get('tier_commission');
        if($query->num_rows > 0){
          foreach ($query->result_array() as $row){
            $row_set[] = htmlentities(stripslashes($row['name'])); //build an array
          }
          echo json_encode($row_set); //format the array into json data
        }
    }
	
	
	public function gettierinfo($id) {
		// $this->db->select("i.*, q.quotation_id, q.quotation_no ,q.job_title, c.client_id, c.company as client, c.contact, c.designation, c.department, CONCAT_WS (' ', c.address_1, c.address_2, c.postal_code) as address, u.name as rep", false);
		$this->db->select('t.tier_commission_id, u.username, t.level');
        $this->db->from('tier_commission t');
		$this->db->join('users u', 'u.user_id = t.user_id', 'left');
	//	$this->db->select("a.*", false);
//		$this->db->from('announcement a');
		//$this->db->where('a.status !=', 1);
		$this->db->where('t.tier_commission_id', $id);
		$query = $this->db->get();	
		return $query->row_array();
	}

}