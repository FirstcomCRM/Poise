<?php
class events_model extends CI_Model {

	public function __construct() {
		$this->load->database();
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
		
	}
	
	/*Update  event */

	Public function updateEvent()
	{

	$sql = "UPDATE events SET title = ?, description = ?, color = ?, events.start = ? ,events.end =? WHERE id = ?";
	$this->db->query($sql, array($_POST['title'],$_POST['description'], $_POST['color'],date('Y-m-d H:i:s',strtotime($this->input->post('start'))),date('Y-m-d H:i:s',strtotime($this->input->post('end'))), $_POST['id']));
	//$this->db->query($sql, array($_POST['title'],$_POST['description'], $_POST['color'],date('Y-m-d H:i:s',strtotime($_POST['start'])),date('Y-m-d H:i:s',strtotime($_POST['end'])), $_POST['id']));
		return ($this->db->affected_rows()!=1)?false:true;
		echo $sql;
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