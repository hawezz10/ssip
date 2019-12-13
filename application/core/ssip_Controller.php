<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class ssip_Controller extends MX_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('typography');
        $this->load->config('config');
        // Set container variable       
        $this->_container = $this->config->item('ssip') . "layout.php";
        $this->_modules = $this->config->item('modules_locations');
        log_message('debug', 'ssip_Controller class loaded');
    }

} 