<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model{
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function admin_validate() {
        // grab user input
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        
        //Prep the query
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        
        // Run the query
        $query = $this->db->get('users');
        // Let's check if there are any results
        if($query->num_rows == 1) {
            // If there is a user, then create session data
            $row = $query->row();
            $data = array(
                'login_user'    => $this->getRolename($row->role_id),//'admin',
                'user_id'       => $row->user_id,
                'name'          => $row->name,
                'role_id'       => $row->role_id,
                'user_img'       => $row->user_img,
                'email'         => $row->email,
                'validate_user' => true,
                'permissions'   => $this->getPermission($row->role_id),  
            );
            $this->session->set_userdata($data);
            return true;
        }
        // else {
        //     $engineer = $this->engineer_validate();
        //     if($engineer) {
        //         $data = array(
        //             'login_user'    => $this->getRolename($engineer->role_id),
        //             'user_id'       => $engineer->engineer_id,
        //             'name'          => $engineer->name,
        //             'role_id'       => $engineer->role_id,
        //             'email'         => $engineer->email,
        //             'validate_user' => true,
        //             'permissions'   => $this->getPermission($engineer->role_id), 
        //         );
        //         $this->session->set_userdata($data);
        //         return true;  
        //     }
        // }
        // If the previous process did not validate
        // then return false.
        return false;
    }

    // public function engineer_validate() {
    //     $username = $this->security->xss_clean($this->input->post('username'));
    //     $password = $this->security->xss_clean($this->input->post('password'));
        
    //     //Prep the query
    //     $this->db->where('username', $username);
    //     $this->db->where('password', md5($password));
        
    //     // Run the query
    //     $query = $this->db->get('engineer');
    //     if($query->num_rows == 1) {
    //         return $query->row();
    //     }
    //     else {
    //         return false;
    //     }
    // }

    public function getPermission($role_id) {
        $this->db->where('role_id', $role_id);
        $this->db->where('status !=', 1);
        $query = $this->db->get('permission');
        $results = $query->result_array();
        $newarr = array();
        if(count($results) > 0) {
            foreach($results as $arr) {
                $newarr[$arr['controller']] = $arr;
            }       
        }
        return $newarr;
    }

    public function getRolename($role_id) {
        $this->db->select('name');
        $this->db->from('role');
        $this->db->where('role_id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['name'];

    }

}
?>