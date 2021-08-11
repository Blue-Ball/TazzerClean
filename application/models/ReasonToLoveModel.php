<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

/**
 * @author Leo: Reason To Love Model
 * @created: 2021/8/1
*/
 
class ReasonToLoveModel extends CI_Model{ 
    
    private $primaryKey = "id";
    private $name = "reason_to_love";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function List($params = array()) {
        $this->db->select('m.*,c.category_name')->from($this->name." m");
        $this->db->join("categories c","c.id = m.category","LEFT");
        if (array_key_exists('category',$params) && !empty($params['category'])) {
            $this->db->where('category', $params['category']);
        }
        if (array_key_exists('status',$params) && !empty($params['status'])) {
            $this->db->where('status', $params['status']);
        }
        if (array_key_exists('limit',$params) && !empty($params['limit'])) {
            $this->db->limit($params['limit']);
        }
        if (array_key_exists('order_by',$params) && !empty($params['order_by'])) {
            $sort = "ASC";
            if (array_key_exists('sort',$params) && !empty($params['sort'])) {
                $sort = $params['sort'];
            }
            $this->db->order_by($params['order_by'], $sort);
        }
        else {
            $this->db->order_by('created_at','DESC');
        }
        $query = $this->db->get();
        if ($query !== false && $query->num_rows() > 0)
        {
            return $query->result_array();
        }
        return [];
    }

    public function add($params = array()) {
        $params['created_at'] = date("Y-m-d H:i:s");
        $params['updated_at'] = date("Y-m-d H:i:s");
        return $this->db->insert($this->name, $params);
    }

    public function get($id) {
        $this->db->select()->from($this->name);
        $this->db->where($this->primaryKey, $id);
        return $this->db->get()->row_array();
    }

    public function update($id, $params = array()) {
        $params['updated_at'] = date("Y-m-d H:i:s");
        $this->db->where($this->primaryKey, $id);
        return $this->db->update($this->name, $params);
    }

    public function delete($id) {
        return $this->db->delete($this->name, array(
            'id' => $id
        ));
    }
    
}