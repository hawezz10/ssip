<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mreport extends CI_Model {
	
        function __construct() {
					parent::__construct();
					$this->load->database();
     
                  }	   
	public function getPLby($period){
		$this->db->select('*');
		$this->db->like('AccR_Date',$period,'LEFT');
		$query = $this->db->get('viewpl');
		return $query;
    }

}
