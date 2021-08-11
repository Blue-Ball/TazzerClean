<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Call_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function regist_call($callInfo){

        $callInfo['room_identifier'] = substr( md5(date('YndHis').$this->session->userdata('id')), 0, 40);
        $this->db->insert('call_room', $callInfo);
        $id = $this->db->insert_id();
        $result = $this->db->select('*')->from('call_room')->
                        where('id='.$id)->
                        get()->result_array()[0];
        return $result['room_identifier'];
    }

    public function get_callme_one()
    {
        $this->db->select("room_identifier, call_type, users.*");
        $this->db->from('call_room rm');
        $this->db->join('users', 'users.id = rm.send_user_id', 'LEFT');
        $this->db->where("rm.status = 0");
        $this->db->where('rm.recv_user_id',$this->session->userdata('id'));
        $this->db->order_by('rm.created_at','ASC');
        $result = $this->db->get();
        $result = $result->result_array();
        if( count($result)>0)   return $result[0];
        return null;
    }

    public function update($data, $where){
       $this->db->where($where);
       $ret=$this->db->update('call_room',$data);
       //print($this->db->last_query()); //exit;
       return $ret;
   }

   public function stop_chatting($roomId)
   {
    $this->db->where(['room_identifier'=>$roomId]);
    $ret=$this->db->update('call_room',['status'=>4]);
   }
   public function GetChatstatus($roomId)
   {
        $result = $this->db->select('*')->from('call_room')->where('room_identifier="'.$roomId.'"')->get()->result_array();
        if(count($result)>0)
        {
            return $result[0]['status'];
        }
        return false;
   }
}
