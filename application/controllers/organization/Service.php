<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

    public $data;

    public function __construct() {

        parent::__construct();
        error_reporting(0);
        $this->data['theme'] = 'organization';
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

    public function organization_dashboard() {
//        if (empty($this->session->userdata('data_organization'))) {
//            redirect(base_url().'organization?msg=login');
//        }
        //get Amount per month

        $revenueAmount = $this->organization->getRevenueAmount($company_id = 1);
        $this->data['revenue'] = json_encode($revenueAmount);
        //get Orders
        $conditions['returnType'] = '';
        $totalRec = $this->userbooking->getOrdersForOrganization($conditions);
        $this->data['pending'] = 0;
        $this->data['progress'] = 0;
        $this->data['reject_in_process'] = 0;
        $this->data['complete_request'] = 0;
        $this->data['accepted'] = 0;
        $this->data['reject'] = 0;
        $this->data['cancelled'] = 0;
        $this->data['complete'] = 0;
        foreach ($totalRec as $item) {
            switch ($item['status']) {
                case BS_PENDING :
                    $this->data['pending'] ++;
                    break;
                case BS_INPROGRESS :
                    $this->data['progress'] ++;
                    if( empty($item['staffname']) )
                        $this->data['reject_in_process']++;
                    break;
                case BS_COMPLETED_PROVIDER :
                    $this->data['complete_request'] ++;
                    break;
                case BS_ACCEPTED :
                    $this->data['accepted'] ++;
                    break;
                case BS_REJECTED :
                    $this->data['reject'] ++;
                    break;
                case BS_COMPLETED :
                    $this->data['complete'] ++;
                    break;
                case BS_CANCELLED :
                    $this->data['cancelled'] ++;
                    break;
                default:
                    break;
            }
        }
        $this->data['total'] = count($totalRec);

        // get review count
        $this->load->model('RatingReview_model', 'ratingReview');
        $reviews = $this->ratingReview->getReviews($this->session->userdata('id'));
        $reviews_count = count($reviews);

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

        $this->data['page'] = 'dashboard';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    } 

    /*
    *   This function used in Organization / View Orders
    */
    public function organization_orders() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if($this->session->userdata('you_are_appling_as') != C_YOUARE_ORGANIZATION){
            redirect(base_url());
        }
        $order_page_type=$this->uri->segment('2');

        $this->data['page'] = 'organization_orders';
        $user_id = $this->session->userdata('id');
        $status = $this->input->post('status');        

        if (!empty($status)) {
            $conditions['where']['b.status'] = $status;
        }else{
            if($order_page_type == 'view'){
                $conditions['where'] = ['(b.status = '.BS_PENDING.' OR (b.status='.BS_INPROGRESS.' AND ISNULL(assign.id) = 1))'];
            }else if($order_page_type == 'complete'){
                $conditions['where']['b.status'] = BS_COMPLETED;
            } 
            elseif($order_page_type == 'missing')
            {
                $conditions['where'] = ['( (b.status='.BS_INPROGRESS.' AND ISNULL(assign.id) = 1))'];
            }
            else
            {
                $conditions['where']['b.status'] = $order_page_type;
            }
        }

        $conditions['returnType'] = 'count';
        $totalRec = $this->userbooking->getOrdersForOrganization($conditions);

        // Pagination configuration
        $config['target'] = '#dataList';
        $config['base_url'] = base_url('organization/Service/orderAjaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library
        $this->ajax_pagination->initialize($config);

        // Get records
        $conditions = array(
            'limit' => $this->perPage
        );
        if (!empty($status)){
            $conditions['where']['b.status'] = $status;
        }else{
            if($order_page_type == 'view'){
                //$conditions['where'] = ['b.status'=> BS_PENDING];
                $conditions['where'] = ['(b.status = '.BS_PENDING.' OR (b.status='.BS_INPROGRESS.' AND ISNULL(assign.id) = 1))'];
            }else if($order_page_type == 'complete'){
                $conditions['where']['b.status'] = BS_COMPLETED;
            }            
            elseif($order_page_type == 'missing')
            {
                $conditions['where'] = ['( (b.status='.BS_INPROGRESS.' AND ISNULL(assign.id) = 1))'];
            }
            else
            {
                $conditions['where']['b.status'] = $order_page_type;
            }
        }
        
        $this->data['order_page_type'] = $order_page_type;
        $this->data['all_bookings'] = $this->userbooking->getOrdersForOrganization($conditions);
        //print( $this->db->last_query() ); exit;
        $organization = $this->organization->getOrganizationByUserId($this->session->userdata('id')); 
        if($organization){
            $conditions = array();            
            $conditions['where']['staffs.organ_id'] = $organization['id'];
            $conditions['where']['staffs.status'] = 1;
            $this->data['staffs'] = $this->staff->get_staffs($conditions); 
        }

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    /*
    *   This function used in Organization / View Orders
    */
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
            $conditions['where']['b.status'] = $status;
        }

        // Get record count
        $conditions['returnType'] = 'count';
        $totalRec = $this->userbooking->getOrdersForOrganization($conditions);

        // Pagination configuration
        $config['target'] = '#dataList';
        $config['base_url'] = base_url('organization/Service/orderAjaxPaginationData');
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
            $conditions['where']['b.status'] = $status;
        }

        $this->data['all_bookings'] = $this->userbooking->getOrdersForOrganization($conditions);

        // Load the data list view
        $this->load->view('organization/home/organization_order_ajax_data', $this->data, false);
    }

    /**
     *  Update Book Service Status
     */
    public function update_status_user() {
        extract($_POST);
        if (empty($this->session->userdata('id'))) {
            echo "3";
        }
        if($this->session->userdata('you_are_appling_as') != C_YOUARE_ORGANIZATION){
            echo "3";
        }

        $book_details['reason'] = $this->input->post('review');
        $book_details['status'] = $this->input->post('status');
        $book_details['id'] = $this->input->post('booking_id');
        $book_details['updated_on'] = (date('Y-m-d H:i:s'));

        if (!empty($this->input->post('booking_id'))) {
            $old_booking_status = $this->db->where('id', $this->input->post('booking_id'))->get('book_service')->row();

            if ($old_booking_status->status == BS_REJECTED || $old_booking_status->status == BS_CANCELLED) {
                echo '2';
                exit;
            }
        }

        if(!empty($this->input->post('staff_id'))) {
            $data = array();
            $data['book_service_id'] = $this->input->post('booking_id');
            $data['staff_id'] = $this->input->post('staff_id');            

            $flag = $this->organ_book_service->existsOrganBookSercice($data['book_service_id'], $data['staff_id'] );
            if(!$flag){
                //assign order by organization
                $data['status'] = ORG_BS_PENDING; //organization_book_service status pending
                $this->organ_book_service->insertOrganBookService($data);
            }           
        }

        //update staff order by organization
        if ($book_details['status'] == BS_REJECTED) {

            $book_service['status'] = BS_REJECTED;
            $book_service['id'] = $old_booking_status->book_service_id;
            $book_service['updated_on'] = (date('Y-m-d H:i:s'));
            $WHERE = array('id' => $book_service['id']);
            $result = $this->service->update_bookingstatus($book_service, $WHERE);

            //organization_book_service status cancelled
            $this->organ_book_service->update_status($this->input->post('booking_id'), ORG_BS_CANCELLED);
        }

        $WHERE = array('id' => $this->input->post('booking_id'));

        $result = $this->service->update_bookingstatus($book_details, $WHERE);
        if ($result) {
            $message = 'Booking updated successfully';

            if ($book_details['status'] == BS_COMPLETED) {

                $token = $this->session->userdata('chat_token');

                $history = $this->api->user_accept_history_flow($this->input->post('booking_id'));
                
                $this->send_push_notification($token, $book_details['id'], 1, ' Have Accepted The Service');
            }

            if ($book_details['status'] == BS_REJECTED) {

                $token = $this->session->userdata('chat_token');
                $this->send_push_notification($token, $book_details['id'], 1, ' Have Rejected The Service');
                
            }
            echo "1";
        } else {
            echo '2';
        }
    }

    /* push notification */
    public function send_push_notification($token, $service_id, $type, $msg = '') {
        $data = $this->api->get_book_info($service_id);
        if (!empty($data)) {
            if ($type == 1) {
                $device_tokens = $this->api->get_device_info_multiple($data['provider_id'], 1);
            } else {
                $device_tokens = $this->api->get_device_info_multiple($data['user_id'], 2);
            }

            if ($type == 2) {
                $user_info = $this->api->get_user_info($data['user_id'], $type);

                $name = $this->api->get_user_info($data['provider_id'], 1);
            } else {
                $name = $this->api->get_user_info($data['user_id'], 2);

                $user_info = $this->api->get_user_info($data['provider_id'], $type);
            }

            /* insert notification */
            $msg = ucfirst($name['name']) . ' ' . strtolower($msg);

            if (!empty($user_info['token'])) {
                $this->api->insert_notification($token, $user_info['token'], $msg);
            }
            $title = $data['service_title'];
            if (!empty($device_tokens)) {
                foreach ($device_tokens as $key => $device) {
                    if (!empty($device['device_type']) && !empty($device['device_id'])) {

                        if (strtolower($device['device_type']) == 'android') {

                            $notify_structure = array(
                                'title' => $title,
                                'message' => $msg,
                                'image' => 'test22',
                                'action' => 'test222',
                                'action_destination' => 'test222',
                            );
                            sendFCMMessage($notify_structure, $device['device_id']);
                        }
                        if (strtolower($device['device_type'] == 'ios')) {
                            $notify_structure = array(
                                'alert' => $msg,
                                'sound' => 'default',
                                'badge' => 0,
                            );
                            sendApnsMessage($notify_structure, $device['device_id']);
                        }
                    }
                }
            }
               /* apns push notification */
        } else {
            $this->token_error();
        }
    }

    public function add_service() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if($this->session->userdata('you_are_appling_as') != C_YOUARE_ORGANIZATION){
            redirect(base_url());
        }

        if ($this->input->post('form_submit')) {
            $inputs = array();

            removeTag($this->input->post());
            $query = $this->db->query("select * from system_settings WHERE status = 1");
            $result = $query->result_array();
            if (!empty($result)) {
                foreach ($result as $data) {
                    if ($data['key'] == 'currency_option') {
                        $currency_option = $data['value'];
                    }
                     if ($data['key'] == 'map_key') {
                        $map_key = $data['value'];
                    }
                }
            }
            $config["upload_path"] = './uploads/services/';
            $config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $service_image = array();
            $thumb_image = array();
            $mobile_image = array();

            if ($_FILES["images"]["name"] != '') {
                for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) {
                    $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
                    $_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
                    $_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
                    $_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
                    $_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
                    if ($this->upload->do_upload('file')) {
                        $data = $this->upload->data();
                        $image_url = 'uploads/services/' . $data["file_name"];
                        $upload_url = 'uploads/services/';
                        $service_image[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
                        $service_details_image[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
                        $thumb_image[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
                        $mobile_image[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
                    }
                }
            }
            
            $inputs['user_id'] = $this->session->userdata('id');
            $inputs['service_title'] = $this->input->post('service_title');
            $inputs['currency_code'] = $this->input->post('currency_code');;
            $inputs['service_sub_title'] = $this->input->post('service_sub_title');
            $inputs['category'] = $this->input->post('category');
            $inputs['subcategory'] = $this->input->post('subcategory');
            $inputs['service_location'] = $this->input->post('service_location');
            $inputs['service_latitude'] = $this->input->post('service_latitude');
            $inputs['service_longitude'] = $this->input->post('service_longitude');
            $inputs['serviceamounttype'] = $this->input->post('serviceamounttype');
            $inputs['metatagdetails'] = $this->input->post('metatagdetails');
            $inputs['service_amount'] = $this->input->post('service_amount');
            $inputs['about'] = $this->input->post('about');
            $inputs['service_image'] = implode(',', $service_image);
            $inputs['service_details_image'] = implode(',', $service_details_image);
            $inputs['thumb_image'] = implode(',', $thumb_image);
            $inputs['mobile_image'] = implode(',', $mobile_image);
            $inputs['created_at'] = date('Y-m-d H:i:s');
            $inputs['updated_at'] = date('Y-m-d H:i:s');
                        
            $result = $this->service->create_service($inputs);

            if (count($_POST['service_offered']) > 0) {
                foreach ($_POST['service_offered'] as $key => $value) {
                    $service_data = array(
                        'service_id' => $result,
                        'service_offered' => $value);
                    $this->db->insert('service_offered', $service_data);
                }
            }
            $temp = count($service_image); //counting number of row's
            $service_image = $service_image;
            $service_details_image = $service_details_image;
            $thumb_image = $thumb_image;
            $mobile_image = $mobile_image;
            $service_id = $result;

            for ($i = 0; $i < $temp; $i++) {
                $image = array(
                    'service_id' => $service_id,
                    'service_image' => $service_image[$i],
                    'service_details_image' => $service_details_image[$i],
                    'thumb_image' => $thumb_image[$i],
                    'mobile_image' => $mobile_image[$i]
                );
                $serviceimage = $this->service->insert_serviceimage($image);
            }

            $navigationName = 'organization'; 
                
            if ($serviceimage == true) {
                $this->session->set_flashdata('success_message', 'Service created successfully');
                redirect(base_url() . $navigationName . "-my-services");
            } else {
                $this->session->set_flashdata('error_message', 'Service created failed');
                redirect(base_url() . $navigationName . "-add-service");
            }
        }
        
        $this->data['map_key'] = $map_key;
        $this->data['page'] = 'add_service';
        
        $this->data['max_service_post_num']=settingValue('max_service_post_number');
        $this->load->model('post'); 
        $conditions = [];
        $conditions['returnType'] = 'count'; 
        $this->data['total_service_num'] = $this->post->getRows($conditions); 

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function check_service_title() {
        $user_data = $this->input->post();
        $input['service_title'] = $user_data['service_title'];
        $service_count = $this->db->where('service_title', $input['service_title'])->count_all_results('services');
        if ($service_count > 0) {
            $isAvailable = FALSE;
        } else {
            $isAvailable = TRUE;
        }
        echo json_encode(
                array(
                    'valid' => $isAvailable
        ));
    }

    public function image_resize($width = 0, $height = 0, $image_url, $filename, $upload_url) {

        // $source_path = base_url() . $image_url;
        $source_path = realpath($image_url);
        list($source_width, $source_height, $source_type) = getimagesize($source_path);
        switch ($source_type) {
            case IMAGETYPE_GIF:
                $source_gdim = imagecreatefromgif($source_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gdim = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $source_gdim = imagecreatefrompng($source_path);
                break;
        }

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $width / $height;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
               /*
             * Triggered when source image is wider
             */
            $temp_height = $height;
            $temp_width = (int) ($height * $source_aspect_ratio);
        } else {
               /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $width;
            $temp_height = (int) ($width / $source_aspect_ratio);
        }

           /*
         * Resize the image into a temporary GD image
         */

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
                $temp_gdim, $source_gdim, 0, 0, 0, 0, $temp_width, $temp_height, $source_width, $source_height
        );

           /*
         * Copy cropped region from temporary image into the desired GD image
         */

        $x0 = ($temp_width - $width) / 2;
        $y0 = ($temp_height - $height) / 2;
        $desired_gdim = imagecreatetruecolor($width, $height);
        imagecopy(
                $desired_gdim, $temp_gdim, 0, 0, $x0, $y0, $width, $height
        );

           /*
         * Render the image
         * Alternatively, you can save the image in file-system or database
         */

        $image_url = $upload_url . $filename;

        imagepng($desired_gdim, $image_url);

        return $image_url;

           /*
         * Add clean-up code here
         */
    }
}