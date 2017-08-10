<?php
class announcement extends CI_Controller {
	
	
	private $error;
    private $success;

	
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("pagination");
		//$this->load->model('announcement_model','file');
		$this->load->model('announcement_model');
	/* 	$this->load->model('service_model');
		$this->load->model('production_model');
		$this->load->model('uom_model');
		$this->load->model('client_model');
		$this->load->model('quotation_model'); */
		//$this->load->model('setting_model');
		$this->load->model('user_model');
		checkPermission();
	}
	
	
	
	
	
	private function handle_error($err) {
        $this->error .= $err . "\r\n";
    }

    private function handle_success($succ) {
        $this->success .= $succ . "\r\n";
    }
	public function index($dt = FALSE)	{	
		if($dt === FALSE ) { 									// First time load
			$data['msg'] = $this->session->flashdata('msg');
			$data['nav_area'] = 'announcement';

			$this->load->view('template/header', $data);
			$this->load->view('announcement/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->announcement_model->getdtannouncements();	
		}
	}

	public function view($id) {
		$data['announcement'] = $this->announcement_model->getannouncementinfo($id); 
		if (empty($data['announcement'])) {
			show_404();
		}
		$data['details'] =  $this->announcement_model->getFilesbyannouncementid($id);
		$this->load->view('announcement/view', $data);
	}

	public function create($submit = FALSE) {
		$this->load->helper('form');
		$this->load->library('form_validation');

	//	$this->form_validation->set_rules('announcement_no', 'announcement No', 'required');
		$this->form_validation->set_rules('announcement_title', 'Announcement Title', 'required');
		$this->form_validation->set_rules('announcement_date', 'Announcement Date', 'required');
		//$this->form_validation->set_rules('quotation_id', 'Quotation', 'required');
		
		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'new';
				$data['nav_area'] = 'announcement';

				$this->load->view('template/header', $data);
				$this->load->view('announcement/edit', $data);
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

			$announcement_id = $this->announcement_model->add_announcement();
			
			if($announcement_id) {
				//logActivity('Created', "Created New announcement  : " . $this->input->post('announcement_no'), $announcement_id);
				$this->session->set_flashdata('msg', 'Announcement Successfully Created');
				$ret = array(
					'status' => 'success',
				);	
				//$this->pv_sendEmail($announcement_id);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in announcement Add'
				);
			}
			echo json_encode($ret);	
		}
	}


	public function edit($id,$submit = FALSE) {
		$data['announcement'] = $this->announcement_model->getannouncementinfo($id); 
		if (empty($data['announcement'])) {
			show_404();
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('announcement_title', 'Announcement Title', 'required');
		$this->form_validation->set_rules('announcement_date', 'Announcement Date', 'required');

		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'edit';
				$data['nav_area'] = 'announcement';
				$data['details'] =  $this->announcement_model->getFilesbyannouncementid($id);

				
				$this->load->view('template/header', $data);
				$this->load->view('announcement/edit', $data);
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
			$updated = $this->announcement_model->update_announcement($id);
			if($updated) {
				logActivity('Updated', "Updated New announcement : " . $this->input->post('announcement_no'), $id);
				$this->session->set_flashdata('msg', 'Announcement Successfully Updated');
				$ret = array(
					'status' => 'success',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in announcement Update'
				);
			}	
			echo json_encode($ret);
		}

		
	}

	public function delete($id) {
		$announcement = $this->announcement_model->get_announcements($id);
		if ( !empty($announcement) ) {
			$deleted = $this->announcement_model->delete_announcement($id);  
		    if($deleted) {
		    	//logActivity('Deleted', "Deleted announcement : " . $announcement['announcement_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> 'Announcement Successfully Deleted',
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in Announcement Delete',
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


		function get_first_file()
		{
			$output = '';
			if(is_array($_FILES))  
			 {  
				  foreach($_FILES['images']['name'] as $name => $value)  
				  {  
					   $file_name = explode(".", $_FILES['images']['name'][$name]);  
					   $allowed_extension = array("jpg", "jpeg", "png", "gif");  
					   if(in_array($file_name[1], $allowed_extension))  
					   {  
							$new_name = rand() . '.'. $file_name[1];  
							$sourcePath = $_FILES["images"]["tmp_name"][$name];  
							$targetPath = "uploads/announcement/".$new_name;  
							//move_uploaded_file($sourcePath, $targetPath); 
							//$output .= '<input type="file" name="images[]" id ="img_select">';	
							$output .= '<input type ="hidden" name ="filename" id="file_name" value ="' . $targetPath .'" >';

							
							echo $output;
					   }  
				  }  
				  /* $images = glob("uploads/*.*");  
				  foreach($images as $image)  
				  {  
					  // $output .= '<div class="col-md-2" align="center" ><img src="' . $image .'" width="180px" height="180px" style="border:1px solid #ccc;" /></div>';  
					   $output .= '<input type ="hidden" name ="filename" id="filename" value ="' . $image .'" >'; 
					   
				  }  
				  echo $output;  */ 
			 }  
		}
	

	
		function upload()
		{
			//$output = '';
			if(is_array($_FILES))  
			 {  
				  foreach($_FILES['images']['name'] as $name => $value)  
				  {  
					   $file_name = explode(".", $_FILES['images']['name'][$name]);  
					   $allowed_extension = array("jpg", "jpeg", "png", "gif","pdf","doc","docx","xls","xlsx","csv");  
					   if(in_array($file_name[1], $allowed_extension))  
					   {  
							//$new_name = rand() . '.'. $file_name[1];  
							$new_name = $file_name[0] . '_'.date('Ymdhis').'.'. $file_name[1];  
							$sourcePath = $_FILES["images"]["tmp_name"][$name];  
							$targetPath = "uploads/announcement/".$new_name;  
							move_uploaded_file($sourcePath, $targetPath); 
							//$output .= '<input type="file" name="images[]" id ="img_select">';	
							/* $output .= '<form id="detail-form">
                                <td>
								
                                  <!--input type="hidden" id="hid-edit-id" name="hid_edit_id" /-->
                                  
									<input type="file" name="images[]" id ="img_select">
									
								</td>
								<td>
									<input type="text" class="form-control input-sm" id="path" name="path" placeholder="Enter Qty" value="" readonly>
								
								</td>
                                <td>

								 
								 <!--input type="file" name="upload_file1" class="btn btn-default" id="upload_file1" readonly="true"/-->
                                  <span id="detail-add"><a href="#" id="ico-add"><i class="fa fa-plus ico"></i></a></span>
                                  <span id="detail-update" style="display: none;"><a href="#" id="ico-update" ><i class="fa fa-save ico"></i></a> / <a href="#" id="ico-cancel" ><i class="fa fa-eraser ico"></i></a></span>
                                </td>
                              </form>'; */

					   }

						
				  }  $output = '<input type ="text" name ="file_path" id="file-path" value ="' . $targetPath .'" >';
							$output .= '<input type ="text" name ="new_file_name" id="new-file-name" value ="' . $new_name .'" >';
					   echo $output;
				  /* $images = glob("uploads/*.*");  
				  foreach($images as $image)  
				  {  
					  // $output .= '<div class="col-md-2" align="center" ><img src="' . $image .'" width="180px" height="180px" style="border:1px solid #ccc;" /></div>';  
					   $output .= '<input type ="hidden" name ="filename" id="filename" value ="' . $image .'" >'; 
					   
				  }  
				  echo $output;  */ 
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
							$targetPath = "uploads/announcement/main_images/".$new_name;  
							move_uploaded_file($sourcePath, $targetPath); 
							
							/* $output.='<form id ="image-form">
							  <label for="name" class="col-md-3 control-label">Image</label>
							  <div class="col-sm-7">
								<input type="file" name="main_image[]" id ="main_img_select">
								<input type="hidden" class="form-control input-sm" id="main-file-name" name="main_file_name" placeholder="Enter Qty" value="">
							  </div>
							</form>';

							//$output .= '<form id ="image-form"><input type="file" name="main_image[]" id ="main_img_select">';
							$output .= '<input type ="hidden" name ="main_file_path" id="main-file-path" value ="' . $targetPath .'" >';
							
							//$output .= '<div class="col-md-2" align="center" ><img src="' . base_url().$targetPath .'" width="180px" height="180px" style="border:1px solid #ccc;" /></div></form>'; ;*/
							
							$output = '<input type ="hidden" name ="main_new_file_name" id="main-new-file-name" value ="' . $targetPath .'" >';
							//$output .= '<script type ="text/javascript">console.log($("main-new-file-name").val())</script>';
							echo $output;
						}

						
				  }  
			
			 }  
		}
		
		
		

	
	    public function do_upload()
        {
			$new_name = $this->input->post('image');
			$announcement_id = $this->input->post('announce_id');
			//$path_name = "public/announcement_pictures/".$new_name.".jpg";
			$pathX = $this->input->post('announce_img_path');
			$path = $path_name.".jpg";

			$config['upload_path']          = './public/announcement_images/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 2048;
			$config['max_width']            = 2048;
			$config['max_height']           = 2048;
			$config['file_name'] 			= $new_name;
			$config['overwrite']			= TRUE;
			
			
			//unlink($pathX); 

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('userfile'))
			{
					$error = array('error' => $this->upload->display_errors());

					//$this->load->view('upload_form', $error);
					$this->session->set_flashdata('msg', 'Image upload failed');
					redirect(base_url().'announcement/edit/'.$announcement_id);
			}
			else
			{

					$this->announcement_model->update_img(12);

					//$data = array('upload_data' => $this->upload->data());
					//$this->load->view('announcement/edit_img', $data);
					//$this->output->cache(10);
					$this->session->set_flashdata('msg', 'Image successfully updated');
					redirect(base_url().'announcement/edit/'.$announcement_id);
			}
        }
		
		public function aj_addAnnouncefile() {
		$detail_id = $this->announcement_model->addFile();
		if($detail_id) {
			$announce_id = $this->input->post('hid_announcement_id');
			$details = $this->announcement_model->getFilesbyannouncementid($announce_id);
			//logActivity('Added', "Added Invoice Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'announcement_files'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}
		
		
		
		
		public function aj_deleteAnnouncefile($id) {
		$announce_file = $this->announcement_model->getFile($id);
		if(!empty($announce_file)) {
			$removed = $this->announcement_model->removeFile($id);
			if($removed) {
				//$this->announcement_model->getFilesbyannouncementid($id);
				$details = $this->announcement_model->getFilesbyannouncementid($announce_file['announce_id']);
				//logActivity('Removed', "Removed Invoice Detail : " . $invoice_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'announcement_files'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in Invoice Detail remove'
				);	
			}			
		}
		else {
			$ret = array(
				'status'	=> 'fail',
				'msg'		=> 'No Data',
			);	
		}
		echo json_encode($ret);
	}
		
		
		
		
		
		
		 /*  // set upload preferences
			$announcement_id = $this->input->post('announce_id');
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = '*';
			$config['max_size']    = '150';

			//initialize upload class
			$this->load->library('upload', $config);
			
			$upload_error = array();
			
			for($i=0; $i<count($_FILES['usr_files']['name']); $i++)
			{
				$_FILES['userfile']['name']= $_FILES['usr_files']['name'][$i];
				$_FILES['userfile']['type']= $_FILES['usr_files']['type'][$i];
				$_FILES['userfile']['tmp_name']= $_FILES['usr_files']['tmp_name'][$i];
				$_FILES['userfile']['error']= $_FILES['usr_files']['error'][$i];
				$_FILES['userfile']['size']= $_FILES['usr_files']['size'][$i];
				
				if (!$this->upload->do_upload())
				{
					// fail
					$upload_error = array('error' => $this->upload->display_errors());
					redirect(base_url().'announcement/create/');
					break;
				}
			}
			
			// success
			if ($upload_error == NULL)
			{
				$data['success_msg'] = '<div class="alert alert-success text-center">Finished uploading...</div>';
				redirect(base_url().'announcement/create/');
			} */
		
		
		
		
			//	$files_id=array();
			
			// if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
			// {
				// $vpb_file_name = strip_tags($_FILES['upload_file']['name']); //File Name
				// $vpb_file_id = strip_tags($_POST['upload_file_ids']); // File id is gotten from the file name
				// $vpb_file_size = $_FILES['upload_file']['size']; // File Size
				// $vpb_uploaded_files_location =  './uploads/announcement_files/'; //This is the directory where uploaded files are saved on your server
				// $vpb_final_location = $vpb_uploaded_files_location . $vpb_file_name; //Directory to save file plus the file to be saved
			//	Without Validation and does not save filenames in the database
				
				// foreach($_FILES['upload_file']['name'] as $filename) {
				/* 	if(move_uploaded_file(strip_tags($_FILES['upload_file']['tmp_name']), $vpb_final_location))
					{ */
						// Display the file id
					//	echo "the file id is>>>".$vpb_file_id;
						// $this->announcement_model->add_files($vpb_file_id);
						// $file_id = $this->announcement_model->add_files($vpb_file_id,$vpb_final_location);
						// if($file_id){
							// $files_id[] =$file_id;
							// echo "count is>>>".count($files_id);
						// }
						
						
					// }
					// else
					// {
					//	Display general system error
						// echo 'general_system_error';
					// }
			//	}
				

			// }
			//$this->create();
			
		
		
		
		
		
		
		
		
		
		
		

}