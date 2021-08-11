<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Post extends CI_Model{ 
     
    function __construct() { 
        // Set table name 
        $this->table = 'services'; 
    } 
     
     # updated by maksimU 
       /* 
     * Fetch records from the database 
     * @param array filter data based on the passed parameters 
     */ 
    function getRows($params = array()){ 
	
        $select_field  = "services.currency_code,services.id,services.user_id,services.service_title";
        $select_field .= ",services.service_amount,services.mobile_image,services.service_location,services.rating_count";
        $select_field .= ",c.category_name";
        $select_field .= ",sc.subcategory_name";
        $select_field .= ",si.service_image AS si_image";
        $select_field .= ",ROUND(rr.rating, 1) AS rr_avg_rating";

        $this->db->select($select_field); 
        
        $this->db->from($this->table); 

        $this->db->join('categories c', 'c.id = services.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = services.subcategory', 'LEFT');
        $join_str = "(SELECT lsi.* FROM services_image AS lsi WHERE lsi.status = 1 GROUP BY lsi.service_id) si";
        $this->db->join($join_str, 'si.service_id = services.id', 'LEFT');
        $join_str = "(SELECT AVG(lrr.rating) AS rating,lrr.service_id FROM rating_review AS lrr WHERE lrr.status = 1 GROUP BY lrr.service_id) rr";
        $this->db->join($join_str, 'rr.service_id = services.id', 'LEFT');

        $this->db->where("services.status = 1");
        $this->db->where('services.user_id',$this->session->userdata('id'));
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        } 
	# end
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
                $this->db->order_by('id', 'desc'); 
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                } 
          
                $query = $this->db->get(); 
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE; 
            } 
        } 
         
        // Return fetched data 
        return $result; 
    } 
    # updated by maksimU 
     function getInactiveRows($params = array()){ 
        $select_field  = "services.currency_code,services.id,services.user_id,services.service_title";
        $select_field .= ",services.service_amount,services.mobile_image,services.service_location,services.rating_count";
        $select_field .= ",c.category_name";
        $select_field .= ",sc.subcategory_name";
        $select_field .= ",si.service_image AS si_image";
        $select_field .= ",ROUND(rr.rating, 1) AS rr_avg_rating";

        $this->db->select($select_field); 
        
        $this->db->from($this->table); 

        $this->db->join('categories c', 'c.id = services.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = services.subcategory', 'LEFT');
        $join_str = "(SELECT lsi.* FROM services_image AS lsi WHERE lsi.status = 1 GROUP BY lsi.service_id) si";
        $this->db->join($join_str, 'si.service_id = services.id', 'LEFT');
        $join_str = "(SELECT AVG(lrr.rating) AS rating,lrr.service_id FROM rating_review AS lrr WHERE lrr.status = 1 GROUP BY lrr.service_id) rr";
        $this->db->join($join_str, 'rr.service_id = services.id', 'LEFT');
        
        $this->db->where("services.status = 2");
        $this->db->where('services.user_id',$this->session->userdata('id'));
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        } 
       # end  
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
                $this->db->order_by('id', 'desc'); 
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                } 
          
                $query = $this->db->get(); 
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE; 
            } 
        } 
         
        // Return fetched data 
        return $result; 
    } 
}