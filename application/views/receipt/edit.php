<?php  
    $action_url =  ($action == 'new') ? base_url() ."receipt/create" : base_url()."receipt/edit/".$receipt['receipt_id'];
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
            calculateGST();
   /*  $("#date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#delivery-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    });  */

    $("#receipt-date").datepicker({
        format: "yyyy-mm-dd",
        autoclose :true,
    }); 
	
	 $("#payment-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

	
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

    $('#sub-total').change(function(e) { 
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
                    url: burl + 'receipt/aj_getContactinfo/'+stateID,
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
    var gst = '7.00';//parseFloat($('#gst').val());
	//var discount = $('#discount').val();
	////var add_gst =$('#total').val() - $('#sub-total').val();
	
	if(isNaN($('#total').val())){
		$('#total').val() = $('#total').val();
	}
	
	
    if( $.isNumeric( $('#sub-total').val() ) && $('#sub-total').val() > 0) {
      var gst_amount = ( gst != '' && gst > 0 ) ? parseFloat(myFixed(( sub_total / 100 ) * gst, 2))  : '0.00' ;
      $('#total').val(myFixed(sub_total  + gst_amount, 2));  
      $('#gst').val(gst_amount);  
    }
    else {
      $('#total').val(myFixed(sub_total , 2)); 
	  
    }    
  }

 /*  function getQuotationinfo() {
    $.ajax({
        type: "POST",
        url: burl + "receipt/aj_getQuotationinfo/" + $("#quotation-id").val(), 
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
  } */
  
  
/* 	function loadsubcategory() {
		getClientinfo();
        var stateID = $("#client-id").val();
		 //alert('aj_getContactinfo/'+stateID);
        if(stateID) {
            $.ajax({
                url: burl + 'receipt/aj_getContactinfo/'+stateID,
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
	} */
  
  
</script>

<?php 

/* if($is_quotation==true){
	
	$page = 'quotation';
	$readonly= '';
}
else{
	$page = 'receipt';
	$readonly= 'readonly';
} */


if ( $action == "new" ) { ?>
  <script src="<?= base_url();?>js/receipt_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/receipt_edit.js"></script>
<?php } ?>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<ol class="breadcrumb">
				<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
				<li><a href="<?= base_url().'receipt'; ?>">Receipt</a></li>
				<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?> Receipt</li>
			</ol>
			<div class="box">
			  <div class="box-header">
				 <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Receipt</h3>
				 <div class="pull-right">
						  <a href="<?= base_url().'receipt'; ?>" class="btn btn-default btn-flat">Back</a>
				 </div>
			   </div><!-- /.box-header -->
			</div>
		</div>
		<div class="col-md-6">
		  <div class="form-group">
			<input type="hidden" id="hid-submitted" name="hid_submitted" value="1" />
			<input type="hidden" id="hid-receipt-id" name="hid_receipt_id" value="<?= (isset($receipt['receipt_id'])) ? $receipt['receipt_id'] : ''; ?>" />
			<label for="name" class="col-md-4 control-label">Receipt No</label>
			<div class="col-md-7">
				<input type="text" class="form-control input-sm" id="receipt-no" name="receipt_no" placeholder="Enter receipt No" value="<?= isset($_POST['receipt_no']) ? $_POST['receipt_no'] : ( isset($receipt['receipt_no']) ? $receipt['receipt_no'] : '') ; ?>" >
			</div>
			<!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
			<div class="col-md-1 req-star">*</div>
		  </div> 
		  <div class="clearfix sp-margin-sm"></div>  
		  <div class="form-group">
			<label for="name" class="col-md-4 control-label">Receipt Date</label>
			<div class="col-md-7">
			   <input type="text" class="form-control input-sm" id="receipt-date" name="receipt_date" placeholder="Enter Receipt Date" value="<?= isset($_POST['receipt_date']) ? $_POST['receipt_date'] : ( isset($receipt['date']) ? $receipt['date'] : ''); ?>" >     
			</div>
			<div class="col-md-1 req-star">*</div>
		  </div>
		  <div class="clearfix sp-margin-sm"></div>  
		  <div class="form-group">
			  <label for="name" class="col-md-4 control-label">Transaction No.</label>
			  <div class="col-md-7">
			   <select class="form-control input-sm" name="transact_id" id="transact-id" placeholder="Select Transaction No">
				  <option value="" selected>Select Transaction No</option>
				   <?php if( isset($transactions) && $transactions != '') { ?>
						<?php foreach($transactions as $transaction) {
							   //echo "<option value='". $client['client_id']. "' >" . $client['company'] . "</option>";
							if( (isset($_POST['transact_id']) && ($_POST['transact_id'] == $transaction['case_id']))|| (isset($receipt['transaction_id']) && ($receipt['transaction_id'] == $transaction['case_id'])) ) {
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
			<label for="name" class="col-md-4 control-label">Deliver To</label>
			<div class="col-md-7">
			  <input type="text" class="form-control input-sm" id="deliver-to" name="deliver_to" placeholder="Enter Deliver To" value="<?= isset($_POST['deliver_to']) ? $_POST['deliver_to'] : ( isset($receipt['deliver_to']) ? $receipt['deliver_to'] : ''); ?>">  
			</div>
		  </div>
		  <div class="clearfix sp-margin-sm"></div> 
		  <div class="form-group">
			<label for="name" class="col-md-4 control-label">Attention</label>
			<div class="col-md-7">
			  <input type="text" class="form-control input-sm" id="attention" name="attention" placeholder="Enter Attention" value="<?= isset($_POST['attention']) ? $_POST['attention'] : ( isset($receipt['attention']) ? $receipt['attention'] : ''); ?>">  
			</div>
		  </div>
		  <div class="clearfix sp-margin-sm"></div>  
		  <div class="form-group">
			<label for="name" class="col-md-4 control-label">Address</label>
				<div class="col-md-7">
				  <textarea class="form-control input-sm" id="address" name="address" placeholder="Enter Address"><?= isset($_POST['address']) ? $_POST['address'] : ( isset($receipt['address']) ? $receipt['address'] : ''); ?></textarea>
				</div>
			</div>
		</div>
		<div class="col-md-6">
		  <div class="form-group">
			<label for="name" class="col-md-4 control-label">In Payment Commission for</label>
			<div class="col-md-7">
			<input type="text" class="form-control input-sm" id="payment-commission" name="payment_commission" placeholder="Enter Payment Commission" value="<?= isset($_POST['payment_commission']) ? $_POST['payment_commission'] : ( isset($receipt['payment_commission_for']) ? $receipt['payment_commission_for'] : ''); ?>" >  
			</div>
		  </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
			<label for="name" class="col-md-4 control-label">Sales Person</label>
			<div class="col-md-7">
			   <select class="form-control input-sm" name="user_id" id="user-id" placeholder="Select Sales Person" required>
                    <option value="" selected>Select Sales Person</option>
                    <?php if( isset($users) && $users != '') { ?>
                        <?php foreach($users as $user) {
                                if( (isset($_POST['user_id']) && ($_POST['user_id'] == $user['user_id'])) || (isset($receipt['sales_person_id']) && ($receipt['sales_person_id'] == $user['user_id'])) ) {
                                  echo "<option selected value='". $user['user_id'] . "' >" . $user['name'] . "</option>";
                                }
                                else {  
                                  echo "<option value='". $user['user_id']. "' >" . $user['name'] . "</option>";
                                }
                        } ?>
                    <?php } ?>
                </select>     
			</div>
		  </div>
		  <div class="clearfix sp-margin-sm"></div> 
		  <div class="form-group">
			<label for="name" class="col-md-4 control-label">Sub Total</label>
			<div class="col-md-7">
			  <input type="text" class="form-control input-sm" id="sub-total" name="sub_total" placeholder="Enter Sum" value="<?= isset($_POST['sub_total']) ? $_POST['sub_total'] : ( isset($receipt['sum']) ? $receipt['sum'] : ''); ?>">  
			</div>
		  </div>
		  <div class="clearfix sp-margin-sm"></div> 
		  <div class="form-group">
			<label for="name" class="col-md-4 control-label">GST (7%)</label>
			<div class="col-md-7">
			  <input type="text" class="form-control input-sm" id="gst" name="gst" placeholder="Enter GST" value="<?= isset($_POST['gst']) ? $_POST['gst'] : ( isset($receipt['add_gst']) ? $receipt['add_gst'] : ''); ?>">  
			  <!--input type="text" class="form-control input-sm" id="add-gst" name="add_gst" placeholder="Enter GST" value=""-->  
			</div>
		  </div>
		   <div class="clearfix sp-margin-sm"></div> 
		  <div class="form-group">
			<label for="name" class="col-md-4 control-label">Total</label>
			<div class="col-md-7">
			  <input type="text" class="form-control input-sm" id="total" name="total" placeholder="Enter Total" value="" readonly />  
			</div>
		  </div>
		   <div class="clearfix sp-margin-sm"></div> 
		  <div class="form-group">
			<label for="name" class="col-md-4 control-label">Cheque</label>
			<div class="col-md-7">
			  <input type="text" class="form-control input-sm" id="cheque" name="cheque" placeholder="Enter Cheque" value="<?= isset($_POST['cheque']) ? $_POST['cheque'] : ( isset($receipt['cheque']) ? $receipt['cheque'] : ''); ?>">  
			</div>
		  </div>
		   <div class="clearfix sp-margin-sm"></div> 
		
		</div>
		<div class="clearfix sp-margin-sm"></div>
		<div class="form-group">
			<div class="col-xs-12 text-right">
			   <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
			</div>
		</div>
	</div>
</section>


  