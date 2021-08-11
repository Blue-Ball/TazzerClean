<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller        # upgrade maksimU for whitelabel using MY_Controller
{

    public $data;

    public function __construct()
    {

        parent::__construct();
        error_reporting(0);
        $this->data['theme'] = 'user';
        $this->data['module'] = 'home';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->data['base_uri'] = $_SERVER['HTTP_HOST'];
        $this->load->model('home_model', 'home');
        // added by MaksimU --------------------------
        $this->load->model('User_meta_model', 'user_meta');
        // end by MaksimU ----------------------------

        $this->user_latitude = (!empty($this->session->userdata('user_latitude'))) ? $this->session->userdata('user_latitude') : '';
        $this->user_longitude = (!empty($this->session->userdata('user_longitude'))) ? $this->session->userdata('user_longitude') : '';

        $this->currency = settings('currency');

        $this->load->library('ajax_pagination');
        $this->perPage = 12;
        $this->data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name() ,
            'hash' => $this->security->get_csrf_hash()
        );
        $this->load->helper('form');

        $this->load->helper('user_timezone_helper');  // Leo
        $this->load->model('templates_model');      // Leo

    }

    /**
     *@ Leo: home page -> using template_theme as module: extensible 
    */
    public function index()
    {
        $this->data['page'] = 'home';
        $this->data['module'] = TEMPLATE_THEME;
        
        $cate_datas = $this->home->get_category();
        foreach ($cate_datas as $k => $data)
        {
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $data['category_image'])) $cate_datas[$k]['category_image'] = 'uploads/category_images/no_image.jpg';
        }
        $this->data['category'] = $cate_datas;
        $this->data['services'] = $this->home->get_service();
        
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function login()
    {
        if (!empty($this->session->userdata('id')))
        {
	    # add by maksimU : For employeee login in Userlogin Page
            if($this->session->userdata('employee_status')=='yes')
                redirect(base_url().'employee-dashboard');
            else
	    # end
                redirect(base_url());
        }
        $this->data['page'] = 'login';
        $this->data['history_uri'] = "";
        if (!empty($this->session->flashdata('history_uri')))
        {
            $this->data['history_uri'] = $this->session->flashdata('history_uri');
        }
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
        // $this->load->view($this->data['theme'] . '/common/template');
        
    }
    
    /**
     * @author Leo: real-time notification, chatting check (enable to use it for another real-time module)
    */
    public function check()
    {

        if (empty($this->session->userdata('id')))
        {
            echo json_encode([]);
            exit;
        }

        $this->load->model('Api_model', 'api');
        $result = [];

        // ------------------ notification -----------------------
        if (!empty($this->session->userdata('chat_token')))
        {
            $ses_token = $this->session->userdata('chat_token');
        }
        else
        {
            $ses_token = '';
        }

        if (!empty($ses_token))
        {
            $ret = $this->db->select('*')->from('notification_table')->where('receiver', $ses_token)->where('status', 1)->order_by('notification_id', 'DESC')->get()->result_array();

            $notification = [];
            if (!empty($ret))
            {
                foreach ($ret as $key => $value)
                {
                    $user_info = $this->api->get_token_info($value['sender']);
                    $notification[$key]['name'] = !empty($user_info->name) ? $user_info->name : '';
                    $notification[$key]['message'] = !empty($value['message']) ? ucfirst($value['message']) : '';
                    $notification[$key]['profile_img'] = !empty($user_info->profile_img) ? $user_info->profile_img : 'assets/img/user.jpg';
                    $notification[$key]['utc_date_time'] = !empty($value['utc_date_time']) ? $value['utc_date_time'] : '';

                    $full_date = date('Y-m-d H:i:s', strtotime($value['utc_date_time']));
                    $date = date('Y-m-d', strtotime($full_date));
                    $date_f = date('d-m-Y', strtotime($full_date));
                    $yes_date = date('Y-m-d', (strtotime('-1 day', strtotime(date('Y-m-d')))));
                    $time = date('H:i', strtotime($full_date));
                    $session = date('h:i A', strtotime($time));
                    if ($date == date('Y-m-d'))
                    {
                        $timeBase = "Today " . $session;
                    }
                    elseif ($date == $yes_date)
                    {
                        $timeBase = "Yester day " . $session;
                    }
                    else
                    {
                        $timeBase = $date_f . " " . $session;
                    }
                    $notification[$key]['time_base'] = $timeBase;
                }
            }

            $n_count = count($notification);
        }
        else
        {
            $n_count = 0;
            $notification = [];
        }

        /* Notification Count */
        if (!empty($n_count) && $n_count != 0)
        {
            $notify = "<span class='badge badge-pill bg-yellow'>" . $n_count . "</span>";
        }
        else
        {
            $notify = "";
        }
        $result['notification'] = ['notify' => $notify, 'list' => $notification, 'count' => $n_count];
        // ------------------------
        // ---------- chat -------------
        $chat_token = $this->session->userdata('chat_token');
        if (!empty($chat_token))
        {
            $chat_detail = $this->db->where('receiver_token', $chat_token)->where('read_status=', 0)->get('chat_table')->result_array();
            foreach ($chat_detail as $key => $row)
            {

                $user_data = $this->db->where('message', $row['message'])->where('receiver_token', $row['sender_token'])->where('chat_id <', $row['chat_id'])->order_by('chat_id desc')->get('chat_table')->row();

                $user_info = $this->api->get_token_info($user_data->sender_token); //$row['sender_token']
                $full_date = date('Y-m-d H:i:s', strtotime($row['utc_date_time']));
                $date = date('Y-m-d', strtotime($full_date));
                $date_f = date('d-m-Y', strtotime($full_date));
                $yes_date = date('Y-m-d', (strtotime('-1 day', strtotime(date('Y-m-d')))));
                $time = date('H:i', strtotime($full_date));
                $session = date('h:i A', strtotime($time));
                if ($date == date('Y-m-d'))
                {
                    $timeBase = "Today " . $session;
                }
                elseif ($date == $yes_date)
                {
                    $timeBase = "Yesterday " . $session;
                }
                else
                {
                    $timeBase = $date_f . " " . $session;
                }
                $profile_img = $user_info->profile_img;
                if (empty($profile_img))
                {
                    $profile_img = 'assets/img/user.jpg';
                }
                $chat_detail[$key]['user_info'] = $user_info;
                $chat_detail[$key]['time_base'] = $timeBase;
                $chat_detail[$key]['profile_img'] = $profile_img;
            }
        }
        else
        {
            $chat_detail = [];
        }
        if (count($chat_detail) != 0)
        {
            $notify = "<span class='badge badge-pill bg-yellow chat-bg-yellow'>" . count($chat_detail) . "</span>";
        }
        else
        {
            $notify = "";
        }
        $result['chat'] = ['notify' => $notify, 'count' => count($chat_detail) , 'list' => $chat_detail];
        // -----------------------------
        echo json_encode($result);
        exit;
    }

    /**
     * @author Leo: Promotion Code Check
    */
    public function promo_code_check() {
        if ($_POST) {
            $code = $_POST['code'];
            $this->load->model("CouponsModel");
            $dataList = $this->CouponsModel->getByCode($code);
            if (is_null($dataList)) {
                $result = [
                    "result"=>"NONE",
                    "msg"=>"This Promotion Code Doesn't exist!"
                ];
                exit(json_encode($result));
            }
            $result = [
                "result"=>"OK",
                "data"=>$dataList
            ];
            exit(json_encode($result));
        }
        else {
            redirect(base_url());
        }
    }

    /**
     * @author Leo: get valid service location list
    */
    public function valid_service_locations()
    {
        exit(json_encode(validServiceLocations()));
    }

    public function contact()
    {

        $this->data['page'] = 'contact';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function pages($param)
    {
        $param = rawurldecode(utf8_decode($param));
        $query = $this->db->query("SELECT * FROM `footer_submenu` WHERE `footer_submenu` = '$param'; ");
        $this->data['list'] = $query->row_array();
        $this->data['module'] = 'pages';
        $this->data['page'] = 'page';
        $this->data['page_title'] = $param;
        $this->load->vars($this->data);
        $this->load->view('user/template');
    }

    /**
     * @author Leo: service master landing page
    */
    public function services()
    {
        $this->data['module'] = 'service_categories';
        $this->data['page'] = 'index';
        $this->data['category'] = $this->home->get_category();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    /**
     * @author Leo: service category landing page
     * @param service category id
    */
    public function service_category_detail($id)
    {
        $this->data['module'] = 'service_categories';
        $this->data['page'] = 'category_detail';
        $this->load->model('Categories_model', 'CategoryModel');
        $params = ['id' => $id];
        $category = $this->CategoryModel->get_category($params);
        $this->data['category'] = $category;
        $this->load->model("SubcategoryModel");
        $params = ['category' => $id, 'status' => 1];
        $subCategory = $this->SubcategoryModel->List($params);
        $this->data['subCategory'] = $subCategory;
        $params = ['categories' => $id];
        $allService = $this->home->get_all_service([], $params);
        $serviceList = array();
        if (count($allService) > 0)
        {
            foreach ($allService as $key => $service)
            {
                $subCateId = "subcate_" . $service['subcategory'];
                if (!array_key_exists($subCateId, $serviceList))
                {
                    $serviceList[$subCateId] = array();
                }
                $service["id"] = md5($service["id"]);
                array_push($serviceList[$subCateId], $service);
            }
        }
        $this->data['serviceList'] = $serviceList;
        $this->load->model("Service_model","Service");
        $params = [
            'category' => $id,
            'limit' => 4
        ];
        $popular_services = $this->Service->getPopularServices($params);
        if (count($popular_services) > 0) {
            foreach($popular_services as &$popular_service) {
                $serviceOffered = $popular_service['service_offered'];
                if (is_null($serviceOffered) || trim($serviceOffered) == "") {
                    $popular_service['service_offered'] = [];
                }
                else {
                    $serviceOffered = json_decode($serviceOffered);
                    if (count($serviceOffered) > 0) {
                        $serviceOffered = explode(',', $serviceOffered[0]);
                    }
                    $popular_service['service_offered'] = $serviceOffered;
                }
            }
        }
        $this->data['popular_services'] = $popular_services;
        $this->load->model("FaqsModel");
        $this->data['faqs'] = $this->FaqsModel->faqList(['category'=>$id]);
        $this->load->model("CustomersComplimentModel");
        $dataList = $this->CustomersComplimentModel->List(['category'=>$id,'order_by'=>'created_at']);
        for ($i=0; $i < count($dataList); $i++) { 
            if($dataList[$i]['customer_image'] == "" || !file_exists(realpath($dataList[$i]['customer_image']))) {
                $dataList[$i]['customer_image'] = 'uploads/customer_images/no_image.jpg';
            }
        }
        $this->data['custom_compliments'] = $dataList;
        $this->load->model("HowToWorkModel");
        $dataList = $this->HowToWorkModel->List(['category'=>$id,'order_by'=>'created_at']);
        for ($i=0; $i < count($dataList); $i++) { 
            if($dataList[$i]['image'] == "" || !file_exists(realpath($dataList[$i]['image']))) {
                $dataList[$i]['image'] = 'uploads/how_to_work_images/no_image.jpg';
            }
        }
        $this->data['how_to_work'] = $dataList;
        $this->load->model("ReasonToLoveModel");
        $dataList = $this->ReasonToLoveModel->List(['category'=>$id,'order_by'=>'created_at']);
        for ($i=0; $i < count($dataList); $i++) { 
            if($dataList[$i]['image'] == "" || !file_exists(realpath($dataList[$i]['image']))) {
                $dataList[$i]['image'] = 'uploads/reason_to_love_images/no_image.jpg';
            }
        }
        $this->data['reason_to_love'] = $dataList;
        $this->load->model("WhyChooseModel");
        $dataList = $this->WhyChooseModel->List(['category'=>$id,'order_by'=>'created_at']);
        for ($i=0; $i < count($dataList); $i++) { 
            if($dataList[$i]['image'] == "" || !file_exists(realpath($dataList[$i]['image']))) {
                $dataList[$i]['image'] = 'uploads/why_choose_images/no_image.jpg';
            }
        }
        $this->data['why_choose'] = $dataList;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    /**
     * @author Leo: get booking info and redirect to service booking page (booking process first step)
     * @param post data-> name, email, location, date, time, etc 
    */
    public function get_service_price()
    {
        extract($_POST);
        $firstName = "";
        $lastName = "";
        $nameArr = explode(" ", $name);
        $firstName = trim($nameArr[0]);
        if(count($nameArr) > 1) {
            $lastName = trim($nameArr[1]);
        }
        $booking_data = array(
            'booking_first_name' => $firstName,
            'booking_last_name' => $lastName,
            'booking_email' => $email,
            'booking_user_address' => $user_address,
            'booking_user_latitude' => $user_latitude,
            'booking_user_longitude' => $user_longitude,
            'booking_phonenumber' => $phone_number,
            // 'booking_subcategory_id' => $sub_category,
            // 'booking_service_id' => $service,
            'booking_date' => $date,
            'booking_time' => $time,
            'booking_description' => $description
        );
        $session_data = [
            'booking_data' => $booking_data
        ];
        // print_r($session_data); exit;
        $this->session->set_userdata($session_data);
        // print_r($this->session->userdata); exit;
        $serviceInfo = $this->home->get_service_details(['id' => $service]);
        redirect(base_url() . "service-booking/" . replace_specials($serviceInfo['service_title']) . "?sid=" . $service);
    }

    /**
     * @author Leo: new service booking page -> support multiple booking type
    */
    public function service_booking()
    {
        // redirected login if session is expired or not login yet
        checkLoginRedirect(); 
        // redirected home in cate of provider
        checkIfNotProvider();

        // print_r($this->session->userdata['booking_data']); exit;

        if (isset($_GET['sid']) && !empty($_GET['sid']))
        {
            extract($_GET);
            $inputs = array();
            $inputs['id'] = $_GET['sid'];

            $this->data['module'] = 'service_booking';
            $this->data['page'] = 'index';
            $this->data['service'] = $service = $this->home->get_service_details($inputs);
            $this->load->model('service_model', 'service');
            $this->data['service_image'] = $this->service->service_image($service['id']);
            $this->load->model("ServiceOfferedModel");
            $this->data['service_offered'] = $this->ServiceOfferedModel->get($service['id']);
            $this->load->model("Service_model");
            $params = [
                'except_id' => $service['id'],
                'category' => $service['category']
            ];
            $popular_service = $this->Service_model->getPopularServices($params);
            $this->data['popular_service'] = $popular_service;
            $this->load->model("ProvidersModel");
            $this->data['provider'] = $this->ProvidersModel->get($service['user_id']);
            if (!empty($service['id']))
            {
                $this->views($this->data['service']);
            }
            $this->load->model("RatingReview_model","RatingReview");
            $reviews = $this->RatingReview->get(['service_id'=>$service['id']]);
            $this->data['reviews'] = $reviews;

            $this->data['booking_data'] = $this->session->userdata('booking_data');
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template');
        }
        else
        {
            redirect(base_url());
        }
    }

    /**
     * @author Leo: service search by category page -> support great search engine
    */
    public function service_search_by_category()
    {
        $this->data['module'] = 'service_categories';
        $this->data['page'] = 'search';
        $category = $this->home->get_category();
        $this->data['category'] = $category;
        $allService = $this->home->get_all_service();
        foreach ($allService as & $data)
        {
            $data['md5_id'] = md5($data['id']);
            $data['encoded_title'] = str_replace($GLOBALS['specials']['src'], $GLOBALS['specials']['des'], $data['service_title']);
        }
        $this->data['service'] = $allService;
        $serviceList = array();
        foreach ($allService as $service)
        {
            $cateId = 'cate_' . $service['category'];
            if (!array_key_exists($cateId, $serviceList))
            {
                $serviceList[$cateId] = array();
            }
            array_push($serviceList[$cateId], $service);
        }
        $this->data['serviceList'] = $serviceList;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function all_services()
    {
        $conditions['returnType'] = 'count';
        $inputs = array();

        if (!empty($this->uri->segment('2')))
        {

            $category_name = str_replace('-', ' ', $this->uri->segment('2'));
            $category = $this->home->get_category_id($category_name);
            $inputs['categories'] = $category;
            $this->data['category_id'] = $category;
        }

        if (isset($_POST) && !empty($_POST))
        {
            $inputs['price_range'] = $this->input->post('price_range');
            $inputs['sort_by'] = $this->input->post('sort_by');
            $inputs['common_search'] = $this->input->post('common_search');
            $inputs['categories'] = $this->input->post('categories');
            $inputs['service_latitude'] = $this->input->post('user_latitude');
            $inputs['service_longitude'] = $this->input->post('user_longitude');
            $inputs['user_address'] = $this->input->post('user_address');
        }

        $totalRec = $this->home->get_all_service($conditions, $inputs);

        // Pagination configuration
        $config['target'] = '#dataList';
        $config['link_func'] = 'getData';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library
        $this->ajax_pagination->initialize($config);

        // Get records
        $conditions = array(
            'limit' => $this->perPage
        );

        $this->data['module'] = 'services';
        $this->data['page'] = 'index';
        $this->data['service'] = $this->home->get_all_service($conditions, $inputs);
        $this->data['count'] = $totalRec;
        $this->data['category'] = $this->home->get_category();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function featured_services()
    {
        $conditions['returnType'] = 'count';
        $inputs = array();
        if (!empty($this->uri->segment('2')))
        {
            $category_name = str_replace('-', ' ', $this->uri->segment('2'));
            $category = $this->home->get_category_id($category_name);
            $inputs['categories'] = $category;
            $this->data['category_id'] = $category;
        }

        if (isset($_POST) && !empty($_POST))
        {
            $inputs['price_range'] = $this->input->post('price_range');
            $inputs['sort_by'] = $this->input->post('sort_by');
            $inputs['common_search'] = $this->input->post('common_search');
            $inputs['categories'] = $this->input->post('categories');
            $inputs['service_latitude'] = $this->input->post('user_latitude');
            $inputs['service_longitude'] = $this->input->post('user_longitude');
        }

        $totalRec = $this->home->get_all_service($conditions, $inputs);

        // Pagination configuration
        $config['target'] = '#dataList';
        $config['link_func'] = 'getData';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library
        $this->ajax_pagination->initialize($config);

        // Get records
        $conditions = array(
            'limit' => $this->perPage
        );

        $this->data['module'] = 'services';
        $this->data['page'] = 'index';
        $this->data['service'] = $this->home->get_all_service($conditions, $inputs);
        $this->data['count'] = $totalRec;
        $this->data['category'] = $this->home->get_category();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    // public function all_services() {
    //     extract($_POST);
    //     $conditions['returnType'] = 'count';
    //     $inputs['min_price'] = $min_price;
    //     $inputs['max_price'] = $max_price;
    //     $inputs['sort_by'] = $this->input->post('sort_by');
    //     $inputs['common_search'] = $this->input->post('common_search');
    //     $inputs['categories'] = $this->input->post('categories');
    //     $inputs['service_latitude'] = $this->input->post('service_latitude');
    //     $inputs['service_longitude'] = $this->input->post('service_longitude');
    //     $inputs['user_address'] = $this->input->post('user_address');
    //     $totalRec = $this->home->get_all_service($conditions, $inputs);
    //     // Pagination configuration
    //     $config['target'] = '#dataList';
    //     $config['link_func'] = 'getData';
    //     $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
    //     $config['base_url'] = base_url('home/ajaxPaginationData');
    //     $config['total_rows'] = $totalRec;
    //     $config['per_page'] = $this->perPage;
    //     // Initialize pagination library
    //     $this->ajax_pagination->initialize($config);
    //     // Get records
    //     $conditions = array(
    //         'limit' => $this->perPage
    //     );
    //     $this->data['module'] = 'services';
    //     $this->data['page'] = 'ajax_service';
    //     $this->data['service'] = $this->home->get_all_service($conditions, $inputs);
    //     $result['count'] = $totalRec;
    //     $result['service_details'] = $this->load->view($this->data['theme'] . '/' . $this->data['module'] . '/' . $this->data['page'], $this->data, TRUE);
    //     echo json_encode($result);
    // }
    function ajaxPaginationData()
    {
        // Define offset
        $page = $this->input->post('page');
        if (!$page)
        {
            $offset = 0;
        }
        else
        {
            $offset = $page;
        }

        // Get record count
        extract($_POST);
        $conditions['returnType'] = 'count';
        $inputs['min_price'] = $min_price;
        $inputs['max_price'] = $max_price;
        $inputs['sort_by'] = $this->input->post('sort_by');
        $inputs['common_search'] = $this->input->post('common_search');
        $inputs['categories'] = $this->input->post('categories');
        // $inputs['service_latitude'] = $this->input->post('service_latitude');
        // $inputs['service_longitude'] = $this->input->post('service_longitude');
        $inputs['user_address'] = $this->input->post('user_address');
        $totalRec = $this->home->get_all_service($conditions, $inputs);

        // Pagination configuration
        $config['target'] = '#dataList';
        $config['link_func'] = 'getData';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;
        $config['cur_page'] = $page;
        // Initialize pagination library
        $this->ajax_pagination->initialize($config);

        // Get records
        $conditions = array(
            'start' => $offset,
            'limit' => $this->perPage
        );

        // Load the data list view
        $this->data['module'] = 'services';
        $this->data['page'] = 'ajax_service';
        $this->data['service'] = $this->home->get_all_service($conditions, $inputs);
        $result['count'] = $totalRec;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/' . $this->data['module'] . '/' . $this->data['page']);
    }

    public function service_preview()
    {
        if (isset($_GET['sid']) && !empty($_GET['sid']))
        {
            extract($_GET);
            $inputs = array();
            $inputs['id'] = $_GET['sid'];

            $this->data['module'] = 'service_preview';
            $this->data['page'] = 'index';
            $this->data['service'] = $service = $this->home->get_service_details($inputs);
            $this->load->model('service_model', 'service');
            $this->data['service_image'] = $this->service->service_image($service['id']);
            $this->load->model("ServiceOfferedModel");
            $this->data['service_offered'] = $this->ServiceOfferedModel->get($service['id']);
            $this->load->model("Service_model");
            $params = [
                'except_id' => $service['id'],
                'category' => $service['category']
            ];
            $popular_service = $this->Service_model->getPopularServices($params);
            $this->data['popular_service'] = $popular_service;
            $this->load->model("ProvidersModel");
            $this->data['provider'] = $this->ProvidersModel->get($service['user_id']);
            if (!empty($service['id']))
            {
                $this->views($this->data['service']);
            }
            $this->load->model("RatingReview_model","RatingReview");
            $reviews = $this->RatingReview->get(['service_id'=>$service['id']]);
            $this->data['reviews'] = $reviews;
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template');
        }
        else
        {
            redirect(base_url());
        }
    }

    private function views($inputs)
    {
        $service_id = $inputs['id'];
        $user_id = rand(1, 100);

        $this->db->select('id');
        $this->db->from('views');
        $this->db->where('user_id', $user_id);
        $this->db->where('service_id', $service_id);
        $check_views = $this->db->count_all_results();

        $this->db->select('id');
        $this->db->from('services');
        $this->db->where('user_id', $user_id);
        $this->db->where('id', $service_id);
        $check_self_gig = $this->db->count_all_results();

        if ($check_views == 0 && $check_self_gig == 0)
        {
            $this->db->insert('views', array(
                'user_id' => $user_id,
                'service_id' => $service_id
            ));

            $this->db->set('total_views', 'total_views+1', false);
            $this->db->where('id', $service_id);
            $this->db->update('services');
        }
    }

    public function get_common_search_value()
    {
        if (isset($_GET['term']))
        {
            $search_value = $_GET['term'];
            $this->db->select("s.service_title,s.service_location,s.service_offered,c.category_name");
            $this->db->from('services s');
            $this->db->join('categories c', 'c.id = s.category', 'LEFT');
            $this->db->where("s.status = 1");
            $this->db->group_start();
            $this->db->like('s.service_title', $search_value);
            $this->db->or_like('s.service_location', $search_value);
            $this->db->or_like('c.category_name', $search_value);
            $this->db->group_end();
            $result = $this->db->get()->result_array();
            if (count($result) > 0)
            {
                foreach ($result as $row) $arr_result[] = ucfirst($row['service_title']);
                $arr_result[] = ucfirst($row['category_name']);

                echo json_encode($arr_result);
            }
        }
    }
    public function send_proposal()
    {
        //die('===========');
        $this->data['page'] = 'send_proposal';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function view_proposal()
    {
        //die('===========');
        $this->data['page'] = 'view_proposal';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function send_proposal_form()
    {
        $job_post_id = $this->input->post('job_post_id');
        $provider_id = $this->input->post('provider_id');
        $user_id = $this->input->post('user_id');
        $send_proposal_description = $this->input->post('send_proposal_description');
        $amount = $this->input->post('amount');

        $m_comment = $this->input->post('m_comment[]');
        //echo count($_POST['m_comment']);
        $m_amount = $this->input->post('m_amount[]');

        $data = array(
            'job_post_id' => $job_post_id,
            'provider_id' => $provider_id,
            'user_id' => $user_id,
            'send_proposal_description' => $send_proposal_description,
            'amount' => $amount,
            'm_comment' => implode(",", $m_comment) ,
            'm_amount' => implode(",", $m_amount)
        );

        $this->db->insert('send_proposal', $data);
        redirect(base_url() . 'latest-job');
    }
    public function send_proposal_edit()
    {
        $job_post_id = $this->input->post('job_post_id');
        $proposal_post_id = $this->input->post('proposal_post_id');

        $provider_id = $this->input->post('provider_id');
        $send_proposal_description = $this->input->post('send_proposal_description');
        $amount = $this->input->post('amount');

        $m_comment = $this->input->post('m_comment[]');
        //echo count($_POST['m_comment']);
        $m_amount = $this->input->post('m_amount[]');

        // print_r($_POST); die("============");
        $data = array(
            'job_post_id' => $job_post_id,
            'provider_id' => $provider_id,
            'send_proposal_description' => $send_proposal_description,
            'amount' => $amount,
            'm_comment' => implode(",", $m_comment) ,
            'm_amount' => implode(",", $m_amount)
        );

        $this->db->insert('', $data);

        $this->db->where('id', $proposal_post_id);
        $this->db->update('send_proposal', $data);
        redirect(base_url() . 'latest-job');
    }
    public function action_proposal()
    {
        if (isset($_GET['id']))
        {
            if ($_GET['action'] == 1)
            {
                $status = 1;
            }
            if ($_GET['action'] == 2)
            {
                $status = 2;
            }
            if ($_GET['action'] == 3)
            {
                $status = 3;
            }
            $data = array(
                'status' => $status,
            );
            $this->db->where('id', $_GET['id']);
            $this->db->update('send_proposal', $data);
            redirect(base_url() . 'manage-proposal?id=' . $_GET['mid']);
        }
    }
    public function user_dashboard()
    {
        $this->data['page'] = 'user_dashboard';
        $this->data['category'] = $this->home->get_category();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function add_service()
    {

        $this->data['page'] = 'add_service';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_bookings()
    {
        $this->data['page'] = 'user_bookings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_notifications()
    {
        $this->data['page'] = 'user_notifications';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_favourites()
    {
        $this->data['page'] = 'user_favourites';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_settings()
    {
        $this->data['page'] = 'user_settings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_reviews()
    {
        $this->data['page'] = 'user_reviews';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_chats()
    {
        $this->data['page'] = 'user_chats';
        $this->data['server_name'] = settingValue('server_name');
        $this->data['port_no'] = settingValue('port_no');
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function prof_services()
    {
        $this->data['page'] = 'prof_services';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function prof_service_detail()
    {
        $this->data['page'] = 'prof_service_detail';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function prof_packages()
    {
        $this->data['page'] = 'prof_packages';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function set_location()
    {
        $details = array(
            'user_address' => $this->input->post('address') ,
            'user_latitude' => $this->input->post('latitude') ,
            'user_longitude' => $this->input->post('longitude')
        );
        $this->session->set_userdata($details);
    }

    public function current_location()
    {
        if (!empty($_POST['location']))
        {
            $location = explode(',', $_POST['location']);
            $city_count = $this->db->like('name', $location[0], 'after')->from('city')->count_all_results();
            if ($city_count >= 1)
            {
                $this->session->set_userdata('current_location', $location[0]);
                echo 1;
            }
            else
            {
                echo 2;
            }
        }
    }

    public function clear_all_noty()
    {
        if (!empty($_POST['id']))
        {
            $user_type = $this->session->userdata('usertype');
            $res = $this->db->where('receiver=', $_POST['id'])->update('notification_table', ['status' => 0]);
            if ($res == true)
            {
                echo json_encode(['success' => true, 'msg' => 'cleared']);
                exit;
            }
            else
            {
                echo json_encode(['success' => false, 'msg' => 'not cleared']);
                exit;
            }
        }
    }

    public function clear_all_chat()
    {
        if (!empty($_POST['id']))
        {
            $user_type = $this->session->userdata('usertype');
            $res = $this->db->where('receiver_token=', $_POST['id'])->update('chat_table', ['read_status' => 1]);
            if ($res == true)
            {
                echo json_encode(['success' => true, 'msg' => 'cleared']);
                exit;
            }
            else
            {
                echo json_encode(['success' => false, 'msg' => 'not cleared']);
                exit;
            }
        }
    }

    public function third_form()
    {
        // echo "hello";
        if (isset($_POST['form_submitss']))
        {

            ///// insert query
            

            $this->data['page'] = 'yourself_next_again';
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template', $service_value);
        }

    }
    public function fifth_step()
    {
        // echo "hello";
        if (isset($_POST['form_submitss']))
        {

            ///// insert query
            

            $this->data['page'] = 'yourself_next_final_again';
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template', $service_value);
        }

    }
    public function fourth_step()
    {
        // echo "hello";
        if (isset($_POST['form_submitss']))
        {

            ///// insert query
            

            $this->data['page'] = 'yourself_next_final';
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template', $service_value);
        }

    }

    public function second_user_form()
    {
        $how_many_years_of_paid_experience_do_you_have = $this->input->post('how_many_years_of_paid_experience_do_you_have');
        $provide_proof_of_qualification_obtained_choose_to_do_your_job_an = $this->input->post('provide_proof_of_qualification_obtained_choose_to_do_your_job_an');
        $name_provide_proof_of_qualification_obtained_choose_to_do_your_j = $this->input->post('name_provide_proof_of_qualification_obtained_choose_to_do_your_j');
        if (!empty($_FILES['file_provide_proof_of_qualification_obtained_choose_to_do_your_j']['name']))
        {
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_provide_proof_of_qualification_obtained_choose_to_do_your_j'] = $_FILES['file_provide_proof_of_qualification_obtained_choose_to_do_your_j']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_provide_proof_of_qualification_obtained_choose_to_do_your_j'))
            {
                $uploadData = $this->upload->data();
                $file_provide_proof_of_qualification_obtained_choose_to_do_your_j = $_FILES['file_provide_proof_of_qualification_obtained_choose_to_do_your_j']['name'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        else
        {
            $file_provide_proof_of_qualification_obtained_choose_to_do_your_j = '';
        }

        $what_supplies_do_you_have_check_all_that_apply = $this->input->post('what_supplies_do_you_have_check_all_that_apply');

        $are_you_legally_eligible_to_work_in_the_current = $this->input->post('are_you_legally_eligible_to_work_in_the_current');

        $provide_proof_of_photo_id_you_must_choose_at_least_one_from = $this->input->post('provide_proof_of_photo_id_you_must_choose_at_least_one_from');
        $name_provide_proof_of_photo_id_you_must_choose_at_least_one_from = $this->input->post('name_provide_proof_of_photo_id_you_must_choose_at_least_one_from');
        $provide_proof_of_right_to_work_in_your_country_you_select_a_mini = $this->input->post('provide_proof_of_right_to_work_in_your_country_you_select_a_mini');
        $name_provide_proof_of_right_to_work_in_your_country_you_select_a = $this->input->post('name_provide_proof_of_right_to_work_in_your_country_you_select_a');
        $provide_proof_of_homes_address_must_be_less_than_3_months_old_fr = $this->input->post('provide_proof_of_homes_address_must_be_less_than_3_months_old_fr');
        $name_provide_proof_of_homes_address_must_be_less_than_3_months_o = $this->input->post('name_provide_proof_of_homes_address_must_be_less_than_3_months_o');
        $bank_name = $this->input->post('bank_name');
        $acc_holder_name = $this->input->post('acc_holder_name');
        $bank_address = $this->input->post('bank_address');
        $sort_code = $this->input->post('sort_code');
        $street_address = $this->input->post('street_address');
        $account_number = $this->input->post('account_number');
        $swost_code = $this->input->post('swost_code');
        $for_business_only = $this->input->post('for_business_only');
        $name_for_business_only = $this->input->post('name_for_business_only');

        if (!empty($_FILES['file_provide_proof_of_photo_id_you_must_choose_at_least_one_from']['name']))
        {
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_provide_proof_of_photo_id_you_must_choose_at_least_one_from'] = $_FILES['file_provide_proof_of_photo_id_you_must_choose_at_least_one_from']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_provide_proof_of_photo_id_you_must_choose_at_least_one_from'))
            {
                $uploadData = $this->upload->data();
                $file_provide_proof_of_photo_id_you_must_choose_at_least_one_from = $_FILES['file_provide_proof_of_photo_id_you_must_choose_at_least_one_from']['name'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        else
        {
            $file_provide_proof_of_photo_id_you_must_choose_at_least_one_from = '';
        }

        if (!empty($_FILES['file_provide_proof_of_right_to_work_in_your_country_you_select_a']['name']))
        {
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_provide_proof_of_right_to_work_in_your_country_you_select_a'] = $_FILES['file_provide_proof_of_right_to_work_in_your_country_you_select_a']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_provide_proof_of_right_to_work_in_your_country_you_select_a'))
            {
                $uploadData = $this->upload->data();
                $file_provide_proof_of_right_to_work_in_your_country_you_select_a = $_FILES['file_provide_proof_of_right_to_work_in_your_country_you_select_a']['name'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        else
        {
            $file_provide_proof_of_right_to_work_in_your_country_you_select_a = '';
        }

        if (!empty($_FILES['file_provide_proof_of_homes_address_must_be_less_than_3_months_o']['name']))
        {
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_provide_proof_of_homes_address_must_be_less_than_3_months_o'] = $_FILES['file_provide_proof_of_homes_address_must_be_less_than_3_months_o']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_provide_proof_of_homes_address_must_be_less_than_3_months_o'))
            {
                $uploadData = $this->upload->data();
                $file_provide_proof_of_homes_address_must_be_less_than_3_months_o = $_FILES['file_provide_proof_of_homes_address_must_be_less_than_3_months_o']['name'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        else
        {
            $file_provide_proof_of_homes_address_must_be_less_than_3_months_o = '';
        }

        if (!empty($_FILES['file_for_business_only']['name']))
        {
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_for_business_only'] = $_FILES['file_for_business_only']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_for_business_only'))
            {
                $uploadData = $this->upload->data();
                $file_for_business_only = $_FILES['file_for_business_only']['name'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        else
        {
            $file_for_business_only = '';
        }

        if (!empty($_FILES['upload_the_must_current_photo_of_you']['name']))
        {
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['upload_the_must_current_photo_of_you'] = $_FILES['upload_the_must_current_photo_of_you']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_the_must_current_photo_of_you'))
            {
                $uploadData = $this->upload->data();
                $upload_the_must_current_photo_of_you = $_FILES['upload_the_must_current_photo_of_you']['name'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        else
        {
            $upload_the_must_current_photo_of_you = '';
        }

        if (!empty($_FILES['facial_video_verification_is_a_must']['name']))
        {
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['facial_video_verification_is_a_must'] = $_FILES['facial_video_verification_is_a_must']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('facial_video_verification_is_a_must'))
            {
                $uploadData = $this->upload->data();
                $facial_video_verification_is_a_must = $_FILES['facial_video_verification_is_a_must']['name'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        else
        {
            $facial_video_verification_is_a_must = '';
        }

        $updateData = ['how_many_years_of_paid_experience_do_you_have' => $how_many_years_of_paid_experience_do_you_have,

        'provide_proof_of_qualification_obtained_choose_to_do_your_job_an' => $provide_proof_of_qualification_obtained_choose_to_do_your_job_an,

        'name_provide_proof_of_qualification_obtained_choose_to_do_your_j' => $name_provide_proof_of_qualification_obtained_choose_to_do_your_j,

        'file_provide_proof_of_qualification_obtained_choose_to_do_your_j' => $file_provide_proof_of_qualification_obtained_choose_to_do_your_j,

        'what_supplies_do_you_have_check_all_that_apply' => implode(",", $what_supplies_do_you_have_check_all_that_apply) ,

        'are_you_legally_eligible_to_work_in_the_current' => $are_you_legally_eligible_to_work_in_the_current,

        'provide_proof_of_photo_id_you_must_choose_at_least_one_from' => $provide_proof_of_photo_id_you_must_choose_at_least_one_from, 'name_provide_proof_of_photo_id_you_must_choose_at_least_one_from' => $name_provide_proof_of_photo_id_you_must_choose_at_least_one_from, 'file_provide_proof_of_photo_id_you_must_choose_at_least_one_from' => $file_provide_proof_of_photo_id_you_must_choose_at_least_one_from, 'provide_proof_of_right_to_work_in_your_country_you_select_a_mini' => $provide_proof_of_right_to_work_in_your_country_you_select_a_mini, 'name_provide_proof_of_right_to_work_in_your_country_you_select_a' => $name_provide_proof_of_right_to_work_in_your_country_you_select_a, 'file_provide_proof_of_right_to_work_in_your_country_you_select_a' => $file_provide_proof_of_right_to_work_in_your_country_you_select_a, 'provide_proof_of_homes_address_must_be_less_than_3_months_old_fr' => $provide_proof_of_homes_address_must_be_less_than_3_months_old_fr, 'name_provide_proof_of_homes_address_must_be_less_than_3_months_o' => $name_provide_proof_of_homes_address_must_be_less_than_3_months_o, 'file_provide_proof_of_homes_address_must_be_less_than_3_months_o' => $file_provide_proof_of_homes_address_must_be_less_than_3_months_o, 'bank_name' => $bank_name, 'acc_holder_name' => $acc_holder_name, 'bank_address' => $bank_address, 'sort_code' => $sort_code, 'account_number_new' => $account_number_new, 'swost_code' => $swost_code, 'for_business_only' => $for_business_only, 'name_for_business_only' => $name_for_business_only, 'file_for_business_only' => $file_for_business_only, 'upload_the_must_current_photo_of_you' => $upload_the_must_current_photo_of_you, 'facial_video_verification_is_a_must' => $facial_video_verification_is_a_must, ];

        // $input_data = $this->input->post();
        // print_r($input_data);
        $id = $this->session->userdata('user_last_insert_id');

        $this->db->where('id', $id);
        $this->db->update('users', $updateData);

        //added by MaksimU ----------------------
        $this->user_meta->register_meta($id);
        //end by MaksimU ------------------------
        redirect(base_url());

    }

    /* Leo: after otp verfication, register user and send confirm email */
    public function otp_verification_check()
    {
        // die("=====");
        $this->session->userdata('mobile_otp');
        $this->input->post('mobile_otp');

        if ($this->session->userdata('mobile_otp') == $this->input->post('mobile_otp'))
        {
            // Leo: if matched otp, register user
            $this->load->model('user_login_model', 'user_login');
            $input_data = $this->session->userdata("register_input");
            if (isset($input_data) && is_array($input_data)) {
                //$check_data= array('mobile_number' => $input_data['mobileno'],'otp'=>$input_data['otp'] );
                $input_data['is_agree'] = 1;
                $input_data['status'] = 1;
                $check = $this->user_login->insertemailusers($input_data);
                $user_details = $check['data'];

                if (is_array($check) && $check['msg'] == 'ok')
                {
                    $this->session->unset_userdata('register_input');

                    $date = utc_date_conversion(date('Y-m-d H:i:s'));

                    if (!empty($input_data['mobileno']))
                    {
                        $this->db->where('mobileno', $input_data['mobileno']);
                        $this->db->where('status', 1);
                        $this->db->update('users', array(
                          'last_login' => $date
                        ));
                    }

                    // mail send to user's email
                    $bodyid = 1;
                    $tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
                    $body = $tempbody_details['template_content'];
                    $body = str_replace('{user_name}', $input_data['name'], $body);
                    $preview_link = base_url();
                    $body = str_replace('{preview_link}', $preview_link, $body);
                    $body = str_replace('{sitetile}', 'Tazzer Group', $body);

                    $phpmail_config = settingValue('mail_config');
                    if (isset($phpmail_config) && !empty($phpmail_config))
                    {
                      if ($phpmail_config == "phpmail")
                      {
                        $from_email = settingValue('email_address');
                      }
                      else
                      {
                        $from_email = settingValue('smtp_email_address');
                      }
                    }
                    $this->load->library('email');

                    if (!empty($from_email))
                    {
                      $mail = $this->email->from($from_email)->to($input_data['email'])->subject('Registration Confirmation')->message($body)->send();
                    }
                }
            }
            
            $this->data['page'] = 'user_form';
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template', $service_value);
        }
        else
        {
            redirect(base_url() . 'otp-verification?msg=not');
        }
    }

    /* Leo: added for register user form */
    public function user_form() {
        $this->data['page'] = 'user_form';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template', $service_value);
    }

    public function otp_verification()
    {
        $this->data['page'] = 'otp_varifocation';
	   
        // Leo: if already registered, redirect to user form
        if (!$this->session->userdata("register_input")) {
            // code...
            redirect(base_url("user-form"));
        }
	# add by maksimU : For Test
        if(ENVIRONMENT=='development')
        {
            print('<script>alert("'.$this->session->userdata('mobile_otp').'");</script>');
        }
	# end
	
        // $postData = $this->input->post();
        // print_r($this->session->userdata('mobile_otp'));
        // print_r($this->session->userdata('email')); exit;
        $postData = ['promo' => $this->session->userdata('mobile_otp') , 'email' => $this->session->userdata('email') , 'description' => 'verfication code'];
        $this->data['postData'] = $postData;
        $config = array(
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'priority' => '1'
        );
        $phpmail_config = settingValue('mail_config');
        if (isset($phpmail_config) && !empty($phpmail_config))
        {
            if ($phpmail_config == "phpmail")
            {
                $from_email = settingValue('email_address');
            }
            else
            {
                $from_email = settingValue('smtp_email_address');
            }
        }
        // $from_email = "info@tazzerclean.co.uk";
        $this->load->library('email');
        $to_email = $postData['email'];
        /* Leo: send_success */
        $send_success = false;   
        if (!empty($from_email))
        {
            $body = $this->load->view('admin/email/promo-notification', $this->data, true);
            $mail = $this->email->initialize($config)->set_newline("\r\n")->from($from_email)->to($to_email)->subject('Verification Code')->message($body)->send();

            if (!$mail)
            {
                echo "send code error! to " . $to_email;
            }
            else {
                $send_success = true;
            }
        }
        // echo json_encode(array('status' => true));
        $this->data["send_success"] = $send_success;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function yourself()
    {

        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');

        $phone = $this->input->post('phone');

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $postal_code = $this->input->post('postal_code');
        $service_values = $this->input->post('service_values');

        //print_r($service_value); die;
        /*print_r($_POST);
         die();*/

        $res = $this->db->insert('tbl_yourself', array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
            'email' => $email,
            'password' => md5($password) ,
            'postal_code' => $postal_code,
            'service_values' => $service_values
        ));

        $id = $this->db->insert_id();

        $service_value['last_inserted'] = $id;

        $this->session->set_userdata('yourself_id', $id);

        $rand = rand(0, 9999);
        $phone_otp = str_pad($rand, 4, '0', STR_PAD_LEFT);
        //Mobile send otp
        // Account details
        $apiKey = urlencode('NTU1MjM5NTEzMTZkNWE0YjcyNjc2NDVhNmI3MDY4NTM=');
        // Message details
        //  $numbers = array(447488233413);
        $numbers = array(
            $phone
        ); //phone number
        $sender = urlencode('Tazzerz');
        $message = rawurlencode('Please activate your account by verifying your number {' . $phone_otp . '}'); //message
        $numbers = implode(',', $numbers);

        // Prepare data for POST request
        $data = array(
            'apikey' => $apiKey,
            'numbers' => $numbers,
            "sender" => $sender,
            "message" => $message
        );

        // Send the POST request with cURL
        $ch = curl_init('https://api.txtlocal.com/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Process your response here
        //echo $response;
        $this->session->set_userdata('mobile_otp', $phone_otp);

        $rand = rand(0, 9999);
        $email_otp = str_pad($rand, 4, '0', STR_PAD_LEFT);

        $this->session->set_userdata('email_otp', $email_otp);
        // the message
        $msg = "Your one time password.\nEnter verify ";

        // use wordwrap() if lines are longer than 70 characters
        //$msg = wordwrap($msg,70);
        $to = "vikasmishra0309@gmail.com";
        $subject = "This is subject";

        $message = "<b>Your one time password.</b>";
        $message .= "<h1>Enter otp and verify :" . $email_otp . "</h1>";
        $message = wordwrap($message, 70, "\r\n");
        $headers = 'From: webmaster@example.com' . "\r\n" . 'Reply-To: webmaster@example.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

        $retval = mail($to, $subject, $message);

        if ($retval == true)
        {
            //echo "Message sent successfully...";
            
        }
        else
        {
            //echo "Message could not be sent...";
            
        }

        $this->data['page'] = 'otp';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template', $service_value);
        /*
        $this->data['page'] = 'yourself';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template',$service_value);*/
    }

    public function yourself_next()
    {

        $this->data['page'] = 'yourself';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template', $service_value);
    }

    public function otp_verify_email()
    {
        $mobile_otp = $this->input->post('mobile_otp');
        $email_otp = $this->input->post('email_otp');

        $phone = $this->input->post('phone');
        $email = $this->input->post('email');

        $service_value['service_data'] = $this->input->post('service_data');
        $sess_mobil = $this->session->userdata('mobile_otp', $mobile_otp);
        $sess_email = $this->session->userdata('email_otp', $email_otp);

        // && $email_otp==$sess_email
        if ($mobile_otp == $sess_mobil)
        {
            $this->data['page'] = 'yourself';
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template', $service_value);
        }
        else
        {

            $service_value['service_phone'] = $phone;
            $service_value['service_email'] = $email;

            $service_value['msg'] = "not";
            $this->data['page'] = 'otp';
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template', $service_value);
        }

    }

    public function yourself_final()
    {
        //die("============================");
        $this->data['page'] = 'yourself_next';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template', $service_value);
    }

    public function second_form()
    {
        // print_r($_POST);
        // die("hhhhhhhhh");
        // cheackbox
        if (isset($_POST['form_submitss']))
        {
            // die("hhhhhhhhh");
            $street_address = $this->input->post('street_address');
            $apt = $this->input->post('apt');
            $city = $this->input->post('city');
            $province = $this->input->post('province');
            $postal_code1 = $this->input->post('postal_code1');
            $what_types_of_jobs_would_you_like_to_see = $this->input->post('what_types_of_jobs_would_you_like_to_see');
            $work_experience = $this->input->post('work_experience');
            $what_are_you_applying_as_or_for_you_can_only_pick_one = $this->input->post('what_are_you_applying_as_or_for_you_can_only_pick_one');
            $what_are_the_areas_would_you_like_to_work_in_as_them_to_select = $this->input->post('what_are_the_areas_would_you_like_to_work_in_as_them_to_select');
            $most_recent_company = $this->input->post('most_recent_company');
            $most_address_company = $this->input->post('most_address_company');
            $salary_wage = $this->input->post('salary_wage');
            $company_from = $this->input->post('company_from');
            $company_to = $this->input->post('company_to');
            $how_many_years_of_paid_experience_do_you_have = $this->input->post('how_many_years_of_paid_experience_do_you_have');
            $name_how_many_years_of_paid_experience_do_you_have = $this->input->post('name_how_many_years_of_paid_experience_do_you_have');

            // $file_how_many_years_of_paid_experience_do_you_have = $this->input->post('file_how_many_years_of_paid_experience_do_you_have');
            

            if (!empty($_FILES['file_how_many_years_of_paid_experience_do_you_have']['name']))
            {
                $config['upload_path'] = './assets/img/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_how_many_years_of_paid_experience_do_you_have'] = $_FILES['file_how_many_years_of_paid_experience_do_you_have']['name'];

                //Load upload library and initialize configuration
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file_how_many_years_of_paid_experience_do_you_have'))
                {
                    $uploadData = $this->upload->data();
                    $file_how_many_years_of_paid_experience_do_you_have = $_FILES['file_how_many_years_of_paid_experience_do_you_have']['name'];
                    //  print_r($_REQUEST);
                    
                }
                else
                {
                    echo $this->data['error'] = $this->upload->display_errors();
                }
            }
            else
            {
                $file_how_many_years_of_paid_experience_do_you_have = '';
            }

            $relevant_to_applied = $this->input->post('relevant_to_applied');
            $member_certificate = $this->input->post('member_certificate');
            $skills_special = $this->input->post('skills_special');
            $worked_similar = $this->input->post('worked_similar');
            $what_supplies_do_you_have_check_all_that_apply = $this->input->post('what_supplies_do_you_have_check_all_that_apply');
            $are_you_legally_eligible_to_work_in_the_united_kingdom = $this->input->post('are_you_legally_eligible_to_work_in_the_united_kingdom');
            $provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload = $this->input->post('provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload');
            $name_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload = $this->input->post('name_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload');

            // $file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload = $this->input->post('file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload');
            if (!empty($_FILES['file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload']['name']))
            {
                $config['upload_path'] = './assets/img/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload'] = $_FILES['file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload']['name'];

                //Load upload library and initialize configuration
                $this->load->library('upload', $config);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload'))
                {
                    $uploadData = $this->upload->data();
                    //  print_r($_REQUEST);
                    $file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload = $_FILES['file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload']['name'];
                }
                else
                {
                    echo $this->data['error'] = $this->upload->display_errors();
                }
            }
            else
            {
                $file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload = '';
            }

            $provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload = $this->input->post('provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload');
            $name_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload = $this->input->post('name_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload');

            // $file_provide_proof_of_right_tos_work_in_your_country_you_select_a_minimum_of_one_and_upload = $this->input->post('file_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload');
            

            if (!empty($_FILES['file_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload']['name']))
            {
                $config['upload_path'] = './assets/img/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload'] = $_FILES['file_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload']['name'];

                //Load upload library and initialize configuration
                $this->load->library('upload', $config);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload'))
                {
                    $uploadData = $this->upload->data();
                    $file_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload = $_FILES['file_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload']['name'];
                    //  print_r($_REQUEST);
                    
                }
                else
                {
                    echo $this->data['error'] = $this->upload->display_errors();
                }
            }
            else
            {
                $file_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload = '';
            }

            $provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue = $this->input->post('provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue');
            $name_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue = $this->input->post('name_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue');

            // $file_provide_proof_of_homyes_address_must_be_less_than_3_months_old_from_the_date_of_issue = $this->input->post('file_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue');
            if (!empty($_FILES['file_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue']['name']))
            {
                $config['upload_path'] = './assets/img/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue'] = $_FILES['file_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue']['name'];

                //Load upload library and initialize configuration
                $this->load->library('upload', $config);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue'))
                {
                    $uploadData = $this->upload->data();
                    $file_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue = $_FILES['file_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue']['name'];
                    //  print_r($_REQUEST);
                    
                }
                else
                {
                    echo $this->data['error'] = $this->upload->display_errors();
                }
            }
            else
            {
                $file_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue = '';
            }

            $for_business_only = $this->input->post('for_business_only');
            $name_for_business_only = $this->input->post('name_for_business_only');

            // $file_for_business_only = $this->input->post('file_for_business_only');
            if (!empty($_FILES['file_for_business_only']['name']))
            {
                $config['upload_path'] = './assets/img/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_for_business_only'] = $_FILES['file_for_business_only']['name'];

                //Load upload library and initialize configuration
                $this->load->library('upload', $config);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file_for_business_only'))
                {
                    $uploadData = $this->upload->data();
                    $file_for_business_only = $_FILES['file_for_business_only']['name'];
                    //  print_r($_REQUEST);
                    
                }
                else
                {
                    echo $this->data['error'] = $this->upload->display_errors();
                }
            }
            else
            {
                $file_for_business_only = '';
            }

            // $file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification = $this->input->post('file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification');
            if (!empty($_FILES['file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification']['name']))
            {
                // $this->upload->initialize($config);
                $config['upload_path'] = './assets/img/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification'] = $_FILES['file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification']['name'];

                //Load upload library and initialize configuration
                $this->load->library('upload', $config);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification'))
                {
                    $uploadData = $this->upload->data();
                    $file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification = $_FILES['file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification']['name'];
                    //  print_r($_REQUEST);
                    
                }
                else
                {
                    echo $this->data['error'] = $this->upload->display_errors();
                }
            }
            else
            {
                $file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification = '';
            }

            $Have_you_ever_been_or_are_you_currently_going_through_any_investigation_or_disciplinary_action = $this->input->post('Have_you_ever_been_or_are_you_currently_going_through_any_investigation_or_disciplinary_action');
            $have_you_been_dismissed_from_any_employment_or_lost_contract = $this->input->post('have_you_been_dismissed_from_any_employment_or_lost_contract');
            $do_you_have_any_spent_unspent_convictions_or_cautions_under_the_rehabilitation_of_offenders_act_1974 = $this->input->post('do_you_have_any_spent_unspent_convictions_or_cautions_under_the_rehabilitation_of_offenders_act_1974');
            $are_you_facing_any_criminal_prosecutions = $this->input->post('are_you_facing_any_criminal_prosecutions');
            $if_business_has_any_of_your_director_been_disqualify_as_a_director_for_the_last_5_years = $this->input->post('cars1');
            $name_if_business_has_any_of_your_director_been_disqualify_as_a_director_for_the_last_5_years = $this->input->post('name_if_business_has_any_of_your_director_been_disqualify_as_a_director_for_the_last_5_years');
            $has_any_of_them_been_made_bankrupt_and_or_insolvency = $this->input->post('cars');
            $name_has_any_of_them_been_made_bankrupt_and_or_insolvency = $this->input->post('name_has_any_of_them_been_made_bankrupt_and_or_insolvency');
            $scheduling_and_interview = $this->input->post('scheduling_and_interview');
            $name_provide1 = $this->input->post('name_provide1');
            $phone_provide1 = $this->input->post('phone_provide1');
            $address_provide1 = $this->input->post('address_provide1');
            $email_provide1 = $this->input->post('email_provide1');
            $position_company_provide1 = $this->input->post('position_company_provide1');
            $relationship_provide1 = $this->input->post('relationship_provide1');
            $name_provide2 = $this->input->post('name_provide2');
            $phone_provide2 = $this->input->post('phone_provide2');
            $address_provide2 = $this->input->post('address_provide2');
            $email_provide2 = $this->input->post('email_provide2');
            $position_company_provide1 = $this->input->post('position_company_provide1');
            $relationship_provide2 = $this->input->post('relationship_provide2');

            $please_tell_us_if_you_are_working_full = $this->input->post('please_tell_us_if_you_are_working_full');
            $please_tell_us_your_working_days = $this->input->post('please_tell_us_your_working_days');
            $please_tell_your_working_ours = $this->input->post('please_tell_your_working_ours');

            $t_shirt_preference = $this->input->post('t_shirt_preference');
            $size = $this->input->post('size');
            $how_do_you_plan_on_commuting_to_jobs = $this->input->post('how_do_you_plan_on_commuting_to_jobs');

            $updateData = ['street_address' => $street_address, 'apt' => $apt, 'city' => $city, 'province' => $province, 'postal_code1' => $postal_code1, 'what_types_of_jobs_would_you_like_to_see' => json_encode(implode(",", $what_types_of_jobs_would_you_like_to_see)) , 'work_experience' => $work_experience, 'what_are_you_applying_as_or_for_you_can_only_pick_one' => json_encode(implode(",", $what_are_you_applying_as_or_for_you_can_only_pick_one)) , 'what_are_the_areas_would_you_like_to_work_in_as_them_to_select' => json_encode(implode(",", $what_are_the_areas_would_you_like_to_work_in_as_them_to_select)) , 'most_recent_company' => $most_recent_company, 'most_address_company' => $most_address_company, 'salary_wage' => $salary_wage, 'company_from' => $company_from, 'company_to' => $company_to, 'how_many_years_of_paid_experience_do_you_have' => $how_many_years_of_paid_experience_do_you_have, 'name_how_many_years_of_paid_experience_do_you_have' => $name_how_many_years_of_paid_experience_do_you_have, 'file_how_many_years_of_paid_experience_do_you_have' => $file_how_many_years_of_paid_experience_do_you_have, 'relevant_to_applied' => $relevant_to_applied, 'member_certificate' => $member_certificate, 'skills_special' => $skills_special, 'worked_similar' => $worked_similar,

            'what_supplies_do_you_have_check_all_that_apply' => json_encode(implode(",", $what_supplies_do_you_have_check_all_that_apply)) , 'are_you_legally_eligible_to_work_in_the_united_kingdom' => $are_you_legally_eligible_to_work_in_the_united_kingdom,

            'provide_proof_of_photo_id_you_must_choose_at_least_one_from_the' => $provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload, 'name_provide_proof_of_photo_id_you_must_choose_at_least_one_from' => $name_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload, 'file_provide_proof_of_photo_id_you_must_choose_at_least_one_from' => $file_provide_proof_of_photo_id_you_must_choose_at_least_one_from_the_list_and_upload,

            'provide_proof_of_right_to_work_in_your_country_you_select_a_mini' => $provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload, 'name_provide_proof_of_right_to_work_in_your_country_you_select_a' => $name_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload, 'file_provide_proof_of_right_to_work_in_your_country_you_select_a' => $file_provide_proof_of_right_to_work_in_your_country_you_select_a_minimum_of_one_and_upload, '    provide_proof_of_homes_address_must_be_less_than_3_months_old_fr' => $provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue, '    name_provide_proof_of_homes_address_must_be_less_than_3_months_o' => $name_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue, 'file_provide_proof_of_homes_address_must_be_less_than_3_months_o' => $file_provide_proof_of_homes_address_must_be_less_than_3_months_old_from_the_date_of_issue, 'for_business_only' => $for_business_only, 'name_for_business_only' => $name_for_business_only, 'file_for_business_only' => $file_for_business_only, 'file_please_upload_the_must_current_photo_which_will_be_used_to' => $file_please_upload_the_must_current_photo_which_will_be_used_to_send_to_clients_for_recognition_and_identification, 'Have_you_ever_been_or_are_you_currently_going_through_any_invest' => $Have_you_ever_been_or_are_you_currently_going_through_any_investigation_or_disciplinary_action, 'have_you_been_dismissed_from_any_employment_or_lost_contract' => $have_you_been_dismissed_from_any_employment_or_lost_contract, 'do_you_have_any_spent_unspent_convictions_or_cautions_under_the_' => $do_you_have_any_spent_unspent_convictions_or_cautions_under_the_rehabilitation_of_offenders_act_1974, 'are_you_facing_any_criminal_prosecutions' => $are_you_facing_any_criminal_prosecutionsare_you_facing_any_criminal_prosecutions, '    if_business_has_any_of_your_director_been_disqualify_as_a_direct' => $if_business_has_any_of_your_director_been_disqualify_as_a_director_for_the_last_5_years, 'name_if_business_has_any_of_your_director_been_disqualify_as_a_d' => $name_if_business_has_any_of_your_director_been_disqualify_as_a_director_for_the_last_5_years, 'has_any_of_them_been_made_bankrupt_and_or_insolvency' => $has_any_of_them_been_made_bankrupt_and_or_insolvency, 'name_has_any_of_them_been_made_bankrupt_and_or_insolvency' => $name_has_any_of_them_been_made_bankrupt_and_or_insolvency, 'scheduling_and_interview' => $scheduling_and_interview, 'name_provide1' => $name_provide1, 'phone_provide1' => $phone_provide1, 'address_provide1' => $address_provide1, 'email_provide1' => $email_provide1, 'position_company_provide1' => $position_company_provide1, 'relationship_provide1' => $relationship_provide1, 'name_provide2' => $name_provide2, 'phone_provide2' => $phone_provide2, 'address_provide2' => $address_provide2, 'email_provide2' => $email_provide2, 'position_company_provide1' => $position_company_provide1, 'relationship_provide2' => $relationship_provide2, 'please_tell_us_if_you_are_working_full' => $please_tell_us_if_you_are_working_full, 'please_tell_us_your_working_days' => json_encode(implode(",", $please_tell_us_your_working_days)) , 'please_tell_your_working_ours' => $please_tell_your_working_ours, 't_shirt_preference' => $t_shirt_preference, 'size' => $size, 'how_do_you_plan_on_commuting_to_jobs' => $how_do_you_plan_on_commuting_to_jobs];

            // $id=$this->db->insert_id();
            $yourself_id = $this->session->userdata('yourself_id');

            $this->db->where('id', $yourself_id);
            $this->db->update('tbl_yourself', $updateData);

            //print_r($this->db->last_query());  die("=====");
            redirect(base_url());

            // $this->data['page'] = 'yourself_next';
            // $this->load->vars($this->data);
            // $this->load->view($this->data['theme'] . '/template',$service_value);
            

            
        }
    }

    public function yourself_last()
    {
        //$passport
        $passport = $_FILES['passport']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['passport']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['passport'] = $_FILES['passport']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('passport'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['passport'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Driving_licence
        $Driving_licence = $_FILES['Driving_licence']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Driving_licence']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Driving_licence'] = $_FILES['Driving_licence']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Driving_licence'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Driving_licence'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        //$Biometric_card
        $Biometric_card = $_FILES['Biometric_card']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Biometric_card']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Biometric_card'] = $_FILES['Biometric_card']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Biometric_card'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Biometric_card'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        //$ID_card
        $ID_card = $_FILES['ID_card']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['ID_card']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['ID_card'] = $_FILES['ID_card']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('ID_card'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['ID_card'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        //$Resident_permit
        $Resident_permit = $_FILES['Resident_permit']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Resident_permit']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Resident_permit'] = $_FILES['Resident_permit']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Resident_permit'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Resident_permit'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Other
        $Other = $_FILES['Other']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Other']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Other'] = $_FILES['Other']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Other'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Other'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$National_Insurence
        $National_Insurence = $_FILES['National_Insurence']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['National_Insurence']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['National_Insurence'] = $_FILES['National_Insurence']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('National_Insurence'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['National_Insurence'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        //$Dri_licence
        $Dri_licence = $_FILES['Dri_licence']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Dri_licence']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Dri_licence'] = $_FILES['Dri_licence']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Dri_licence'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Dri_licence'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Biometriccard
        $Biometriccard = $_FILES['Biometriccard']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Biometriccard']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Biometriccard'] = $_FILES['Biometriccard']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Biometriccard'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Biometriccard'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        //$IDcard
        $IDcard = $_FILES['IDcard']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['IDcard']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['IDcard'] = $_FILES['IDcard']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('IDcard'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['IDcard'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Resident_permit
        $Resident_permit = $_FILES['Resident_permit']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Resident_permit']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Resident_permit'] = $_FILES['Resident_permit']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Resident_permit'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Resident_permit'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Other1
        $Other1 = $_FILES['Other1']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Other1']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Other1'] = $_FILES['Other1']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Other1'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Other1'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Telephone_Bill
        $Telephone_Bill = $_FILES['Telephone_Bill']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Telephone_Bill']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Telephone_Bill'] = $_FILES['Telephone_Bill']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Telephone_Bill'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Telephone_Bill'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Gas_or_electric
        $Gas_or_electric = $_FILES['Gas_or_electric']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Gas_or_electric']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Gas_or_electric'] = $_FILES['Gas_or_electric']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Gas_or_electric'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Gas_or_electric'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Bank_statement
        $Bank_statement = $_FILES['Bank_statement']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Bank_statement']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Bank_statement'] = $_FILES['Bank_statement']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Bank_statement'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Bank_statement'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Letter_from_government
        $Letter_from_government = $_FILES['Letter_from_government']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Letter_from_government']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Letter_from_government'] = $_FILES['Letter_from_government']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Letter_from_government'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Letter_from_government'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Other2
        $Other2 = $_FILES['Other2']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Other2']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Other2'] = $_FILES['Other2']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Other2'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Other2'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$NVQ
        $NVQ = $_FILES['NVQ']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['NVQ']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['NVQ'] = $_FILES['NVQ']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('NVQ'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['NVQ'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$GCE
        $GCE = $_FILES['GCE']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['GCE']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['GCE'] = $_FILES['GCE']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('GCE'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['GCE'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$College_degreev
        $College_degreev = $_FILES['College_degreev']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['College_degreev']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['College_degreev'] = $_FILES['College_degreev']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('College_degreev'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['College_degreev'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$University_degree
        $University_degree = $_FILES['University_degree']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['University_degree']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['University_degree'] = $_FILES['University_degree']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('University_degree'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['University_degree'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        //$HND
        $HND = $_FILES['HND']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['HND']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['HND'] = $_FILES['HND']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('HND'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['HND'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Vocational_qualification
        $Vocational_qualification = $_FILES['Vocational_qualification']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Vocational_qualification']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Vocational_qualification'] = $_FILES['Vocational_qualification']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Vocational_qualification'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Vocational_qualification'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$any_qualifications
        $any_qualifications = $_FILES['any_qualifications']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['any_qualifications']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['any_qualifications'] = $_FILES['any_qualifications']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('any_qualifications'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['any_qualifications'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Other3
        $Other3 = $_FILES['Other3']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Other3']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Other3'] = $_FILES['Other3']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Other3'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Other3'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$name_and_upload
        $name_and_upload = $_FILES['name_and_upload']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['name_and_upload']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['name_and_upload'] = $_FILES['name_and_upload']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('name_and_upload'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['name_and_upload'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        //$Comp_register_number
        $Comp_register_number = $_FILES['Comp_register_number']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Comp_register_number']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Comp_register_number'] = $_FILES['Comp_register_number']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Comp_register_number'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Comp_register_number'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Comp_register_document
        $Comp_register_document = $_FILES['Comp_register_document']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Comp_register_document']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Comp_register_document'] = $_FILES['Comp_register_document']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Comp_register_document'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Comp_register_document'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Business_insurance
        $Business_insurance = $_FILES['Business_insurance']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Business_insurance']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Business_insurance'] = $_FILES['Business_insurance']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Business_insurance'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Business_insurance'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$Methodstatement
        $Methodstatement = $_FILES['Methodstatement']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Methodstatement']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Methodstatement'] = $_FILES['Methodstatement']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Methodstatement'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Methodstatement'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$trading_address
        $trading_address = $_FILES['trading_address']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['trading_address']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['trading_address'] = $_FILES['trading_address']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('trading_address'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['trading_address'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }

        //$responsible_individual
        $responsible_individual = $_FILES['responsible_individual']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['responsible_individual']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['responsible_individual'] = $_FILES['responsible_individual']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('responsible_individual'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['responsible_individual'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
        //$Other4
        $Other4 = $_FILES['Other4']['name'];
        ///Check whether user upload picture
        if (!empty($_FILES['Other4']['name']))
        {
            $config['upload_path'] = './Assets/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['Other4'] = $_FILES['Other4']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if ($this->upload->do_upload('Other4'))
            {
                $uploadData = $this->upload->data();
                $image = $uploadData['Other4'];
                //  print_r($_REQUEST);
                
            }
            else
            {
                echo $this->data['error'] = $this->upload->display_errors();
            }
        }
    }
    public function email_subscription()
    {
        $input['email'] = $this->input->post('email');
        $res = $this->home->add_email_subscription($input);
        if ($res)
        {
            echo 1;
        }
    }
    //    if(){
    /*$employee = $this->input->post('');
    $subcontractors= $this->input->post('');
    $self_employed= $this->input->post('');
    $business_with_emp= $this->input->post('');
    $around_postcode= $this->input->post('');
    $10miles= $this->input->post('');
    $30miles= $this->input->post('');
    $50miles= $this->input->post('');
    $all_country= $this->input->post('');
    $material_tools= $this->input->post('');
    $employment_working= $this->input->post('');
    $willing_train= $this->input->post('');
    $present_our_client= $this->input->post('');
    $general_cleaning= $this->input->post('');
    $commercial_clean= $this->input->post('');
    $deep_clean= $this->input->post('');
    $industrial= $this->input->post('');
    $factory= $this->input->post('');
    $specialized= $this->input->post('');
    $fogging= $this->input->post('');
    $other= $this->input->post('');
    $hoovers= $this->input->post('');
    $cleaning_materials= $this->input->post('');
    $fogging_machines= $this->input->post('');
    $industrial_cleaning= $this->input->post('');
    $other_please_list= $this->input->post('');*/
    //  }
    /*if(){
    $employee= $this->input->post('');
    $subcontractors= $this->input->post('');
    $self_employed= $this->input->post('');
    $individual_staff= $this->input->post('');
    $Just_postcode= $this->input->post('');
    $over_country= $this->input->post('');
    $qualification_carry= $this->input->post('');
    $carinsurance= $this->input->post('');
    $proof_of_address= $this->input->post('');
    $motcertificate= $this->input->post('');
    $driving_licence= $this->input->post('');
    $utility_bill_dated= $this->input->post('');
    $right_to_work= $this->input->post('');
    $Business_r_certificate= $this->input->post('');
    $Businessinsurance= $this->input->post('');
    $Methodstatement= $this->input->post('');
    $Proof_ID_Passport= $this->input->post('');
    $References_last_employers= $this->input->post('');
    $Bank_Information_payment= $this->input->post('');
    $transport_applicable= $this->input->post('');
    $employment_while= $this->input->post('');
    $opportunityarises= $this->input->post('');
    $present_to_client= $this->input->post('');
    $general_handyman= $this->input->post('');
    $garage_Door_Repair= $this->input->post('');
    $Fence_Gate_Repair= $this->input->post('');
    $Kitchen_Refurbishment= $this->input->post('');
    $Bathroom_Tilers= $this->input->post('');
    $Fence_Gate_Installation= $this->input->post('');
    $Garage_Door_Installation= $this->input->post('');
    $Handyman= $this->input->post('');
    $Internal_Fitter= $this->input->post('');
    $Paint_Stripping= $this->input->post('');
    $Hanging_Art= $this->input->post('');
    $Cabinetry= $this->input->post('');
    $Pipes_Maintenance_Taps= $this->input->post('');
    $Splashback_worktops= $this->input->post('');
    $Shelving_Repair_Installation= $this->input->post('');
    $Switche_Outlets= $this->input->post('');
    $Polycarbonate_sheet_Installation= $this->input->post('');
    $roof_sheet_Repairs= $this->input->post('');
    $Shop_Fitters_Audio= $this->input->post('');
    $steel_Furniture_Tree= $this->input->post('');
    $Demolition= $this->input->post('');
    $Drainage_Sewage= $this->input->post('');
    $Civil_Engineering= $this->input->post('');
    $Electricity_Electrical= $this->input->post('');
    $Ref_Carpentry_Joinery= $this->input->post('');
    $Decoration_Painting= $this->input->post('');
    $Equipment_Construction= $this->input->post('');
    $Door_Repair_Services= $this->input->post('');
    $Kitchen_Tiling= $this->input->post('');
    $General_Tiling= $this->input->post('');
    $Drywall_Plaster_Repair= $this->input->post('');
    $General_Plastering= $this->input->post('');
    $Furniture_Flat_Assembling= $this->input->post('');
    $Man_Van_Service= $this->input->post('');
    $Taps_Fixture_Installation= $this->input->post('');
    $Mounting_Theatre_Installation= $this->input->post('');
    $Worktops_Splash= $this->input->post('');
    $Grouting_Caulking= $this->input->post('');
    $Wallpapering= $this->input->post('');
    $conservatory_Installation= $this->input->post('');
    $Conservatory_Repair= $this->input->post('');
    $Lift_Services= $this->input->post('');
    $Sea_Defence_Work= $this->input->post('');
    $Sport_Playground= $this->input->post('');
    $Glazing= $this->input->post('');
    $Ground_works= $this->input->post('');
    $Heating_Condi_Gas= $this->input->post('');
    $Furniture_Soft_Furnishings= $this->input->post('');
    $Bridge_Assessment_Repairs= $this->input->post('');
    $Skip_Hire= $this->input->post('');
    $Bird_Netting= $this->input->post('');
    
    }
    
    if(){
    $Employee= $this->input->post('');
    $Subcontractors= $this->input->post('');
    $self_employed= $this->input->post('');
    $business_own_employees= $this->input->post('');
    $Just_around_my_postcode= $this->input->post('');
    $10miles_postcode= $this->input->post('');
    $30miles= $this->input->post('');
    $50miles= $this->input->post('');
    $All_the_country= $this->input->post('');
    $qualification= $this->input->post('');
    $car_insurance= $this->input->post('');
    $mot_certificate= $this->input->post('');
    $proof_of_address= $this->input->post('');
    $valid_dl= $this->input->post('');
    $utility_bill_dated= $this->input->post('');
    $rental_mortgage= $this->input->post('');
    $right_to_work= $this->input->post('');
    $Business_register_cert= $this->input->post('');
    $business_insurance= $this->input->post('');
    $method_statement= $this->input->post('');
    $customer_references= $this->input->post('');
    $two_references_name= $this->input->post('');
    $bank_information= $this->input->post('');
    $material_tools= $this->input->post('');
    $transport= $this->input->post('');
    $other_employment= $this->input->post('');
    $willing_train= $this->input->post('');
    $expected_wear= $this->input->post('');
    $qualify_applying= $this->input->post('');
    $Pick_and_delivery= $this->input->post('');
    $Meals= $this->input->post('');
    $Porters_Marke_tshops= $this->input->post('');
    $decluttering= $this->input->post('');
    $Companionship= $this->input->post('');
    $Support_hospital= $this->input->post('');
        }
    
    if(){
    $Employee= $this->input->post('');
    $Self_employed= $this->input->post('');
    $business_own_employees= $this->input->post('');
    $Just_around_postcode= $this->input->post('');
    $10miles_outside= $this->input->post('');
    $30miles= $this->input->post('');
    $50miles= $this->input->post('');
    $over_the_country= $this->input->post('');
    $Carinsurance= $this->input->post('');
    $Motcertificate= $this->input->post('');
    $Proof_of_address= $this->input->post('');
    $Valid_driving_licence= $this->input->post('');
    $Utility_bill_dated= $this->input->post('');
    $Right_to_work= $this->input->post('');
    $Business_register_certificate= $this->input->post('');
    $Business_insurance= $this->input->post('');
    $Method_statement= $this->input->post('');
    $Valid_driving_licence= $this->input->post('');
    $business_address= $this->input->post('');
    $Bank_Information= $this->input->post('');
    $material_and_tools= $this->input->post('');
    $transport_applicable= $this->input->post('');
    $transport_applicable= $this->input->post('');
    $employment_while_working= $this->input->post('');
    $opportunity_arises= $this->input->post('');
    $wear_our_uniform= $this->input->post('');
    $Cleaning= $this->input->post('');
    $Catering_Vending= $this->input->post('');
    $Security= $this->input->post('');
    $Front_of_House= $this->input->post('');
    $Preventative_Maintenance= $this->input->post('');
    $Post_room_services= $this->input->post('');
    $Switch_board= $this->input->post('');
    $office_removal= $this->input->post('');
    $housekeeping= $this->input->post('');
    $Garden_and_Lawn= $this->input->post('');
    $Fabric_Asset= $this->input->post('');
    $Mail_Room_management= $this->input->post('');
          
          }
    if(){
    $Employee= $this->input->post('');
    $Subcontractors= $this->input->post('');
    $Self_employed= $this->input->post('');
    $business_with_employees= $this->input->post('');
    $Just_around_postcode= $this->input->post('');
    $10miles= $this->input->post('');
    $30miles= $this->input->post('');
    $All_over_country= $this->input->post('');
    $I= $this->input->post('');
    $carry_out_the_job= $this->input->post('');
    $Car_insurance= $this->input->post('');
    $Mot_certificate= $this->input->post('');
    $Proof_of_address= $this->input->post('');
    $Valid_driving_licence= $this->input->post('');
    $Right_to_work= $this->input->post('');
    $registration= $this->input->post('');
    $Business_insurance= $this->input->post('');
    $Method_statement= $this->input->post('');
    $Proof_ID_Passport= $this->input->post('');
    $customer_references_from= $this->input->post('');
    $Two_References= $this->input->post('');
    $Bank_Information_payment= $this->input->post('');
    $provide_your= $this->input->post('');
    $ransport_applicable= $this->input->post('');
    $employment_while_working= $this->input->post('');
    $opp_arises= $this->input->post('');
    $wear_uniform= $this->input->post('');
    $Van_and_Clearance= $this->input->post('');
    $rubbish_removal= $this->input->post('');
    $House_office= $this->input->post('');
    $Trade_waste= $this->input->post('');
    $Business_waste= $this->input->post('');
    $Bin_washing= $this->input->post('');
    $Event_Management= $this->input->post('');
    
    }
    
    if(){
    $Employee= $this->input->post('');
    $Subcontractors= $this->input->post('');
    $Self_employed= $this->input->post('');
    $business_with_employees= $this->input->post('');
    $my_postcode= $this->input->post('');
    $10_miles_outside= $this->input->post('');
    $30_miles= $this->input->post('');
    $50_miles= $this->input->post('');
    $over_country= $this->input->post('');
    $part_our_requirement= $this->input->post('');
    $Qualification_to_carry= $this->input->post('');
    $Carinsurance= $this->input->post('');
    $Motcertificate= $this->input->post('');
    $Proof_of_address= $this->input->post('');
    $Valid_driving= $this->input->post('');
    $Utility_bill= $this->input->post('');
    $council_tax_bill= $this->input->post('');
    $Right_to_work= $this->input->post('');
    $Business_registration= $this->input->post('');
    $Business_insurance= $this->input->post('');
    $Method_statement= $this->input->post('');
    $ID_Passport_Valid= $this->input->post('');
    $references_from= $this->input->post('');
    $last_employers= $this->input->post('');
    $Bank_Information_payment= $this->input->post('');
    $own_material_tools= $this->input->post('');
    $own_transport= $this->input->post('');
    $employment_while_working= $this->input->post('');
    $Sprinkler_System= $this->input->post('');
    $Gazebo_Installation= $this->input->post('');
    $Pergola_and_Arbor= $this->input->post('');
    $Feature_Repair= $this->input->post('');
    $Awning_Installation= $this->input->post('');
    $Fence_and_Gate= $this->input->post('');
    $Gate_Repair= $this->input->post('');
    $Gazebo_Repair= $this->input->post('');
    $Hot_Tub= $this->input->post('');
    $Spa_Repair= $this->input->post('');
    $Lawn_Mower_Repair= $this->input->post('');
    $Lawn_Turfing= $this->input->post('');
    $Play_Equipment= $this->input->post('');
    $Retaining_Well_Construction= $this->input->post('');
    $Security_Fence_Instal= $this->input->post('');
    $Tree_Shrub= $this->input->post('');
    $Ploughing= $this->input->post('');
    $Surgery_Removal= $this->input->post('');
    $Well_System= $this->input->post('');
    $Soil_Irrigation= $this->input->post('');
    $Xeriscaping= $this->input->post('');
    $Deck_Installation= $this->input->post('');
    $Wildlife= $this->input->post('');
    $Fertilizing= $this->input->post('');
    $Garden_Clearance= $this->input->post('');
    $System_Repair= $this->input->post('');
    $Grass_Cutting= $this->input->post('');
    $Lighting_Power= $this->input->post('');
    $Equipment= $this->input->post('');
    $Greenhouse= $this->input->post('');
    $Landscape_Design= $this->input->post('');
    $Cover_Repair= $this->input->post('');
    $Installation= $this->input->post('');
    $Gardening= $this->input->post('');
    $Landscaping= $this->input->post('');
    $Lawn_Care= $this->input->post('');
    $Tub_and_Spa= $this->input->post('');
    $Artificial_Turf= $this->input->post('');
    $Barbecue_Installation= $this->input->post('');
    $Garden_Shed= $this->input->post('');
    $Patio_Service= $this->input->post('');
    $Artificial_Turf_Repair= $this->input->post('');
    $Boulde_Placement= $this->input->post('');
    $Garden_Maintenance_Services= $this->input->post('');
    */
}
