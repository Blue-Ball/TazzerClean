<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    protected $whitelabelInfo = false;
    public $MY = NULL;

    function __construct()
    {
        parent::__construct();

        $this->load->model('Whitelabel_model', 'whitelabel_model');
        $this->whitelabelInfo = $this->whitelabel_model->get_whitelabel_fromip($_SERVER['HTTP_HOST']);
        $this->MY = &get_instance();
    }
    public function WLA()
    {
        return $this->whitelabelInfo;
    }
}
