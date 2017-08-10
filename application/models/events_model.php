<?php
class events_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_announcements($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('announcement');
			return $query->result_array();
		}

		$query = $this->db->get_where('announcement', array('announce_id' => $id));
		return $query->row_array();
	}

	public function add_announcement() {
		//$announcement_date = get_timestamp('d/m/Y', '/');
		$data = array(
			'announce_title'		=> $this->input->post('announcement_title'),
			'announce_body'			=> $this->input->post('announcement_body'),
			'announce_date'			=> date('Y-m-d'),
			//'announcement_date'					=> $this->input->post('terms'),
		);
		$this->db->insert('announcement', $data);
		$announce_id = $this->db->insert_id();
		if($announce_id) {
			$this->addDetails($announce_id);	
		}
		return $announce_id;
	}
	
	
	public function addDetails($announce_id) {
		$details = $this->input->post('files_info');
		if($details) {
			foreach($details as $detail) {
				$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'announce_id'	 	=> $announce_id,
					'file_name'		=> $detail['file_uploads'],
				);
				$this->db->insert('announcement_files', $data);
			}	
		}
	}
	
	
	
	
	
	
	public function update_announcement($id) { 
		//$delivery_date = ($this->input->post('delivery_date')) ? get_timestamp($this->input->post('delivery_date'), '/') : 0;
		//$announcement_date = ($this->input->post('announcement_date')) ? get_timestamp($this->input->post('announcement_date'), '/') : 0;
		$data = array(
			'announce_title'		=> $this->input->post('announcement_title'),
			'announce_body'			=> $this->input->post('announcement_body'),
			// 'commission'			=> $this->input->post('commission'),
			// 'mf'					=> $this->input->post('mf'),
		);
		$this->db->where('announce_id', $id);
		return $this->db->update('announcement', $data); 
	}

	public function delete_announcement($id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('announce_id', $id);
		return $this->db->update('announcement', $data);
	}	

	
	
	
	
	public function getdtannouncements() {
		if( $this->input->post('announcement_title') && ( $this->input->post('announcement_title') != '' ) ) {
			$this->datatables->filter('a.announce_title LIKE "%' . $this->input->post('announcement_title') . '%"');
		}
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = $this->input->post('start_date');
        	$this->datatables->filter('a.announce_date >=', date('Y-m-d',strtotime($start_date)));
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date = $this->input->post('end_date');
        	$this->datatables->filter('a.announce_date <=', date('Y-m-d',strtotime($end_date)));
        }
		/* if( $this->input->post('quotation_no') && ( $this->input->post('quotation_no') != '' ) ) {
			$this->datatables->filter('q.quotation_no LIKE "%' . $this->input->post('quotation_no') . '%"');
		}
		
  		// if( $this->input->post('project_no') && ( $this->input->post('project_no') != '' ) ) {
  		//  	$this->datatables->filter('p.project_no LIKE "%' . $this->input->post('project_no') . '%"');
		// }
		// if( $this->input->post('client_id') && ( $this->input->post('client_id') != '' ) ) {
		// 	$this->datatables->filter('q.client_id', $this->input->post('client_id') );
		// }
		if( $this->input->post('announcement_status') && ( $this->input->post('announcement_status') != '' ) ) {
			$this->datatables->filter('i.announcement_status', $this->input->post('announcement_status') );
		} */

		//$role_id = $this->session->userdata('role_id');
		/* if($role_id != 1) {
			$this->datatables->where('q.sale_person_id', $this->session->userdata('user_id'));
		} */
		
        $this->datatables->select("a.announce_id,a.announce_title,a.announce_body,a.announce_date,a.announce_img", false);
        $this->datatables->from('announcement a');
       
		$this->datatables->where('a.status !=', 1);
		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="announcement/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="print-link" data-toggle="tooltip" data-placement="top" title="Print" href="announcement/printannouncement/$1"><i class="fa fa-print ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="announcement/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'announce_id');
		$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="announcement/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="announcement/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'announce_id');
		echo $this->datatables->generate();
	}


	
	
	 public function update_img($announce_id){

        $data = array(
        'announce_img'   =>  $this->input->post('announce_img_path')

        );
        $this->db->where('announce_id', $announce_id);
        return $this->db->update('announcement', $data);
    }



    public function exist_img($announce_id){
        
        $this->db->select('announce_img');
        
        $this->db->from('announcement');
        
        $this->db->where('announce_id',$announce_id);
        
        $query = $this->db->get();

        return $query;

	}
	
	
	
	
	

	public function getannouncementinfo($id) {
		// $this->db->select("i.*, q.quotation_id, q.quotation_no ,q.job_title, c.client_id, c.company as client, c.contact, c.designation, c.department, CONCAT_WS (' ', c.address_1, c.address_2, c.postal_code) as address, u.name as rep", false);
		$this->db->select("a.*", false);
		$this->db->from('announcement a');
		$this->db->where('a.status !=', 1);
		$this->db->where('a.announce_id', $id);
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

	
	
	
	
	
	Public function getEvents()
	{
		
	$sql = "SELECT * FROM events WHERE events.start BETWEEN ? AND ? ORDER BY events.start ASC";
	return $this->db->query($sql, array($_GET['start'], $_GET['end']))->result();

	}

/*Create new events */

	Public function addEvent()
	{

	$sql = "INSERT INTO events (title,events.start,events.end,description, color) VALUES (?,?,?,?,?)";
	$this->db->query($sql, array($this->input->post('title'), date('Y-m-d H:i:s',strtotime($this->input->post('start'))),date('Y-m-d H:i:s',strtotime($this->input->post('end'))), $this->input->post('description'), $this->input->post('color')));
		return ($this->db->affected_rows()!=1)?false:true;
		echo $sql;
	}
	
	/*Update  event */

	Public function updateEvent()
	{

	$sql = "UPDATE events SET title = ?, description = ?, color = ? WHERE id = ?";
	$this->db->query($sql, array($_POST['title'],$_POST['description'], $_POST['color'], $_POST['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}


	/*Delete event */

	Public function deleteEvent()
	{

	$sql = "DELETE FROM events WHERE id = ?";
	$this->db->query($sql, array($_GET['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	/*Update  event */

	Public function dragUpdateEvent()
	{
			//$date=date('Y-m-d h:i:s',strtotime($_POST['date']));

			$sql = "UPDATE events SET  events.start = ? ,events.end = ?  WHERE id = ?";
			$this->db->query($sql, array($_POST['start'],$_POST['end'], $_POST['id']));
		return ($this->db->affected_rows()!=1)?false:true;


	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

}