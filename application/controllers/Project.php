<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller {
	 function __construct() { 
        parent::__construct(); 
        error_reporting(0);
        
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->load->helper('push_notifications');
        $this->load->model('api_model','api');

        $this->load->model('User_skill_model','user_skill');
        $this->load->model('Project_model','project');
        $this->load->model('Project_files_model','project_files');
        // Load pagination library 
        $this->load->library('ajax_pagination'); 
         
        // Load post model 
        $this->load->model('post'); 
         
        // Per page limit 
        $this->perPage = 10; 

        $this->data['theme']     = '';
        if(empty($this->session->userdata('id'))){
            redirect(base_url());
        }
        if($this->session->userdata('you_are_appling_as') == C_YOUARE_EMPLOYEE){
            $this->data['theme']     = 'employee';
        } else if($this->session->userdata('you_are_appling_as') == C_YOUARE_SOLETRADER){
            $this->data['theme']     = 'employee';
        } else if($this->session->userdata('you_are_appling_as') == C_YOUARE_ORGANIZATION){
            $this->data['theme']     = 'organization';
        } else if($this->session->userdata('you_are_appling_as') == C_YOUARE_PARTNER){
            $this->data['theme']     = 'partner';
        } else if($this->session->userdata('you_are_appling_as') == C_YOUARE_SELF_EMPLOYED){
            $this->data['theme']     = 'employee';
        }
        $this->data['module']    = 'project';
        
    } 
     
    public function index(){ 
    	if(empty($this->session->userdata('id'))){
          redirect(base_url());
        }

        $conditions = array();        
        $conditions['returnType'] = 'count';
        $totalRec = $this->project->getRows($conditions);

        // Pagination configuration
        $config['target'] = '#dataList';
        $config['base_url'] = base_url($this->data['theme'].'/Project/indexAjaxPagination');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library
        $this->ajax_pagination->initialize($config);

        // Get records
        $conditions = array(
            'limit' => $this->perPage
        );
        $sort_by = $this->input->post('sort_by');
        if(!empty($sort_by)){
            if($sort_by == 1){
                $conditions['order_by'] = "created_at DESC";
            }else if($sort_by == 2){
                $conditions['order_by'] = "created_at";
            }
            
        }else{
            $conditions['order_by'] = "created_at DESC";
        }
        $this->data['project_list'] = $this->project->getRows($conditions);
        $skills = $this->user_skill->getSkillList();
        $skill_list = array();
        foreach ($skills as $skill) {
            $skill_list[$skill['id']] = $skill['name'];
        }
        $this->data['skill_list'] = $skill_list;
       	
        $this->data['page'] = 'index';
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['module'].'/template');
      
    } 

    public function post_project(){ 
        if(empty($this->session->userdata('id'))){
          redirect(base_url());
        }

        $this->data['skill_list'] = $this->user_skill->getSkillList();
        $user_currency = get_user_currency();
        $this->data['user_currency'] = $user_currency;

        if ($this->input->post('form_submit')) {
            $config["upload_path"] = './uploads/project/';
            $config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $file_path = array();

            if ($_FILES["files"]["name"] != '') {
                if (!file_exists($config["upload_path"])) {
                    mkdir($config["upload_path"], 0777, true);
                }
                for ($count = 0; $count < count($_FILES["files"]["name"]); $count++) {
                    $_FILES["file"]["name"] = 'full_' . time() . $_FILES["files"]["name"][$count];
                    $_FILES["file"]["type"] = $_FILES["files"]["type"][$count];
                    $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$count];
                    $_FILES["file"]["error"] = $_FILES["files"]["error"][$count];
                    $_FILES["file"]["size"] = $_FILES["files"]["size"][$count];
                    if ($this->upload->do_upload('file')) {
                        $data = $this->upload->data();
                        $file_path[] = 'uploads/project/' . $data["file_name"];
                    }
                }
            }

            $inputs = array();
            $inputs['user_id'] = $this->session->userdata('id');
            $inputs['name'] = $this->input->post('project_name');
            $inputs['description'] = $this->input->post('description');
            $inputs['skills'] = implode(',', $this->input->post('skills'));
            $inputs['currency_code'] = $user_currency['user_currency_code'];      
            $inputs['currency_sign'] = $user_currency['user_currency_sign'];            
            $inputs['price_from'] = $this->input->post('price_from');
            $inputs['price_to'] = $this->input->post('price_to');
            $inputs['status'] = 1; //pending
            $inputs['created_at'] = date('Y-m-d H:i:s');

            $project_id = $this->project->insertData($inputs);
            if(!empty($file_path)){
                foreach ($file_path as $path) {
                    $project_files_data = array();
                    $project_files_data['project_id'] = $project_id;
                    $project_files_data['file_path'] = $path;
                    $this->project_files->insertData($project_files_data);
                }
            }
            redirect(base_url() . $this->data['theme'] . "-project");
        }
        
        $this->data['page'] = 'post_project';
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['module'].'/template');
      
    } 
     
    public function detail(){ 
        if(empty($this->session->userdata('id'))){
          redirect(base_url());
        }
        // $id=$this->uri->segment('2');
        
        $this->data['page'] = 'detail';
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['module'].'/template');
      
    }

    public function proposals(){ 
        if(empty($this->session->userdata('id'))){
          redirect(base_url());
        }
        // $id=$this->uri->segment('2');
        
        $this->data['page'] = 'proposals';
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['module'].'/template');
      
    }  
    

    
}