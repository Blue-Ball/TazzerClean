<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_meta_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = 'user_meta';
    }

    public function register_meta($user_id){
        $query = $this->db->select("*")->from($this->table)->where('user_id',$user_id)->get();
        if($query->num_rows() <= 0){
            $input_data = array();
            $input_data['user_id'] = $user_id;
            $result  = $this->db->insert($this->table,$input_data);
        }
    }

    public function get_meta_data($where) {
        $select_field  = "um.*"; //id, user_id, skill_id, department_id
        $select_field .= ",us.name as skill_name";
        $select_field .= ",pd.name as partner_department_name";
        $this->db->select($select_field);
        $this->db->from('user_meta as um');
        $this->db->join('user_skills us', 'um.skill_id = us.id', 'LEFT');
        $this->db->join('partner_department pd', 'um.department_id = pd.id', 'LEFT');
        $this->db->where($where);
        $query = $this->db->get(); 
        $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
        return $result;
    }

    public function update_meta_data($data) {
        $this->db->where('user_id', $data['user_id'])->update($this->table,$data);
    }
}
