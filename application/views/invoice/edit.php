<?php  
    $action_url =  ($action == 'new') ? base_url() ."invoice/create" : base_url()."invoice/edit/".$invoice['invoice_id'];
?>
<link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery.textcomplete.css" rel="stylesheet">
<script src="<?= base_url();?>js/jquery.textcomplete.min.js"></script>
<!--script src="<?= base_url();?>js/select2-4.js"></script-->
<script src="<?= base_url();?>js/jquery.autosize.min.js"></script>
<!-- <script src="<?= base_url();?>js/ckeditor/ckeditor.js"></script> -->
<script>
  var burl = "<?= base_url() ?>";
 /* var names = <?//= json_encode($desc_name); ?>;*/

  
  <?php if( isset($details) ) { ?>
    var detail_arr = <?= json_encode($details); ?>;   
  <?php } ?>
  
   <?php if( isset($payment_details) ) { ?>
    var payment_detail_arr = <?= json_encode($payment_details); ?>;   
  <?php } ?>

 
  
  
  
   $( document ).ready(function() {
		//	loadsubcategory();
            
   /*  $("#date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#delivery-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    });  */

    $("#invoice-date").datepicker({
        format: "yyyy-mm-dd",
        autoclose :true,
    }); 
	/* 
	 $("#payment-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    });  */

	
    //$("#stock-id").select2();

 //   $('#description').autosize();

    //$("#quotation-id").select2({
   //   minimumInputLength: 2,
    //  ajax: {
     //   url: "<?= base_url(); ?>" + "quotation/aj_getQuotations",
     //   dataType: 'json',
       // data: function (term, page) {
       //   return {
       //     q : term
     //     };
     //   },
      //  processResults: function (data, page) {
      //    return { results: data };
        //},
      //},  
    //});

   /*  $('#description').textcomplete([
      { // tech companies
        words: names,
        match: /\b(\w{2,}(?:\s\w*)?)$/,
        search: function (term, callback) {
            var regexp;
            if (/\s\w/.test(term)) {
                regexp = new RegExp('^(' + term + '|' + term.split(' ')[1] + ')', 'i')
            } else {
                regexp = new RegExp('^' + term, 'i')
            }
            callback($.map(names, function (name) {
                return regexp.test(name) ? name : null;
            }));
        },
        index: 1,
        replace: function (word) {
            return word + ' ';
        }
      }
    ]); */

    $('#sub-total, #gst,#discount').change(function(e) { 
      calculateGST();
    });

    /* $('#quotation-id').change(function(e) { 
      if( $('#quotation-id').val() != 0 ) {
        getQuotationinfo();  
      }
    }); */

    <?php if($this->session->userdata('role_id') != 1) { ?>
      $('.commission-area').hide();
    <?php } ?>

   // $("#service-id").select2({
     //   minimumInputLength: 2,
       // ajax: {
         // url: "<?= base_url(); ?>" + "service/aj_getServices",
          //dataType: 'json',
          //data: function (term, page) {
           // return {
             // q : term
            //};
          //},
          //processResults: function (data, page) {
          //  return { results: data };
          //},
        //},  
    //});
/* 	$('select[name="client_id"]').on('change', function() {
		getClientinfo();
            var stateID = $(this).val();
			 //alert('aj_getContactinfo/'+stateID);
            if(stateID) {
                $.ajax({
                    url: burl + 'invoice/aj_getContactinfo/'+stateID,
                    type: "post",
                    dataType: "json",
                    success:function(data) {
					
                        $('select[name="contact"]').empty();
						$('select[name="contact"]').append('<option value="">'+ 'Select Contact' +'</option>');
						for(var x = 0; x < data.count; x++){
							$('select[name="contact"]').append('<option value="'+ data.data[x].client_detail_id +'">'+ data.data[x].name +'</option>');
						}
						
                    }
                });
            }else{
                $('select[name="contact"]').append('<option value="">'+ 'Select Contact' +'</option>');
            }
        });  */
	
	
	
	
	
    /* $('#service-id').change(function(e) { 
      if( $('#service-id').val() != 0) {
        // alert( $('#service-id').val() ) ;
        $.ajax({
            type: "POST",
            url: burl + 'service/aj_getDescription/' + $('#service-id').val(), 
            data: { },
            success: function(data){  
              var result = $.parseJSON(data);
              if(result['status'] == 'success') {
                $('#description').val( result['description'] );
                $('#description').trigger('autosize.resize');
              }
              else {
                alert(result['msg']);
              }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
              alert("ERROR!!!");           
            } 
        });
      }
    }); */
    
  });

  function resetForm() {
    $("#detail-form").each(function(){
      this.reset();
    });

    $('#description').trigger('autosize.resize');
    $('#detail-add').show();
    $('#detail-update').hide();

  }

  function resetFormPD() {
   
	 $("#detail-form-pd").each(function(){
      this.reset();
    });

	$('#detail-add-pd').show();
    $('#detail-update-pd').hide();
  }

  function getClientinfo() {
    $.ajax({
        type: "POST",
        url: burl + "quotation/aj_getClientinfo/" + $("#client-id").val(), 
        data: { },
        success: function(data){  
          var result = $.parseJSON(data);
          if(result['status'] == 'success') {
			  //console.log(data);
			 // alert();
            $("#contact").val(result['contact']);
            $("#designation").val(result['designation']);
            $("#department").val(result['department']);
            $("#address").val(result['address']);
          }
          else {
            alert(result['msg']);
            $("#designation").val('');  
            $("#department").val('');
            $("#contact").val('');  
            $("#address").val('');  
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          alert("ERROR!!!");        
        } 
    });  
  }
  
  function calculateGST() {
    var sub_total = parseFloat($('#sub-total').val());
    var gst = parseFloat($('#gst').val());
	var discount = $('#discount').val();
	//var add_gst =$('#total').val() - $('#sub-total').val();
	
	if(isNaN($('#total').val())){
		$('#total').val() = $('#total').val();
	}
	
	
    if( $.isNumeric( $('#gst').val() ) && $('#gst').val() > 0) {
      var gst_amount = ( gst != '' && gst > 0 ) ? parseFloat(myFixed(( (sub_total - discount) / 100 ) * gst, 2))  : '0.00' ;
      $('#total').val(myFixed(sub_total  + gst_amount, 2));
	  $('#add-gst').val(gst_amount);
    }
    else {
      $('#total').val(myFixed(sub_total , 2)); 
    }     
  }

  function getQuotationinfo() {
    $.ajax({
        type: "POST",
        url: burl + "invoice/aj_getQuotationinfo/" + $("#quotation-id").val(), 
        data: { },
        success: function(data){  
          var result = $.parseJSON(data);
          if(result['status'] == 'success') {
            $('#job-title').val(result['job_title']);
            $('#client').val(result['client']);
            $("#contact").val(result['contact']);
            $("#designation").val(result['designation']);
            $("#department").val(result['department']);
            $("#address").val(result['address']);
            $("#rep").val(result['rep']);
            $("#commission").val(result['default_commission']);
          }
          else {
            alert(result['msg']);
            $('#job-title').val();
            $('#client').val('');
            $("#designation").val('');  
            $("#department").val('');
            $("#contact").val('');  
            $("#address").val('');
            $("#rep").val('');
            $("#commission").val('');  
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          alert("ERROR!!!");        
        } 
    });  
  }
  
  
   function loadsubcategory() {
   getClientinfo();
            var stateID = $("#client-id").val();
			 //alert('aj_getContactinfo/'+stateID);
            if(stateID) {
                $.ajax({
                    url: burl + 'invoice/aj_getContactinfo/'+stateID,
                    type: "post",
                    dataType: "json",
                    success:function(data) {
					
                        $('select[name="contact"]').empty();
						$('select[name="contact"]').append('<option value="">'+ 'Select Contact' +'</option>');
						for(var x = 0; x < data.count; x++){
							$('select[name="contact"]').append('<option value="'+ data.data[x].client_detail_id +'">'+ data.data[x].name +'</option>');
						}
						
                    }
                });
            }else{
                $('select[name="contact"]').append('<option value="">'+ 'Select Contact' +'</option>');
            }
}
  
  
</script>

<?php 

/* if($is_quotation==true){
	
	$page = 'quotation';
	$readonly= '';
}
else{
	$page = 'invoice';
	$readonly= 'readonly';
} */


if ( $action == "new" ) { ?>
  <script src="<?= base_url();?>js/invoice_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/invoice_edit.js"></script>
<?php } ?>
<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= base_url().'invoice'; ?>">Announcement</a></li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?> Invoice</li>
		</ol>
		<div class="box">
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Invoice</h3>
			 <div class="pull-right">
                      <a href="<?= base_url().'invoice'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
            <div class="col-md-6">
              <div class="form-group">
                <input type="hidden" id="hid-submitted" name="hid_submitted" value="1" />
                <input type="hidden" id="hid-invoice-id" name="hid_invoice_id" value="<?= (isset($invoice['invoice_id'])) ? $invoice['invoice_id'] : ''; ?>" />
                <label for="name" class="col-md-3 control-label">Invoice No</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="invoice-no" name="invoice_no" placeholder="Enter Invoice No" value="<?= isset($_POST['invoice_no']) ? $_POST['invoice_no'] : ( isset($invoice['invoice_no']) ? $invoice['invoice_no'] : '') ; ?>" >
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
              </div> 
              <div class="clearfix sp-margin-sm"></div>  
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Invoice Date</label>
                <div class="col-md-7">
                   <input type="text" class="form-control input-sm" id="invoice-date" name="invoice_date" placeholder="Enter Invoice Date" value="<?= isset($_POST['invoice_date']) ? $_POST['invoice_date'] : ( isset($invoice['invoice_date']) ? $invoice['invoice_date'] : ''); ?>" >     
                </div>
                <div class="col-md-1 req-star">*</div>
              </div>
              <div class="clearfix sp-margin-sm"></div>  
              <div class="form-group">
                  <label for="name" class="col-md-3 control-label">Transaction No.</label>
                  <div class="col-md-7">
                   <select class="form-control input-sm" name="transact_id" id="transact-id" placeholder="Select Transaction No">
                      <option value="" selected>Select Transaction No</option>
                       <?php if( isset($transactions) && $transactions != '') { ?>
                            <?php foreach($transactions as $transaction) {
                                   //echo "<option value='". $client['client_id']. "' >" . $client['company'] . "</option>";
                                if( (isset($_POST['transact_id']) && ($_POST['transact_id'] == $transaction['case_id']))|| (isset($invoice['transaction_id']) && ($invoice['transaction_id'] == $transaction['case_id'])) ) {
                                  echo "<option selected value='". $transaction['case_id'] . "' >" . $transaction['case_no'] . "</option>";
                                }
                                else {  
                                  echo "<option value='". $transaction['case_id']. "' >" . $transaction['case_no'] . "</option>";
                                }

                            } ?>
                        <?php } ?>
                    </select>
                  </div>
              </div>
              
              <div class="clearfix sp-margin-sm"></div>  
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Entry No</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="entry-no" name="entry_no" placeholder="Enter Entry No" value="<?= isset($_POST['entry_no']) ? $_POST['entry_no'] : ( isset($invoice['entry_no']) ? $invoice['entry_no'] : ''); ?>" >  
                </div>
              </div>
             
              <div class="clearfix sp-margin-sm"></div> 
            </div>
            <div class="col-md-6">
               <div class="form-group">
                 <label for="name" class="col-md-3 control-label">Deliver To</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="deliver-to" name="deliver_to" placeholder="Enter Deliver To" value="<?= isset($_POST['deliver_to']) ? $_POST['deliver_to'] : ( isset($invoice['deliver_to']) ? $invoice['deliver_to'] : ''); ?>">  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Address</label>
                <div class="col-md-7">
                  <textarea class="form-control input-sm" id="address" name="address" placeholder="Enter Address"><?= isset($_POST['address']) ? $_POST['address'] : ( isset($invoice['address']) ? $invoice['address'] : ''); ?></textarea>
                </div>
              </div>
			  <div class="clearfix sp-margin-sm"></div> 
			   <div class="form-group">
                <label for="name" class="col-md-3 control-label">Attention</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="attention" name="attention" placeholder="Enter Attention" value="<?= isset($_POST['attention']) ? $_POST['attention'] : ( isset($invoice['attention']) ? $invoice['attention'] : ''); ?>">  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
			  <div class="form-group">
                <label for="name" class="col-md-3 control-label">Customer No</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="customer-no" name="customer_no" placeholder="Enter Customer No" value="<?= isset($_POST['customer_no']) ? $_POST['customer_no'] : ( isset($invoice['customer_no']) ? $invoice['customer_no'] : ''); ?>">  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Associate</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="associate" name="associate" placeholder="Enter Associate" value="<?= isset($_POST['associate']) ? $_POST['associate'] : ( isset($invoice['associate']) ? $invoice['associate'] : ''); ?>">  
                </div>
              </div>
            </div>
            <div class="clearfix sp-margin-lg"></div>
            <div class ="box-body">
			<div class="panel panel-default tab-panel">
              <div class="panel-heading">
                  Invoice Detail
              </div>
              <div class="panel-body">
                <div class="row-fluid table-responsive" id="tbl-detail">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" id='detail-table'>
                          <thead>
                            <tr>
                              <th style="width:30%">Description</th>
                              <th style="width:20%">Qty</th>
                              <th style="width:15%">Amount</th>
                              <th style="width:35%">Action</th>
                            </tr>
                          </thead>
                          <tbody> 
                            <?php if( isset($details) ) { 
                              foreach($details as $key=>$pd) {
                            ?>
                              <!-- <tr class="id-<//?= $pd['invoice_detail_id'] ?>">
                                <td class="small-tbl-column"><?= $pd['no'] ?></td>
                                <td><?= nl2br($pd['description']) ?></td>
                                <td><?= $pd['qty'] ?></td>
                                <td><?= $pd['unit'] ?></td>
                                <td><?= $pd['amount'] ?></td>
                                <td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td>
                              </tr> -->
                            <?php
                              }
                            } ?>
                          </tbody>
                          <tfoot>
                              <tr id="detail-row">
                              <form id="detail-form">
                                
                                <td> 
								<input type="hidden" id="hid-edit-id" name="hid_edit_id" />
                                  <select class="form-control input-sm" name="service_id" id="service-id" placeholder="Select Services" />  
                                    <option value="" selected>Select Services</option>
                                     <option value="" selected>Select Services</option>
                                    <option value="test">Test Service</option>
                                    <?php /* if( isset($services) && $services != '') { ?>
                                        <?php foreach($services as $service) {
                                               echo "<option value='". $service['service_id']. "' >" . $service['name'] . "</option>";
                                        } ?>
                                    <?php } */ ?>
                                  </select>
                                  <div class="clearfix sp-margin-sm"></div> 
                                  <textarea class="form-control input-sm" id="description" name="description" placeholder="Enter Description"></textarea>
                                </td>
                                <td><input type="text" class="form-control input-sm" id="qty" name="qty" placeholder="Enter Qty" value=""></td>
                               
                                <td><input type="text" class="form-control input-sm" id="amount" name="amount" placeholder="amount" value="" /></td>
                                <td>
                                  <span id="detail-add"><a href="#" id="ico-add"><i class="fa fa-plus ico"></i></a></span>
                                  <span id="detail-update" style="display: none;"><a href="#" id="ico-update" ><i class="fa fa-save ico"></i></a> / <a href="#" id="ico-cancel" ><i class="fa fa-eraser ico"></i></a></span>
                                </td>
                              </form>
                              </tr>
                              <tr id="total-row">
                                <td colspan="2" class='text-right'>SUBTOTAL</td>
                                <td class='text-right'><input type="text" class="form-control input-sm" id="sub-total" name="sub_total" placeholder="Enter Sub Total" value="<?= isset($_POST['sub_total']) ? $_POST['sub_total'] : ( isset($invoice['sub_total']) ? $invoice['sub_total'] : '') ; ?>" /></td>
                                <td></td>
                              </tr>
							  <tr>
                                 <td colspan="2" class='text-right'>Discount</td>
                                <td class='text-right'><input type="text" class="form-control input-sm" id="discount" name="discount" placeholder="Enter Discount" value="<?= isset($_POST['discount']) ? $_POST['discount'] : ( isset($invoice['discount']) ? $invoice['discount'] : '') ; ?>" /></td>
								<td></td>
                              </tr>
                              <tr>
                                <td colspan="2" class='text-right'>GST</td>
                                <td class='text-right'><input type="text" class="form-control input-sm" id="gst" name="gst" placeholder="Enter GST" value="<?= isset($_POST['gst']) ? $_POST['gst'] : ( isset($invoice['gst']) ? $invoice['gst'] : '') ; ?>" /></td>
                                <td></td>
                              </tr>
							  <tr>
                                <td colspan="2" class='text-right'>ADD GST</td>
                                <td class='text-right'><input type="text" class="form-control input-sm" id="add-gst" name="add_gst" placeholder="Enter GST" value="" /></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td colspan="2" class='text-right'>Grand Total</td>
                                <td class='text-right'><input type="text" class="form-control input-sm" id="total" name="total" placeholder="Enter Grand Total" value="<?= isset($_POST['total']) ? $_POST['total'] : ( isset($invoice['total']) ? $invoice['total'] : '') ; ?>"/></td>
                                <td></td>
                              </tr>
                          </tfoot>
                        </table>      
                    </div>
                </div>    
              </div>
            </div>
			</div>
            <div class="clearfix sp-margin-sm"></div>
            <div class="form-group">
                <div class="col-xs-12 text-right">
                   <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
                </div>
            </div>
          <!-- </form> -->
      </div>
</section>

