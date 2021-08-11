<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Organization_book_service_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'organization_book_service';
    }

    public function existsOrganBookSercice($book_service_id, $staff_id){
        $this->db->select("*");
        $this->db->from($this->table); 
        $this->db->where('book_service_id', $book_service_id);
        $this->db->where('staff_id', $staff_id);
        $query = $this->db->get(); 
        if($query->num_rows() > 0){
            $result = true;
        }else{
            $result = false;
        }
        return $result; 
    }
    
    public function insertOrganBookService($input_data){
        $input_data['created_at'] = date('Y-m-d H:i:s');
        $result  = $this->db->insert($this->table,$input_data);
        return $result;
    }

    public function update_status($book_service_id, $status) {
        $this->db->set('status', $status);
        $this->db->where('book_service_id', $book_service_id);
        $ret = $this->db->update($this->table);
        return $ret;
    }

    public function updateStatusById($id, $status, $reason) {
        $this->db->set('status', $status);
        $this->db->set('reason', $reason);
        $this->db->where('id', $id);
        $ret = $this->db->update($this->table);
        return $ret;
    }

    public function getOrdersForStaff($params = array()){ 
        
        $select_field  = "ob.id as organ_book_id, ob.status as organ_book_status";
        $select_field .= ",b.*";
        $select_field .= ",s.service_title,s.service_image,s.service_amount,s.rating,s.service_image";
        $select_field .= ",c.category_name";
        $select_field .= ",sc.subcategory_name";
        $select_field .= ",p.name,p.profile_img,p.mobileno,p.country_code";
        $this->db->select($select_field);
        $this->db->from('organization_book_service ob');
        $this->db->join('book_service b', 'ob.book_service_id = b.id', 'INNER');
        $this->db->join('services s', 'b.service_id = s.id', 'INNER');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('staffs st', 'ob.staff_id = st.id', 'INNER');
        $this->db->join('users p', 'st.user_id = p.id', 'INNER');
        $this->db->where("st.user_id",$this->session->userdata('id')); //for employee
      
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
    
}
