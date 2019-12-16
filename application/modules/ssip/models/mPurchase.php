<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mPurchase extends CI_Model {

	var $tblPurch = 'tblpurchase';
    var $cOrdPurch = array(null,'AccP_ID','AccP_TrxStatus','Supplier_Name','AccP_Item','AccP_Price','AccP_Qty','AccP_Amount','AccP_Date'); //set column field database for datatable orderable
    var $cSrcPurch = array('AccP_ID','AccP_TrxStatus','Supplier_Name','AccP_Item','AccP_Price','AccP_Qty','AccP_Amount','AccP_Date'); //set column field database for datatable searchable 
	var $oPurch = array('AccP_ID' => 'desc'); // default order 
	
        function __construct() {
					parent::__construct();
					$this->load->database();
     
                  }	   
	public function insertData($data, $tbl)
	{
        $this->db->insert($tbl, $data);
	}
	public function updateData($data,$clause, $tbl)
	{
		$this->db->where($clause);
        $this->db->update($tbl, $data);
    }
    public function deleteData($clause, $tbl)
	{
		$this->db->where($clause);
        $this->db->delete($tbl);
    }

	public function getPurchbyId($id){
		$this->db->select('*');
		$this->db->where('AccP_ID',$id);
		$query = $this->db->get('tblpurchase');
		return $query;
    }
    
	private function _get_datatables_query()
    {
         
        $this->db->from($this->tblPurch);
 
        $i = 0;
     
        foreach ($this->cSrcPurch as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->cSrcPurch) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->cOrdPurch[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->oPurch))
        {
            $order = $this->oPurch;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all()
    {
        $this->db->from($this->tblPurch);
        return $this->db->count_all_results();
    }
}
