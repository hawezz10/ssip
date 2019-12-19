<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/ssip_Controller.php");
class Purchase extends ssip_Controller {
                    function __construct() {
					parent::__construct();
					$this->load->model('Mpurchase');
     
                  }	   
	public function index()
	{ 
		$data['page'] = $this->config->item('ssip') . "view_purchase";
		$data['breadcrumb'] = '<h5 class="page-title pull-left">Purchasing</h5>
								<ul class="breadcrumbs pull-left">
										<li><a href="index.html">Home</a></li>
										<li><span>Purchase</span></li>									
									</ul>';
		$data['title'] = $this->config->item('title');
		$this->load->view($this->_container, $data);
               
	}
	
	public function submit(){		
		//AMBIL POST DATA
		$insertdata = $_POST;
		//UNTUK CEK APAKAH INSERT BARU ATAU UPDATE
		$opr = $insertdata['opr'];
		$prc = $insertdata['AccP_Price'];
		$qty = $insertdata['AccP_Qty'];
		$amnt = $prc * $qty;
		$insertdata['AccP_Amount'] = $amnt;
		//SAVE NEW
		if($opr=='new'){
			//REMOVE ARRAY OPR
			\array_splice($insertdata,1,1);
			//REMOVE ARRAY ID (AUTOINCREMENT)
			\array_splice($insertdata,0,1);	

			$this->Mpurchase->insertData($insertdata,'tblpurchase');
			}else{
				//UPDATE
				//ambil id nya dulu
				$id = $insertdata['AccP_ID'];
				//REMOVE ARRAY OPR DARI DATA
				\array_splice($insertdata,1,1);
				//REMOVE ARRAY ID (AUTOINCREMENT)
				\array_splice($insertdata,0,1);	;	
				//CLAUSE WHERE
				$clause = array('AccP_ID' => $id);
				$this->Mpurchase->updateData($insertdata,$clause,'tblpurchase');
				
			}
		header("Location: ".base_url()."ssip/Purchase");
		die();
	}

	public function delete(){		
		$id = $_POST['id'];
		$clause = array('AccP_ID'=> $id);
		$this->Mpurchase->deleteData($clause,'tblpurchase');
		echo json_encode(array('success' => TRUE));	
	}

	public function posting(){		
		$id = $_POST['id'];
		$clause = array('AccP_ID'=> $id);
		$updatedata = array('AccP_TrxStatus' => 'P');
		$this->Mpurchase->updateData($updatedata,$clause,'tblpurchase');
		echo json_encode(array('success' => TRUE));	
	}


	//FUNGSI PURCHASELIST TABLE
	public function purchaseList()
    {
        $list = $this->Mpurchase->get_datatables();
		$data = array();
	
		$no = $_POST['start'];
	
        foreach ($list as $purchase) {
			$stlb = "";
			$label = "";
			$st = $purchase->AccP_TrxStatus;
			$id = $purchase->AccP_ID;
			$td = $purchase->AccP_Date;
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
            $row[] = $purchase->Supplier_Name;
            $row[] = $purchase->AccP_Item;
			$row[] = $purchase->AccP_Price;
			$row[] = $purchase->AccP_Qty;
			$row[] = $purchase->AccP_Price*$purchase->AccP_Qty;
			$row[] = $purchase->AccP_Date;
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mpurchase->count_all(),
                        "recordsFiltered" => $this->Mpurchase->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
	}

	public function getPurchaseById(){
		$id = $_POST['id'];
		$q1 = $this->Mpurchase->getPurchbyId($id);	
		if($q1->num_rows() > 0 ){
				foreach($q1->result() as $purchase){
					$row = array();
					$st = $purchase->AccP_TrxStatus;
					$id = $purchase->AccP_ID;
					$td = $purchase->AccP_Date;
					$row[] = $id;
					$row[] = $st;
					$row[] = $purchase->Supplier_Name;
					$row[] = $purchase->AccP_Item;
					$row[] = $purchase->AccP_Price;
					$row[] = $purchase->AccP_Qty;
					$row[] = $purchase->AccP_Price*$purchase->AccP_Qty;
					$row[] = $purchase->AccP_Date;
					
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
