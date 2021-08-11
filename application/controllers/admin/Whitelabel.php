<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * @author Leo
 * @description Chat history track
 * @created 
*/

class Whitelabel extends CI_Controller {

    public $data;

    public function __construct() {

        parent::__construct();
        $this->load->model('admin_model','admin');
        $this->load->model('whitelabel_model','whitelabel');
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
        $this->data['model'] = 'whitelabel';
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
            // $from_date = $this->input->post('from');
            // $to_date = $this->input->post('to');
            $params['name'] = $name;
        }
        $whitelabels = $this->whitelabel->find_whitelabels($params);
        // print_r($whitelabels); exit;
    
        $this->data['params'] = $params;
        $this->data['lists'] = $whitelabels;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function create()
    {   
        $directoryName = 'assets/wll_logos/temp/';
        if ($this->input->post('form_submit')) {
                $data['name'] = $this->input->post('name');
                $data['brandname'] = $this->input->post('brandname');
                $data['country'] = $this->input->post('country');
                $data['logofile'] = $this->input->post('logofile');
                if(!empty($data['logofile'])&&file_exists($directoryName.$data['logofile'])){
                    rename($directoryName.$data['logofile'], 'assets/wll_logos/'.$data['logofile']);
                }
                $data['favicon'] = $this->input->post('favicon');
                if(!empty($data['favicon'])&&file_exists($directoryName.$data['favicon'])){
                    rename($directoryName.$data['favicon'], 'assets/wll_logos/'.$data['favicon']);
                }
                $data['color'] = $this->input->post('color');
                $data['hostaddress'] = $this->input->post('hostaddress');
                $data['status'] = $this->input->post('status');
                if(!isset($data['status']) || $data['status'] == null){
                    $data['status'] = 0;
                }
                
                if ($this->whitelabel->addWhitelabel($data,$this->input->post('category'),$this->input->post('subcategory'))) {
                    $this->session->set_flashdata('success_message','new whitelabel created successfully.');
                    redirect(base_url() . 'admin/whitelabel');
                }else{
                    $this->session->set_flashdata('error_message','Something wrong, Please try fill again');
                }
        }

        $categories = $this->admin->categories_list();
        $c_list = array();
        foreach($categories as $category){
            $c_list[$category['id']] = $category['category_name'];
        }

        $subcategories = $this->admin->subcategories_list();
        $s_list = array();
        foreach($subcategories as $subcategory){
            $s_list[$subcategory['category']][$subcategory['id']] = $subcategory['subcategory_name'];
            $s_list[$subcategory['category']]['category_name'] = $subcategory['category_name'];
        }

        $this->data['c_list'] = $c_list;
        $this->data['s_list'] = $s_list;
        $this->data['page'] = 'create';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function change_subcategory(){

        $selected_categories = $this->input->post('categories');
       
        $selected_subcategories = $this->input->post('subcategories');

        $subcategories = $this->admin->subcategories_list();
        $s_list = array();
        foreach($subcategories as $subcategory){
            if(!in_array($subcategory['category'], $selected_categories)){
                $s_list[$subcategory['category']][$subcategory['id']] = $subcategory['subcategory_name'];
                $s_list[$subcategory['category']]['category_name'] = $subcategory['category_name'];
            }
        }

        $options = '';
        foreach($s_list as $s_c_list){
            $options .= '<optgroup  label="'.$s_c_list['category_name'].'">';
            foreach($s_c_list as $id => $name){
                if($id != 'category_name'){
                    $selected = (!empty($selected_subcategories) && in_array($id, $selected_subcategories))?'selected':'';
                    $options .= '<option value="'.$id.'" '.$selected.' >'.$name.'</option>';
                }
            }
            $options .= '</optgroup>';
        }
        echo $options;
    }

    public function edit($id)
    {    
        $directoryName = 'assets/wll_logos/temp/';
        $whitelabel = $this->whitelabel->getWhitelabelInfo($id);
        if(!isset($id)||empty($whitelabel)){
            $this->session->set_flashdata('error_message','Invalid whitelabel, Please try again');
            redirect(base_url() . 'admin/whitelabel');
        }
        if ($this->input->post('form_submit')) {
            $data['name'] = $this->input->post('name');
            $data['brandname'] = $this->input->post('brandname');
            $data['country'] = $this->input->post('country');
            $data['logofile'] = $this->input->post('logofile');
            if(!empty($data['logofile'])&&file_exists($directoryName.$data['logofile'])){
                rename($directoryName.$data['logofile'], 'assets/wll_logos/'.$data['logofile']);
            }
            $data['favicon'] = $this->input->post('favicon');
            if(!empty($data['favicon'])&&file_exists($directoryName.$data['favicon'])){
                rename($directoryName.$data['favicon'], 'assets/wll_logos/'.$data['favicon']);
            }
            $data['color'] = $this->input->post('color');
            $data['hostaddress'] = $this->input->post('hostaddress');
            $data['status'] = $this->input->post('status');
            if(!isset($data['status']) || $data['status'] == null){
                $data['status'] = 0;
            }
            
            if ($this->whitelabel->updateWhitelabel($id, $data, $this->input->post('category'),$this->input->post('subcategory'))) {
                $this->session->set_flashdata('success_message','new whitelabel created successfully.');
                redirect(base_url() . 'admin/whitelabel');
            }else{
                $this->session->set_flashdata('error_message','Something wrong, Please try again');
            }
        }

        $whitelabel_categories = $whitelabel['categories'];
        $selected_categories = array();
        $selected_subcategories = array();

        foreach($whitelabel_categories as $category){
            if(!empty($category['categ_id'])){
                $selected_categories[] = $category['categ_id'];
            }elseif(!empty($category['subcate_id'])){
                $selected_subcategories[] = $category['subcate_id'];
            }
        }
        $categories = $this->admin->categories_list();
        $subcategories = $this->admin->subcategories_list();
        $s_list = array();

        $category_options = '';
        foreach($categories as $cate){
            $selected = (!empty($selected_categories) && in_array($cate['id'], $selected_categories))?'selected':'';
            $category_options .= '<option value="'.$cate['id'].'" '.$selected.' >'.$cate['category_name'].'</option>';
        }

        foreach($subcategories as $subcategory){
            if(!in_array($subcategory['category'], $selected_categories)){
                $s_list[$subcategory['category']][$subcategory['id']] = $subcategory['subcategory_name'];
                $s_list[$subcategory['category']]['category_name'] = $subcategory['category_name'];
            }
        }

        $subcate_options = '';
        foreach($s_list as $s_c_list){
            $subcate_options .= '<optgroup  label="'.$s_c_list['category_name'].'">';
            foreach($s_c_list as $id => $name){
                if($id != 'category_name'){
                    $selected = (!empty($selected_subcategories) && in_array($id, $selected_subcategories))?'selected':'';
                    $subcate_options .= '<option value="'.$id.'" '.$selected.' >'.$name.'</option>';
                }
            }
            $subcate_options .= '</optgroup>';
        }

        $this->data['page'] = 'edit';
        $this->data['category_options'] = $category_options;
        $this->data['subcate_options'] = $subcate_options;
        $this->data['detail'] = $whitelabel;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function upload_logofile(){
        $directoryName = 'assets/wll_logos/temp/';
        if (!is_dir($directoryName))
        {
            mkdir($directoryName, 0755);
        }
        
        if (isset($_FILES) && isset($_FILES['logofile']['name']) && !empty($_FILES['logofile']['name'])) {
            $uploaded_file_name = $_FILES['logofile']['name'];
            $uploaded_file_name_arr = explode('.', $uploaded_file_name);
            $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
            $this->load->library('common');
            $upload_sts = $this->common->global_file_upload($directoryName, 'logofile', time() . $filename);
            
            if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                $uploaded_file_name = $upload_sts['data']['file_name'];
                $data = [
                    "sec"=>1,
                    "name"=>$uploaded_file_name,
                    "message"=>'file upload seccess!'
                ];
                echo json_encode($data);
            }else{
                $data = [
                    "sec"=>0,
                    "name"=>0,
                    "message"=>'file save failed!'
                ];
                echo json_encode($data);
            }
        }else{
            $data = [
                "sec"=>0,
                "name"=>0,
                "message"=>'file upload failed!'
            ];
            echo json_encode($data);
        }
    }

    public function delete_whitelabel()
    {   
        $id = $this->input->post('whitelabel_id');
        if (!empty($id)) {
            $this->whitelabel->delete_row($id);
            $this->session->set_flashdata('success_message','whitelabel deleted successfully');
            echo json_encode(array('sec'=>1));
        }else{
            $this->session->set_flashdata('error_message','Something wrong, Please try again');
            echo json_encode(array('sec'=>0));
        }
    }


}
