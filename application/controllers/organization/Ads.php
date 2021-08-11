<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ads extends CI_Controller {
     function __construct() { 
        parent::__construct(); 
        error_reporting(0);
        
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');

        $this->load->model('Request_ads_model','request_ads');
        // $this->load->model('Categories_model', 'CategoryModel');
        // $this->load->model("SubcategoryModel");
        
        $this->data['theme']     = 'organization';
        $this->data['module']    = 'ads';        
    } 
     
    public function index(){ 
        if(empty($this->session->userdata('id'))){
            redirect(base_url());
        }

        if($this->session->userdata('you_are_appling_as') != C_YOUARE_ORGANIZATION){
            redirect(base_url());
        }

        $conditions = array();
        $conditions['where']['ra.user_id'] = $this->session->userdata('id');
        $this->data['ads_list'] = $this->request_ads->getRequsetAdsList($conditions); 
        
        // $this->data['category'] = $this->CategoryModel->get_category();
        $user_id = $this->session->userdata('id');
        $this->data['category'] = $this->request_ads->getCategoriesByUserId($user_id);
        $this->data['selected_category_id'] = 0;

        // $params = ['category' => $this->data['category'][0]['id'], 'status' => 1];
        // $this->data['subcategory'] = $this->SubcategoryModel->List($params);
        $category_id = $this->data['category'][0]['id'];
        $this->data['subcategory'] = $this->request_ads->getSubCategoriesByUserId($user_id, $category_id);

        $this->data['selected_subcategory_id'] = 0;

        $this->data['page'] = 'index';
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');      
    } 

    public function change_subcategory(){

        $user_id = $this->session->userdata('id');
        $category_id = $this->input->post('category_id');
        $selected_subcategory = $this->input->post('selected_subcategory');
        // echo $selected_subcategory;exit;
        $params = ['category' => $category_id, 'status' => 1];
        // $subcategories = $this->SubcategoryModel->List($params);
        $subcategories = $this->request_ads->getSubCategoriesByUserId($user_id, $category_id);
        
        $options = '';
        foreach($subcategories as $subcategory){
            $selected = (!empty($selected_subcategory) && $selected_subcategory == $subcategory['id'] )?'selected':'';
            $options .= '<option value="'.$subcategory['id'].'" '.$selected.' >'.$subcategory['subcategory_name'].'</option>';
        } 
        echo $options;
    }

    public function add_ads(){
        if(empty($this->session->userdata('id'))){
           echo json_encode(['result'=>false]);   exit;
        }

        if($this->session->userdata('you_are_appling_as') != C_YOUARE_ORGANIZATION){
            echo json_encode(['result'=>false]);   exit;
        }

        $input_data = $this->input->post();
        $edit_ads_id = $this->input->post('ads_id');

        if(empty($edit_ads_id))
        {
            $data = array();
            $data['user_id'] = $this->session->userdata('id');
            $data['subcategory_id'] = $this->input->post('subcategory_id');
            $data['description'] = $this->input->post('description');
            $data['status'] = ADS_PENDING;

            $conditions['where']['ra.user_id'] = $this->session->userdata('id');
            $conditions['where']['ra.subcategory_id'] = $data['subcategory_id'];
            // $conditions['where']['ra.status'] = $data['status'];
            $ads_ary = $this->request_ads->getRequsetAdsList($conditions);
            if(empty($ads_ary)){
                $ret = $this->request_ads->insertAds($data);
            } else {
                $ret = false; 
            }        
            
        } else{
            $data = array();
            $data['id'] = $edit_ads_id;
            $data['subcategory_id'] = $this->input->post('subcategory_id');
            $data['description'] = $this->input->post('description');
            $ret = $this->request_ads->updateAds($data);  
        }
        echo json_encode(['result'=>$ret]);        
    }

    public function ajax_ads_info()
    {
        $input_data = $this->input->post();
        $ads_id = $input_data['ads_id'];
        if(!empty($ads_id))
        {
            $conditions = [];            
            $conditions['where']['ra.id'] = $ads_id;
            $data = $this->request_ads->getRequsetAdsList($conditions); 
            $result = ['result'=>'ok', 'ads'=>$data[0] ];
        }
        else
        {
            $result = ['result'=>'failed', 'ads'=>[] ];
        }
        print(json_encode($result)); exit;
    }

    public function ajax_remove_ads()
    {
        $ads_id = $this->input->post('ads_id');
        $conditions['where']['ra.id'] = $ads_id;
        $data = $this->request_ads->getRequsetAdsList($conditions); 
        
        if(!is_array($data)||count($data)==0)
        {
            $result = ['result'=>'failed', 'data'=>['msg'=>'Ads could not found.'] ];
        }
        else
        {
            $this->request_ads->deleteAds($ads_id);
            $result = ['result'=>'ok', 'data'=>[] ];
        }
        print(json_encode($result)); exit;
    }
    
}