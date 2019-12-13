<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends ssip_Controller {
                    function __construct() {
                    parent::__construct();
     
                  }	   
	public function index()
	{
                $data['page'] = $this->config->item('ssip') . "home";
				$data['breadcrumb'] = '<h5 class="page-title pull-left">Dashboard</h5>
										<ul class="breadcrumbs pull-left">
												<li><a href="index.html">Home</a></li>
												<li><span>Dashboard</span></li>
											</ul>';
                $data['title'] = $this->config->item('title');
                $this->load->view($this->_container, $data);
	}
	
	public function login()
	{
                $data['page'] = $this->config->item('ssip') . "home";
				$data['breadcrumb'] = '<h5 class="page-title pull-left">Dashboard</h5>
										<ul class="breadcrumbs pull-left">
												<li><a href="index.html">Home</a></li>
												<li><span>Dashboard</span></li>
											</ul>';
                $data['title'] = $this->config->item('title');
                $this->load->view($this->_container, $data);
	}
       
}
