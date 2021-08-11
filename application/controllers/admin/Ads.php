<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * @author Leo
 * @description Chat history track
 * @created 
*/

class Ads extends CI_Controller {

    public $data;

    public function __construct() {

        parent::__construct();
        $this->load->model('admin_model','admin');
        $this->load->model('Request_ads_model','request_ads');
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
        $this->data['model'] = 'ads';
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

        $result = $this->request_ads->getRequsetAdsListForCampain();
    
        $this->data['params'] = $params;
        $this->data['list'] = $result;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function detail($id)
    { 
        if(!isset($id)){
            $this->session->set_flashdata('error_message','Invalid Ads, Please try again');
            redirect(base_url() . 'admin/ads');
        }
        $conditions = array();            
        $conditions['where']['ra.id'] = $id;
        $ads_ary = $this->request_ads->getRequsetAdsList($conditions);

        $this->data['page'] = 'detail';
        $this->data['ads_ary'] = $ads_ary[0];
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function update_status(){
        $id = $this->input->post('ads_id');
        if (!empty($id)) {
            $status = $this->input->post('ads_status');
            $reason = $this->input->post('ads_reason');
            $data = array();
            $data['id'] = $id;
            $data['status'] = $status;
            $data['reason'] = $reason;
            $ret = $this->request_ads->updateAds($data); 
            $this->session->set_flashdata('success_message','Ads Update successfully');
            echo json_encode(array('sec'=>1));
        }else{
            $this->session->set_flashdata('error_message','Something wrong, Please try again');
            echo json_encode(array('sec'=>0));
        }
    }

    public function delete_ads()
    {   
        $id = $this->input->post('ads_id');
        if (!empty($id)) {
            $this->request_ads->deleteAds($id);
            $this->session->set_flashdata('success_message','Ads deleted successfully');
            echo json_encode(array('sec'=>1));
        }else{
            $this->session->set_flashdata('error_message','Something wrong, Please try again');
            echo json_encode(array('sec'=>0));
        }
    }

}
