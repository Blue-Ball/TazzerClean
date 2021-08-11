<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    # added by maksimU : for staff deleting
    public function delete_user($user_id){

        $this->db->where('id',$user_id);
        $this->db->delete('users');
        return;
    }
    # maksimU end

    public function get_user($user_id){

        $user=$this->db->select('*')->from('users')->
                        where('id',$user_id)->
                        get()->result_array();

        if(!empty($user)){
            return $user[0];
        }
        return [];
    }
    public function find_users($where){

        $users=$this->db->select('*')->from('users')->
                        where($where)->
                        get()->result_array();

        if(!empty($users)){
            return $users;
        }
        return [];
    }
    public function get_myserviceman_list()
    {
        $this->db->select("users.id, users.name, users.mobileno, users.email");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('book_service as bs', 'bs.service_id = s.id', 'LEFT');
        $this->db->join('users', 'users.id = s.user_id', 'LEFT');
        $this->db->where("s.status = 1");
        $this->db->where("NOT ISNULL(users.id)");
        $this->db->where('bs.user_id',$this->session->userdata('id'));
        $this->db->group_by('users.id');
        $this->db->order_by('s.id','DESC');
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }
    public function get_myclient_list()
    {
        $this->db->select("users.id, users.name, users.mobileno, users.email");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('book_service as bs', 'bs.service_id = s.id', 'LEFT');
        $this->db->join('users', 'users.id = bs.user_id', 'LEFT');
        $this->db->where("s.status = 1");
        $this->db->where("NOT ISNULL(users.id)");
        $this->db->where('s.user_id',$this->session->userdata('id'));
        $this->db->group_by('users.id');
        $this->db->order_by('s.id','DESC');
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }


  /*find user type*/
  public function get_user_type($mobile_no,$country_code){
    
     $user=$this->db->select('*')->from('users')->
                    where('country_code',$country_code)->
                    where('mobileno',$mobile_no)->
                    get()->row();

    $provider=$this->db->select('*')->from('providers')->
                    where('country_code',$country_code)->
                    where('mobileno',$mobile_no)->
                    get()->row();

    if(!empty($user)){
       return $user;
    }

    if(!empty($provider)){
       return $provider;
    }
    return '';
  }

    function getUserInfo($user_id){
        $this->db->select("users.*");
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $this->db->where('status', 1);
        $query = $this->db->get();
        if($query->num_rows() == 1){
            $userdata =  $query->row_array();
            return $userdata;
        }else{
            return false;
        }
    }

    public function getEmployeeScheduleList($start_date, $end_date){
        $sql  =" SELECT users.id, users.name, users.profile_img ";
        $sql .=" , bs.book_service_id, bs.service_date, bs.service_time, bs.status as book_service_status ";
        $sql .=" , bs.category_name, bs.subcategory_name, bs.service_title ";
        $sql .=" FROM users ";
        $sql .=" LEFT JOIN ( ";
            $sql .=" SELECT jbs.id AS book_service_id, jbs.service_date AS service_date, jbs.service_time AS service_time ";
            $sql .=" ,jbs.status as status ";
            $sql .=" ,jsv.user_id AS user_id ,jsv.service_title AS service_title  ";
            $sql .=" ,mc.category_name AS category_name ";
            $sql .=" ,sc.subcategory_name AS subcategory_name ";
            $sql .=" FROM book_service AS jbs  ";
            $sql .=" LEFT JOIN services jsv ON jbs.service_id = jsv.id ";
            $sql .=" LEFT JOIN categories mc ON jsv.category = mc.id ";
            $sql .=" LEFT JOIN subcategories sc ON jsv.subcategory = sc.id ";
            $sql .=" WHERE DATEDIFF('".$start_date."',jbs.service_date) <=0 ";
            $sql .=" AND DATEDIFF(jbs.service_date,'".$end_date."') <=0 ";
        $sql .=" ) bs ON users.id = bs.user_id ";
        $sql .=" WHERE users.you_are_appling_as = 8 AND users.`status` = 1 ";
        $sql .=" ORDER BY users.id,bs.service_date,bs.service_time ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        
        return $result;
    }
}
