<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class announcement_model extends CI_Model {
	 private $file = 'files';  
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

	
	
	public function get_top_announcements($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->join('users u', 'u.user_id = announcement.user_id', 'left');
			$this->db->where('announcement.status !=', 1);
			$this->db->order_by('announce_date', 'DESC');
			$this->db->limit(5);
			$query = $this->db->get('announcement');
			return $query->result_array();
		}

		$query = $this->db->get_where('announcement', array('announce_id' => $id));
		return $query->row_array();
	}
	
	
	
	 private function handle_error($err) {
        $this->error .= $err . "\r\n";
    }

    private function handle_success($succ) {
        $this->success .= $succ . "\r\n";
    }

	/* public function add_files($announce_id,$filename,$filepath){
		
		$data = array(
			'file_name'				=> $filename,
			'file_path'				=> $filepath,
			'date_uploaded'			=> date('Y-m-d'),
			//'announcement_date'					=> $this->input->post('terms'),
		);
		$this->db->insert('announcement_files', $data);
		$announce_file_id = $this->db->insert_id();
		/* if($announce_id) {
			$this->addDetails($announce_id);	
		}*/
		/*return $announce_file_id; 
		
		
		
		
	} */
	
	
	public function add_announcement() {
		//$announcement_date = get_timestamp('d/m/Y', '/');
		
		/*if ($this->input->post('file_upload')) {
							//file upload destination
							$dir_path = './upload/';
							$config['upload_path'] = $dir_path;
							$config['allowed_types'] = '*';
							$config['max_size'] = '0';
							$config['max_filename'] = '255';
							$config['encrypt_name'] = TRUE;

							//upload file
							$i = 0;
							$files = array();
							$is_file_error = FALSE;

							if ($_FILES['upload_file1']['size'] <= 0) {
								$this->handle_error('Select at least one file.');
							} else {
								foreach ($_FILES as $key => $value) {
									if (!empty($value['name'])) {
										$this->load->library('upload', $config);
										if (!$this->upload->do_upload($key)) {
											$this->handle_error($this->upload->display_errors());
											$is_file_error = TRUE;
										} else {
											$files[$i] = $this->upload->data();
											++$i;
											//echo ">>>>>>>>>>>>>>.".$files[$i];
										}
									}
								}
							}
							//echo "FILE IS>>>".count($files);
							// There were errors, we have to delete the uploaded files
							if ($is_file_error && $files) {
								for ($i = 0; $i < count($files); $i++) {
									$file = $dir_path . $files[$i]['file_name'];
									//echo "FILE ISSSSSS>>>".$files[$i]['file_name'];
									if (file_exists($file)) {
										unlink($file);
									}
								}
							}

							if (!$is_file_error && $files) {
								$resp = $this->save_files_info($files);
								if ($resp === TRUE) {
									$this->handle_success('File(s) was/were successfully uploaded.');
								} else {
									for ($i = 0; $i < count($files); $i++) {
										$file = $dir_path . $files[$i]['file_name'];
										if (file_exists($file)) {
											unlink($file);
										}
									}
									$this->handle_error('Error while saving file info to Database.');
								}
							}
						
					}*/
		
		
		$data = array(
			'announce_title'		=> $this->input->post('announcement_title'),
			'announce_body'			=> $this->input->post('announcement_body'),
			'announce_date'			=> date('Y-m-d',strtotime($this->input->post('announcement_date'))),
			'announce_img'			=> $this->input->post('main_new_file_name'),
			'user_id'				=> $this->session->userdata('user_id'),
			//'announcement_date'					=> $this->input->post('terms'),
		);
		$this->db->insert('announcement', $data);
		$announce_id = $this->db->insert_id();
		if($announce_id) {
			$this->add_files($announce_id);	
		}
		return $announce_id;
	}
	
	
	public function add_files($announce_id) {
		$details = $this->input->post('files_info');
		/* echo '<pre>';
		print_r($details);
		echo '</pre>';
		die(); */
		if($details) {
			foreach($details as $detail) {
				//$no = ($detail['no']) ? $detail['no'] : NULL;
				$data = array(
					'announce_id'	 	=> $announce_id,
					'file_name'			=> mysqli_real_escape_string($detail['file_name']),
					'new_file_name'		=> mysqli_real_escape_string($detail['new_file_name']),
					'file_path'			=> mysqli_real_escape_string($detail['file_path']),
					'date_uploaded'		=> date('Y-m-d'),
					//'file_name'		=> $this->input->post('name'),
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
			'announce_date'			=> date('Y-m-d',strtotime($this->input->post('announcement_date'))),
			'announce_img'			=> $this->input->post('main_new_file_name'),
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
		
        $this->datatables->select("a.announce_id,a.announce_img,a.announce_title,u.username, a.announce_body,a.announce_date,a.announce_img", false);
        $this->datatables->from('announcement a');
		$this->datatables->join('users u', 'u.user_id = a.user_id', 'left');
		$this->datatables->where('a.status !=', 1);
		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="view-link btn btn-mtac admin-control btn-view" data-toggle="tooltip" data-placement="top" title="View" href="announcement/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="print-link" data-toggle="tooltip" data-placement="top" title="Print" href="announcement/printannouncement/$1"><i class="fa fa-print ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="announcement/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'announce_id');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View" href="announcement/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="announcement/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'announce_id');
		$this->datatables->add_column('action', '<a class="btn btn-mtac admin-control btn-view btn-primary view-link" data-toggle="tooltip" data-placement="top" title="View" href="announcement/view/$1">View</a><a class="btn btn-mtac admin-control btn-view btn-success" data-toggle="tooltip" data-placement="top" title="Edit" href="announcement/edit/$1">Edit</a><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1">Delete</a>', 'announce_id');
		//$this->datatables->add_column('action', '<a class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="View" href="announcement/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="announcement/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="announcement/delete/$1"><i class="fa fa-trash-o ico"></i></a>', 'announce_id');
		//$this->datatables->add_column('action', '<a class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="View" href="property/view/$1"><i class="fa fa-save ico-btn"></i>View</a><a class="btn btn-mtac admin-control btn-approve" data-toggle="tooltip" data-placement="top" title="Approve" href="property/edit_status/$1"><i class="fa fa-save ico-btn"></i>Approve</a><a class="btn btn-mtac admin-control btn-reject" data-toggle="tooltip" data-placement="top" title="Reject" href="property/edit_status/$1"><i class="fa fa-save ico-btn"></i>Reject</a>', 'property_id');
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
	
	
    
    function save_files_info($files) {
        //start db traction
        $this->db->trans_start();
        //file data
        $file_data = array();
        foreach ($files as $file) {
            $file_data[] = array(
                'file_name' => $file['file_name'],
                'file_orig_name' => $file['orig_name'],
                'file_path' => $file['full_path'],
                'upload_date' => date('Y-m-d H:i:s')
            );
        }
        //insert file data
        $this->db->insert_batch($this->file, $file_data);
        //complete the transaction
        $this->db->trans_complete();
        //check transaction status
        if ($this->db->trans_status() === FALSE) {
            foreach ($files as $file) {
                $file_path = $file['full_path'];
                //delete the file from destination
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            //rollback transaction
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //commit the transaction
            $this->db->trans_commit();
            return TRUE;
        }
    }
	
	
	public function addFile() {
		//$no = $this->input->post('no');
		$data = array(
			'announce_id'	 			=>  $this->input->post('hid_announcement_id'),
			'file_name'					=>  $this->input->post('file_name'),
			'new_file_name'				=>  $this->input->post('new_file_name'),
			'file_path'					=>  $this->input->post('file_path'),
			'date_uploaded'				=>  date('Y-m-d'),
			
		);
		$this->db->insert('announcement_files', $data);
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


}