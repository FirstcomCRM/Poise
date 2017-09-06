<?php
class permission_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_permissions($id = FALSE) {
		if ($id === FALSE)
		{
			$this->db->where('status !=', 1);
			$query = $this->db->get('permission');
			return $query->result_array();
		}

		$query = $this->db->get_where('permission', array('permission_id' => $id));
		return $query->row_array();
	}

	public function add_permission() {
		$role_id = $this->input->post('role_id');
		$chk_perms = $this->input->post();
		unset( $chk_perms['role_id'] );
		if(count($chk_perms) > 0) {
			foreach($chk_perms as $key=>$chk_perm) {
				$data = array(
					'role_id' 		=> $role_id ,
					'controller' 	=> $key,
					'permission'	=> implode(', ', array_keys($chk_perm)),
				);	
				$this->db->insert('permission', $data);		
			}
		}
		return $role_id;
	}

	public function update_permission($role_id) {
		$old_permission = $this->getPermisssionbyrole($role_id);
		$new_permission = $this->input->post(); 
		$new_role_id = $new_permission['role_id'];
		unset( $new_permission['role_id'] );

		$old_permission = $this->reStructurearray($old_permission);

		//Loop new chk array
		foreach($new_permission as $newKey=>$new) {
			if (array_key_exists($newKey, $old_permission) ) {
				$this->updatePermission($role_id, $newKey, $new);	
			}
			else {
				$this->addPermission($role_id, $newKey, $new);

			}
		}

		//Loop old chk array 
		foreach($old_permission as $oldKey=>$op) {
			if (!array_key_exists($oldKey, $new_permission) ) {
				$this->removePermission($role_id, $oldKey);
			}		
		}

		//Update for role_id Change
		$data = array(
			'role_id' => $new_role_id,
		);
		$this->db->where('role_id', $role_id);
		return $this->db->update('permission', $data);
	}

	public function remove_permission($permission_id) {
		$data = array(
			'status' => 1,
        );
        $this->db->where('permission_id', $permission_id);
		return $this->db->update('permission', $data); 
	}

	public function getdtPermission() {
  		// $this->datatables->select('p.permission_id, p.role_id, r.name as role, p.controller, p.permission');
  		// $this->datatables->from('permission p');
  		// $this->datatables->join('role r', 'p.role_id = r.role_id', 'left');
		// $this->datatables->where('p.status !=', 1);

		// $this->datatables->add_column('no', '');
		// $this->datatables->add_column('action', '<a class="edit-link" href="permission/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" href="permission/delete/$2"><i class="fa fa-trash-o ico"></i></a>', 'role_id, permission_id');
		// echo $this->datatables->generate();
		$this->datatables->select("p.permission_id, p.role_id, r.name as role, GROUP_CONCAT( p.controller SEPARATOR  ', ' ) AS controller", false);
        $this->datatables->from('permission p');
        $this->datatables->join('role r', 'p.role_id = r.role_id', 'left');
		$this->datatables->where('p.status !=', 1);
		$this->datatables->group_by('p.role_id');

		$this->datatables->add_column('no', '');
		//$this->datatables->add_column('action', '<a class="view-link" data-toggle="tooltip" data-placement="top" title="View Detail" href="permission/view/$1"><i class="fa fa-eye ico"></i></a> / <a class="edit-link" data-toggle="tooltip" data-placement="top" title="Edit" href="permission/edit/$1"><i class="fa fa-edit ico"></i></a> / <a class="delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="permission/delete/$2"><i class="fa fa-trash-o ico"></i></a>', 'role_id, permission_id');
		$this->datatables->add_column('action', '<ul class="list-inline hidden-xs"><li class = "li-padds"><a class="btn btn-mtac admin-control btn-view btn-success" data-toggle="tooltip" data-placement="top" title="Edit" href="permission/edit/$1">Edit</a></li><li class = "li-padds"><a class="btn btn-mtac btn-delete btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete" href="permission/delete/$1">Delete</a></li></ul>', 'role_id' , 'permission_id');
		echo $this->datatables->generate();
	}

	public function getPermisssionbyrole($role_id) {
		$this->db->where('role_id', $role_id);
		$this->db->where('status !=', 1);
		$query = $this->db->get('permission');
		return $query->result_array();
	}

	public function addPermission($role_id, $controller, $perm) {
		$data = array(
			'role_id'		=> $role_id,
			'controller'	=> $controller,
			'permission'	=> implode(', ', array_keys($perm)),
		);
		return $this->db->insert('permission', $data); 
	}

	public function updatePermission($role_id, $controller, $perm) {
		$data = array(
			'permission'	=> implode(', ', array_keys($perm)),
		);
		$this->db->where('role_id', $role_id);
		$this->db->where('controller', $controller);
		return $this->db->update('permission', $data); 
	}

	public function removePermission($role_id, $controller) {
		$data = array(
			'status'	=> 1,
		);
		$this->db->where('role_id', $role_id);
		$this->db->where('controller', $controller);
		return $this->db->update('permission', $data); 
	}

	private function reStructurearray($array) {
		$newarr = array();
		if(count($array) > 0) {
			foreach($array as $arr) {
				$newarr[$arr['controller']] = $arr;
			}		
		}
		return $newarr;
	}
}