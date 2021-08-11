<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2021-07-26
 * Time: 12:35 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class RatingReview_model extends CI_Model
{
    private $primaryKey = "id";
    private $name = "rating_review";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getReviews($userId){

        $this->db->select("*");
        $this->db->from($this->name);
        $this->db->where("user_id",$userId);
        $this->db->where("status",1);
        $this->db->order_by('id','DESC');
        return $this->db->get()->result_array();
    }

    public function get($params = array()) {
        $this->db->select("r.*,u.profile_img,u.name");
        $this->db->from($this->name.' r');
        $this->db->join('users u', 'u.id = r.user_id', 'LEFT');
        $this->db->where('r.status', 1);
        if (array_key_exists('service_id', $params)) {
            $this->db->where(array('r.service_id' => $params['service_id']));
        }
        if (array_key_exists('user_id', $params)) {
            $this->db->where(array('r.user_id' => $params['user_id']));
        }
        $query = $this->db->get();
        $data = array();
        if ($query !== false && $query->num_rows() > 0)
        {
            $data = $query->result_array();
        }
        return $data;
    }

}