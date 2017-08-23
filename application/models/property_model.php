<?php
class property_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_properties($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('property');
			return $query->result_array();
		}

		$query = $this->db->get_where('property', array('property_id' => $id));
		return $query->row_array();
	}

	public function add_property() {
		
		
		 /* $query = $this->db->query("SELECT property_no FROM property
                               WHERE property_no = '".$this->input->post('property_no')."' and status = 0"); */
		//if($query->num_rows() == 0){
			
				$data = array(
				'user_id' 			=>$this->session->userdata('user_id'),
				'property_title'    => $this->input->post('property_title'),
				'tenure'            => $this->input->post('tenure'),
				'location'            => $this->input->post('location'),
				'district'          => $this->input->post('district'),
				'category'         => $this->input->post('category'),
				'address'          => $this->input->post('address'),
				'unit_size'         => $this->input->post('unit_size'),
				'land_area'         => $this->input->post('land_area'),
				'no_of_bedrooms'    => $this->input->post('bedrooms'),
				'property_price'   	=> $this->input->post('property_price'),
				'price_currency'    => $this->input->post('price_currency'),
				'meta_description'  => $this->input->post('meta_description'),
				'meta_robots_index' => $this->input->post('meta_robots_index'),
				'meta_robots_follow'=> $this->input->post('meta_robots_follow'),
				'meta_keywords'     => $this->input->post('meta_keywords'),
				'date_added'     	=> date('Y-m-d'),
				'property_img'		=> $this->input->post('main_new_file_name'),
				'property_status'   => $this->input->post('property_status'),
				
			);
			$this->db->insert('property', $data);
			$property_id = $this->db->insert_id();
			if($property_id) {
				$this->add_files($property_id);	
			}
			return $property_id;
			
		/* }
		else{
			return false;
		}
		 */

	}

	public function add_files($property_id) {
		$details = $this->input->post('files_info');
		/* echo '<pre>';
		print_r($details);
		echo '</pre>';
		die(); */
		if($details) {
			foreach($details as $detail) {
				//$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'property_id'	 	=> $property_id,
					'file_name'			=> $detail['file_name'],
					'new_file_name'		=> $detail['new_file_name'],
					'file_path'			=> $detail['file_path'],
					'date_uploaded'		=> date('Y-m-d'),
					//'file_name'		=> $this->input->post('name'),
				);
				$this->db->insert('property_files', $data);
			}	
		}
	}
	
	public function update_status($id,$stat) {
		//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$invoice_date = ($this->input->post('invoice_date')) ? get_timestamp($this->input->post('invoice_date'), '/') : 0;
		if($stat == 'Published'){
			$status = 'Published';
		}
		else{
			$status = 'Rejected';
		}
		
		$data = array(
				'property_status'   => $status,
		);
		$this->db->where('property_id', $id);
		return $this->db->update('property', $data); 
	}
	
	
	
	
	
	

	public function update_property($id) {
		//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$invoice_date = ($this->input->post('invoice_date')) ? get_timestamp($this->input->post('invoice_date'), '/') : 0;
		$data = array(
				'user_id' 			=>$this->session->userdata('user_id'),
				'property_title'    => $this->input->post('property_title'),
				'tenure'            => $this->input->post('tenure'),
				'location'            => $this->input->post('location'),
				'district'          => $this->input->post('district'),
				'category'         => $this->input->post('category'),
				'address'          => $this->input->post('address'),
				'unit_size'         => $this->input->post('unit_size'),
				'land_area'         => $this->input->post('land_area'),
				'no_of_bedrooms'    => $this->input->post('no_of_bedrooms'),
				'property_price'   	=> $this->input->post('property_price'),
				'price_currency'    => $this->input->post('price_currency'),
				'meta_description'  => $this->input->post('meta_description'),
				'meta_robots_index' => $this->input->post('meta_robots_index'),
				'meta_robots_follow'=> $this->input->post('meta_robots_follow'),
				'meta_keywords'     => $this->input->post('meta_keywords'),
				'date_added'     	=> date('Y-m-d'),
				'property_img'			=> $this->input->post('main_new_file_name'),
				'property_status'   => $this->input->post('property_status'),
		);
		$this->db->where('property_id', $id);
		return $this->db->update('property', $data); 
	}

	public function delete_property($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('property_id', $id);
		return $this->db->update('property', $data);
	}	

	public function getDetilsbypropertyid($property_id) {
		$this->db->select('d.*, u.name as unit,s.company as supplier');
		$this->db->from('property_detail d');
		$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->join('supplier s', 'd.supplier_id = s.supplier_id', 'left');
		$this->db->where('d.property_id', $property_id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}

	public function getdtproperties() {
		if( $this->input->post('title') && ( $this->input->post('title') != '' ) ) {
			$this->datatables->filter('p.property_title LIKE "%' . $this->input->post('title') . '%"');
		}
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = date('Y-m-d',strtotime($this->input->post('start_date')));
        	$this->datatables->filter('p.date_added >=', $start_date);
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date = date('Y-m-d',strtotime($this->input->post('end_date')));
        	$this->datatables->filter('p.date_added <=', $end_date);
        }
		
		if( $this->input->post('property_status') && ( $this->input->post('property_status') != '' ) ) {
			$this->datatables->filter('p.property_status', $this->input->post('property_status') );
		}

        $this->datatables->select("p.property_id,b.file_path as property_img, p.property_title, p.district, p.category, p.address, p.property_price,p.property_status, DATE_FORMAT(p.date_added, '%d/%m/%Y')as date_added", false);
        $this->datatables->from('property p');
        $this->datatables->join('users u', 'u.user_id = p.user_id', 'left');
		$this->datatables->join('(select property_id,file_path from property_files group by property_id order by property_id desc) b', 'p.property_id = b.property_id', 'left');
		$this->datatables->where('p.status !=', 1);
		$this->datatables->where('p.user_id =', $this->session->userdata('user_id'));
		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="property/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="property/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="property/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'property_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="property/view/$1">View</a><a class="btn btn-mtac admin-control btn-success edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="property/edit/$1">Edit</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="property/delete/$1">Delete</a>', 'property_id');
		echo $this->datatables->generate();
	}
	
	
	public function getdtproperties_admin() {
		/* if( $this->input->post('property_no') && ( $this->input->post('property_no') != '' ) ) {
			$this->datatables->filter('p.case_no LIKE "%' . $this->input->post('case_no') . '%"');
		} */
		if( $this->input->post('title') && ( $this->input->post('title') != '' ) ) {
			$this->datatables->filter('p.property_title LIKE "%' . $this->input->post('title') . '%"');
		}
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = date('Y-m-d',strtotime($this->input->post('start_date')));
        	$this->datatables->filter('p.date_added >=', $start_date);
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date = date('Y-m-d',strtotime($this->input->post('end_date')));
        	$this->datatables->filter('p.date_added <=', $end_date);
        }
		
		if( $this->input->post('property_status') && ( $this->input->post('property_status') != '' ) ) {
			$this->datatables->filter('p.property_status', $this->input->post('property_status') );
		}

		/* $role_id = $this->session->userdata('role_id');
		if($role_id != 1) {
			$this->datatables->where('p.user_id', $this->session->userdata('user_id'));
		} */
		//select a.property_id, b.file_name as image from property a left join (select property_id,file_name from property_files group by property_id order by property_id desc) b on a.property_id = b.property_id
        $this->datatables->select("p.property_id,b.file_path as property_img,p.property_title, p.district, p.category, p.address, p.property_price,p.property_status, DATE_FORMAT(p.date_added, '%d/%m/%Y')as date_added", false);
        $this->datatables->from('property p');
        $this->datatables->join('users u', 'u.user_id = p.user_id', 'left');
        $this->datatables->join('(select property_id,file_path from property_files group by property_id order by property_id desc) b', 'p.property_id = b.property_id', 'left');
		$this->datatables->where('p.property_status !=', 'Draft');
		$this->datatables->where('p.status !=', 1);
		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', ' <button type="submit" class="btn btn-mtac admin-control" id="btn-view"><i class="fa fa-save ico-btn"></i>View</button><button type="submit" class="btn btn-mtac admin-control" id="btn-approve"><i class="fa fa-save ico-btn"></i>Approve</button><button type="submit" class="btn btn-mtac admin-control" id="btn-reject"><i class="fa fa-save ico-btn"></i>Reject</button>', 'property_id');
		//$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view" data-toggle="tooltip" data-placement="top" title="View" href="property/view/$1"><i class="fa fa-save ico-btn"></i>View</a><a class="btn btn-mtac admin-control btn-approve" data-toggle="tooltip" data-placement="top" title="Approve" href="property/edit_status/$1"><i class="fa fa-save ico-btn"></i>Approve</a><a class="btn btn-mtac admin-control btn-reject" data-toggle="tooltip" data-placement="top" title="Reject" href="property/edit_status/$1"><i class="fa fa-save ico-btn"></i>Reject</a>', 'property_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="property/view/$1">View</a><a class="btn btn-mtac admin-control btn-approve btn-success" data-toggle="tooltip" data-placement="top" title="Edit" href="property/edit_status/$1">Approve</a><a class="btn btn-mtac btn-danger btn-reject" data-toggle="tooltip" data-placement="top" title="Delete" href="property/edit_status/$1">Reject</a>', 'property_id');
		echo $this->datatables->generate();
	}
	
	
	
	
	public function getFilesbypropertyid($property_id) {
		$this->db->select('f.*');
		$this->db->from('property_files f');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('f.property_id', $property_id);
		$this->db->where('f.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}
	
	
	
	

	public function addDetail() {
		$no = $this->input->post('no');
		$data = array(
			'property_id'	 	=> $this->input->post('hid_property_id'),
			'no'				=> ($no) ? $no : NULL,
			'description'		=> $this->input->post('description'),
			'supplier_id'		=> $this->input->post('supplier_id'),
			'supplier_cost'		=> $this->input->post('supplier_cost'),
			'discount'			=> $this->input->post('discount'),
			'qty'				=> $this->input->post('qty'),
			'uom_id'			=> $this->input->post('uom_id'),
			'amount'			=> $this->input->post('amount'),
		);
		$this->db->insert('property_detail', $data);
		$detail_id = $this->db->insert_id();
		return $detail_id;	
	}

	public function getDetail($id) {
		$this->db->select('d.*, u.name as unit,s.company');
		$this->db->from('property_detail d');
		$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->join('supplier s', 'd.supplier_id = s.supplier_id', 'left');
		$this->db->where('d.property_detail_id', $id);
		$this->db->where('d.status !=', 1);
		$query = $this->db->get('property_detail');
		return $query->row_array();
	}

	public function addFile() {
		//$no = $this->input->post('no');
		$data = array(
			'property_id'	 			=>  $this->input->post('hid_property_id'),
			'file_name'					=>  $this->input->post('file_name'),
			'new_file_name'				=>  $this->input->post('new_file_name'),
			'file_path'					=>  $this->input->post('file_path'),
			'date_uploaded'				=>  date('Y-m-d'),
			
		);
		$this->db->insert('property_files', $data);
		$detail_id = $this->db->insert_id();
		return $detail_id;	
	}
	
	
	
	public function removeFile($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('announce_file_id', $id);
		return $this->db->update('announcement_files', $data); 	
	}
	
	
	public function getFile($id) {
		$this->db->select('f.*');
		$this->db->from('announcement_files f');
	//	$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('f.announce_file_id', $id);
		$this->db->where('f.status !=', 1);
		$query = $this->db->get('announcement_files');
		return $query->row_array();
	}
	
	
	
	public function getFilesbyannouncementid($announce_id) {
		$this->db->select('f.*');
		$this->db->from('announcement_files f');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('f.announce_id', $announce_id);
		$this->db->where('f.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();	
	}

	public function getpropertyno() {
		$latest_no = $this->getLatestpropertyno();
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

	public function getLatestpropertyno() {
		$this->db->where('status !=', 1);
		$this->db->order_by("property_id", "desc");
		$this->db->limit(1);
		$query = $this->db->get('property');
		$result = $query->row_array();
		return ($result) ? $result['property_no'] : '';
	}

	public function getpropertyinfo($id) {
		$this->db->select("p.*", false);
		$this->db->from('property p');
        //$this->db->join('project p', 'q.project_id = p.project_id', 'left');
        $this->db->join('users u', 'u.user_id = p.user_id', 'left');
		$this->db->where('p.status !=', 1);
		$this->db->where('p.property_id', $id);
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
	public function getInvoice($property_id) {
		$this->db->select('i.*');
		$this->db->from('invoice i');
		//$this->db->join('uom u', 'd.uom_id = u.uom_id', 'left');
		$this->db->where('i.property_id', $property_id);
		$this->db->where('i.status !=', 1);
		$query = $this->db->get();
		return $query->result_array();
		//echo json_encode($query);
	}

	public function getQoNo($name) {
		// $this->db->like('property_no', $name);
		// $this->db->where('status !=', 1);
		// $query = $this->db->get('property');
		// return $query->result_array();

		$role_id = $this->session->userdata('role_id');
		$user_id = $this->session->userdata('user_id');

		$this->db->like('property_no', $name);
		$this->db->where('status !=', 1);

		if ($role_id == 1) {
			$query = $this->db->get('property');
			return $query->result_array();
		}
		else {
			$query = $this->db->get_where('property', array('sale_person_id' => $user_id));
			return $query->result_array();	
		}
	}

}