<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Request_ads_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'request_ads';
    }

    public function getRequsetAdsList($params = array()){
        $select_field  = " ra.* ";
        $select_field .= " ,us.name AS user_name, us.profile_img AS user_profile_img ";
        $select_field .= " ,sc.subcategory_name,sc.subcategory_image,sc.category as category_id ";
        $select_field .= " ,mc.category_name ";
        $this->db->select($select_field);
        $this->db->from('request_ads ra');
        $this->db->join('users us', 'ra.user_id = us.id', 'LEFT');
        $this->db->join('subcategories sc', 'ra.subcategory_id = sc.id', 'LEFT');
        $this->db->join('categories mc', 'sc.category = mc.id', 'LEFT');
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        }
        $query = $this->db->get(); 
        $result = $query->result_array();
        return $result; 
    }

    public function getRequsetAdsListForCampain(){
        $sql  = " SELECT ra.subcategory_id, sc.subcategory_name,mc.category_name ";
        $sql .= " FROM request_ads ra ";
        $sql .= " LEFT JOIN subcategories sc ON ra.subcategory_id = sc.id ";
        $sql .= " LEFT JOIN categories mc ON sc.category = mc.id ";
        $sql .= " GROUP BY ra.subcategory_id ";
        $result_1 = $this->db->query($sql)->result_array();
        
        $ads_list = array();
        $i = 0;
        if(!empty($result_1)){
            foreach($result_1 as $sub_row){
                $ads_list[$i] = array();
                $ads_list[$i]['subcategory_id'] = $sub_row['subcategory_id'];
                $ads_list[$i]['subcategory_name'] = $sub_row['subcategory_name'];
                $ads_list[$i]['category_name'] = $sub_row['category_name'];
                $sql  = " SELECT ra.*,u.name as user_name,u.profile_img AS user_profile_img ";
                $sql .= " FROM request_ads ra ";
                $sql .= " LEFT JOIN users u ON ra.user_id=u.id ";
                $sql .= " WHERE subcategory_id =".$sub_row['subcategory_id'];
                $result_2 = $this->db->query($sql)->result_array();
                $ads_list[$i]['child_list'] = $result_2;
                $ads_list[$i]['request_cnt'] = count($result_2);

                $sql  = " SELECT user_id ";
                $sql .= " FROM services ";
                $sql .= " WHERE subcategory = ".$sub_row['subcategory_id'];
                $sql .= " GROUP BY user_id ";
                $result_3 = $this->db->query($sql)->result_array();
                $ads_list[$i]['total_cnt'] = count($result_3);

                $i++;
            }
        }
        return $ads_list; 
    }

    public function insertAds($input_data) {    
        $input_data['created_at'] = date('Y-m-d H:i:s');
        $result  = $this->db->insert($this->table,$input_data);        
        return $result;
    }

    public function updateAds($input_data) {
        $id = $input_data['id'];
        $result = $this->db->where('id', $id)->update($this->table,$input_data);
        return $result;
    }

    public function deleteAds($id){
        $this->db->where('id',$id);
        $result = $this->db->delete($this->table);
        return $result;
    }

    public function getCategoriesByUserId($user_id){
        $sql  = " SELECT mc.category AS id, rc.category_name "; 
        $sql .= " FROM ( "; 
        $sql .= " SELECT sv.subcategory,sc.subcategory_name,sc.category "; 
        $sql .= " FROM services sv "; 
        $sql .= " INNER JOIN subcategories sc ON sv.subcategory = sc.id "; 
        $sql .= " WHERE sv.status = 1 AND sc.status = 1 AND sv.user_id = ".$user_id; 
        $sql .= " GROUP BY sv.subcategory "; 
        $sql .= " ) mc ";
        $sql .= " INNER JOIN categories rc ON mc.category = rc.id "; 
        $sql .= " WHERE rc.status = 1 "; 
        $sql .= " GROUP BY mc.category "; 
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    public function getSubCategoriesByUserId($user_id,$category_id = ""){
        $sql  = " SELECT sv.subcategory AS id,sc.subcategory_name,sc.category "; 
        $sql .= " FROM services sv "; 
        $sql .= " INNER JOIN subcategories sc ON sv.subcategory = sc.id "; 
        $sql .= " WHERE sv.status = 1 AND sc.status = 1 AND sv.user_id =".$user_id; 
        if($category_id != ""){
            $sql .= " AND sc.category = ".$category_id;
        }
        $sql .= " GROUP BY sv.subcategory "; 
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}
