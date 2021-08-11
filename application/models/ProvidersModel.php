<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

/**
 * @author Leo: Providers Model
 * @created: 
*/
 
class ProvidersModel extends CI_Model{ 
    
    private $primaryKey = "id";
    private $name = "providers";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get($id) {
        $this->db->select()->from($this->name);
        $this->db->where($this->primaryKey, $id);
        return $this->db->get()->row_array();
    }
    
}