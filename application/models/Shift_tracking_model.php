<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Shift_tracking_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = 'shift_tracking';
    }

    public function getShiftTrackingByUser($user_id,$from_date = '', $to_date = '' ){
        $sql  = " SELECT st.id,st.name, st.clock_in, st.clock_out, st.break_hours ";
        $sql .= " ,IF(st.clock_out !='' && DATEDIFF(st.clock_out, st.clock_in)>=0 , TIMEDIFF(st.clock_out, st.clock_in) , '') AS total_hours  "; 
        $sql .= " ,IF(st.clock_out !='' && DATEDIFF(st.clock_out, st.clock_in)>=0 ";
            $sql .= " , IF(st.break_hours !='',TIMEDIFF(TIMEDIFF(st.clock_out, st.clock_in), st.break_hours) ,TIMEDIFF(st.clock_out, st.clock_in))  ";
            $sql .= " , '' ";
        $sql .= " ) AS daily_total ";
        $sql .= " FROM shift_tracking st ";
        $sql .= " WHERE st.user_id = ".$user_id;
        if(!empty($from_date)){
            $sql .= " AND DATEDIFF('".$from_date."',st.clock_in) <= 0 ";
        }
        if(!empty($to_date)){
            $sql .= " AND DATEDIFF(st.clock_in,'".$to_date."') <= 0 ";
        } 
        $sql .= " ORDER BY st.clock_in DESC ";

        $query = $this->db->query($sql); 
        $result = $query->result_array();
        return $result;         
    }

    public function getClockedToday(){
        $sql  = " SELECT st.id, st.user_id, st.name, st.clock_in, st.clock_out ";
        $sql .= " ,st.location ,st.latitude, st.longitude ";
        $sql .= " ,IF(st.clock_out !='' && DATEDIFF(st.clock_out, st.clock_in)>=0 , TIMEDIFF(st.clock_out, st.clock_in) , '') AS total_hours  ";
        //user info
        $sql .= " ,u.name AS user_name ,u.profile_img AS user_profile_img ";
        //employee Geofencing info
        $sql .= " , (SELECT latitude  FROM gps_track WHERE shift_tracking_id=st.id ORDER BY track_time DESC LIMIT 0,1) AS e_latitude ";
        $sql .= " , (SELECT longitude  FROM gps_track WHERE shift_tracking_id=st.id ORDER BY track_time DESC LIMIT 0,1) AS e_longitude ";
        $sql .= " FROM shift_tracking st ";
        $sql .= " LEFT JOIN users u ON st.user_id = u.id ";
        $sql .= " WHERE DATEDIFF(NOW(),st.clock_in) = 0 ";
        $sql .= " ORDER BY st.user_id, st.clock_in DESC ";
        $query = $this->db->query($sql); 
        $result = $query->result_array();
        return $result; 
    }
}
