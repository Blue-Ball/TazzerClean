<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends CI_Controller
{

    public $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model', 'admin');
        $this->load->model('common_model', 'common_model');
        $this->data['theme'] = 'admin';
        $this->data['model'] = 'categories';
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->data['user_role'] = $this->session->userdata('role');
    }

    public function index()
    {
        redirect(base_url('categories'));
    }

    public function categories()
    {
        $this->common_model->checkAdminUserPermission(2);
        $this->data['page'] = 'categories';

        $this->data['list_filter'] = $this->admin->categories_list();

        if ($this->input->post('form_submit'))
        {
            extract($_POST);
            $category = $this->input->post('category');
            $from_date = $this->input->post('from');
            $to_date = $this->input->post('to');
            $this->data['list'] = $this->admin->categories_list_filter($category, $from_date, $to_date);
        }
        else
        {
            $this->data['list'] = $this->admin->categories_list();
        }

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function add_categories()
    {
        $this->common_model->checkAdminUserPermission(2);
        if ($this->input->post('form_submit'))
        {
            removeTag($this->input->post());

            $uploaded_file_name = '';
            if (isset($_FILES) && isset($_FILES['category_image']['name']) && !empty($_FILES['category_image']['name']))
            {
                $uploaded_file_name = $_FILES['category_image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/category_images/', 'category_image', time() . $filename);

                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y')
                {
                    $uploaded_file_name = $upload_sts['data']['file_name'];

                    if (!empty($uploaded_file_name))
                    {
                        $image_url = 'uploads/category_images/' . $uploaded_file_name;
                        $table_data['category_image'] = $image_url;
                        $table_data['thumb_image'] = image_resize(50, 50, $image_url, 'thu_' . $uploaded_file_name, "category_images");
                    }
                }
            }

            if (isset($_FILES) && isset($_FILES['category_mobile_icon']['name']) && !empty($_FILES['category_mobile_icon']['name']))
            {
                $uploaded_file_name = $_FILES['category_mobile_icon']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/category_images/', 'category_mobile_icon', time() . $filename);
                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y')
                {
                    $uploaded_file_name = $upload_sts['data']['file_name'];
                    if (!empty($uploaded_file_name))
                    {
                        $image_url = 'uploads/category_images/' . $uploaded_file_name;
                        $table_data['category_mobile_icon'] = image_resize(60, 60, $image_url, 'ic_' . $filename, "category_images");
                    }
                }
            }

            $table_data['category_name'] = strip_tags($this->input->post('category_name'));
            $table_data['unique_id'] = $this->input->post('unique_id');
            $table_data['description'] = $this->input->post('description');
            $table_data['status'] = 1;
            $table_data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('categories', $table_data);
            $ret_id = $this->db->insert_id();
            if (!empty($ret_id))
            {
                $this->session->set_flashdata('success_message', 'Category added successfully');
                redirect(base_url() . "categories");
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                redirect(base_url() . "add-category");
            }
        }

        $this->data['page'] = 'add_categories';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function edit_categories($id)
    {
        $this->common_model->checkAdminUserPermission(2);
        $this->load->model('Categories_model','Categories');
        $category = $this->Categories->get_category(['id'=>$id]);
        $table_data = array();
        if ($this->input->post('form_submit'))
        {
            removeTag($this->input->post());

            $uploaded_file_name = '';
            if (isset($_FILES) && isset($_FILES['category_image']['name']) && !empty($_FILES['category_image']['name']))
            {
                $uploaded_file_name = $_FILES['category_image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/category_images/', 'category_image', time() . $filename);

                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y')
                {
                    $uploaded_file_name = $upload_sts['data']['file_name'];

                    if (!empty($uploaded_file_name))
                    {
                        $image_url = 'uploads/category_images/' . $uploaded_file_name;
                        $table_data['category_image'] = $image_url;
                        $table_data['thumb_image'] = image_resize(50, 50, $image_url, 'thu_' . $uploaded_file_name, "category_images");
                        @unlink(realpath($category['category_image']));
                        @unlink(realpath($category['thumb_image']));
                    }
                }
            }

            if (isset($_FILES) && isset($_FILES['category_mobile_icon']['name']) && !empty($_FILES['category_mobile_icon']['name']))
            {
                $uploaded_file_name = $_FILES['category_mobile_icon']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/category_images/', 'category_mobile_icon', time() . $filename);
                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y')
                {
                    $uploaded_file_name = $upload_sts['data']['file_name'];
                    if (!empty($uploaded_file_name))
                    {
                        $image_url = 'uploads/category_images/' . $uploaded_file_name;
                        $table_data['category_mobile_icon'] = image_resize(60, 60, $image_url, 'ic_' . $uploaded_file_name, "category_images");
                        @unlink(realpath($category['category_mobile_icon']));
                    }
                }
            }

            $id = $this->input->post('category_id');
            $table_data['category_name'] = $this->input->post('category_name');
            $table_data['unique_id'] = $this->input->post('unique_id');
            $table_data['description'] = $this->input->post('description');
            $table_data['status'] = 1;
            $this->db->where('id', $id);
            if ($this->db->update('categories', $table_data))
            {
                $this->session->set_flashdata('success_message', 'Category updated successfully');
                redirect(base_url() . "categories");
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                redirect(base_url() . "categories");
            }
        }

        $this->data['page'] = 'edit_categories';
        $this->data['categories'] = $this->admin->categories_details($id);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function check_category_name()
    {
        $category_name = $this->input->post('category_name');
        $id = $this->input->post('category_id');
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('replace(category_name," ","")=replace("' . $category_name . '"," ","")');

            $this->db->where('id !=', $id);
            $this->db->from('categories');
            $result = $this->db->get()->num_rows();
        }
        else
        {
            $this->db->select('*');
            $this->db->where('replace(category_name," ","")=replace("' . $category_name . '"," ","")');
            $this->db->from('categories');
            $result = $this->db->get()->num_rows();
        }
        if ($result > 0)
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

    public function delete_category()
    {
        $this->common_model->checkAdminUserPermission(2);
        $id = $this->input->post('category_id');
        $table_data['status'] = 0;
        $this->db->where('id', $id);
        if ($this->db->update('categories', $table_data))
        {
            $this->db->where('category', $id);
            $query1 = $this->db->get('subcategories');
            $result = $query1->result_array();
            $this->db->where('category', $id);
            if ($this->db->update('subcategories', $table_data))
            {
                for ($i = 0;$i < $query1->num_rows();$i++)
                {
                    $this->db->where('subcategory', $result[$i]['id']);
                    $this->db->update('services', $table_data);
                }
                $this->db->where('category', $id);
                $this->db->update('services', $table_data);
                $this->session->set_flashdata('success_message', 'Category,Sub-category and Services deleted successfully');
                echo 1;
            }
            else
            {
                $this->session->set_flashdata('success_message', 'Category deleted successfully');
                echo 1;
            }

        }
        else
        {
            $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
            echo 1;
        }
    }

    public function subcategories()
    {
        $this->common_model->checkAdminUserPermission(3);
        $this->data['page'] = 'subcategories';
        $this->data['model'] = 'subcategories';

        if ($this->input->post('form_submit'))
        {
            extract($_POST);
            $category = $this->input->post('category');
            $subcategory = $this->input->post('subcategory');
            $from_date = $this->input->post('from');
            $to_date = $this->input->post('to');
            $this->data['list'] = $this->admin->subcategory_filter($category, $subcategory, $from_date, $to_date);
        }
        else
        {
            $this->data['list'] = $this->admin->subcategories_list();
        }
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function add_subcategories()
    {
        $this->common_model->checkAdminUserPermission(3);

        if ($this->input->post('form_submit'))
        {

            removeTag($this->input->post());
            $uploaded_file_name = '';
            if (isset($_FILES) && isset($_FILES['subcategory_image']['name']) && !empty($_FILES['subcategory_image']['name']))
            {
                $uploaded_file_name = $_FILES['subcategory_image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/subcategory_images/', 'subcategory_image', time() . $filename);
                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y')
                {
                    $uploaded_file_name = $upload_sts['data']['file_name'];
                    if (!empty($uploaded_file_name))
                    {
                        $image_url = 'uploads/subcategory_images/' . $uploaded_file_name;
                        $table_data['subcategory_image'] = $image_url;
                        $table_data['thumb_image'] = image_resize(50, 50, $image_url, "thu_". $uploaded_file_name, "subcategory_images");
                    }
                }
            }
            $table_data['subcategory_name'] = $this->input->post('subcategory_name');
            $table_data['category'] = $this->input->post('category');
            //  $table_data['cat_id'] = $this->input->post('cat_id');
            $table_data['created_at'] = date('Y-m-d H:i:s');
            $table_data['status'] = 1;
            if ($this->db->insert('subcategories', $table_data))
            {
                $this->session->set_flashdata('success_message', 'Sub Category added successfully');
                redirect(base_url() . "subcategories");
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                redirect(base_url() . "add-subcategory");
            }
        }

        $this->data['page'] = 'add_subcategories';
        $this->data['model'] = 'subcategories';
        $this->data['categories'] = $this->admin->categories_list();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function edit_subcategories($id)
    {
        $this->common_model->checkAdminUserPermission(3);
        
        if ($this->input->post('form_submit'))
        {
            $this->load->model("SubcategoryModel");
            $subCategory = $this->SubcategoryModel->get($id);
            removeTag($this->input->post());

            $uploaded_file_name = '';
            if (isset($_FILES) && isset($_FILES['subcategory_image']['name']) && !empty($_FILES['subcategory_image']['name']))
            {
                $uploaded_file_name = $_FILES['subcategory_image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/subcategory_images/', 'subcategory_image', time() . $filename);

                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y')
                {
                    $uploaded_file_name = $upload_sts['data']['file_name'];
                    if (!empty($uploaded_file_name))
                    {
                        $image_url = 'uploads/subcategory_images/' . $uploaded_file_name;
                        $table_data['subcategory_image'] = $image_url;
                        $table_data['thumb_image'] = image_resize(50, 50, $image_url, "thu_". $uploaded_file_name, "subcategory_images");
                        @unlink(realpath($subCategory['subcategory_image']));
                        @unlink(realpath($subCategory['thumb_image']));
                    }
                }
            }
            $id = $this->input->post('subcategory_id');
            $table_data['subcategory_name'] = $this->input->post('subcategory_name');
            $table_data['category'] = $this->input->post('category');
            // $table_data['cat_id'] = $this->input->post('cat_id');
            $table_data['status'] = 1;
            $this->db->where('id', $id);
            if ($this->db->update('subcategories', $table_data))
            {
                $this->session->set_flashdata('success_message', 'Sub Category updated successfully');
                redirect(base_url() . "subcategories");
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                redirect(base_url() . "subcategories");
            }
        }

        $this->data['page'] = 'edit_subcategories';
        $this->data['model'] = 'subcategories';
        $this->data['subcategories'] = $this->admin->subcategories_details($id);
        $this->data['categories'] = $this->admin->categories_list();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function check_subcategory_name()
    {
        $category = $this->input->post('category');
        $subcategory_name = $this->input->post('subcategory_name');
        $id = $this->input->post('subcategory_id');
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('category', $category);
            $this->db->where('replace(subcategory_name," ","")=replace("' . $subcategory_name . '"," ","")');
            $this->db->where('id !=', $id);
            $this->db->from('subcategories');
            $result = $this->db->get()->num_rows();
        }
        else
        {
            $this->db->select('*');
            $this->db->where('category', $category);
            $this->db->where('replace(subcategory_name," ","")=replace("' . $subcategory_name . '"," ","")');
            $this->db->from('subcategories');
            $result = $this->db->get()->num_rows();
        }
        if ($result > 0)
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

    public function delete_subcategory()
    {
        $this->common_model->checkAdminUserPermission(3);
        $id = $this->input->post('category_id');
        $table_data['status'] = 0;
        $this->db->where('id', $id);
        if ($this->db->update('subcategories', $table_data))
        {
            $this->db->where('subcategory', $id);
            $this->db->update('services', $table_data);
            $this->session->set_flashdata('success_message', 'Sub-category and Services deleted successfully');
            echo 1;
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
            echo 1;
        }
    }

}
