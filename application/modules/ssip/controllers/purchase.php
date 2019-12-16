<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class purchase extends ssip_Controller {
                    function __construct() {
					parent::__construct();
					$this->load->model('mPurchase');
     
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
		//SAVE NEW
		if($opr=='new'){
			//REMOVE ARRAY OPR
			\array_splice($insertdata,1,1);
			//REMOVE ARRAY ID (AUTOINCREMENT)
			\array_splice($insertdata,0,1);	

			$this->mPurchase->insertData($insertdata,'tblPurchase');
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
				$this->mPurchase->updateData($insertdata,$clause,'tblPurchase');
				
			}
		header("Location: ".base_url()."ssip/purchase");
		die();
	}

	public function delete(){		
		$id = $_POST['id'];
		$clause = array('AccP_ID'=> $id);
		$this->mPurchase->deleteData($clause,'tblPurchase');
		echo json_encode(array('success' => TRUE));	
	}

	public function posting(){		
		$id = $_POST['id'];
		$clause = array('AccP_ID'=> $id);
		$updatedata = array('AccP_TrxStatus' => 'P');
		$this->mPurchase->updateData($updatedata,$clause,'tblPurchase');
		echo json_encode(array('success' => TRUE));	
	}


	//FUNGSI PURCHASELIST TABLE
	public function purchaseList()
    {
        $list = $this->mPurchase->get_datatables();
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
                        "recordsTotal" => $this->mPurchase->count_all(),
                        "recordsFiltered" => $this->mPurchase->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
	}

	public function getPurchaseById(){
		$id = $_POST['id'];
		$q1 = $this->mPurchase->getPurchbyId($id);	
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
