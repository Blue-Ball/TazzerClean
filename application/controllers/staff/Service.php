<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

    public $data;

    public function __construct() {

        parent::__construct();
        error_reporting(0);
        $this->data['theme'] = 'staff';
        $this->data['module'] = 'service';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->load->helper('push_notifications');
        $this->load->model('api_model', 'api');

        $this->load->model('service_model', 'service');
        $this->load->model('home_model', 'home');
        $this->load->model('User_booking', 'userbooking');

        $this->load->model('Organization_model','organization');
        $this->load->model('Staff_model','staff');
        $this->load->model('Organization_book_service_model','organ_book_service');

        // Load pagination library 
        $this->load->library('ajax_pagination');
        // Per page limit 
        $this->perPage = 10;
        
    }

    public function index() {
        $this->data['page'] = 'index';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function staff_orders() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if($this->session->userdata('you_are_appling_as') != C_YOUARE_STAFF){
            redirect(base_url());
        }
        $order_page_type=$this->uri->segment('2');

        $this->data['page'] = 'staff_orders';
        $user_id = $this->session->userdata('id');
        $status = $this->input->post('status');        

        if (!empty($status)) {
            $conditions['where']['ob.status'] = $status;
        }else{
            if($order_page_type == 'view'){
                $conditions['where']['ob.status'] = ORG_BS_PENDING;
            }else if($order_page_type == 'complete'){
                $conditions['where']['ob.status'] = ORG_BS_COMPLETED;
            } 
        }

        $conditions['returnType'] = 'count';
        $totalRec = $this->organ_book_service->getOrdersForStaff($conditions);

        // Pagination configuration
        $config['target'] = '#dataList';
        $config['base_url'] = base_url('staff/Service/orderAjaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library
        $this->ajax_pagination->initialize($config);

        // Get records
        $conditions = array(
            'limit' => $this->perPage
        );
        if (!empty($status)){
            $conditions['where']['ob.status'] = $status;
        }else{
            if($order_page_type == 'view'){
                $conditions['where']['ob.status'] = ORG_BS_PENDING;
            }else if($order_page_type == 'complete'){
                $conditions['where']['ob.status'] = ORG_BS_COMPLETED;
            }            
        }
        $this->data['order_page_type'] = $order_page_type;
        $this->data['all_bookings'] = $this->organ_book_service->getOrdersForStaff($conditions);

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    function orderAjaxPaginationData()
    {
        // Define offset
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }

        // Set conditions for search and filter
        $status = $this->input->post('status');        

        if (!empty($status)) {
            $conditions['where']['ob.status'] = $status;
        }

        // Get record count
        $conditions['returnType'] = 'count';
        $totalRec = $this->organ_book_service->getOrdersForStaff($conditions);

        // Pagination configuration
        $config['target'] = '#dataList';
        $config['base_url'] = base_url('staff/Service/orderAjaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library
        $this->ajax_pagination->initialize($config);

        // Get records
        $conditions = array(
            'start' => $offset,
            'limit' => $this->perPage
        );
        if (!empty($status)) {
            $conditions['where']['ob.status'] = $status;
        }

        $this->data['all_bookings'] = $this->organ_book_service->getOrdersForStaff($conditions);

        // Load the data list view
        $this->load->view('staff/home/staff_order_ajax_data', $this->data, false);
    }

    public function update_status_user() {
        extract($_POST);
        if (empty($this->session->userdata('id'))) {
            echo "3";
        }
        if($this->session->userdata('you_are_appling_as') != C_YOUARE_STAFF){
            echo "3";
        }        
        $old_booking_status = array();
        if (!empty($this->input->post('booking_id'))) {
            $old_booking_status = $this->db->where('id', $this->input->post('booking_id'))->get('organization_book_service')->row();

            if ($old_booking_status->status == ORG_BS_REJECTED || $old_booking_status->status == ORG_BS_CANCELLED) {
                echo '2';
                exit;
            }
        }

        $reason = $this->input->post('review');
        $status = $this->input->post('status');
        $booking_id = $this->input->post('booking_id');

        $result = $this->organ_book_service->updateStatusById($booking_id, $status, $reason);
        if($status == ORG_BS_ACCEPTED){
            $book_details['status'] = $status;
            $book_details['id'] = $old_booking_status->book_service_id;
            $book_details['updated_on'] = (date('Y-m-d H:i:s'));
            $WHERE = array('id' => $book_details['id']);
            $result = $this->service->update_bookingstatus($book_details, $WHERE);
        }

        if ($result) {            
            echo "1";
        } else {
            echo '2';
        }
    }

    public function staff_dashboard() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if($this->session->userdata('you_are_appling_as') != C_YOUARE_STAFF){
            redirect(base_url());
        }
        // get booking info
        $conditions['returnType'] = '';
        $totalRec = $this->organ_book_service->getOrdersForStaff($conditions);
        $this->data['pending'] = 0;
        $this->data['progress'] = 0;
        $this->data['complete_request'] = 0;
        $this->data['accepted'] = 0;
        $this->data['reject'] = 0;
        $this->data['cancelled'] = 0;
        $this->data['complete'] = 0;
        foreach ($totalRec as $item) {
            switch ($item['status']) {
                case ORG_BS_PENDING :
                    $this->data['pending'] ++;
                    break;
                case ORG_BS_INPROGRESS :
                    $this->data['progress'] ++;
                    break;
                case ORG_BS_ACCEPTED :
                    $this->data['accepted'] ++;
                    break;
                case ORG_BS_REJECTED :
                    $this->data['reject'] ++;
                    break;
                case ORG_BS_COMPLETED :
                    $this->data['complete'] ++;
                    break;
                case ORG_BS_CANCELLED :
                    $this->data['cancelled'] ++;
                     break;
                default:
                    break;
            }
        }
        $this->data['total'] = count($totalRec);

        // get review count
        // $this->load->model('RatingReview_model', 'ratingReview');
        // $reviews = $this->ratingReview->getReviews($this->session->userdata('id'));
        // $reviews_count = count($reviews);

        // get notification
        $this->load->model('Notificaiton_model', 'notification');
        if(!empty($this->session->userdata('chat_token'))){
            $ses_token=$this->session->userdata('chat_token');
        }else{
            $ses_token='';
        }
        if(!empty($ses_token)){
            $ret = $this->notification->getNotification($ses_token);

            $notification=[];
            if(!empty($ret)){
                foreach ($ret as $key => $value) {
                    $user_table=$this->db->select('id,name,profile_img,token,type')->
                    from('users')->
                    where('token',$value['sender'])->
                    get()->row();
                    $provider_table=$this->db->select('id,name,profile_img,token,type')->
                    from('providers')->
                    where('token',$value['sender'])->
                    get()->row();
                    if(!empty($user_table)){
                     $user_info= $user_table;
                    }else{
                     $user_info= $provider_table;
                    }
                    $notification[$key]['name']= !empty($user_info->name)?$user_info->name:'';
                    $notification[$key]['message']= !empty($value['message'])?$value['message']:'';
                    $notification[$key]['profile_img']= !empty($user_info->profile_img)?$user_info->profile_img:'';
                    $notification[$key]['utc_date_time']= !empty($value['utc_date_time'])?$value['utc_date_time']:'';
                }
            }
            $n_count=count($notification);
        }else{
            $n_count=0;
            $notification=[];
        }


        $this->data['n_count'] = $n_count;
        $this->data['notification'] = $notification;

        $this->data['reviews_count'] = $reviews_count;
        $this->data['reviews'] = $reviews;

        $this->data['page'] = 'staff_dashboard';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
}