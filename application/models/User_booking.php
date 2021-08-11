<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class User_booking extends CI_Model{ 
     
    function __construct() { 
        // Set table name 
        $this->table = 'book_service'; 
    } 
     
       /* 
     * Fetch records from the database 
     * @param array filter data based on the passed parameters 
     */ 

    function getRows($params = array()){ 
    $this->db->select("b.*,s.service_title,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users p', 'b.user_id = p.id', 'LEFT');
        $this->db->where("b.user_id",$this->session->userdata('id'));
      
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

    # added by maksimU : for getting Employee's Orders
    /*
    *   This function used in employee / View Orders
    *   params : where condition, return type, pagenation params,
    */

    function getOrdersForEmployee($params = array()){ 
        
        $select_field  = "b.*";
        $select_field .= ",s.service_title,s.service_image,s.service_amount,s.rating,s.service_image";
        $select_field .= ",c.category_name";
        $select_field .= ",sc.subcategory_name";
        $select_field .= ",p.name,p.profile_img,p.mobileno,p.country_code";
        $this->db->select($select_field);
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'INNER');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users p', 'b.user_id = p.id', 'LEFT');
        $this->db->where("s.user_id",$this->session->userdata('id')); //for employee
      
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
    # added by maksimU : for getting Organization's Orders
    /*   
    *   This function used in organization / View Orders
    *   params : where condition, return type, pagenation params,
    */
    function getOrdersForOrganization($params = array()){ 
        
        $select_field  = "b.*";
        $select_field .= ",s.service_title,s.service_image,s.service_amount,s.rating,s.service_image";
        $select_field .= ",c.category_name";
        $select_field .= ",sc.subcategory_name";
        $select_field .= ",p.name,p.profile_img,p.mobileno,p.country_code";
        $select_field .= ",su.name as staffname,su.mobileno as staffphone, su.profile_img as staffimgm, assign.status as staffstatus";
        
        $this->db->select($select_field);
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'INNER');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users p', 'b.user_id = p.id', 'LEFT');
        $this->db->join('organization_book_service assign', 'assign.book_service_id = b.id AND assign.status<>'.ORG_BS_REJECTED, 'LEFT');
        $this->db->join('staffs stf', 'stf.id = assign.staff_id', 'LEFT');
        $this->db->join('users su', 'su.id = stf.user_id', 'LEFT');
        
        $this->db->where("s.user_id",$this->session->userdata('id')); //for organization
      
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                if(empty($key))
                    $this->db->where($val); 
                else
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
        //print($this->db->last_query()); 
        return $result; 
    } 

    # end
}