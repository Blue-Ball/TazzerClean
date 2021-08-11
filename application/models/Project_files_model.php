<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Project_files_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = 'project_files';
    }

    public function insertData($data){
        $result = $this->db->insert($this->table,$data);
        return $result;
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
