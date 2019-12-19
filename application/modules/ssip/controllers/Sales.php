<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/ssip_Controller.php");
class Sales extends ssip_Controller {
                    function __construct() {
					parent::__construct();
					$this->load->model('Msales');
     
                  }	   
	public function index()
	{ 
		$data['page'] = $this->config->item('ssip') . "view_sales";
		$data['breadcrumb'] = '<h5 class="page-title pull-left">Sales</h5>
								<ul class="breadcrumbs pull-left">
										<li><a href="index.html">Home</a></li>
										<li><span>Sales</span></li>									
									</ul>';
		$data['title'] = $this->config->item('title');
		$this->load->view($this->_container, $data);
               
	}
	
	public function submit(){		
		//AMBIL POST DATA
		$insertdata = $_POST;
		//UNTUK CEK APAKAH INSERT BARU ATAU UPDATE
		$opr = $insertdata['opr'];
		$prc = $insertdata['AccR_Price'];
		$qty = $insertdata['AccR_Qty'];
		$amnt = $prc * $qty;
		$insertdata['AccR_Amount'] = $amnt;
		//SAVE NEW
		if($opr=='new'){
			//REMOVE ARRAY OPR
			\array_splice($insertdata,1,1);
			//REMOVE ARRAY ID (AUTOINCREMENT)
			\array_splice($insertdata,0,1);	

			$this->Msales->insertData($insertdata,'tblsales');
			}else{

				//UPDATE
				//ambil id nya dulu
				$id = $insertdata['AccR_ID'];
				//REMOVE ARRAY OPR DARI DATA
				\array_splice($insertdata,1,1);
				//REMOVE ARRAY ID (AUTOINCREMENT)
				\array_splice($insertdata,0,1);	;	
				//CLAUSE WHERE
				$clause = array('AccR_ID' => $id);
				$this->Msales->updateData($insertdata,$clause,'tblsales');
				
			}
		header("Location: ".base_url()."ssip/Sales");
		die();
	}

	public function delete(){		
		$id = $_POST['id'];
		$clause = array('AccR_ID'=> $id);
		$this->Msales->deleteData($clause,'tblsales');
		echo json_encode(array('success' => TRUE));	
	}

	public function posting(){		
		$id = $_POST['id'];
		$clause = array('AccR_ID'=> $id);
		$updatedata = array('AccR_TrxStatus' => 'P');
		$this->Msales->updateData($updatedata,$clause,'tblsales');
		echo json_encode(array('success' => TRUE));	
	}


	//FUNGSI SalesLIST TABLE
	public function SalesList()
    {
        $list = $this->Msales->get_datatables();
		$data = array();
	
		$no = $_POST['start'];
	
        foreach ($list as $sales) {
			$stlb = "";
			$label = "";
			$st = $sales->AccR_TrxStatus;
			$id = $sales->AccR_ID;
			$td = $sales->AccR_Date;
			if($st=='R'){
				$label = $label."<button title='Edit' onclick='editRecord($id)' class='btn btn-primary btn-xs'><i class='fa fa-edit'></i></button> ";
				$label = $label."<button title='Delete' onclick='deleteRecord($id)' class='btn btn-danger btn-xs'><i class='fa fa-trash'></i></button> ";
				$label = $label."<button title='Posting' onclick='postingRecord($id)' class='btn btn-success btn-xs'><i class='fa fa-forward'></i></button> ";
				$stlb = "<label class='badge badge-danger'>Recorded</label>";
			}else{
				$stlb = "<label class='badge badge-success'>Posted</label>";
			}
			$row = array();
            $row[] = $label;
            $row[] = $id;
            $row[] = $stlb;
            $row[] = $sales->Customer_Name;
            $row[] = $sales->AccR_Item;
			$row[] = $sales->AccR_Price;
			$row[] = $sales->AccR_Qty;
			$row[] = $sales->AccR_Price*$sales->AccR_Qty;
			$row[] = $sales->AccR_Date;
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Msales->count_all(),
                        "recordsFiltered" => $this->Msales->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
	}

	public function getSalesById(){
		$id = $_POST['id'];
		$q1 = $this->Msales->getSalesById($id);	
		if($q1->num_rows() > 0 ){
				foreach($q1->result() as $sales){
					$row = array();
					$st = $sales->AccR_TrxStatus;
					$id = $sales->AccR_ID;
					$td = $sales->AccR_Date;
					$row[] = $id;
					$row[] = $st;
					$row[] = $sales->Customer_Name;
					$row[] = $sales->AccR_Item;
					$row[] = $sales->AccR_Price;
					$row[] = $sales->AccR_Qty;
					$row[] = $sales->AccR_Price*$sales->AccR_Qty;
					$row[] = $sales->AccR_Date;
					
					if($st == "R"){
						$row[] = '<label class="badge badge-danger">Recorded</label>';
					}else{
						$row[] = '<label class="badge badge-success">Posted</label>';
					}
					$data = $row;
				}
			}
		echo json_encode($data);
		}
}
