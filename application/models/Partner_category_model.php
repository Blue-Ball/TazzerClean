<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Partner_category_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = 'partner_categories';
    }

    public function find_partner_categories($where)
    {
        $select_field  = "pc.id, pc.user_id, pc.status, pc.subcategory_id";
        $select_field .= ",u.name as user_name, u.email as user_email";
        $select_field .= ",mc.category_name as category_name, mc.id as category_id";
        $select_field .= ",sc.subcategory_name as subcategory_name";

        $this->db->select($select_field);
        $this->db->from('partner_categories as pc');
        $this->db->join('subcategories sc', 'pc.subcategory_id = sc.id', 'LEFT');
        $this->db->join('categories mc', 'sc.category = mc.id', 'LEFT');
        $this->db->join('users u', 'pc.user_id = u.id', 'LEFT');
        $this->db->where($where);
        $query = $this->db->get(); 
        // $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
        $result = $query->result_array();
        return $result;
    }

    public function find_partner(){
        $select_field  = "id, name, email";

        $this->db->select($select_field);
        $this->db->from('users');
        $this->db->where('you_are_appling_as',C_YOUARE_PARTNER);
        $this->db->where('status','1');
        $query = $this->db->get();
        // $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
        $result = $query->result_array();
        return $result;   
    }

    public function insert_partner_category($input_data){
        $where = "pc.user_id = ".$input_data['user_id']." AND pc.subcategory_id = ".$input_data['subcategory_id'];
        $flag = $this->find_partner_categories($where);
        if(!$flag){
            $input_data['created_at'] = date('Y-m-d H:i:s');
            $result  = $this->db->insert($this->table,$input_data);
            if($result){
                $ret = 1;
            }else{
                $ret = 3; //insert failed.
            }            
        }else{
            $ret = 2; //already exsist.
        }        
        return $ret;
    }

    public function update_partner_category($data) {
        $where = "pc.user_id = ".$data['user_id']." AND pc.subcategory_id = ".$data['subcategory_id'];
        $where .= " AND pc.id != ". $data['id'];
        $flag = $this->find_partner_categories($where);
        if(!$flag){
            $result  = $this->db->where('id', $data['id'])->update('partner_categories',$data);
            if($result){
                $ret = 1;
            }else{
                $ret = 3; //update failed.
            }            
        }else{
            $ret = 2; //already exsist.
        }        
        return $ret;
    }
    
    public function getCategories(){
        $select_field  = "mc.*";
        $this->db->select($select_field);
        $this->db->from('partner_categories as pc');
        $this->db->join('subcategories sc', 'pc.subcategory_id = sc.id', 'INNER');
        $this->db->join('categories mc', 'sc.category = mc.id', 'INNER');
        $this->db->where('pc.status','1');
        $this->db->where('sc.status','1');
        $this->db->where('mc.status','1');
        $this->db->group_by('mc.id');
        $query = $this->db->get(); 
        // $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
        $result = $query->result_array();
        return $result; 
    }

    public function getSubCategories($category_id) {
        $select_field  = "sc.*";
        $this->db->select($select_field);
        $this->db->from('partner_categories as pc');
        $this->db->join('subcategories sc', 'pc.subcategory_id = sc.id', 'INNER');
        $this->db->where('pc.status','1');
        $this->db->where('sc.status','1');
        $this->db->where('sc.category',$category_id);
        $query = $this->db->get();        
        // $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
        $result = $query->result_array();
        return $result; 
    }

    public function delete_row($id)
    {
        $this->db->where('id',$id);
        $this->db->delete('partner_categories');
    }
       
}
