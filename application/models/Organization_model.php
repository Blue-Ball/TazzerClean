<?php defined('BASEPATH') or exit('No direct script access allowed');

class Organization_model extends CI_Model
{

    function __construct()
    {
        // Set table name 
        $this->table = 'organization';
    }

    public function getRevenueAmount($company_id)
    {
        $this->db->select("org.*, sum(revenue.amount) as amount, revenue.date as re_date");
        $this->db->from('revenue');
        $this->db->join('users', ' users.id = revenue.user', 'LEFT');
        $this->db->join('organization org', 'org.user_id = users.id', 'LEFT');
        $this->db->where('org.company_number', $company_id);
        $this->db->where("month(now()) = month(revenue.date)");
        $this->db->group_by('day(revenue.date)');
        $this->db->order_by('revenue.date');
        $result = $this->db->get();
        $result = $result->result_object();
        return $result;
    }

    public function getOrganizationByUserId($user_id)
    {
        $select_field  = "*";
        $this->db->select($select_field);
        $this->db->from($this->table);
        $this->db->where("status = 1");
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }



    function getOrgData($id)
    {
        $this->db->select('director_name, company_number, business_name, business_file, address as org_address, proof_id_file');
        $this->db->from('organization');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    function getDetail($Id)
    {
        $this->db->select("users.*");
        $this->db->from('users');
        $this->db->where('id', $Id);
        $this->db->where('status', 1);
        $this->db->where('you_are_appling_as', 10);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $userdata =  $query->row_array();
            $orgData = $this->getOrgData($Id);
            if (!is_array($orgData)) $orgData = [];
            return array_merge($userdata, $orgData);
        } else {
            return false;
        }
    }

    function updateOrg($Id, $data)
    {
        $user_count = $this->db->where('user_id', $Id)->count_all_results('organization');
        if (count($data) > 0) {
            if ($user_count == 1) {
                $this->db->where('user_id', $Id);
                $this->db->update('organization', $data);
            } else {
                $data['user_id'] = $Id;
                $this->db->insert('organization', $data);
            }

            return true;
        } else {
            return false;
        }
    }
}
