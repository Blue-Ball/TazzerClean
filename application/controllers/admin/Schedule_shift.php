<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * @author Leo
 * @description Chat history track
 * @created 
*/

class Schedule_shift extends CI_Controller {

    public $data;

    public function __construct() {

        parent::__construct();
        $this->load->model('admin_model','admin');
        $this->load->model('User_model','user_model');
        $this->load->model('Shift_tracking_model', 'shift_tracking');
        if(!$this->session->userdata('admin_id'))
        {
        redirect(base_url()."admin");
        }
        $admin_id=$this->session->userdata('admin_id');
        if ($admin_id != 1) {
        header('Location: '.base_url());
        http_response_code(404);
        exit();
        }
        $this->data['theme'] = 'admin';
        $this->data['model'] = 'schedule_shift';
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->data['user_role']=$this->session->userdata('role');
    }

    public function index()
    {
        $this->data['page'] = 'index';
        $date = date('Y-m-d');
        if ($this->input->post('form_submit')) {
            extract($_POST);
            if(!empty($selected_date)){
                $date = $selected_date;        
            }            
        }
        
        $nbDay = date('N', strtotime($date));
        $monday = new DateTime($date);
        $sunday = new DateTime($date);
        $monday->modify('-'.($nbDay-1).' days');
        $sunday->modify('+'.(7-$nbDay).' days'); 
        $start_date = $monday->format('Y-m-d');
        $end_date = $sunday->format('Y-m-d');

        $day_start = date( "d", strtotime( $start_date ) ); 
        for ( $x = 0; $x < 7; $x++ )
            $week_days[] = date( "m/d", mktime( 0, 0, 0, date( "m" ), $day_start + $x, date( "y" ) ) ); // create weekdays array. 
             
        $es_list = $this->user_model->getEmployeeScheduleList($start_date,$end_date);

        $schedule_list = array();
        $cnt1 = -1;
        if(!empty($es_list)){
            $old_user_id = 0;
            foreach ($es_list as $row) {
                $employee = array();
                if($old_user_id != $row['id']){
                    $cnt1 ++;

                    $schedule_list[$cnt1] = array();
                    $schedule_list[$cnt1]['id'] = $row['id'];
                    $schedule_list[$cnt1]['name'] = $row['name'];
                    $schedule_list[$cnt1]['profile_img'] = $row['profile_img'];
                }

                if(empty($schedule_list[$cnt1]['date_ary'])){
                    $schedule_list[$cnt1]['date_ary'] = array();
                }

                if(!empty($row['service_date'])){ 
                    $day_key = date('N', strtotime($row['service_date']))*1-1;   
                    if(empty( $schedule_list[$cnt1]['date_ary'][$day_key] )){
                        $schedule_list[$cnt1]['date_ary'][$day_key] = array();                        
                    }
                    // $schedule_list[$cnt1]['date_ary'][$day_key][] = $row['service_date'];
                    $item = array();
                    $item['book_service_id'] = $row['book_service_id'];
                    $item['category_name'] = $row['category_name'];
                    if(!empty($row['service_time']) && $row['service_time'] != "00:00:00"){
                        $dateObject = new DateTime($row['service_time']);
                        $item['service_time'] = $dateObject->format('h:i A');
                    }else{
                        $item['service_time'] = "00:00";
                    }
                    // $item['service_time'] = $row['service_time'];
                    $item['bs_status'] = $row['book_service_status'];
                    $schedule_list[$cnt1]['date_ary'][$day_key][] = $item;
                }
                $old_user_id = $row['id'];
            }
        }
    
        $this->data['week_days'] = $week_days;
        $this->data['list'] = $schedule_list;
        $this->data['selected_date'] = $date;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function employee_shift($user_id){

        $date = date('Y-m-d');
        $nbDay = date('N', strtotime($date));
        $monday = new DateTime($date);
        $sunday = new DateTime($date);
        $monday->modify('-'.($nbDay-1).' days');
        $sunday->modify('+'.(7-$nbDay).' days'); 
        $start_date = $monday->format('Y-m-d');
        $end_date = $sunday->format('Y-m-d');

        if ($this->input->post('form_submit')) {
            extract($_POST);
            if(!empty($from_date)){
                $start_date = $from_date;        
            } 
            if(!empty($to_date)){
                $end_date = $to_date;        
            }            
        }

        $result = $this->shift_tracking->getShiftTrackingByUser($user_id,$start_date,$end_date);

        $days = array('Mon', 'Tue', 'Wed','Thu','Fri', 'Sat', 'Sun');

        $shift_list = array();
        if(!empty($result)){
            foreach($result as $row){
                $shift = array();
                $shift['id'] = $row['id'];
                $shift['job'] = $row['name'];
                if(!empty($row['clock_in'])){
                    $n_week = date('N', strtotime($row['clock_in']))-1;

                    $dateObject = new DateTime($row['clock_in']);                    
                    $shift['shift_date'] = $dateObject->format('m/d')." (".$days[$n_week].") ";
                    $shift['clock_in'] = $dateObject->format('h:i A');
                }else{
                    $shift['shift_date'] = "--";
                    $shift['clock_in'] = "--";
                }
                if(!empty($row['clock_out'])){
                    $dateObject = new DateTime($row['clock_out']);
                    $shift['clock_out'] = $dateObject->format('h:i A');
                }else{
                    $shift['clock_out'] = "--";
                }
                if(!empty($row['total_hours'])){
                    $dateObject = new DateTime($row['total_hours']);
                    $shift['total_hours'] = $dateObject->format('h:i A');
                }else{
                    $shift['total_hours'] = "--";
                }
                if(!empty($row['break_hours']) && $row['break_hours'] != "00:00:00"){
                    $dateObject = new DateTime($row['break_hours']);
                    $shift['break_hours'] = $dateObject->format('h:i A');
                }else{
                    $shift['break_hours'] = "--";
                }
                if(!empty($row['daily_total'])){
                    $dateObject = new DateTime($row['daily_total']);
                    $shift['daily_total'] = $dateObject->format('h:i A');
                }else{
                    $shift['daily_total'] = "--";
                }
                $shift_list[] = $shift;
            }
        }

        $this->data['page'] = 'employee_shift';
        $this->data['start_date'] = $start_date;
        $this->data['end_date'] = $end_date;
        $this->data['list'] = $shift_list;
        $this->data['user_id'] = $user_id;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function clocked_today(){

        // $date = date('Y-m-d');

        $result = $this->shift_tracking->getClockedToday();

        $shift_list = array();
        if(!empty($result)){
            foreach($result as $row){
                $shift = array();
                $shift['id'] = $row['id'];
                $shift['user_id'] = $row['user_id'];
                if(!empty($row['user_profile_img'])){
                    $shift['user_profile_img'] = $row['user_profile_img'];
                }else{
                    $shift['user_profile_img'] = 'assets/img/user.jpg';
                }

                $shift['user_name'] = $row['user_name'];

                if(!empty($row['location'])){
                    $shift['location'] = $row['location'];
                }else{
                    $shift['location'] = "";
                }
                if(!empty($row['latitude'])){
                    $shift['latitude'] = $row['latitude'];
                }else{
                    $shift['latitude'] = "";
                }
                if(!empty($row['longitude'])){
                    $shift['longitude'] = $row['longitude'];
                }else{
                    $shift['longitude'] = "";
                }

                if(!empty($row['e_latitude'])){
                    $shift['e_latitude'] = $row['e_latitude'];
                }else{
                    $shift['e_latitude'] = "";
                }
                if(!empty($row['e_longitude'])){
                    $shift['e_longitude'] = $row['e_longitude'];
                }else{
                    $shift['e_longitude'] = "";
                }
                
                $shift['status'] = $row['name'];
                if(!empty($row['clock_in'])){
                    $dateObject = new DateTime($row['clock_in']);                    
                    $shift['clock_in'] = $dateObject->format('h:i A');
                }else{
                    $shift['clock_in'] = "--";
                }

                if(!empty($row['clock_out'])){
                    $dateObject = new DateTime($row['clock_out']);
                    $shift['clock_out'] = $dateObject->format('h:i A');
                }else{
                    $shift['clock_out'] = "--";
                }

                if(!empty($row['total_hours'])){
                    $dateObject = new DateTime($row['total_hours']);
                    $shift['total_hours'] = $dateObject->format('h:i A');
                }else{
                    $shift['total_hours'] = "--";
                } 

                $shift_list[] = $shift;
            }
        }

        // print_r($shift_list);exit;

        $this->data['page'] = 'clocked_today';
        $this->data['list'] = $shift_list;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

}
