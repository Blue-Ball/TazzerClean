<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

  public $data;

  public function __construct()
  {

    parent::__construct();
    error_reporting(0);
    $this->data['theme'] = 'user';
    $this->data['module'] = 'login';
    $this->data['page'] = '';
    $this->data['base_url'] = base_url();
    $this->load->helper('user_timezone_helper');
    $this->load->model('user_login_model', 'user_login');
    $this->load->model('admin_model', 'admin');
    $this->load->model('templates_model');
    $this->load->library('session');

  }

  public function index()
  {
    $this->data['page'] = 'index';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function registration()
  {

    $this->data['page'] = 'add_service';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }
  public function login()
  {
    $mobile = $this->input->post('mobile');
    $countryCode = $this->input->post('country_code');
    $is_available_mobile = $this->user_login->check_mobile_no($mobile);
    $is_available_mobileuser = $this->user_login->check_user_mobileno($mobile);
    $user_details = $this->user_login->get_user_details($mobile);
    $provider_details = $this->user_login->get_provider_details($mobile);
    if ($is_available_mobile == 1 && $is_available_mobileuser == 0)
    {
      $session_data = array(
        'id' => $provider_details['id'],
        'chat_token' => $provider_details['token'],
        'name' => $this->input->post('userName') ,
        'email' => $this->input->post('userEmail') ,
        'mobileno' => $mobile,
        'usertype' => 'provider'
      );
      $this->session->set_userdata($session_data);
      echo 1;

    }
    elseif ($is_available_mobile == 0 && $is_available_mobileuser == 1)
    {
      $session_data = array(
        'id' => $user_details['id'],
        'chat_token' => $user_details['token'],
        'name' => $this->input->post('userName') ,
        'email' => $this->input->post('userEmail') ,
        'mobileno' => $mobile,
        'usertype' => 'user'
      );

      $this->session->set_userdata($session_data);
      echo 2;
    }
    else
    {
      $this->session->set_flashdata('error_message', 'Wrong login credentials.');
      echo 3;
    }

  }
  public function go_to_vendor()
  {
    $session_data_echo = $this->session->userdata($session_data);

    $email = $session_data_echo['email'];

    $this->db->select('*');
    $this->db->from('providers');
    $this->db->where('email', $email);
    $result = $this->db->get()->result();

    $row = $result['0'];

    $id = $row->id;
    $token = $row->token;
    $userName = $row->name;
    $userEmail = $row->email;
    $mobileno = $row->mobileno;

    $session_data = array(
      'id' => $id,
      'chat_token' => $token,
      'name' => $userName,
      'email' => $userEmail,
      'mobileno' => $mobileno,
      'usertype' => 'provider'
    );
    $this->session->unset_userdata($session_data);
    $this->session->set_userdata($session_data);
    echo 1;

    redirect(base_url() . "provider-dashboard");
  }
  public function go_to_user()
  {
    $session_data_echo = $this->session->userdata($session_data);

    $email = $session_data_echo['email'];

    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('email', $email);
    $result = $this->db->get()->result();

    $row = $result['0'];

    $id = $row->id;
    $token = $row->token;
    $userName = $row->name;
    $userEmail = $row->email;
    $mobileno = $row->mobileno;

    $session_data = array(
      'id' => $id,
      'chat_token' => $token,
      'name' => $userName,
      'email' => $userEmail,
      'mobileno' => $mobileno,
      'usertype' => 'user'
    );
    $this->session->unset_userdata($session_data);
    $this->session->set_userdata($session_data);
    echo 1;

    redirect(base_url() . "user-dashboard");
  }
  public function insert_user()
  {
    $mobile = $this->input->post('mobile');
    $email = $this->input->post('email');
    $name = $this->input->post('username');

    $ShareCode = $this->user_login->ShareCode(6);

    // print_r($ShareCode);exit;
    

    $user_details['mobileno'] = $this->input->post('mobile');
    $user_details['email'] = $this->input->post('email');
    $user_details['name'] = $this->input->post('username');
    $user_details['country_code'] = $this->input->post('country_code');

    $is_available = $this->user_login->check_user_emailid($email);
    $is_available_mobileno = $this->user_login->check_user_mobileno($mobile);
    $is_available_provider = $this->user_login->check_provider_email($email);
    $is_available_mobile_provider = $this->user_login->check_mobile_no($mobile);

    if ($is_available == 0 && $is_available_mobileno == 0 && $is_available_provider == 0 && $is_available_mobile_provider == 0)
    {
      $result = $this->user_login->user_signup($user_details);

      echo 1;

    }

    else
    {
      $this->session->set_flashdata('error_message', 'Something Went wrong.');
      echo 2;
    }

  }

  public function logout()
  {

    if ($this->session->userdata('usertype') == "provider")
    {
      $login_details = array(
        'last_logout' => date('Y-m-d H:i:s') ,
        'is_online' => 2
      );
      $this->db->where('id', $this->session->userdata('id'))->update('providers', $login_details);
    }
    $this->session->sess_destroy();
    redirect(base_url());
  }

  public function user_dashboard()
  {
    $this->data['page'] = 'user_dashboard';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function get_category()
  {
    $this->db->where('status', 1);
    $query = $this->db->get('categories');
    $result = $query->result();
    $data = array();
    foreach ($result as $r)
    {
      $data['value'] = $r->id;
      $data['label'] = $r->category_name;
      $json[] = $data;
    }
    echo json_encode($json);
  }

  public function email_chk()
  {
    $user_data = $this->input->post();
    $input['email'] = $user_data['userEmail'];
    $is_available = $this->user_login->otp_check_email($input);
    $is_available_user = $this->user_login->otp_check_uemail($input);
    if ($is_available > 0 || $is_available_user > 0)
    {
      $isAvailable = false;
    }
    else
    {
      $isAvailable = true;
    }
    echo json_encode(array(
      'valid' => $isAvailable
    ));
  }

  public function email_chk_user()
  {
    $user_data = $this->input->post();

    $input['email'] = $user_data['userEmail'];
    $input['user_id'] = $user_data['user_id'];  // added maksimU for editting
    $is_available = $this->user_login->otp_check_uemail($input);
    $is_available_provider = $this->user_login->otp_check_email($input);
    if ($is_available > 0 || $is_available_provider > 0)
    {
      $isAvailable = false;
    }
    else
    {
      $isAvailable = true;
    }
    echo json_encode(array(
      'valid' => $isAvailable
    ));
  }

  public function mobileno_chk()
  {
    $user_data = $this->input->post();

    if ($user_data['checked'] == 0)
    {
      $input['mobileno'] = $user_data['userMobile'];
      $input['countryCode'] = $user_data['countryCode'];
      $is_available_mobile = $this->user_login->check_mobile_no($input);
      $is_available_mobileuser = $this->user_login->check_user_mobileno($input);

      if ($is_available_mobile > 0 || $is_available_mobileuser > 0)
      {
        $isAvailable = false;
      }
      else
      {
        $isAvailable = true;
      }
      echo json_encode(array(
        'valid' => $isAvailable
      ));
    }
    elseif ($user_data['checked'] == 1)
    {
      $input['mobileno'] = $user_data['userMobile'];
      $input['countryCode'] = $user_data['countryCode'];
      $is_available_mobile = $this->user_login->check_mobile_no($input);

      if ($is_available_mobile > 0)
      {
        $isAvailable = true;
      }
      else
      {
        $isAvailable = false;
      }
      echo json_encode(array(
        'valid' => $isAvailable
      ));
    }
  }

  public function check_mobile_existing()
  {
    $user_data = $this->input->post();
    $input['mobileno'] = $user_data['userMobile'];
    $input['countryCode'] = $user_data['countryCode'];

    if ($user_data['userMobile'])
    {
      $is_available_mobile = $this->user_login->check_mobile_no($input);
      if ($is_available_mobile > 0)
      {
        $isAvailable = 1;
        $mode = 1;
      }
      else
      {
        $is_available_mobileuser = $this->user_login->check_user_mobileno($input);
        if ($is_available_mobileuser > 0)
        {
          $isAvailable = 1;
          $mode = 2;
        }
        else
        {
          $isAvailable = 2;
          $mode = 2;
        }
      }
      echo json_encode(['data' => $isAvailable, 'mode' => $mode]);

    }
  }

  public function chkmailexist()
  {
    $user_data = $this->input->post(); //echo "<pre>";print_r($input);exit;
    $input['email'] = $user_data['userEmail'];

    if ($user_data['userEmail'])
    {
      $is_available_mobile = $this->user_login->check_emailid($input);
      if ($is_available_mobile > 0)
      {
        $isAvailable = 1;
        $mode = 1;
      }
      else
      {
        $is_available_mobileuser = $this->user_login->check_user_emailidlogin($input);
        if ($is_available_mobileuser > 0)
        {
          $isAvailable = 1;
          $mode = 2;
        }
        else
        {
          $isAvailable = 2;
          $mode = 2;
        }
      }
      echo json_encode(['data' => $isAvailable, 'mode' => $mode]);

    }
  }

  public function mobileno_chk_user()
  {
    $user_data = $this->input->post();

    if ($user_data['checked'] == 0)
    {
      $input['mobileno'] = $user_data['userMobile'];
      $input['countryCode'] = $user_data['countryCode'];
      $input['user_id'] = $user_data['user_id'];        // added maksimU for editting
      $is_available_mobile = $this->user_login->check_mobile_no($input);
      $is_available_mobileuser = $this->user_login->check_user_mobileno($input);

      if ($is_available_mobile > 0 || $is_available_mobileuser > 0)
      {
        $isAvailable = false;
      }
      else
      {
        $isAvailable = true;
      }
      echo json_encode(array(
        'valid' => $isAvailable
      ));
    }
    elseif ($user_data['checked'] == 1)
    {
      $input['mobileno'] = $user_data['userMobile'];
      $input['countryCode'] = $user_data['countryCode'];
      $is_available_mobileuser = $this->user_login->check_user_mobileno($input);

      if ($is_available_mobileuser > 0)
      {
        $isAvailable = true;
      }
      else
      {
        $isAvailable = false;
      }
      echo json_encode(array(
        'valid' => $isAvailable
      ));
    }
  }

  /*resend otp*/
  public function re_send_otp_user()
  {
    extract($_POST);

    $user_type = $this->user_login->get_user_type($mobile_no, $country_code);

    $default_otp = settingValue('default_otp');
    if ($default_otp == 1)
    {
      $otp = '1234';
    }
    else
    {
      $otp = rand(1000, 9999);
    }

    $message = 'This is Your Login OTP  ' . $otp . '';
    $user_data['otp'] = $otp;

    error_reporting(0);
    $key = settingValue('sms_key');
    $secret_key = settingValue('sms_secret_key');
    $sender_id = settingValue('sms_sender_id');
    require_once ('vendor/nexmo/src/NexmoMessage.php');
    $nexmo_sms = new NexmoMessage($key, $secret_key);
    $result = $nexmo_sms->sendText($country_code . $mobile_no, $sender_id, $message);
    $this->session->set_tempdata('otp', '$user_data', 300);

    $otp_data = array(
      'endtime' => time() + 300,
      'mobile_number' => $mobile_no,
      'country_code' => $country_code,
      'otp' => $otp,
      'created_at' => date('Y-m-d H:i:s')
    );

    /*check OTP*/

    $check_otp_table = $this->user_login->isset_mobile_otp($mobile_no, $country_code, $otp_data);

    echo $check_otp_table;

    /*find mobile user type*/

  }

  /*resend otp*/
  public function re_send_otp_provider()
  {
    extract($_POST);

    $user_type = $this->user_login->get_user_type($mobile_no, $country_code);

    $default_otp = settingValue('default_otp');
    if ($default_otp == 1)
    {
      $otp = '1234';
    }
    else
    {
      $otp = rand(1000, 9999);
    }

    $message = 'This is Your Login OTP  ' . $otp . '';
    $user_data['otp'] = $otp;

    error_reporting(0);
    $key = settingValue('sms_key');
    $secret_key = settingValue('sms_secret_key');
    $sender_id = settingValue('sms_sender_id');
    require_once ('vendor/nexmo/src/NexmoMessage.php');
    $nexmo_sms = new NexmoMessage($key, $secret_key);
    $result = $nexmo_sms->sendText($country_code . $mobile_no, $sender_id, $message);
    $this->session->set_tempdata('otp', '$user_data', 300);

    $otp_data = array(
      'endtime' => time() + 300,
      'mobile_number' => $mobile_no,
      'country_code' => $country_code,
      'otp' => $otp,
      'created_at' => date('Y-m-d H:i:s') ,
    );

    /*check OTP*/

    $check_otp_table = $this->user_login->isset_mobile_otp($mobile_no, $country_code, $otp_data);

    echo $check_otp_table;

  }

  public function send_otp_request()
  {

    $user_data = $this->input->post();

    $query = $this->db->query("select * from system_settings WHERE status = 1");
    $sresult = $query->result_array();
    $login_type = '';
    $otp_by = '';
    foreach ($sresult as $res)
    {
      if ($res['key'] == 'login_type')
      {
        $login_type = $res['value'];
      }
      if ($res['key'] == 'otp_by')
      {
        $otp_by = $res['value'];
      }
    }
    if (!empty($user_data['mobileno']) && !empty($user_data['email']))
    {
      $is_available = $this->user_login->otp_check_email($user_data);

      $is_available_mobile = $this->user_login->otp_check_mobile_no($user_data);

      if ($is_available == 0)
      {
        if ($is_available_mobile == 0)
        {

          $default_otp = settingValue('default_otp');
          if ($default_otp == 1)
          {
            $otp = '1234';
          }
          else
          {
            $otp = rand(1000, 9999);
          }

          $message = 'This is Your Login OTP  ' . $otp . '';
          $user_data['otp'] = $otp;

          error_reporting(0);

          if ($login_type == 'mobile' && $otp_by == 'email')
          {

            $body = 'Hi ' . $user_data["username"] . ',<br> ' . $message;
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
            if (!empty($from_email) && isset($from_email))
            {
              $mail = $this->email->from($from_email)->to($user_data["email"])->subject('Provider Registration')->message($body)->send();
              //print_r($mail);exit;
              
            }

            $msg = 'Mail Sent Successfully';
            $this->session->set_flashdata('success_message', $msg);
            //echo 1;
            
          }
          else
          {
            $key = settingValue('sms_key');
            $secret_key = settingValue('sms_secret_key');
            $sender_id = settingValue('sms_sender_id');
            require_once ('vendor/nexmo/src/NexmoMessage.php');
            $nexmo_sms = new NexmoMessage($key, $secret_key);
            $result = $nexmo_sms->sendText($user_data['countryCode'] . $user_data['mobileno'], $sender_id, $message);
          }

          $this->db->where('country_code', $user_data['countryCode']);
          $this->db->where('mobile_number', $user_data['mobileno']);
          $this->db->where('status', 1);
          $save_otp = $this->db->update('mobile_otp', array(
            'status' => 0
          ));

          $this->session->set_tempdata('otp', '$user_data', 300);

          $otp_data = array(
            'endtime' => time() + 300,
            'mobile_number' => $user_data['mobileno'],
            'country_code' => $user_data['countryCode'],
            'otp' => $otp,
            'created_at' => date('Y-m-d H:i:s')
          );
          $save_otp = $this->user_login->save_otp($otp_data);

          echo json_encode(array(
            'response' => 'ok',
            'result' => 'true',
            'msg' => 'OTP has sent successfully'
          ));
        }
        else
        {
          echo json_encode(array(
            'response' => 'error',
            'result' => 'mobile',
            'msg' => 'Mobile number is already exists'
          ));
        }

      }
      else
      {
        echo json_encode(array(
          'response' => 'error',
          'result' => 'email',
          'msg' => 'Email is already exists'
        ));
      }

    }
    elseif (!empty($user_data['mobileno']))
    {
      $is_available = $this->user_login->otp_check_email($user_data);

      $is_available_mobile = $this->user_login->otp_check_mobile_no($user_data);

      if ($is_available_mobile == 1)
      {

        $userdet = $this->db->where('mobileno', $user_data['mobileno'])->from('providers')->get()->row_array();
        $default_otp = settingValue('default_otp');
        if ($default_otp == 1)
        {
          $otp = '1234';
        }
        else
        {
          $otp = rand(1000, 9999);
        }

        $message = 'This is Your Login OTP  ' . $otp . '';
        $user_data['otp'] = $otp;

        error_reporting(0);

        if ($login_type == 'mobile' && $otp_by == 'email')
        {

          $body = 'Hi ' . $userdet["name"] . ',<br> ' . $message;
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
          if (!empty($from_email) && isset($from_email))
          {
            $mail = $this->email->from($from_email)->to($userdet["email"])->subject('Provider Login')->message($body)->send();
            //print_r($mail);exit;
            
          }

          $msg = 'Mail Sent Successfully';
          $this->session->set_flashdata('success_message', $msg);
          echo 1;
        }
        else
        {
          $key = settingValue('sms_key');
          $secret_key = settingValue('sms_secret_key');
          $sender_id = settingValue('sms_sender_id');
          require_once ('vendor/nexmo/src/NexmoMessage.php');
          $nexmo_sms = new NexmoMessage($key, $secret_key);
          $result = $nexmo_sms->sendText($user_data['countryCode'] . $user_data['mobileno'], $sender_id, $message);
          $this->session->set_tempdata('otp', '$user_data', 300);
        }

        $this->db->where('country_code', $user_data['countryCode']);
        $this->db->where('mobile_number', $user_data['mobileno']);
        $this->db->where('status', 1);
        $save_otp = $this->db->update('mobile_otp', array(
          'status' => 0
        ));

        $otp_data = array(
          'endtime' => time() + 300,
          'mobile_number' => $user_data['mobileno'],
          'country_code' => $user_data['countryCode'],
          'otp' => $otp,
          'created_at' => date('Y-m-d H:i:s')
        );
        $save_otp = $this->user_login->save_otp($otp_data);

        echo json_encode(array(
          'response' => 'ok',
          'result' => 'true',
          'msg' => 'OTP has sent successfully'
        ));
      }
      else
      {
        echo json_encode(array(
          'response' => 'error',
          'result' => 'mobile',
          'msg' => 'Mobile number does not exists'
        ));
      }

    }

  }

  public function send_otp_request_user()
  {

    $user_data = $this->input->post();

    $query = $this->db->query("select * from system_settings WHERE status = 1");
    $result = $query->result_array();
    $login_type = '';
    $otp_by = '';
    foreach ($result as $res)
    {
      if ($res['key'] == 'login_type')
      {
        $login_type = $res['value'];
      }
      if ($res['key'] == 'otp_by')
      {
        $otp_by = $res['value'];
      }
    }

    if (!empty($user_data['mobileno']) && !empty($user_data['email']))
    {
      $is_available = $this->user_login->otp_check_uemail($user_data);

      $is_available_mobile = $this->user_login->otp_check_mobile_no_user($user_data);

      if ($is_available == 0)
      {
        if ($is_available_mobile == 0)
        {

          $default_otp = settingValue('default_otp'); //print_r($default_otp);exit;
          if ($default_otp == 1)
          {
            $otp = '1234';
          }
          else
          {
            $otp = rand(1000, 9999);
          }

          $message = 'This is Your Login OTP  ' . $otp . '';
          $user_data['otp'] = $otp;

          error_reporting(0);
          if ($login_type == 'mobile' && $otp_by == 'email')
          {

            $body = 'Hi ,<br> ' . $message;
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
            if (!empty($from_email) && isset($from_email))
            {
              $mail = $this->email->from($from_email)->to($user_data["email"])->subject('User Registration')->message($body)->send();

            }

            $msg = 'Mail Sent Successfully';
            $this->session->set_flashdata('success_message', $msg);
            echo 1;
          }
          else
          {
            $key = settingValue('sms_key');
            $secret_key = settingValue('sms_secret_key');
            $sender_id = settingValue('sms_sender_id');
            require_once ('vendor/nexmo/src/NexmoMessage.php');
            $nexmo_sms = new NexmoMessage($key, $secret_key);
            $result = $nexmo_sms->sendText($user_data['countryCode'] . $user_data['mobileno'], $sender_id, $message);
          }
          $this->db->where('country_code', $user_data['countryCode']);
          $this->db->where('mobile_number', $user_data['mobileno']);
          $this->db->where('status', 1);
          $save_otp = $this->db->update('mobile_otp', array(
            'status' => 0
          ));

          $this->session->set_tempdata('otp', '$user_data', 300);

          $otp_data = array(
            'endtime' => time() + 300,
            'mobile_number' => $user_data['mobileno'],
            'country_code' => $user_data['countryCode'],
            'otp' => $otp,
            'created_at' => date('Y-m-d H:i:s')
          );
          $save_otp = $this->user_login->save_otp($otp_data);

          echo json_encode(array(
            'response' => 'ok',
            'result' => 'true',
            'msg' => 'OTP has sent successfully'
          ));
        }
        else
        {
          echo json_encode(array(
            'response' => 'error',
            'result' => 'mobile',
            'msg' => 'Mobile number is already exists'
          ));
        }

      }
      else
      {
        echo json_encode(array(
          'response' => 'error',
          'result' => 'email',
          'msg' => 'Email is already exists'
        ));
      }

    }
    elseif (!empty($user_data['mobileno']))
    {
      $is_available = $this->user_login->otp_check_uemail($user_data);

      $is_available_mobile = $this->user_login->otp_check_mobile_no_user($user_data);

      if ($is_available_mobile == 1)
      {
        $userdet = $this->db->where('mobileno', $user_data['mobileno'])->from('users')->get()->row_array();
        $default_otp = settingValue('default_otp');
        if ($default_otp == 1)
        {
          $otp = '1234';
        }
        else
        {
          $otp = rand(1000, 9999);
        }

        $message = 'This is Your Login OTP  ' . $otp . '';
        $user_data['otp'] = $otp;

        error_reporting(0);

        if ($login_type == 'mobile' && $otp_by == 'email')
        {

          $body = 'Hi ' . $userdet["name"] . ',<br> ' . $message;
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
          if (!empty($from_email) && isset($from_email))
          {
            $mail = $this->email->from($from_email)->to($userdet["email"])->subject('User Login')->message($body)->send();
            //print_r($mail);exit;
            
          }

          $msg = 'Mail Sent Successfully';
          $this->session->set_flashdata('success_message', $msg);
          echo 1;
        }
        else
        {
          $key = settingValue('sms_key');
          $secret_key = settingValue('sms_secret_key');
          $sender_id = settingValue('sms_sender_id');
          require_once ('vendor/nexmo/src/NexmoMessage.php');
          $nexmo_sms = new NexmoMessage($key, $secret_key);
          $result = $nexmo_sms->sendText($user_data['countryCode'] . $user_data['mobileno'], $sender_id, $message);
          $this->session->set_tempdata('otp', '$user_data', 300);
        }

        $this->db->where('country_code', $user_data['countryCode']);
        $this->db->where('mobile_number', $user_data['mobileno']);
        $this->db->where('status', 1);
        $save_otp = $this->db->update('mobile_otp', array(
          'status' => 0
        ));

        $otp_data = array(
          'endtime' => time() + 300,
          'mobile_number' => $user_data['mobileno'],
          'country_code' => $user_data['countryCode'],
          'otp' => $otp,
          'created_at' => date('Y-m-d H:i:s')
        );
        $save_otp = $this->user_login->save_otp($otp_data);

        echo json_encode(array(
          'response' => 'ok',
          'result' => 'true',
          'msg' => 'OTP has sent successfully'
        ));
      }
      else
      {
        echo json_encode(array(
          'response' => 'error',
          'result' => 'mobile',
          'msg' => 'Mobile number does not exists'
        ));
      }

    }

  }

  public function check_otp()
  {

    $input_data = $this->input->post();
    $username = strlen($input_data['name']);
    $input_data['share_code'] = $this->user_login->ShareCode(6, $username);
    $input_data['currency_code'] = settings('currency');

    $check_data = array(
      'mobile_number' => $input_data['mobileno'],
      'otp' => $input_data['otp']
    );

    if ($input_data['name'] && $input_data['email'] != '')
    {

      $check = $this->user_login->otp_validation($check_data, $input_data);
      $provider_details = (!empty($check['data'])) ? $check['data'] : '';

      $bodyid = 1;
      $tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
      $body = $tempbody_details['template_content'];
      $body = str_replace('{user_name}', $input_data['name'], $body);
      $body = str_replace('{sitetitle}', $this->site_name, $body);
      $preview_link = base_url();
      $body = str_replace('{preview_link}', $preview_link, $body);

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
        $mail = $this->email->from($from_email)->to($input_data['email'])->subject('Welcome to TazzerGroup!')->message($body)->send();
      }

    }
    else
    {
      $check = $this->user_login->check_otp($check_data);
      $provider_details = $this->user_login->get_provider_details($input_data['mobileno']);

    }

    if (is_array($check) && $check['msg'] == 'ok')
    {
      $return = array(
        'response' => 'ok',
        'msg' => 'Successful',
        'login_data' => $check
      );
      $check['logged_in'] = true;

      $session_data = array(
        'id' => $provider_details['id'],
        'chat_token' => $provider_details['token'],
        'name' => $provider_details['name'],
        'email' => $provider_details['email'],
        'mobileno' => $provider_details['mobileno'],
        'usertype' => 'provider'
      );
      $this->session->set_userdata($session_data);
      $login_details = array(
        'last_login' => date('Y-m-d H:i:s') ,
        'is_online' => 1
      );
      $this->db->where('id', $provider_details['id'])->update('providers', $login_details);
      echo json_encode($return);
    }
    else if ($check == 'invalid_otp')
    {
      $return = array(
        'response' => 'error',
        'msg' => 'Invaild OTP',
        'result' => 'otp_invalid'
      );
      echo json_encode($return);
    }
    elseif ($check == 'otp_expired')
    {
      $return = array(
        'response' => 'error',
        'msg' => 'OTP expired',
        'result' => 'otp_expired'
      );
      echo json_encode($return);
    }
    else
    {
      $return = array(
        'response' => 'error',
        'msg' => 'Check Your OTP'
      );
      echo json_encode($return);
    }
  }

  //paramesh...
  

  public function checkemaillogin_user()
  {

    $input_data = $this->input->post();
    $check_data = array(
      'email' => $input_data['email'],
      'password' => md5($input_data['login_password']) ,
      'status' => 1
    );
    $check = $this->user_login->check_emailloginuser($check_data);
    $user_details = $this->user_login->get_provider_detailsbymailuser($input_data['email']);
    
    if ($check['data'] != '' && $check['msg'] == 'ok')
    {
      $date = utc_date_conversion(date('Y-m-d H:i:s'));
      $return = array(
        'response' => 'ok',
        'msg' => 'Successful',
        'login_data' => $check
      );
      $check['logged_in'] = true;

      if (!empty($input_data['mobileno']))
      {
        $this->db->where('mobileno', $input_data['mobileno']);
        $this->db->where('status', 1);
        $this->db->update('users', array(
          'last_login' => $date
        ));
      }

      $session_data = array(
        'id' => $user_details['id'],
        'chat_token' => $user_details['token'],
        'name' => $user_details['name'],
        'email' => $user_details['email'],
        'mobileno' => $user_details['mobileno'],
        'usertype' => 'user'
      );
      $this->session->set_userdata($session_data);
      $this->load->model('LoginHistory');
      $historyData = ['user_token' => $user_details['token'], 'user_id' => $user_details['id'], 'user_type' => 2, 'user_name' => $user_details['name']];
      $this->LoginHistory->insert($historyData);

      # add by maksimU : For Employee login in Users LoginPage
      if($user_details['you_are_appling_as']==C_YOUARE_EMPLOYEE)
      {
        $data_employee = array(
          'email' => $user_details['email'],
          'password' => $user_details['password'],
          );
        $this->session->set_userdata('data_employee',$data_employee);
        $this->session->set_userdata('employee_status','yes');

        $this->session->set_userdata('serviceman','yes');
      }
      elseif($user_details['you_are_appling_as']==C_YOUARE_SOLETRADER)
      {
        $data_employee = array(
          'email' => $user_details['email'],
          'password' => $user_details['password'],
          );
        $this->session->set_userdata('data_employee',$data_employee);
        $this->session->set_userdata('soletrader_status','yes');
        $this->session->set_userdata('serviceman','yes');
      }
      elseif($user_details['you_are_appling_as']==C_YOUARE_SELF_EMPLOYED)
      {
        $data_employee = array(
          'email' => $user_details['email'],
          'password' => $user_details['password'],
          );
        $this->session->set_userdata('data_employee',$data_employee);
        $this->session->set_userdata('self_employed_status','yes');
        $this->session->set_userdata('serviceman','yes');
      }
      $this->session->set_userdata('you_are_appling_as', $user_details['you_are_appling_as']);
      # <---

      echo json_encode($return);
    }
    else
    {
      $return = array(
        'response' => 'error',
        'msg' => 'Check Your Credentials'
      );
      echo json_encode($return);
    }

  }

  public function checkemaillogin()
  {

    $input_data = $this->input->post();
    $check_data = array(
      'email' => $input_data['email'],
      'password' => md5($input_data['login_password']) ,
      'status' => 1
    );
    $check = $this->user_login->check_emaillogin($check_data);
    $provider_details = $this->user_login->get_provider_detailsbymail($input_data['email']);

    if ($check['data'] != '' && $check['msg'] == 'ok')
    {
      $return = array(
        'response' => 'ok',
        'msg' => 'Successful',
        'login_data' => $check
      );
      $check['logged_in'] = true;

      $session_data = array(
        'id' => $provider_details['id'],
        'chat_token' => $provider_details['token'],
        'name' => $provider_details['name'],
        'email' => $provider_details['email'],
        'mobileno' => $provider_details['mobileno'],
        'usertype' => 'provider'
      );
      $this->session->set_userdata($session_data);
      $this->load->model('LoginHistory');
      $historyData = ['user_token' => $provider_details['token'], 'user_id' => $provider_details['id'], 'user_type' => 1, 'user_name' => $provider_details['name']];
      $this->LoginHistory->insert($historyData);
      $login_details = array(
        'last_login' => date('Y-m-d H:i:s') ,
        'is_online' => 1
      );
      $this->db->where('id', $provider_details['id'])->update('providers', $login_details);
      echo json_encode($return);
    }

    else
    {
      $return = array(
        'response' => 'error',
        'msg' => 'Check Your Credentials'
      );
      echo json_encode($return);
    }
  }

  /* Leo: fix register engine */
  public function emailregister()
  {

    $input_data = $this->input->post();
    $username = strlen($input_data['name']);
    $input_data['share_code'] = $this->user_login->ShareCode(6, $username);
    $input_data['currency_code'] = settings('currency');
    $input_data['password'] = md5($input_data['password']);

    $digits = 4;
    $otpsession = rand(pow(10, $digits - 1) , pow(10, $digits) - 1);
    $this->session->set_userdata('register_input', $input_data);
    $this->session->set_userdata('email', $input_data['email']);
    $this->session->set_userdata('mobile_otp', $otpsession);

    $return = array(
      'response' => 'ok',
      'msg' => 'Successful'
    );
    echo json_encode($return);

  }

  public function emailregisterprovider()
  {

    /* echo '<pre>';
    print_r($this->session->userdata());
    exit; */
    $input_data = $this->input->post();
    $username = strlen($input_data['name']);
    $input_data['share_code'] = $this->user_login->ShareCode(6, $username);
    $input_data['currency_code'] = settings('currency');
    $input_data['password'] = md5($input_data['password']);

    if ($this->session->userdata('id_proof') and $this->session->userdata('id_proof') != '')
    {
      $input_data['id_proof'] = $this->session->userdata('id_proof');
      $this->session->set_userdata('id_proof', '');
    }
    if ($this->session->userdata('qualification_document') and $this->session->userdata('qualification_document') != '')
    {
      $input_data['qualification_document'] = $this->session->userdata('qualification_document');
      $this->session->set_userdata('qualification_document', '');
    }
    if ($this->session->userdata('address_proof') and $this->session->userdata('address_proof') != '')
    {
      $input_data['address_proof'] = $this->session->userdata('address_proof');
      $this->session->set_userdata('address_proof', '');
    }
    if ($this->session->userdata('mot_certificate') and $this->session->userdata('mot_certificate') != '')
    {
      $input_data['mot_certificate'] = $this->session->userdata('mot_certificate');
      $this->session->set_userdata('mot_certificate', '');
    }
    if ($this->session->userdata('driving_license') and $this->session->userdata('driving_license') != '')
    {
      $input_data['driving_license'] = $this->session->userdata('driving_license');
      $this->session->set_userdata('driving_license', '');
    }
    if ($this->session->userdata('car_insurance') and $this->session->userdata('car_insurance') != '')
    {
      $input_data['car_insurance'] = $this->session->userdata('car_insurance');
      $this->session->set_userdata('car_insurance', '');
    }
    if ($this->session->userdata('business_insurance') and $this->session->userdata('business_insurance') != '')
    {
      $input_data['business_insurance'] = $this->session->userdata('business_insurance');
      $this->session->set_userdata('car_insurance', '');
    }

    $check = $this->user_login->insertemailproviders($input_data);
    $user_details = $check['data'];

    if (is_array($check) && $check['msg'] == 'ok')
    {

      $bodyid = 1;
      $tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
      $body = $tempbody_details['template_content'];
      $body = str_replace('{user_name}', $input_data['name'], $body);
      $preview_link = base_url();
      $body = str_replace('{preview_link}', $preview_link, $body);

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
        $mail = $this->email->from($from_email)->to($input_data['email'])->subject('Welcome to TazzerGroup!')->message($body)->send();
      }

      $date = utc_date_conversion(date('Y-m-d H:i:s'));
      $return = array(
        'response' => 'ok',
        'msg' => 'Successful',
        'login_data' => $check
      );
      $check['logged_in'] = true;

      if (!empty($input_data['mobileno']))
      {
        $this->db->where('mobileno', $input_data['mobileno']);
        $this->db->where('status', 1);
        $this->db->update('providers', array(
          'last_login' => $date
        ));
      }

      /*   $session_data = array(
      'id' => $user_details['id'],
      'chat_token' => $user_details['token'],
      'name'  => $user_details['name'],
      'email'     => $user_details['email'],
      'mobileno' => $user_details['mobileno'],
      'usertype' => 'provider'
      );
      $this->session->set_userdata($session_data);  */

      $this->session->set_flashdata('success_message', 'You have registered to the website successfully. Once admin verified you will be able to login.');

      echo json_encode($return);
    }
    else
    {
      $return = array(
        'response' => 'error',
        'msg' => 'Check Your Credentials'
      );
      echo json_encode($return);
    }
  }

  //
  public function check_otp_user()
  {

    $input_data = $this->input->post();
    $username = strlen($input_data['name']);
    $input_data['share_code'] = $this->user_login->ShareCode(6, $username);
    $input_data['currency_code'] = settings('currency');

    $check_data = array(
      'mobile_number' => $input_data['mobileno'],
      'otp' => $input_data['otp']
    );

    if ($input_data['name'] && $input_data['email'] != '')
    {

      $check = $this->user_login->otp_validation_user($check_data, $input_data);
      $user_details = $check['data'];
      $bodyid = 1;
      $tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
      $body = $tempbody_details['template_content'];
      $body = str_replace('{user_name}', $input_data['name'], $body);
      $body = str_replace('{sitetitle}', $this->site_name, $body);
      $preview_link = base_url();
      $body = str_replace('{preview_link}', $preview_link, $body);

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
        $mail = $this->email->from($from_email)->to($input_data['email'])->subject('Welcome to TazzerGroup!')->message($body)->send();
      }
    }
    else
    {

      $check = $this->user_login->check_otp_user($check_data);
      $user_details = $this->user_login->get_user_details($input_data['mobileno']);

    }

    if (is_array($check) && $check['msg'] == 'ok')
    {

      $date = utc_date_conversion(date('Y-m-d H:i:s'));
      $return = array(
        'response' => 'ok',
        'msg' => 'Successful',
        'login_data' => $check
      );
      $check['logged_in'] = true;

      if (!empty($input_data['mobileno']))
      {
        $this->db->where('mobileno', $input_data['mobileno']);
        $this->db->where('status', 1);
        $this->db->update('users', array(
          'last_login' => $date
        ));
      }

      $session_data = array(
        'id' => $user_details['id'],
        'chat_token' => $user_details['token'],
        'name' => $user_details['name'],
        'email' => $user_details['email'],
        'mobileno' => $user_details['mobileno'],
        'usertype' => 'user'
      );
      $this->session->set_userdata($session_data);

      echo json_encode($return);
    }
    else if ($check == 'invalid_otp')
    {
      $return = array(
        'response' => 'error',
        'msg' => 'Invaild OTP',
        'result' => 'otp_invalid'
      );
      echo json_encode($return);
    }
    elseif ($check == 'otp_expired')
    {
      $return = array(
        'response' => 'error',
        'msg' => 'OTP expired',
        'result' => 'otp_expired'
      );
      echo json_encode($return);
    }
    else
    {
      $return = array(
        'response' => 'error',
        'msg' => 'Check Your OTP'
      );
      echo json_encode($return);
    }
  }

  public function checkforgotmail()
  {
    $email = $this->input->post('email');
    $mode = $this->input->post('mode');

    if ($mode == 2)
    {
      $result = $this->user_login->check_user_emaildet($email);
      $user_type = 'user';
    }
    else
    {
      $result = $this->user_login->check_provider_emaildet($email);
      $user_type = 'provider';
    }

    //print_r($result);exit;
    if (!empty($result))
    {
      $token = rand(1000, 9999);
      $pwdlink = base_url() . "user/login/userchangepwd/" . base64_encode($result['id']) . "/" . base64_encode($token) . "/" . base64_encode($mode);
      $chk_forpawd = $this->db->where('user_id', $result['id'])->where('user_type', $user_type)->where('status', '1')->select('*')->get('forget_password_det')->result_array();
      if (empty($chk_forpawd))
      {
        $pwdlink_data = array(
          'endtime' => time() + 300,
          'token' => $token,
          'user_id' => $result['id'],
          'email' => $result['email'],
          'pwdlink' => $pwdlink,
          'user_type' => $user_type,
          'created_at' => date('Y-m-d H:i:s')
        );
        $save_forpwd = $this->admin->save_pwdlink_data($pwdlink_data);
      }
      else
      {
        $pwdlink_data = array(
          'endtime' => time() + 300,
          'token' => $token,
          'user_id' => $result['id'],
          'email' => $result['email'],
          'pwdlink' => $pwdlink,
          'user_type' => $user_type,
          'updated_on' => date('Y-m-d H:i:s')
        );
        $save_forpwd = $this->admin->update_pwdlink_data($pwdlink_data, $result['id']);
      }

      $message = 'Reset Link  ' . $pwdlink . '';
      $body = 'Hi ' . $result["name"] . ',<br> ' . $message;

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
      if (!empty($from_email) && isset($from_email))
      {
        $mail = $this->email->from($from_email)->to($result["email"])->subject('User Forget Password Link')->message($body)->send();
      }
      // Leo: check for dev
      if(ENVIRONMENT=='development')
      {
        echo $pwdlink;
      }

      echo 1;
    }
    else
    {
      $this->session->set_flashdata('error_message', 'Email ID Not Exist...!');
      echo 2;
    }
  }

  public function userchangepwd($id, $token, $mode)
  {
    //echo "hi";exit;
    $this->data['euser_id'] = base64_decode($id);
    $this->data['etoekn'] = base64_decode($token);
    $this->data['emode'] = base64_decode($mode);
    if ($this->data['emode'] == 2)
    {
      $user_type = 'user';
    }
    else
    {
      $user_type = 'provider';
    }
    $this->data['chk_data'] = $this->db->where('user_id', $this->data['euser_id'])->where('user_type', $user_type)->where('status', '1')->where('token', $this->data['etoekn'])->select('*')->get('forget_password_det')->result_array();

    $this->data['module'] = 'home';
    $this->data['page'] = 'reset_forgot_password';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function save_reset_password()
  {
    $user_id = $this->input->post('user_id');
    $mode = $this->input->post('mode');
    $confirm_password = array(
      'password' => md5($this->input->post('confirm_password'))
    );

    if ($mode == 2)
    {
      $chkdata = $this->db->where('id', $user_id)->select('*')->get('users')->result_array();
    }
    else
    {
      $chkdata = $this->db->where('id', $user_id)->select('*')->get('providers')->result_array();
    }

    //print_r($result);exit;
    if (!empty($chkdata))
    {
      if ($mode == 2)
      {
        $save_pwd = $this->user_login->update_res_userpwd($confirm_password, $user_id);
      }
      else
      {
        $save_pwd = $this->user_login->update_res_providerpwd($confirm_password, $user_id);
      }
      $change_sts = $this->admin->update_forpwd_status($status = array(
        'status' => 0
      ) , $user_id);
      $this->session->set_flashdata('error_message', 'Password Changed Successfully...!');

      echo 1;
    }
    else
    {
      $this->session->set_flashdata('error_message', 'Something Went wrong...!');
      echo 2;
    }
  }

  public function uploaddoc()
  {
    $upload_type = $_REQUEST['upload_type'];

    $baseFolder = 'uploads/identity_documents/';
    if (!is_dir($baseFolder . date('Y')))
    {
      mkdir($baseFolder . date('Y') , 0755);
    }
    if (!is_dir($baseFolder . date('Y') . '/' . date('m')))
    {
      mkdir($baseFolder . date('Y') . '/' . date('m') , 0755);
    }
    if (!is_dir($baseFolder . date('Y') . '/' . date('m') . '/' . date('d')))
    {
      mkdir($baseFolder . date('Y') . '/' . date('m') . '/' . date('d') , 0755);
    }
    if (!is_dir($baseFolder . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $upload_type))
    {
      mkdir($baseFolder . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $upload_type, 0755);
    }
    if (isset($_FILES[$upload_type]))
    {
      if (move_uploaded_file($_FILES[$upload_type]['tmp_name'], $baseFolder . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $upload_type . '/' . $_FILES[$upload_type]['name']))
      {
        $this->session->set_userdata($upload_type, $baseFolder . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $upload_type . '/' . $_FILES[$upload_type]['name']);
        echo 'success';
        exit;
      }
      else
      {
        $this->session->set_userdata($upload_type, '');
        echo 'error';
        exit;
      }
    }
    else
    {
      $this->session->set_userdata($upload_type, '');
      echo 'error';
      exit;
    }
  }

}
