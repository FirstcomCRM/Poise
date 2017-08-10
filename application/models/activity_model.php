<?php
class activity_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function log($action, $description, $record_id = NULL) {
		$this->load->dbforge();
		$log_array = array(
			'action'		=> $action,
			'date'			=> time(),
			'user_id'		=> $this->session->userdata('user_id'),
			'user_type'		=> $this->session->userdata('login_user'),
			'record_id'		=> $record_id,
			'description'	=> $description,
		);

		$table_name = 'log_'. date('Y', time());
		if(!$this->db->table_exists($table_name)) {
			$this->createTable($table_name);
		}
		return $this->db->insert($table_name, $log_array);
	}

	public function getAllyear() {
		$tables = $this->db->list_tables();
		$tbl_arr = array();
		foreach($tables as $tbl) {
			if(substr($tbl, 0, 4) == 'log_') {
				$tbl_arr [] = substr($tbl, 4, 4);
			}
		}
		return $tbl_arr;
	}

	public function createTable($table_name) {
		$this->dbforge->add_field(array(
		    'log_id' => array(
		        'type' 			 => 'INT',
		        'constraint' 	 => 11,
		        'unsigned' 		 => TRUE,
		        'auto_increment' => TRUE
		    ),
		    'action' => array(
		        'type' 			 => 'VARCHAR',
		        'constraint'	 => '45',
		    ),
		    'date' => array(
		        'type' 			 => 'INT',
		        'constraint' 	 => 11,
		    ),
		    'user_id' => array(
		        'type' 			 => 'INT',
		        'constraint' 	 => 11,
		        'null' 			 => TRUE,
		    ),
		    'user_type' => array(
		        'type' 			 => 'VARCHAR',
		        'constraint' 	 => 45,
		        'null' 			 => TRUE,
		    ), 
		    'record_id' => array(
		        'type' 			 => 'INT',
		        'constraint' 	 => 11,
		        'null' 			 => TRUE,
		    ),
		    'description' => array(
		        'type' 			 => 'VARCHAR',
		        'constraint' 	 => 500,
		        'null' 			 => TRUE,
		    ),
		));
		$this->dbforge->add_key('log_id', TRUE);
		$this->dbforge->create_table($table_name);	
	}

	public function getdtActivitys() {
		if( $this->input->post('start_date') && ( $this->input->post('start_date') != '' ) ) { 
        	$start_date = get_earliesttimestamp($this->input->post('start_date'), '-');
        	$this->datatables->filter('date >=', $start_date);
        }
        if( $this->input->post('end_date') && ( $this->input->post('end_date') != '' ) ) { 
        	$end_date = get_latesttimestamp($this->input->post('end_date'), '-');
        	$this->datatables->filter('date <=', $end_date);
        }
		$this->datatables->select("FROM_UNIXTIME( date,  '%Y-%m-%d &nbsp;&nbsp; %H:%i:%s' ) as date, action, user_type, description", false);
        $this->datatables->from('log_'.$this->input->post('year'));
        $this->datatables->add_column('no', '');
		echo $this->datatables->generate();
	}

}