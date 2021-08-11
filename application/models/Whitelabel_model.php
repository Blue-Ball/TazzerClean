<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Whitelabel_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getWhitelabelInfo($id)
    {

        $query = $this->db->select('*')->from('whitelabel')->where('id', $id)->get();
        if ($query !== false && $query->num_rows() > 0) {
            $user = $query->result_array();
        }
        
        if (!empty($user)) {
            $this->db->select('*');
            $this->db->from('whitelalbel_categories');
            $this->db->where('whila_id', $id);
            $categories = $this->db->get()->result_array();
            $user[0]['categories'] = $categories;
            return $user[0];
        }
        return [];
    }
    public function find_whitelabels($where)
    {

        $query = $this->db->select('*')->from('whitelabel')->where($where)->get();
        if ($query !== false && $query->num_rows() > 0) {
            $users = $query->result_array();
        }

        if (!empty($users)) {
            return $users;
        }
        return [];
    }
    public function get_whitelabel_fromip($httpHost)
    {
        $whila = $this->db->query('SELECT * FROM whitelabel WHERE hostaddress LIKE "%' . $httpHost . '%" ORDER BY id DESC');
        if ($whila !== false && $whila->num_rows() > 0) {
            $whila = $whila->result_array();
        }
        else {
            $whila = [];
        }
        if (count($whila) > 0) {
            $result = $whila[0];
            $where = ['whila_id' => $result['id']];
            $category_infos = $this->db->select('*')->from('whitelalbel_categories')->where($where)->get()->result_array();

            foreach ($category_infos as $rows) {
                if (!empty($rows['categ_id']))
                    $result['cate_ids'][] = $rows['categ_id'];
                if (!empty($rows['subcate_id']))
                    $result['subcate_ids'][] = $rows['subcate_id'];
            }
            return $result;
        }
        return false;
    }
    public function addWhitelabel($param = array(),$categories, $subcategories){
        $param['created_at'] = date("Y-m-d H:i:s");
        $this->db->insert('whitelabel', $param);
        $id = $this->db->insert_id();
        if(isset($categories) && !empty($categories)){
            foreach($categories as $category){
                $whitelabel_categories = array('whila_id' =>$id, 'categ_id'=>$category, 'status'=>1, 'created_at' => date("Y-m-d H:i:s"));
                $this->db->insert('whitelalbel_categories', $whitelabel_categories);
            }
        }
        if(isset($subcategories) && !empty($subcategories)){
            foreach($subcategories as $subcategory){
                $whitelabel_categories = array('whila_id' =>$id, 'subcate_id'=>$subcategory, 'status'=>1, 'created_at' => date("Y-m-d H:i:s"));
                $this->db->insert('whitelalbel_categories', $whitelabel_categories);
            }
        }
        return $id;
    }

    public function updateWhitelabel($id, $params = array(), $categories, $subcategories) {
        $param['updated_at'] = date("Y-m-d H:i:s");

        $this->db->where('whila_id',$id);
        $this->db->delete('whitelalbel_categories');

        if(isset($categories) && !empty($categories)){
            foreach($categories as $category){
                $whitelabel_categories = array('whila_id' =>$id, 'categ_id'=>$category, 'status'=>1, 'created_at' => date("Y-m-d H:i:s"));
                $this->db->insert('whitelalbel_categories', $whitelabel_categories);
            }
        }
        if(isset($subcategories) && !empty($subcategories)){
            foreach($subcategories as $subcategory){
                $whitelabel_categories = array('whila_id' =>$id, 'subcate_id'=>$subcategory, 'status'=>1, 'created_at' => date("Y-m-d H:i:s"));
                $this->db->insert('whitelalbel_categories', $whitelabel_categories);
            }
        }

        $this->db->where('id', $id);
        return $this->db->update('whitelabel', $params);
    }

    public function delete_row($id)
    {
        $this->db->where('id',$id);
        $this->db->delete('whitelabel');
    }
}
