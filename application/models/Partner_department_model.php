<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Partner_department_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = 'partner_department';
    }

    public function getDepartmentList() {
        $this->db->select("*");
        $this->db->from($this->table);
        return $this->db->get()->result_array();
    }

    public function insertDepartment($input_data){
        $where = "name = '".$input_data['name']."'";
        $flag = $this->db->select("*")->from($this->table)->where($where)->get()->result_array();
        if(!$flag){
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

    public function findDepartment($id){
        $result = $this->db->select("*")->from($this->table)->where('id',$id)->get()->row_array();
        return $result;
    }

    public function updateDepartment($data){
        $where = "name = '".$data['name']."'";
        $flag = $this->db->select("*")->from($this->table)->where($where)->get()->result_array();
        if(!$flag){
            $result  = $this->db->where('id', $data['id'])->update($this->table,$data);
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

    public function delete_row($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table);
    }
}
