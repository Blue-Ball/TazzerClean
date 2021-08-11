<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'staffs';
    }
    
    public function get_staffs($params = array()){
        $select_field  = "staffs.id ,staffs.user_id";
        $select_field .= ",u.name ,u.l_name ,u.email ,u.mobileno ,u.house_name ,u.street_address";
        $select_field .= ",u.city, u.status";
	# added makksimU for opganization staff's order reject
        $select_field .= ",u.country_code, u.province, u.postal_code2, u.house_number, u.postal_code";
	# maksimU end
        $this->db->select($select_field);
        $this->db->from($this->table); 

        $this->db->join('users u', 'u.id = staffs.user_id', 'LEFT');

        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        }
        $query = $this->db->get(); 
        $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
        return $result; 
    } 

    public function update_status($data) {
        $this->db->set('status', $data['status']);
        $this->db->where('id', $data['user_id']);
        $ret = $this->db->update('users');
        if($ret){
            $this->db->set('status', $data['status']);
            $this->db->where('id', $data['staff_id']);
            $ret = $this->db->update($this->table);
        }
        return $ret;
    }

    public function insertStaff($input_data,$organ_id) {
    
        $input_data['created_at'] = date('Y-m-d H:i:s');
        $result  = $this->db->insert('users',$input_data);
        $last_query=$this->db->insert_id();        
        $records=array();
        $ret = false;
        if($result) {
            $user_id = $last_query;
            $token = $this->getToken(14,$user_id);
            
            /*insert wallet*/
            $wallet_data=array(
                "token"=>$token,
                "currency_code" => $input_data['currency_code'],
                "user_provider_id"=>$user_id,
                "type"=>2,
                "wallet_amt"=>0,
                "created_at"=>date('Y-m-d H:i:s')
            );

            $wallet_result  = $this->db->insert('wallet_table',$wallet_data);
            
            $this->db->where('id', $user_id);
            $this->db->update('users', array('token'=>$token));
          
            $profile_img=base_url().'assets/img/professional.png';
            $this->db->select('id,name,email,mobileno,IF(profile_img IS NULL or profile_img = "", "'.$profile_img.'", profile_img) as profile_img,token');            
            $this->db->where('id',$user_id);
            $records=$this->db->get('users')->row_array();
            if($records){
                $staff_data=array(
                    "organ_id"=>$organ_id,
                    "user_id" => $records['id'],
                    "status"=>1,
                    "created_at"=>date('Y-m-d H:i:s')
                );
                $staff  = $this->db->insert('staffs',$staff_data);
                if($staff){
                    $ret = true;
                }else{
                    $this->db->where('id', $records['id']);
                    $this->db->delete('users');
                }
            }
        }
        return $ret;
    }

    public function getToken($length,$user_id) {
        $token = $user_id;
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited
        for ($i=0; $i < $length; $i++) {
             $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }
        return $token;
    }

    public function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    # added makksimU 
    public function delete_staff($staff_id){

        $this->db->where('id',$staff_id);
        $this->db->delete('staffs');
        return;
    }

    public function updateStaff($input_data,$organ_id) {
        $user_id = $input_data['user_id'];
        unset($input_data['user_id']);
        unset($input_data['staff_id']);
        $this->db->where('id', $user_id)->update('users',$input_data);
        //print($this->db->last_query()); exit;
        return;
    }
    # makksimU end    
}
