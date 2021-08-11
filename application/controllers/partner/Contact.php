<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

	public $data;

   public function __construct() {

        parent::__construct();
        error_reporting(0);
        $this->data['theme']     = 'partner';
        $this->data['module']    = 'contact';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
        $this->load->model('home_model','home');
		$this->load->model('User_model','user_model');

         $this->user_latitude=(!empty($this->session->userdata('user_latitude')))?$this->session->userdata('user_latitude'):'';
         $this->user_longitude=(!empty($this->session->userdata('user_longitude')))?$this->session->userdata('user_longitude'):'';

         $this->currency= settings('currency');

         $this->load->library('ajax_pagination'); 
         $this->perPage = 12;
         
    }

	
	public function index()
	{
		
		 $this->data['page'] = 'index';
	     $this->data['category']=$this->home->get_category();
	     $this->data['services']=$this->home->get_service();
         $this->load->vars($this->data);
		 $this->load->view($this->data['theme'].'/template');
	}
	
	public function contact_user()
	{
		if (empty($this->session->userdata('id'))) {
		    redirect(base_url());
		}
		if($this->session->userdata('you_are_appling_as') != C_YOUARE_PARTNER){
		    redirect(base_url());
		}
		$this->data['users'] = $this->user_model->get_myclient_list();
		$this->data['toFlag'] = 'partner_to_user';
		$this->data['page'] = 'contact';
        $this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template');
	}
	public function contact()
	{
		if (empty($this->session->userdata('id'))) {
		    redirect(base_url());
		}
		if($this->session->userdata('you_are_appling_as') != C_YOUARE_PARTNER){
		    redirect(base_url());
		}
		$this->data['toFlag'] = 'partner_to_admin';
		$this->data['page'] = 'contact';
        $this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template');
    }

	
	public function insert_contact_admin()
	{
		$admin = $this->admin_model->admin_details(1);
		$this->insert_contact($admin['email']);
	} 

	public function insert_contact_user()
	{
		$user_id = $this->input->post('userId');

		$this->form_validation->set_rules('userId', $this->lang->line('userId'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
			echo 0;
			return;
		}
		else
		{
			$user = $this->User_model->get_user($user_id);
			$this->insert_contact($user['email']);
		}
	} 

    public function insert_contact($to_email = '')
	{
		
		$query = $this->db->query("select * from system_settings WHERE status = 1");
		$result = $query->result_array();
		$mail_config='';
		$email_address='';
		$smtp_email_address='';
		$tomail='';
		foreach ($result as $res) {
			if($res['key'] == 'mail_config'){
				$mail_config = $res['value'];
			} 
			if($res['key'] == 'email_address'){
				$email_address = $res['value'];
			} 

			if($res['key'] == 'smtp_email_address'){
				$smtp_email_address = $res['value'];
			}

		}
		
		if($mail_config=='smtp')
		{
			$tomail=$smtp_email_address;
		}
		else
		{
			$tomail=$email_address;
		}
		if( !empty( $to_email) )  $tomail = $to_email;

		 $table_datas['name']=$this->input->post('name');
		 $table_datas['email']=$this->input->post('email');
		 $table_datas['message']=$this->input->post('message');
		 $result=$this->db->insert('contact_form_details', $table_datas);
		 if ($result) {
            $this->data['user'] = $this->session->userdata();
            
			//$this->data['contact_det'] = //$this->db->where('id', $id)->from('contact_reply')->get()->row_array();
            $body = $this->load->view('user/email/contact_form', $table_datas, true);
            $phpmail_config = settingValue('mail_config');
            if (isset($phpmail_config) && !empty($phpmail_config)) {
                if ($phpmail_config == "phpmail") {
                    $from_email = settingValue('email_address');
                } else {
                    $from_email = settingValue('smtp_email_address');
                }
            }
            $this->load->library('email');
            if (!empty($from_email) && isset($from_email)) {
                $mail = $this->email
                        ->from($from_email)
                        ->to($tomail)
                        ->subject('User Contact Form Details')
                        ->message($body)
                        ->send();
            }
			
			//print_r($mail);exit;
            $message = 'Mail Sent Successfully';
            $this->session->set_flashdata('success_message', $message);
			echo 1;
        } else {
            $message = 'Sorry, something went wrong';
            $this->session->set_flashdata('error_message', $message);
			echo 0;
        }
		 
	} 
    
}
