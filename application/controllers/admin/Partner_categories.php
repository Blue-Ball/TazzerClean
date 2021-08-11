<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * @author Leo
 * @description Chat history track
 * @created 
*/

class Partner_categories extends CI_Controller {

    public $data;

    public function __construct() {

        parent::__construct();
        $this->load->model('admin_model','admin');
        $this->load->model('Partner_category_model','partner_category');
        $this->load->model('admin_model', 'admin');
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
        $this->data['model'] = 'partner_categories';
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->data['user_role']=$this->session->userdata('role');
    }

    public function index()
    {
        $this->data['page'] = 'index';
        $params = array();
        if ($this->input->post('form_submit')) {
            extract($_POST);
            $params['name'] = $name;
        }

        $result = $this->partner_category->find_partner_categories($params);
    
        $this->data['params'] = $params;
        $this->data['lists'] = $result;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function create()
    {           
        if ($this->input->post('form_submit')) {
            $data = array();
            $data['user_id'] = $this->input->post('user_id');
            $data['subcategory_id'] = $this->input->post('subcategory');
            $data['status'] = $this->input->post('status');

            $result = $this->partner_category->insert_partner_category($data);
            if($result == 1){
                $this->session->set_flashdata('success_message','New Partner Category created successfully.');
                redirect(base_url() . 'admin/partner_categories');
            }else if($result == 2){
                $this->session->set_flashdata('error_message','Already exist same category');
            }else{
                $this->session->set_flashdata('error_message','Something wrong, Please try fill again');
            }
        }

        $categories = $this->admin->categories_list();
        $c_list = array();
        $c_list['0'] = "Select Category";
        foreach($categories as $category){
            $c_list[$category['id']] = $category['category_name'];
        }

        $partners = $this->partner_category->find_partner();
        $partner_list = array();
        foreach($partners as $partner){
            $partner_list[$partner['id']] = $partner['name'].'('.$partner['email'].')';
        }

        $this->data['c_list'] = $c_list;
        $this->data['partner_list'] = $partner_list;
        $this->data['page'] = 'create';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function change_subcategory(){

        $category_id = $this->input->post('category_id');
        $subcategories = $this->admin->search_subcategory($category_id);
        $selected_subcategory = $this->input->post('selected_subcategory');
        $options = '';
        foreach($subcategories as $subcategory){
            $selected = (!empty($selected_subcategory) && $selected_subcategory == $subcategory['id'] )?'selected':'';
            $options .= '<option value="'.$subcategory['id'].'" '.$selected.' >'.$subcategory['subcategory_name'].'</option>';
        } 
        echo $options;
    }

    public function edit($id)
    { 
        if(!isset($id)){
            $this->session->set_flashdata('error_message','Invalid Partner Category, Please try again');
            redirect(base_url() . 'admin/partner_categories');
        }
        
        if ($this->input->post('form_submit')) {
            $data = array();
            $data['id'] = $id;
            $data['user_id'] = $this->input->post('user_id');
            $data['subcategory_id'] = $this->input->post('subcategory');
            $data['status'] = $this->input->post('status');
            $result = $this->partner_category->update_partner_category($data);

            if($result == 1){
                $this->session->set_flashdata('success_message','Partner Category updated successfully.');
                redirect(base_url() . 'admin/partner_categories');
            }else if($result == 2){
                $this->session->set_flashdata('error_message','Already exist same category');
            }else{
                $this->session->set_flashdata('error_message','Something wrong, Please try fill again');
            }
        }

        $where = "pc.id = ".$id;
        $selected_partner_category = $this->partner_category->find_partner_categories($where);
        
        $this->data['selected_user_id'] = $selected_partner_category[0]['user_id'];
        $this->data['selected_category_id'] = $selected_partner_category[0]['category_id'];
        $this->data['selected_subcategory_id'] = $selected_partner_category[0]['subcategory_id'];

        $categories = $this->admin->categories_list();
        $c_list = array();
        $c_list['0'] = "Select Category";
        foreach($categories as $category){
            $c_list[$category['id']] = $category['category_name'];
        }

        $subcategories = $this->admin->search_subcategory($this->data['selected_category_id']);
        $sc_list = array();
        foreach($subcategories as $subcategory){
            $sc_list[$subcategory['id']] = $subcategory['subcategory_name'];
        }

        $partners = $this->partner_category->find_partner();
        $partner_list = array();
        foreach($partners as $partner){
            $partner_list[$partner['id']] = $partner['name'].'('.$partner['email'].')';
        }


        $this->data['page'] = 'edit';
        $this->data['c_list'] = $c_list;
        $this->data['sc_list'] = $sc_list;
        $this->data['partner_list'] = $partner_list;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function delete_partner_category()
    {   
        $id = $this->input->post('partner_category_id');
        if (!empty($id)) {
            $this->partner_category->delete_row($id);
            $this->session->set_flashdata('success_message','Partner Category deleted successfully');
            echo json_encode(array('sec'=>1));
        }else{
            $this->session->set_flashdata('error_message','Something wrong, Please try again');
            echo json_encode(array('sec'=>0));
        }
    }

}
