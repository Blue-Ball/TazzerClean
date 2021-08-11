<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @modifier Leo
*/
class Booking_model extends CI_Model
{
  private $primaryKey = 'id';
  private $name = "book_service";

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    date_default_timezone_set('UTC');
  }

  public function get_subscription_list()
  {
    return $this->db->get_where('subscription_fee', array(
      'status' => 1
    ))->result_array();
  }
  public function get_service($id)
  {
    return $this->db->get_where('services', array(
      'status' => 1,
      'id' => $id
    ))->row_array();
  }

  public function get_my_subscription()
  {
    $user_id = $this->session->userdata('id');
    return $this->db->get_where('subscription_details', array(
      'subscriber_id' => $user_id
    ))->row_array();
  }

  /**
   * @author Leo: booking Service
   */
  public function bookService($inputs)
  {
    $bookingData = array(
      'service_id' => $inputs['service_id'],
      'provider_id' => $inputs['provider_id'],
      'user_id' => $inputs['user_id'],
      'service_date' => $inputs['service_date'],
      'service_time' => $inputs['service_time'],
      'currency_code' => $inputs['currency_code'],
      'amount' => $inputs['amount'],
      'location' => $inputs['location'],
      'latitude' => $inputs['latitude'],
      'longitude' => $inputs['longitude'],
      'notes' => $inputs['notes'],
      'first_name' => $inputs['first_name'],
      'last_name' => $inputs['last_name'],
      'country' => $inputs['country'],
      'town' => $inputs['town'],
      'street_addr_1' => $inputs['street_addr_1'],
      'street_addr_2' => $inputs['street_addr_2'],
      'email' => $inputs['email'],
      'phone' => $inputs['phone']
    );

    $bookingData['request_date'] = date('Y-m-d');
    $bookingData['request_time'] = date('H:i:s');
    $bookingData['updated_on'] = date('Y-m-d H:i:s');

    $this->db->insert($this->name, $bookingData);
    return $this->db->insert_id();
  }

}