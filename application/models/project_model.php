<?php
class project_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_projects($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('project');
			return $query->result_array();
		}

		$query = $this->db->get_where('project', array('project_id' => $id));
		return $query->row_array();
	}

	public function add_project() {
		
		
		 $query = $this->db->query("SELECT project_code FROM project
                               WHERE project_code = '".$this->input->post('project_code')."' and status = 0");
		if($query->num_rows() == 0){
			//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
			//$invoice_date = ($this->input->post('invoice_date')) ? get_timestamp($this->input->post('invoice_date'), '/') : 0;
			//$formatdate = DateTime::createFromFormat('Y-m-d', $weddingdate);
			//$formatteddate = $myDateTime->format('d-m-Y');
			//'date' 	  				=> date_format($this->input->post('date'),"Y-m-d"),
			//echo date('Y-m-d',strtotime($this->input->post('date')));
			$data = array(
				'project_code' 	  		=> $this->input->post('project_code'),
				'description' 	  		=> $this->input->post('description'),
				'project_ic' 	  		=> $this->input->post('project_ic'),
				'date' 	  				=> date('Y-m-d',strtotime($this->input->post('date'))),
				'category' 	  			=> $this->input->post('category'),
				'project_type'			=> $this->input->post('project_type'),
				'project_status'		=> $this->input->post('status'),
			);
			$this->db->insert('project', $data);
			$project_id = $this->db->insert_id();
			// if($project_id) {
				// $this->addDetails($project_id);	
			// }
			return $project_id;
			
		}
		else{
			return false;
		}
		
		
		
		
		
		
	}

	/* public function addDetails($project_id) {
		$details = $this->input->post('detail_info');
		if($details) {
			foreach($details as $detail) {
				$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'project_id'	 	=> $project_id,
					'no'				=> $no,
					'description'		=> $detail['description'],
					'qty'				=> $detail['qty'],
					'supplier_id'		=> $detail['supplier_id'],
					'supplier_cost'		=> $detail['supplier_cost'],
					'uom_id'			=> $detail['uom_id'],
					'amount'			=> $detail['amount'],
				);
				$this->db->insert('project_detail', $data);
			}	
		}
	} */

	public function update_project($id) {
		// $delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		// $invoice_date = ($this->input->post('invoice_date')) ? get_timestamp($this->input->post('invoice_date'), '/') : 0;
		$data = array(
			'project_code' 	  		=> $this->input->post('project_code'),
			'description' 	  		=> $this->input->post('description'),
			'project_ic' 	  		=> $this->input->post('project_ic'),
			'date' 	  				=> date('Y-m-d',strtotime($this->input->post('date'))),
			'category' 	  			=> $this->input->post('category'),
			'project_type'			=> $this->input->post('project_type'),
			'project_status'		=> $this->input->post('status'),
		);
		$this->db->where('project_id', $id);
		return $this->db->update('project', $data); 
	}

	public function delete_project($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('project_id', $id);
		return $this->db->update('project', $data);
	}	

	public function getDetilsbyprojectid($project_id) {
		$this->db->select('d.*, u.name as unit,s.company as supplier');
		$this->db->from('project_detail d');
		$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->join('supplier s', 'd.supplier_id = s.supplier_id', 'left');
		$this->db->where('d.project_id', $project_id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}
//date('Y-m-d',strtotime($start_date)))
	public function getdtprojects() {
		if( $this->input->post('project_code') && ( $this->input->post('project_code') != '' ) ) {
			$this->datatables->filter('p.project_code LIKE "%' . $this->input->post('project_code') . '%"');
		}
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = $this->input->post('start_date');
        	$this->datatables->filter('p.date >=', date('Y-m-d',strtotime($start_date)));
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date =$this->input->post('end_date');
        	$this->datatables->filter('p.date <=', date('Y-m-d',strtotime($end_date)));
        }
		if( $this->input->post('description') && ( $this->input->post('description') != '' ) ) {
			$this->datatables->filter('p.description', $this->input->post('description') );
		}
		if( $this->input->post('project_type') && ( $this->input->post('project_type') != '' ) ) {
			$this->datatables->filter('p.project_type', $this->input->post('project_type') );
		}

/*		$role_id = $this->session->userdata('role_id');
		if($role_id != 1) {
			$this->datatables->where('q.sale_person_id', $this->session->userdata('user_id'));
		}
*/
        $this->datatables->select("p.project_id, p.project_code, p.description, p.project_ic, p.date, p.category, p.project_type, p.project_status", false);
        $this->datatables->from('project p');
		$this->datatables->where('p.status !=', 1);
		$this->datatables->add_column('no', '');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="project/view/$1">View</a><a class="btn btn-mtac admin-control btn-view btn-success" data-toggle="tooltip" data-placement="top" title="Edit" href="project/edit/$1">Edit</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="project/delete/$1">Delete</a>', 'project_id');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="project/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="project/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="project/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'project_id');
		echo $this->datatables->generate();
	}

	public function addDetail() {
		$no = $this->input->post('no');
		$data = array(
			'project_id'	 	=> $this->input->post('hid_project_id'),
			'no'				=> ($no) ? $no : NULL,
			'description'		=> $this->input->post('description'),
			'supplier_id'		=> $this->input->post('supplier_id'),
			'supplier_cost'		=> $this->input->post('supplier_cost'),
			'discount'			=> $this->input->post('discount'),
			'qty'				=> $this->input->post('qty'),
			'uom_id'			=> $this->input->post('uom_id'),
			'amount'			=> $this->input->post('amount'),
		);
		$this->db->insert('project_detail', $data);
		$detail_id = $this->db->insert_id();
		return $detail_id;	
	}

	public function getDetail($id) {
		$this->db->select('d.*, u.name as unit,s.company');
		$this->db->from('project_detail d');
		$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->join('supplier s', 'd.supplier_id = s.supplier_id', 'left');
		$this->db->where('d.project_detail_id', $id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get('project_detail');
		return $query->row_array();
	}

	public function updateDetail($id) {
		$no = $this->input->post('no');
		$data = array(
			'project_id'	 	=> $this->input->post('hid_project_id'),
			'no'				=> ($no) ? $no : NULL,
			'description'		=> $this->input->post('description'),
			'supplier_id'		=> $this->input->post('supplier_id'),
			'supplier_cost'		=> $this->input->post('supplier_cost'),
			'discount'			=> $this->input->post('discount'),
			'qty'				=> $this->input->post('qty'),
			'uom_id'			=> $this->input->post('uom_id'),
			'amount'			=> $this->input->post('amount'),
		);	
		$this->db->where('project_detail_id', $id);
		return $this->db->update('project_detail', $data); 
	}

	public function removeDetail($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('project_detail_id', $id);
		return $this->db->update('project_detail', $data); 	
	}

	public function getprojectcode() {
		$latest_no = $this->getLatestprojectcode();
		if($latest_no) {
			$year = substr($latest_no, 0, 2);
			$no = substr($latest_no, 2);
			$no_arr = explode('-', $no);
			if($year == date('y')) {
				return $year . $no_arr[0] + 1;
			}
			else {
				return date('y') . str_pad(1, 4, "0", STR_PAD_LEFT);
			}
		}
		else {
			return date('y') . str_pad(1, 4, "0", STR_PAD_LEFT);
		}
	}

	public function getLatestprojectcode() {
		$this->db->where('status !=', 1);
		$this->db->order_by("project_id", "desc");
		$this->db->limit(1);
		$query = $this->db->get('project');
		$result = $query->row_array();
		return ($result) ? $result['project_code'] : '';
	}

	public function getprojectinfo($id) {
		$this->db->select("p.*", false);
		$this->db->from('project p');
        //$this->db->join('project p', 'q.project_id = p.project_id', 'left');
		$this->db->where('p.status !=', 1);
		$this->db->where('p.project_id', $id);
		$query = $this->db->get();	
		return $query->row_array();
	}

	public function getClientinfo($client_id) {
		$this->db->select("c.client_id, c.company, c.contact, c.designation, c.department, CONCAT_WS (' ', c.address_1, c.address_2, c.postal_code) as address", false);
		$this->db->from('client c');
		$this->db->where('c.client_id', $client_id);
		$query = $this->db->get();
		return $query->row_array();	
	}
	
	
	public function getClientDetail($client_id) {
		$this->db->select('d.*');
		$this->db->from('client_detail d');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('d.client_id', $client_id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();
		//echo json_encode($query);
	}
	public function getInvoice($project_id) {
		$this->db->select('i.*');
		$this->db->from('invoice i');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('i.project_id', $project_id);
		$this->db->where('i.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();
		//echo json_encode($query);
	}

	public function getQoNo($name) {
		// $this->db->like('project_no', $name);
		// $this->db->where('status !=', 1);
		// $query = $this->db->get('project');
		// return $query->result_array();

		$role_id = $this->session->userdata('role_id');
		$user_id = $this->session->userdata('user_id');

		$this->db->like('project_no', $name);
		$this->db->where('status !=', 1);

		if ($role_id == 1) {
			$query = $this->db->get('project');
			return $query->result_array();
		}
		else {
			$query = $this->db->get_where('project', array('sale_person_id' => $user_id));
			return $query->result_array();	
		}
	}

}