<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {
     function __construct() { 
        parent::__construct(); 
        error_reporting(0);
        
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');

        $this->load->model('Organization_model','organization');
        $this->load->model('Staff_model','staff');
        $this->load->model('user_login_model', 'user_login');
        $this->load->model('User_model','user_model');
        
        $this->data['theme']     = 'organization';
        $this->data['module']    = 'staff';        
    } 
     
    public function index(){ 
        if(empty($this->session->userdata('id'))){
            redirect(base_url());
        }

        if($this->session->userdata('you_are_appling_as') != C_YOUARE_ORGANIZATION){
            redirect(base_url());
        }

        $organization = $this->organization->getOrganizationByUserId($this->session->userdata('id')); 
        if($organization){
            $conditions = [];            
            $conditions['where']['staffs.organ_id'] = $organization['id'];
            $this->data['staffs'] = $this->staff->get_staffs($conditions); 
            $this->data['organization_id'] = $organization['id'];
        }  
        $this->data['page'] = 'index';
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');      
    } 

    public function update_staff_status(){
        $data = [];
        $data['status'] = $this->input->post('status');
        $data['staff_id'] = $this->input->post('staff_id');
        $data['user_id'] = $this->input->post('user_id');
        $result = $this->staff->update_status($data); 
        if($result){
            echo json_encode(['success' => true, 'result'=>$result]);
        }else{
            echo json_encode(['success' => false, 'result'=>$result]);
        }        
    }

    public function add_staff(){
        $input_data = $this->input->post();

        $edit_staff_id = $this->input->post('staff_id');
        $edit_user_id = $this->input->post('user_id');
        $organization = $this->organization->getOrganizationByUserId($this->session->userdata('id'));

        if(empty($edit_staff_id) && empty($edit_user_id))
        {
            $username = strlen($input_data['name']);
            $input_data['share_code'] = $this->user_login->ShareCode(6, $username);
            $input_data['currency_code'] = settings('currency');
            $input_data['password'] = md5($input_data['password']);
            $input_data['status'] = 1;
    
            $organ_id = $organization['id']; 
    
            $ret = $this->staff->insertStaff($input_data,$organ_id);  
        } else{
            $username = strlen($input_data['name']);
            $input_data['share_code'] = $this->user_login->ShareCode(6, $username);
            $input_data['currency_code'] = settings('currency');
            if(!empty($input_data['password']))
            {
                $input_data['password'] = md5($input_data['password']);
            }
            $input_data['status'] = 1;
    
            $organization = $this->organization->getOrganizationByUserId($this->session->userdata('id'));
            $organ_id = $organization['id']; 
    
            $this->staff->updateStaff($input_data,$organ_id);  
            $ret = 'ok';
        }
        echo json_encode(['result'=>$ret]);        
    }

    public function ajax_remove_staff()
    {
        $staff_id = $this->input->post('staff_id');
        $conditions['where']['staffs.id'] = $staff_id;
        $data = $this->staff->get_staffs($conditions); 
        
        if(!is_array($data)||count($data)==0)
        {
            $result = ['result'=>'failed', 'data'=>['msg'=>'Staff could not found.'] ];
        }
        else
        {
            $this->staff->delete_staff($staff_id);
            $user_id = $data[0]['user_id'];
            $this->user_model->delete_user($user_id);
            $result = ['result'=>'ok', 'data'=>[] ];
        }
        print(json_encode($result)); exit;
    }

    public function ajax_staff_info()
    {
        $input_data = $this->input->post();
        $staff_id = $input_data['staff_id'];
        if(!empty($staff_id))
        {
            $conditions = [];            
            $conditions['where']['staffs.id'] = $staff_id;
            $data = $this->staff->get_staffs($conditions); 
            $result = ['result'=>'ok', 'data'=>$data[0] ];
        }
        else
        {
            $result = ['result'=>'failed', 'data'=>[] ];
        }
        print(json_encode($result)); exit;
    }
}