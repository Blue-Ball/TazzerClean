<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
/**
 * @author Leo: Service offered Model
 * @created: 
*/

class ServiceOfferedModel extends CI_Model{ 
    
    private $primaryKey = "id";
    private $name = "service_offered";
    private $indexKey = "service_id";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get($service_id) {
        $this->db->select()->from($this->name);
        $this->db->where($this->indexKey, $service_id);
        return $this->db->get()->result_array();
    }
    
}