<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Project_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = 'projects';
    }

    public function getRows($params = array()){
        $select_field  = "*";
        $this->db->select($select_field);
        $this->db->from($this->table);
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        } 

        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            $result = $this->db->count_all_results(); 
        }else{ 
            if(array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')){ 
                if(!empty($params['id'])){ 
                    $this->db->where('id', $params['id']); 
                } 
                $query = $this->db->get(); 
                $result = $query->row_array(); 
            }else{ 
                // $this->db->order_by('id', 'desc'); 
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                } 

                if(array_key_exists("order_by",$params)){
                    $this->db->order_by($params['order_by']);
                }
            
                $query = $this->db->get(); 
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE; 
            } 
        } 
        return $result;
    }

    public function insertData($data){
        $result = $this->db->insert($this->table,$data);
        // print_r($this->db->last_query());
        $id = $this->db->insert_id();
        return $id;
    }

    public function updataData($data){
        $result  = $this->db->where('id', $data['id'])->update($this->table,$data);
    }

    public function deleteData($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table);
    }
       
}
