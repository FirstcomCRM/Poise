<?php
class form_agreement extends CI_Controller {
	
	
	private $error;
    private $success;
	
	
	
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->model('form_agreement_model');
	 	$this->load->model('form_category_model');
	/*	$this->load->model('production_model');
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
			$data['nav_area'] = 'form_agreement';

			$this->load->view('template/header', $data);
			$this->load->view('form_agreement/index', $data);
			$this->load->view('template/footer', $data);	
		}
		else { 													// Datatable Load
			$this->load->library("Datatables");
			$this->form_agreement_model->getdtforms();	
		}
	}

	public function view($id) {
		$data['form_agreement'] = $this->form_agreement_model->getform_agreementinfo($id); 
		if (empty($data['form_agreement'])) {
			show_404();
		}
		$data['details'] =  $this->form_agreement_model->getFilesbyformid($id);
		$this->load->view('form_agreement/view', $data);
	}

	public function create($submit = FALSE) {
		$this->load->helper('form');
		$this->load->library('form_validation');

	//	$this->form_validation->set_rules('form_agreement_no', 'form_agreement No', 'required');
		$this->form_validation->set_rules('form_title', 'Form Title', 'required');
		//$this->form_validation->set_rules('form_category', 'Form Category', 'required');
		//$this->form_validation->set_rules('quotation_id', 'Quotation', 'required');
		
		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'new';
				$data['nav_area'] = 'form_agreement';
				$data['categories'] = $this->form_category_model->get_form_categories();
		
				$this->load->view('template/header', $data);
				$this->load->view('form_agreement/edit', $data);
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
			
			
			$form_agreement_id = $this->form_agreement_model->add_form();
			if($form_agreement_id) {
				//logActivity('Created', "Created New form_agreement  : " . $this->input->post('form_agreement_no'), $form_agreement_id);
				$this->session->set_flashdata('msg', 'Form Successfully Created');
				$ret = array(
					'status' => 'success',
				);	
				//$this->pv_sendEmail($form_agreement_id);
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in Form Add'
				);
			}
			echo json_encode($ret);	
		}
	}


	public function edit($id,$submit = FALSE) {
		$data['form_agreement'] = $this->form_agreement_model->getform_agreementinfo($id); 
		if (empty($data['form_agreement'])) {
			show_404();
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('form_title', 'Form Title', 'required');
		//$this->form_validation->set_rules('form_category', 'Form Category', 'required');

		if($this->form_validation->run() === FALSE) {
			if($submit == FALSE) {
				$data['action'] = 'edit';
				$data['nav_area'] = 'form_agreement';
				$data['categories'] = $this->form_category_model->get_form_categories();
				$data['details'] = $this->form_agreement_model->getFilesbyformid($id);

				
				$this->load->view('template/header', $data);
				$this->load->view('form_agreement/edit', $data);
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
			$updated = $this->form_agreement_model->update_form_agreement($id);
			if($updated) {
				logActivity('Updated', "Updated New form_agreement : " . $this->input->post('form_agreement_no'), $id);
				$this->session->set_flashdata('msg', 'form_agreement Successfully Updated');
				$ret = array(
					'status' => 'success',
				);	
			}
			else {
				$ret = array(
					'status' => 'fail',
					'msg'	 => 'Error in form_agreement Update'
				);
			}	
			echo json_encode($ret);
		}
		
		
		
		
		
	}

	public function delete($id) {
		$form_agreement = $this->form_agreement_model->get_form_agreements($id);
		if ( !empty($form_agreement) ) {
			$deleted = $this->form_agreement_model->delete_form_agreement($id);  
		    if($deleted) {
		    	//logActivity('Deleted', "Deleted form_agreement : " . $form_agreement['form_agreement_no'], $id);
			    $ret = array(
			      'status' 	=> 'success',
			      'msg'  	=> 'form_agreement Successfully Deleted',
			    );	    	
		    }
		    else {
		    	$ret = array(
			      'status' 	=> 'fail',
			      'msg'  	=> 'Error in form_agreement Delete',
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
		  foreach($_FILES['images']['name'] as $name => $value)  
		  {  
			   $file_name = explode(".", $_FILES['images']['name'][$name]);  
			   $allowed_extension = array("pdf","doc","docx","xls","xlsx","csv");  
			   if(in_array($file_name[1], $allowed_extension))  
			   {  
					//$new_name = rand() . '.'. $file_name[1];  
					$new_name = $file_name[0] . '_'.date('Ymdhis').'.'. $file_name[1];  
					$sourcePath = $_FILES["images"]["tmp_name"][$name];  
					$targetPath = "uploads/form_agreement/".$new_name;  
					move_uploaded_file($sourcePath, $targetPath); 
					
			   }
		  }  
		  $output = '<input type ="text" name ="file_path" id="file-path" value ="' . $targetPath .'" >';
		  $output .= '<input type ="text" name ="new_file_name" id="new-file-name" value ="' . $new_name .'" >';
		  echo $output;
		}  
	}
	
	
	
	
	public function aj_addFormfile() {
		$detail_id = $this->form_agreement_model->addFile();
		if($detail_id) {
			$form_id = $this->input->post('hid_form_id');
			$details = $this->form_agreement_model->getFilesbyformid($form_id);
			//logActivity('Added', "Added Invoice Detail : " . $this->input->post('description'), $detail_id);
			$ret = array(
				'status'			=> 'success',
				'form_files'	=> $details
			);
		}
		else {
			$ret = array(
				'status'	=> 'fail',
			);	
		}
		echo json_encode($ret);
	}
		
		
		
		
		public function aj_deleteFormfile($id) {
		$form_file = $this->form_agreement_model->getFile($id);
		if(!empty($form_file)) {
			$removed = $this->form_agreement_model->removeFile($id);
			if($removed) {
				//$this->announcement_model->getFilesbyannouncementid($id);
				$details = $this->form_agreement_model->getFilesbyformid($form_file['form_id']);
				//logActivity('Removed', "Removed Invoice Detail : " . $invoice_detail['description'], $id);
				$ret = array(
					'status'			=> 'success',
					'form_files'	=> $details
				);
			}
			else {
				$ret = array(
					'status'	=> 'fail',
					'msg'		=> 'Error in File remove'
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
		
		

}