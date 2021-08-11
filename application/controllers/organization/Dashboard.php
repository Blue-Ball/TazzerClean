<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require $_SERVER['DOCUMENT_ROOT'].'/stripeonsite/Stripe.php';
use Stripe\Stripe;

require $_SERVER['DOCUMENT_ROOT'] . '/paypal/PayPalClient.php';

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class Dashboard extends CI_Controller
{

    public $data;

    public function __construct()
    {

        parent::__construct();
        error_reporting(0);
        if (empty($this->session->userdata('id'))) {
            $this->session->set_flashdata('history_uri', $this->uri->uri_string());
            redirect(base_url() . "login");
        }
        $this->data['theme'] = 'organization';
        $this->data['module'] = 'home';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->load->helper('user_timezone_helper');
        $this->load->model('service_model', 'service');
        $this->load->model('home_model', 'home');
        $this->load->model('Api_model', 'api');
        $this->load->model('Stripe_model');
        $this->load->model('employee');
        $this->load->model('organization_model', 'organization');

        // Load pagination library
        $this->load->library('paypal_lib');
        $this->load->library('ajax_pagination');
        $this->load->helper('form');
        $this->data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );

        // Load post model
        $this->load->model('booking');
        $this->load->model('User_booking', 'userbooking');

        // Per page limit
        $this->perPage = 6;

        $stripeKeys = stripeKeys();
        \Stripe\Stripe::setApiKey($stripeKeys['secret_key']);
        $this->stripeKeys = $stripeKeys;

        //public $data;
        $this->load->model('common_model', 'common_model');
        $this->data['base_url'] = base_url();
        $this->load->helper('user_timezone');
        $this->data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $this->load->helper('ckeditor');
        $this->load->helper('common_helper');
        $this->data['ckeditor_editor252'] = array(
            'id' => 'ck_editor_textarea_id252',
            'path' => 'assets/js/ckeditor',
            'config' => array(
                'toolbar' => "Full",
                'filebrowserBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );
    }

    public function index()
    {
        $this->data['page'] = 'index';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function organization_wallet()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if($this->session->userdata('you_are_appling_as') != C_YOUARE_ORGANIZATION){
            redirect(base_url());
        }
        $this->data['page'] = 'organization_wallet';
        $this->data['wallet'] = $this->api->get_wallet($this->session->userdata('chat_token'));
        $this->data['wallet_history'] = $this->api->get_wallet_history_info($this->session->userdata('chat_token'));
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    // ===================================================================================================
    public function razorpay_details()
    {
        //echo "hi";exit;
        removeTag($this->input->post());
        $params = $this->input->post();
        $user_id = $this->session->userdata('id');

        $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if (!empty($result)) {
            foreach ($result as $data1) {
                if ($data1['key'] == 'razorpay_apikey') {
                    $apikey = $data1['value'];
                }

                if ($data1['key'] == 'razorpay_secret_key') {
                    $apisecret = $data1['value'];
                }

                if ($data1['key'] == 'live_razorpay_apikey') {
                    $apikey = $data1['value'];
                }

                if ($data1['key'] == 'live_razorpay_secret_key') {
                    $apisecret = $data1['value'];
                }
            }
        }

        // $razor_option = settingValue('razor_option');
        // if($razorpay_option == 1){
        // $apikey = settingValue('razorpay_apikey');
        // $apisecret = settingValue('razorpay_secret_key');
        // }else if($razorpay_option == 2){
        // $apikey = settingValue('live_razorpay_apikey');
        // $apisecret = settingValue('live_razorpay_secret_key');
        // }
        $user_currency = 'INR';
        if (!empty($params)) {
            $url = "https://api.razorpay.com/v1/contacts";
            $unique = strtoupper(uniqid());
            $data = ' {
              "name":"' . $params['name'] . '",
              "email":"' . $params['email'] . '",
              "contact":"' . $params['contact'] . '",
              "type":"employee",
              "reference_id":"' . $unique . '",
              "notes":{}
            }';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $apikey . ":" . $apisecret);
            $headers = array(
                'Content-Type:application/json'
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                $result = 'Error:' . curl_error($ch);
                echo json_encode(array(
                    'status' => false,
                    'msg' => $result
                ));
            }

            $results = json_decode($result);
            $user_id = $this->session->userdata('id');
            $cnotes = $results->notes;
            $serializedcnotes = serialize($cnotes);
            $contact_data = array(
                'user_id' => $user_id,
                'rp_contactid' => $results->id,
                'entity' => $results->entity,
                'name' => $results->name,
                'contact' => $results->contact,
                'email' => $results->email,
                'type' => $results->type,
                'reference_id' => $results->reference_id,
                'batch_id' => $results->batch_id,
                'active' => $results->active,
                'accountnumber' => $params['accountnumber'],
                'mode' => $params['mode'],
                'purpose' => $params['purpose'],
                'notes' => $serializedcnotes,
                'created_at' => $results->created_at
            );

            $createcontact = $this->db->insert('razorpay_contact', $contact_data);
            if (!empty($createcontact)) {
                $faurl = "https://api.razorpay.com/v1/fund_accounts";
                $faunique = strtoupper(uniqid());
                $fadata = ' {
                  "contact_id": "' . $results->id . '",
                  "account_type": "bank_account",
                  "bank_account": {
                    "name": "' . $params['bank_name'] . '",
                    "ifsc": "' . $params['ifsc'] . '",
                    "account_number":"' . $params['accountnumber'] . '"
                  }
                }';

                $fach = curl_init();
                curl_setopt($fach, CURLOPT_URL, $faurl);
                curl_setopt($fach, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($fach, CURLOPT_POSTFIELDS, $fadata);
                curl_setopt($fach, CURLOPT_POST, 1);
                curl_setopt($fach, CURLOPT_USERPWD, $apikey . ":" . $apisecret);
                $faheaders = array(
                    'Content-Type:application/json'
                );
                curl_setopt($fach, CURLOPT_HTTPHEADER, $faheaders);
                $faresult = curl_exec($fach);

                if (curl_errno($fach)) {
                    $faresult = 'Error:' . curl_error($fach);
                    echo json_encode(array(
                        'status' => false,
                        'msg' => $faresult
                    ));
                }
                $faresults = json_decode($faresult);

                $fa_data = array(
                    'fund_account_id' => $faresults->id,
                    'entity' => $faresults->entity,
                    'contact_id' => $faresults->contact_id,
                    'account_type' => $faresults->account_type,
                    'ifsc' => $faresults->bank_account->ifsc,
                    'bank_name' => $faresults->bank_account->bank_name,
                    'name' => $faresults->bank_account->name,
                    'account_number' => $faresults->bank_account->account_number,
                    'active' => $faresults->active,
                    'batch_id' => $faresults->batch_id,
                    'created_at' => $faresults->created_at
                );

                $facreatecontact = $this->db->insert('razorpay_fund_account', $fa_data);

                if ($facreatecontact) {
                    $purl = "https://api.razorpay.com/v1/payouts";
                    $punique = strtoupper(uniqid());
                    $pdata = ' {
                      "account_number": "2323230032510196",
                      "fund_account_id": "' . $faresults->id . '",
                      "amount": "' . $params['amount'] . '",
                      "currency": "INR",
                      "mode": "' . $params['mode'] . '",
                      "purpose": "' . $params['purpose'] . '",
                      "queue_if_low_balance": true,
                      "reference_id": "' . $punique . '",
                      "narration": "",
                      "notes": {}
                    }';

                    $pch = curl_init();
                    curl_setopt($pch, CURLOPT_URL, $purl);
                    curl_setopt($pch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($pch, CURLOPT_POSTFIELDS, $pdata);
                    curl_setopt($pch, CURLOPT_POST, 1);
                    curl_setopt($pch, CURLOPT_USERPWD, $apikey . ":" . $apisecret);
                    $pheaders = array(
                        'Content-Type:application/json'
                    );
                    curl_setopt($pch, CURLOPT_HTTPHEADER, $pheaders);
                    $presult = curl_exec($pch);

                    if (curl_errno($pch)) {
                        $presult = 'Error:' . curl_error($pch);
                        echo json_encode(array(
                            'status' => false,
                            'msg' => $presult
                        ));
                    }
                    $presults = json_decode($presult);

                    $pydata = array(
                        'payout_id' => $presults->id,
                        'entity' => $presults->entity,
                        'fund_account_id' => $presults->fund_account_id,
                        'amount' => $presults->amount,
                        'currency' => $presults->currency,
                        'fees' => $presults->fees,
                        'tax' => $presults->tax,
                        'status' => $presults->status,
                        'utr' => $presults->utr,
                        'mode' => $presults->mode,
                        'purpose' => $presults->purpose,
                        'reference_id' => $presults->reference_id,
                        'narration' => $presults->narration,
                        'batch_id' => $presults->batch_id,
                        'failure_reason' => $presults->failure_reason,
                        'created_at' => $presults->created_at
                    );
                    $payouts = $this->db->insert('razorpay_payouts', $pydata);
                    if ($payouts) {
                        $wdata = array(
                            'user_id' => $user_id,
                            'amount' => $presults->amount,
                            'currency_code' => $presults->currency,
                            'transaction_status' => 1,
                            'transaction_date' => date('Y-m-d'),
                            'request_payment' => 'RazorPay',
                            'status' => 1,
                            'created_by' => $user_id,
                            'created_at' => $presults->created_at
                        );

                        $payoutins = $this->db->insert('wallet_withdraw', $wdata);
                        if ($payoutins) {
                            $amount = $presults->amount;
                            $user_id = $this->session->userdata('id');
                            $user = $this->db->where('id', $user_id)->get('providers')->row_array();
                            $user_name = $user['name'];
                            $user_token = $user['token'];
                            $currency_type = $user['currency_code'];
                            $wallet = $this->db->where('user_provider_id', $user_id)->where('type', 1)->get('wallet_table')->row_array();
                            $wallet_amt = $wallet['wallet_amt']; //echo json_encode($wallet_amt);exit;
                            $history_pay['token'] = $user_token;
                            $history_pay['user_provider_id'] = $user_id;
                            $history_pay['currency_code'] = 'INR';
                            $history_pay['credit_wallet'] = 0;
                            $history_pay['debit_wallet'] = $amount;

                            $history_pay['transaction_id'] = $presults->id;
                            $history_pay['paid_status'] = '1';
                            $history_pay['total_amt'] = $presults->amount;
                            if ($wallet_amt) {
                                $current_wallet = $wallet_amt - $amount;
                            } else {
                                $current_wallet = $amount;
                            }
                            $history_pay['current_wallet'] = $wallet_amt;
                            $history_pay['avail_wallet'] = $current_wallet;
                            $history_pay['reason'] = 'Withdrawn Wallet Amt';
                            $history_pay['created_at'] = date('Y-m-d H:i:s');
                            if ($this->db->insert('wallet_transaction_history', $history_pay)) {
                                $this->db->where('user_provider_id', $user_id)->update('wallet_table', array(
                                    'currency_code' => 'INR',
                                    'wallet_amt' => $current_wallet
                                ));
                            }
                            $message = "Amount Withdrawn Successfully";
                            echo json_encode(array(
                                'status' => true,
                                'msg' => $message
                            ));
                        } else {
                            $message = "Payout details not Inserted";
                            echo json_encode(array(
                                'status' => false,
                                'msg' => $message
                            ));
                        }
                    } else {
                        $message = "Payout details not Inserted";
                        echo json_encode(array(
                            'status' => false,
                            'msg' => $message
                        ));
                    }
                }
            }
        } else {
            $message = (!empty($this->user_language[$this->user_selected]['lg_something_went_wrong'])) ? $this->user_language[$this->user_selected]['lg_something_went_wrong'] : $this->default_language['en']['lg_something_went_wrong'];
            echo json_encode(array(
                'status' => false,
                'msg' => $message
            ));
        }
    }
    // ===================================================================================================
    public function bank_details()
    {
        removeTag($this->input->post());
        $params = $this->input->post();
        $user_id = $this->session->userdata('id');
        $user_currency = 'INR';
        if (!empty($params)) {
            $check_bank = $this->db->where('user_id', $user_id)->get('bank_account')->num_rows();
            $user_det = $this->db->where('id', $user_id)->get('providers')->row_array();
            $data = array(
                'user_id' => $user_id,
                'account_number' => $params['account_no'],
                'account_holder_name' => $user_det['name'],
                'bank_name' => $params['bank_name'],
                'bank_address' => $params['bank_address'],
                'sort_code' => $params['sort_code'],
                'routing_number' => $params['routing_number'],
                'account_ifsc' => $params['ifsc_code'],
                'pancard_no' => $params['pancard_no'],
                'paypal_account' => $params['paypal_id'],
                'paypal_email_id' => $params['paypal_email_id']
            );
            if ($check_bank > 0) {
                $result = $this->db->where('user_id', $user_id)->update('stripe_bank_details', $data);
            } else {
                $result = $this->db->insert('stripe_bank_details', $data);
            }
            if ($result == true) {
                $wallet_data = array(
                    'user_id' => $user_id,
                    'amount' => $params['amount'],
                    'currency_code' => $user_currency,
                    'status' => 1,
                    'transaction_status' => 0,
                    'request_payment' => $params['payment_type'],
                    'created_by' => $user_id,
                    'created_at' => date('Y-m-d H:i:s')
                );
                $amount = $this->db->insert('wallet_withdraw', $wallet_data);

                //echo json_encode($user_id);exit;
                if ($amount == true) {
                    $amount_withdraw = $this->Stripe_model->wallet_withdraw_flow($params['amount'], $user_currency, $user_id, 1);
                }
                $message = 'Amount Withdrawn Successfully';
                echo json_encode(array(
                    'status' => true,
                    'msg' => $message
                ));
            } else {
                $message = (!empty($this->user_language[$this->user_selected]['lg_something_went_wrong'])) ? $this->user_language[$this->user_selected]['lg_something_went_wrong'] : $this->default_language['en']['lg_something_went_wrong'];
                echo json_encode(array(
                    'status' => false,
                    'msg' => $message
                ));
            }
        }
    }
    // ===================================================================================================

    public function organization_settings()
    {
        $this->data['page'] = 'organization_settings';
        $this->data['details'] = $this->organization->getDetail($this->session->userdata('id'));
        $this->data['country'] = $this->db->select('id,country_name')->from('country_table')->get()->result_array();
        $this->data['city'] = $this->db->select('id,name')->from('city')->get()->result_array();
        $this->data['state'] = $this->db->select('id,name')->from('state')->get()->result_array();
        $this->data['user_address'] = $this->db->where('user_id', $this->session->userdata('id'))->get('user_address')->row_array();
        $this->data['profile'] = $this->service->get_profile($this->session->userdata('id'));
        $this->data['wallet'] = $this->api->get_wallet($this->session->userdata('chat_token'));
        $this->data['wallet_history'] = $this->api->get_wallet_history_info($this->session->userdata('chat_token'));
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function update_user()
    {
        if (!empty($_POST)) {
            removeTag($this->input->post());
            $uploaded_file_name = '';
            if (isset($_FILES) && isset($_FILES['profile_img']['name']) && !empty($_FILES['profile_img']['name'])) {
                $uploaded_file_name = $_FILES['profile_img']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/profile_img/', 'profile_img', time() . $filename);
                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                    $uploaded_file_name = $upload_sts['data']['file_name'];
                    if (!empty($uploaded_file_name)) {
                        $image_url = 'uploads/profile_img/' . $uploaded_file_name;
                        $table_data['profile_img'] = $this->image_resize(100, 100, $image_url, $filename);
                    }
                }
            }
            $id = $this->session->userdata('id');
            $table_data['mobileno'] = $this->input->post('mobileno');
            if (!empty($this->input->post('dob'))) {
                $table_data['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));
            } else {
                $table_data['dob'] = NULL;
            }

            $this->db->where('id', $id);
            if ($this->db->update('users', $table_data)) {
                $table_datas['address'] = $_POST['address'];
                if (!empty($_POST['state_id'])) {
                    $table_datas['state_id'] = $_POST['state_id'];
                }
                if (!empty($_POST['city_id'])) {
                    $table_datas['city_id'] = $_POST['city_id'];
                }
                if (!empty($_POST['country_id'])) {
                    $table_datas['country_id'] = $_POST['country_id'];
                }
                if (!empty($_POST['pincode'])) {
                    $table_datas['pincode'] = $_POST['pincode'];
                }

                // organization data insert or update ----->
                $orgData['director_name'] = $_POST['director_name'];
                if (!empty($_POST['reg_num'])) {
                    $orgData['company_number'] = $_POST['reg_num'];
                }
                if (!empty($_POST['business_name'])) {
                    $orgData['business_name'] = $_POST['business_name'];
                }
                if (!empty($_POST['business_addr'])) {
                    $orgData['address'] = $_POST['business_addr'];
                }
                if (!empty($_POST['method_state'])) {
                    $orgData['business_file'] = $_POST['method_state'];
                }
                if (!empty($_POST['proof_id'])) {
                    $orgData['proof_id_file'] = $_POST['proof_id'];
                }
                $this->organization->updateOrg($id, $orgData);
                //<--------

                $user_count = $this->db->where('user_id', $id)->count_all_results('user_address');

                if (count($table_datas) > 0) {
                    if ($user_count == 1) {
                        $this->db->where('user_id', $id);
                        $this->db->update('user_address', $table_datas);
                    } else {
                        $table_datas['user_id'] = $id;
                        $this->db->insert('user_address', $table_datas);
                    }
                    $this->session->set_flashdata('success_message', 'Profile updated successfully');
                    redirect(base_url() . "organization-settings");
                } else {
                    $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                    redirect(base_url() . "organization-settings");
                }
            } else {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                redirect(base_url() . "organization-settings");
            }
        }
    }

    public function getStateId()
    {
        $country_id = $_POST['country_id'];
        $result =  $this->db->select('id,name')->from('state')->where('country_id', $country_id)->get();
        $result = $result->result_array();
        print json_encode( $result );
    }
    public function getCityId()
    {
        $state_id = $_POST['state_id'];
        $result =  $this->db->select('id,name')->from('city')->where('state_id', $state_id)->get();
        $result = $result->result_array();
        print json_encode( $result );
    }
    public function organization_subscription()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if($this->session->userdata('you_are_appling_as') != C_YOUARE_ORGANIZATION){
            redirect(base_url());
        }
        $user_id = $this->session->userdata('id');
        $this->data['page'] = 'organization_subscription';
        $this->data['user_details'] = $user = $this->db->where('providers.id', $user_id)->join('provider_address', 'provider_address.provider_id=providers.id', 'left')->get('providers')->row_array();
        $this->data['paypal_gateway'] = settingValue('paypal_gateway');
        $this->data['braintree_key'] = settingValue('braintree_key');
        $razor_option = settingValue('razor_option');

        //echo "<pre>";print_r($razorpay_apikey);exit;
        if ($razor_option == 1)
        {
            $this->data['razorpay_apikey'] = settingValue('razorpay_apikey');
            $this->data['razorpay_apisecret'] = settingValue('razorpay_apisecret');
        }
        else if ($razor_option == 2)
        {
            $this->data['razorpay_apikey'] = settingValue('live_razorpay_apikey');
            $this->data['razorpay_apisecret'] = settingValue('live_razorpay_secret_key');
        }

        $this->data['wallet'] = $this->api->get_wallet($this->session->userdata('chat_token'));
        $this->data['wallet_history'] = $this->api->get_wallet_history_info($this->session->userdata('chat_token'));
        $this->load->vars($this->data);

        $this->load->view($this->data['theme'] . '/template');
    }
}
