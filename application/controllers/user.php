<?php
class user extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->model('user_model');
		$this->load->model('role_model');
		$this->load->model('team_model');
		checkPermission();
	}

	public function index($dt = FALSE)	{	
		if($dt === FALSE ) { 									// First time load
			$data['msg'] = $this->session->flashdata('msg');
			$data['nav_area'] = 'user';

		
			//$data['levels'] = $this->team_model->get_teams(); 
			$this->load->view('template/header', $data);
			$this->load->view('user/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->user_model->getdtusers();
		}
	}

	public function view($id) {
		$data['property'] = $this->property_model->getpropertyinfo($id); 
		if (empty($data['property'])) {
			show_404();
		}
		$data['details'] =  $this->property_model->getFilesbypropertyid($id);
		$this->load->view('property/view', $data);
	}

	public function create($submit = FALSE) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('username', 'User Name', 'required|callback_pv_check_username');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('role_id', "Role", 'required');
		$this->form_validation->set_rules('nric', 'NRIC', 'required');
		$this->form_validation->set_rules('cea_no', 'CEA No.', 'required');

		/* $this->form_validation->set_rules('date', 'Date', 'required');
		$this->form_validation->set_rules('client_id', 'Client', 'required');
		$this->form_validation->set_rules('sale_person_id', 'REP', 'required'); */
		
		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'new';
				$data['nav_area'] = 'user';
				//$data['users'] = $this->user_model->getUsers();
				$data['roles'] = $this->role_model->getRoles(); 
				$data['teams'] = $this->team_model->get_teams(); 

				$this->load->view('template/header', $data);
				$this->load->view('user/edit', $data);
				$this->load->view('template/footer', $data);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => validation_errors(),
				);	
				echo json_encode($ret);
			}
		}
		else {
			$user_id = $this->user_model->add_user(); 
			if($user_id) {
				logActivity('Created', "Created New User : " . $this->input->post('name'), $user_id);
				$ret = array(
				  'status' => 'success',
				  'msg'  => 'User Successfully Added',
				);
			}
			else {
				$ret = array(
				  'status' => 'fail',
				  'msg'  => 'Something Wrong',
				);
			}
			echo json_encode($ret);	
		}
	}

	public function edit($id,$submit=FALSE) {
		$data['user'] = $this->user_model->get_users($id); 
		if (empty($data['user'])) {
			show_404();
		}
		 $this->load->helper('form');
	      $this->load->library('form_validation');

	      $this->form_validation->set_rules('name', 'Name', 'required');
		  $old_info = $this->user_model->get_users($id);
		  $this->form_validation->set_rules('username', 'Username', 'required|callback_pv_edit_check_username[' . $old_info['username'] . ']');
		  //$this->form_validation->set_rules('password', 'Password', 'required');
		  $this->form_validation->set_rules('role_id', "Role", 'required');
		  $this->form_validation->set_rules('nric', 'NRIC', 'required');
		  $this->form_validation->set_rules('cea_no', 'CEA No.', 'required');


		if($this->form_validation->run() === FALSE) {
			$data['action'] = 'edit';
			$data['nav_area'] = 'project';
			if($submit == FALSE) { 
			 	$data['users'] = $this->user_model->getUsers();
				//$data['details'] =  $this->property_model->getFilesbypropertyid($id);
				$data['roles'] = $this->role_model->getRoles(); 
				$data['teams'] = $this->team_model->get_teams(); 
				
				$this->load->view('template/header', $data);
				$this->load->view('user/edit', $data);
				$this->load->view('template/footer', $data);		
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => validation_errors(),
				);	
				echo json_encode($ret);
			}			
		} 
		else {
			$updated = $this->user_model->update_user($id);
	        if($updated) {
	        	logActivity('Updated', "Updated User : " . $this->input->post('name'), $id);
	        	$ret = array(
		          'status' => 'success',
		          'msg'  => 'User Successfully Updated',
		        );
	        }
	        else {
	        	$ret = array(
		          'status' => 'fail',
		          'msg'  => 'Error in User Update',
		        );
	        }	
			echo json_encode($ret);
		}
	}

	
	
	
	
	public function delete($id) {
		$property = $this->property_model->get_properties($id);
		if ( !empty($property) ) {
			$deleted = $this->property_model->delete_property($id);  
		    if($deleted) {
		    	//logActivity('Deleted', "Deleted property : " . $property['property_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> 'Property Successfully Deleted',
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in property Delete',
			    );
		    }
		}
		else {
	    	$ret = array(
		      'status' 	=> 'fail',
		      'msg'  	=> 'No data',
		    );			
		}
	    echo json_encode($ret);
	}
	
	
	function upload()
	{
		//$output = '';
		if(is_array($_FILES))  
		 {  
			  foreach($_FILES['cv_files']['name'] as $name => $value)  
			  {  
				   $file_name = explode(".", $_FILES['cv_files']['name'][$name]);  
				   $allowed_extension = array("pdf","doc","docx","xls","xlsx","csv");  
				   if(in_array($file_name[1], $allowed_extension))  
				   {  
						//$new_name = rand() . '.'. $file_name[1];  
						$new_name = $file_name[0] . '_'.date('Ymdhis').'.'. $file_name[1];  
						$sourcePath = $_FILES["cv_files"]["tmp_name"][$name];  
						$targetPath = "uploads/user/cv/".$new_name;  
						move_uploaded_file($sourcePath, $targetPath); 

				   }

					
			  }  $output = '<input type ="hidden" name ="cv" id="cv-new-file-name" value ="' . $targetPath .'" >';
						//$output .= '<input type ="text" name ="new_file_name" id="new-file-name" value ="' . $new_name .'" >';
				   echo $output;
			
		 }  
	}
			
		
	function upload_main_img()
	{
		//$output = '';
		if(is_array($_FILES))  
		{  
			foreach($_FILES['main_image']['name'] as $name => $value)  
			{  
				$file_name = explode(".", $_FILES['main_image']['name'][$name]);  
				$allowed_extension = array("jpg", "jpeg", "png", "gif","JPG");
				//$allowed_extension = array("jpg", "jpeg", "png", "gif","pdf","doc","docx","xls","xlsx","csv"); 
				if(in_array($file_name[1], $allowed_extension))  
				{  
					//$new_name = rand() . '.'. $file_name[1];  
					$new_name = $file_name[0] . '_'.date('Ymdhis').'.'. $file_name[1];  
					$sourcePath = $_FILES["main_image"]["tmp_name"][$name];  
					$targetPath = "uploads/user/img/".$new_name;  
					move_uploaded_file($sourcePath, $targetPath); 
					
				
					
					$output = '<input type ="hidden" name ="user_img" id="img-new-file-name" value ="' . $targetPath .'" >';
					//$output .= '<script type ="text/javascript">console.log($("main-new-file-name").val())</script>';
					echo $output;
				}

					
			}  
		
		}  
	}
	
	
	public function aj_getTeamTierDetail($team_id) {
		$team['data'] = $this->user_model->getTeamTierDetails($team_id);
		$team['count'] = count($team['data']);
		//$team['levels'] = $team['data['levels'];
		if(!empty($team)) {
			$team['status'] = 'success';
			
			//$client['designation']	=$client['designation'];
			
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($team);
	}
	

		
	public function aj_getUserBelongto($team_id,$level) {
		$team['data'] = $this->user_model->getUserBelongto($team_id,$level);
		$team['count'] = count($team['data']);
		//$team['levels'] = $team['data['levels'];
		if(!empty($team)) {
			$team['status'] = 'success';
			
			//$client['designation']	=$client['designation'];
			
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Record'
			);
		}
		echo json_encode($team);
	}

	public function pv_getNamefromarray($arr, $ret = FALSE) {
		if($ret == FALSE) {
			$ret = array();
		}
		foreach($arr as $ar) {
			$ret[] = $ar['name'];
		}
		return $ret;
	}


	
	//}


}