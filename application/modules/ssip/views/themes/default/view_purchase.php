<div class="col-lg-12 col-md-12" style="margin-top:10px;">
<button type="button" class="btn btn-primary" data-toggle="modal" onclick='newPurchase()' >
<i class='fa fa-plus'></i> New Purchase</button>
</div>
<div class="row" style="overflow-y:scroll;margin-top:10px;">
    <div class="col-lg-12 col-md-12">

    <table id='listPurchase' class='table table-advance table-bordered table-striped tblPurch'>
        <thead>
        <tr><th></th><th>Trans_ID</th>
        <th>Status</th>
        <th>Supplier</th>  
        <th>Item</th>
        <th>Qty</th>
        <th>Price</th>
        <th>TotalAmount</th>  
        <th>Trans.Date</th> 
        </tr>
        </thead>
    <tbody></tbody>
    </table>
    </div>
</div>

<div id="purchModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Purchase</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button> 
      </div>
      <div class="modal-body">
      <table class='table'>
        <form id='formPurchase' name='formPurchase' autocomplete="off" action='<?= base_url(); ?>ssip/purchase/submit' method='POST' enctype="multipart/form-data">
        <tr><td>ID</td><td><input type="text" name="AccP_ID" id="AccP_ID" readonly style='width:70px;'/>
        <input name='opr' id='opr' style='width:30px;' readonly/>
        <input name='AccP_TrxStatus' id='AccP_TrxStatus' style='width:30px;' readonly/><span id='stdesc'></span>
        </td></tr>
        <tr><td>SupplierName</td><td><input type="search" name="Supplier_Name" style='width:300px;'/></td>
        <tr><td>Item</td><td><input type="text" name="AccP_Item" id="AccP_Item" style='width:200px;'/></td></tr> 
        <tr><td>Price</td><td><input type="number" name="AccP_Price" id="AccP_Price"/></td></tr>
        <tr><td>Qty</td><td><input type="number" name="AccP_Qty" id="AccP_Qty"/></td></tr>
        <tr><td>Total</td><td><input type="number" name="AccP_Amount" id="AccP_Amount" readonly/></td></tr>
        <tr><td>Trans.Date</td><td><input type="text" name="AccP_Date" id="datepicker" class='dtpicker' style="cursor:pointer;disabled:true;"/></td></tr>
        </form>
        <tr><td colspan='2'><button id='submitBtn' onclick="savePurchase()">Submit</button></td></tr>       
        </table>
      </div>   
      </div>
  </div>
</div>

<script>
var table;
$(document).ready(function() {
     var uri = '<?= base_url(); ?>ssip/purchase/PurchaseList';
      table = $('#listPurchase').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "width":'100%',
        //"responsive":true,
        // Load data for the table's content from an Ajax source
            "ajax": {
                "url": uri,
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

            });    
  });

  $( function() {
    $( ".dtpicker" ).datepicker({dateFormat:'yy-mm-dd'});
  } );

  function savePurchase(){
    var r = confirm("Save Purchase Data?");
    if(r==true){   
        $("#submitBtn").attr("disabled",true);
        $("#formPurchase").submit();
    }
  }

  function newPurchase(){
      $("#formPurchase")[0].reset();
      $('#purchModal').modal('show');
      $('#AccP_ID').val('');
      $('#opr').val('new');
      $('#AccP_TrxStatus').val('R');
      $("#stdesc").html('');
  }

  function editRecord(id){
    var url = '<?= base_url(); ?>ssip/purchase/getPurchaseById';
        $.ajax({
        type: "POST",
            url: url,
            data: {id:id},
            dataType: 'JSON',
            success: function (data) {
                $("[name='AccP_TrxStatus']").val(data[1]);
                $("[name='Supplier_Name']").val(data[2]);
                $("[name='AccP_Item']").val(data[3]);
                $("[name='AccP_Price']").val(data[4]);
                $("[name='AccP_Qty']").val(data[5]);
                $("[name='AccP_Amount']").val(data[6]);
                $("[name='AccP_Date']").val(data[7]);
                $("#stdesc").html(data[1]);
                }
            });
      $("#formPurchase")[0].reset();
      $('#purchModal').modal('show');
      $('#AccP_ID').val(id);
      $('#opr').val('edit');
        }

        function deleteRecord(id){
          var r = confirm("Delete Purchase Record?")
          if(r==true){
            if(confirm("Are you sure? This cannot be undone!")){
              var url = '<?= base_url(); ?>ssip/purchase/delete';
              $.ajax({
              type: "POST",
                  url: url,
                  data: {id:id},
                  dataType: 'JSON',
                  success: function (data) {
                    alert('Data id ' + id + ' deleted!');
                    location.reload();
                      
                      }
                  });
            }
          }
        }
          function postingRecord(id){
          var r = confirm("Posting Purchase Record?")
          if(r==true){
            if(confirm("Are you sure? This cannot be undone!")){
              var url = '<?= base_url(); ?>ssip/purchase/posting';
              $.ajax({
              type: "POST",
                  url: url,
                  data: {id:id},
                  dataType: 'JSON',
                  success: function (data) {
                    alert('Data id ' + id + ' posted!');
                    location.reload();
                      
                      }
                  });
            }
          }
        }
    </script>
  <style>
  .tblPurch  tr td{
    font-size:0.9em;
  }
  </style>  