<?php  
    $action_url =  ($action == 'new') ? base_url() ."transaction/create" : base_url()."transaction/edit/".$transaction['case_id'];
?>
<link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery.textcomplete.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.theme.css" rel="stylesheet">
<script src="<?= base_url();?>js/jquery.textcomplete.min.js"></script>
<!--script src="<?= base_url();?>js/select2-4.js"></script-->
<script src="<?= base_url();?>js/jquery.autosize.min.js"></script>
<script src="<?= base_url();?>js/jquery-ui.js"></script>
<!-- <script src="<?= base_url();?>js/ckeditor/ckeditor.js"></script> -->
<script>
  var burl = "<?= base_url() ?>";
  var user_id;

   <?php if( isset($details) ) { ?>
    var detail_arr = <?= json_encode($details); ?>;   
  <?php } ?>
  
  
  $(function() {

    $("#transact-date").datepicker({
        format: "yyyy-mm-dd",
        autoclose :true,
    }); 

    $("#option-date").datepicker({
        format: "yyyy-mm-dd",
        autoclose :true,
    }); 

    $("#contract-date").datepicker({
        format: "yyyy-mm-dd",
        autoclose :true,
    }); 

    //$("#stock-id").select2();

	
    $('#property-id').change(function(e) { 
      if( $('#property-id').val() != '' ) {
        getPropertyinfo();  
      }
    });

    /*$("#client-id").select2({
      minimumInputLength: 2,
      ajax: {
        url: "<?= base_url(); ?>" + "client/aj_getClients",
        dataType: 'json',
        data: function (term, page) {
          return {
            q : term
          };
        },
        processResults: function (data, page) {
          return { results: data };
        },
      },  
    });*/

   <?php if($this->session->userdata('role_id') != 1) { ?>
	  user_id = <?php echo $this->session->userdata('user_id') ?>;
      //var xx = $('#user-id').val(JSON.stringify(x));
	  $('.commission-area').hide();
	  
	  
	  //alert(x);
    <?php }else{?>
	  user_id = $('#user-id').val();
	
	<?php

	}
	?>
	

	function getPropertyinfo() {
		$.ajax({
			type: "POST",
			url: burl + "transaction/aj_getPropertyinfo/" + $("#property-id").val(), 
			data: { },
			success: function(data){  
			  var result = $.parseJSON(data);
			  if(result['status'] == 'success') {
				  console.log(data);
				 // alert();
				 console.log("transaction/aj_getPropertyinfo/" + $("#property-id").val());
				$("#property-type").val(result['category']);
			   // $("#designation").val(result['designation']);
			//    $("#department").val(result['department']);
				//$("#address").val(result['address']);
			  }
			  else {
				  console.log("transaction/aj_getPropertyinfo/" + $("#property-id").val());
				alert(result['msg']);
				//$("#designation").val('');  
				//$("#department").val('');
				//$("#contact").val('');  
				$("#property-type").val('');  
			  }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
			  alert("ERROR!!!");        
			} 
		});  
	}
	
	
	
	
	
	
	

  });

  
	 function resetForm() {
		$("#detail-form").each(function(){
		  this.reset();
		});

		//$('#description').trigger('autosize.resize');
		$('#detail-add').show();
		$('#detail-update').hide();

	  }
	

</script>

<?php if ( $action == "new" ) { ?>
  <script src="<?= base_url();?>js/transaction_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/transaction_edit.js"></script>
<?php } ?>
<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<div class="box">
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Transaction</h3>
			 <div class="pull-right">
               <a href="<?= base_url().'transaction'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
            <div class="col-md-6">
              <div class="form-group">
                <input type="hidden" id="hid-transact-id" name="hid_transact_id" value="<?= (isset($transaction)) ? $transaction['case_id'] : ''; ?>" />
                <label for="name" class="col-md-4 control-label">Case No.</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="case-no" name="case_no" placeholder="Enter Case Number" value="<?= isset($_POST['case_no']) ? $_POST['case_no'] : ( isset($transaction['case_no']) ? $transaction['case_no'] : $case_no) ; ?>"/>
					
			   </div>
           
                <div class="col-md-1 req-star">*</div>
              </div> 
              <div class="clearfix sp-margin-sm"></div>
			  <div class="form-group">
                <label for="name" class="col-md-4 control-label">Property Title</label>
                <div class="col-md-7">
                    <select class="form-control input-sm" name="property_id" id="property-id" placeholder="Select Property" required>
                      <option value="" selected>Select Property</option>
                       <?php if( isset($properties) && $properties != '') { ?>
                            <?php foreach($properties as $property) {
                                   //echo "<option value='". $client['client_id']. "' >" . $client['company'] . "</option>";
                                //if( (isset($_POST['property_id']) && ($_POST['property_id'] == $property['property_id'])) ) {
                                if( (isset($_POST['property_id']) && ($_POST['property_id'] == $property['property_id']))|| (isset($transaction['property_id']) && ($property['property_id'] == $transaction['property_id'])) ) {
									echo "<option selected value='". $property['property_id'] . "' >" . $property['property_title'] . "</option>";
                                }
                                else {  
									echo "<option value='". $property['property_id']. "' >" . $property['property_title'] . "</option>";
                                }

                            } ?>
                        <?php } ?>
                    </select>  
                </div>
                <!-- <div class="col-md-4"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
              </div> 
				<div class="clearfix sp-margin-sm"></div>			  
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Amount</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="amount" name="amount" placeholder="Enter Amount" value="<?= isset($_POST['amount']) ? $_POST['amount'] : ( isset($transaction['amount']) ? $transaction['amount'] : '') ; ?>">      
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div>  
             <div class="form-group">
                <label for="name" class="col-md-4 control-label">Transaction Date</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="transact-date" name="transact_date" placeholder="Enter Transaction Date" value="<?= isset($_POST['transact_date']) ? $_POST['transact_date'] : ( isset($transaction['transact_date']) ? $transaction['transact_date'] : '') ; ?>">
                </div>
                <!-- <div class="col-md-4"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
              </div>  
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Property Type</label>
                <div class="col-md-7">
					
					<!--select class="form-control input-sm" name="district" id="district" placeholder="Select District">
						<option value="" selected>Select District</option>
						<!--option value="District 1" <?= ( (isset($_POST['district']) && $_POST['district'] == 'District 1') || (isset($property['district']) && $property['district'] == 'District 1') ) ? 'selected' : ''; ?> >District 1</option>
						<option value="District 2" <?= ( (isset($_POST['district']) && $_POST['district'] == 'District 2') || (isset($property['district']) && $property['district'] == 'District 2') ) ? 'selected' : ''; ?> >District 2</option-->
					<!--/select-->
				
                  <input type="text" class="form-control input-sm" id="property-type" name="property_type" placeholder="Enter Property Type" value="<?= isset($_POST['property_type']) ? $_POST['property_type'] : ( isset($transaction['property_type']) ? $transaction['property_type'] : '') ; ?>" />
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
			  <div class="form-group">
                <label for="name" class="col-md-4 control-label">Address</label>
                <div class="col-md-7">
                  <textarea class="form-control input-sm" id="address" name="address" placeholder="Enter Address"><?= isset($_POST['address']) ? $_POST['address'] : ( isset($transaction['address']) ? $transaction['address'] : ''); ?></textarea>
                </div>
              </div>
			   <div class="clearfix sp-margin-sm"></div>
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Price</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="price" name="price" placeholder="Enter Price" value="<?= isset($_POST['price']) ? $_POST['price'] : ( isset($transaction['price']) ? $transaction['price'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div>
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">GST</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="gst" name="gst" placeholder="Enter GST" value="<?= isset($_POST['gst']) ? $_POST['gst'] : ( isset($transaction['gst']) ? $transaction['gst'] : ''); ?>" />  
                </div>
              </div>
			  
              <div class="clearfix sp-margin-sm"></div>
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Owner Name/Company</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="owner-name" name="owner_name" placeholder="Enter Owner Name" value="<?= isset($_POST['owner_name']) ? $_POST['owner_name'] : ( isset($transaction['owner_name']) ? $transaction['owner_name'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
            </div>
			
			
			
			<!--right side-->
            <div class="col-md-6">
			  <div class="form-group">
                <label for="name" class="col-md-4 control-label">Buyer Name</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="buyer-name" name="buyer_name" placeholder="Enter Buyer Name" value="<?= isset($_POST['buyer_name']) ? $_POST['buyer_name'] : ( isset($transaction['buyer_name']) ? $transaction['buyer_name'] : ''); ?>" />  
                </div>
              </div>
			  <div class="clearfix sp-margin-sm"></div>
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Co-Broke Agent</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="co-broke-agent" name="co_broke_agent" placeholder="Enter Co-Broke Agent" value="<?= isset($_POST['co_broke_agent']) ? $_POST['co_broke_agent'] : ( isset($transaction['co_broke_agent']) ? $transaction['co_broke_agent'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Co-Broke Agency</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="co-broke-agency" name="co_broke_agency" placeholder="Enter Co-Broke Agency" value="<?= isset($_POST['co_broke_agency']) ? $_POST['co_broke_agency'] : ( isset($transaction['co_broke_agency']) ? $transaction['co_broke_agency'] : ''); ?>" />  
                  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Contract Date</label>
                <div class="col-md-7">
                 <input type="text" class="form-control input-sm" id="contract-date" name="contract_date" placeholder="Enter Contract Date" value="<?= isset($_POST['contract_date']) ? $_POST['contract_date'] : ( isset($transaction['contract_date']) ? $transaction['contract_date'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
               <div class="form-group">
                <label for="name" class="col-md-4 control-label">Option Date</label>
                <div class="col-md-7">
                 <input type="text" class="form-control input-sm" id="option-date" name="option_date" placeholder="Enter Option Date" value="<?= isset($_POST['option_date']) ? $_POST['option_date'] : ( isset($transaction['option_date']) ? $transaction['option_date'] : ''); ?>" />  
                </div>
              </div>
			  <div class="clearfix sp-margin-sm"></div> 
               <div class="form-group commission-area">
                <label for="name" class="col-md-4 control-label">Sale Person</label>
                <div class="col-md-7">
					<select class="form-control input-sm" name="user_id" id="user-id" placeholder="Select Rep" required>
						<option value="" selected>Select Rep</option>
						<?php if( isset($users) && $users != '') { ?>
							<?php foreach($users as $user) {
									if( (isset($_POST['user_id']) && ($_POST['user_id'] == $user['user_id'])) || (isset($transaction['user_id']) && ($transaction['user_id'] == $user['user_id'])) ) {
									  echo "<option selected value='". $user['user_id'] . "' >" . $user['name'] . "</option>";
									}
									else {  
									  echo "<option value='". $user['user_id']. "' >" . $user['name'] . "</option>";
									}
							} ?>
						<?php } ?>
                  </select> 

                 <!--input type="text" class="form-control input-sm" id="sale-person" name="sale_person" placeholder="Enter Sale Perosn" value="<?= isset($_POST['option_date']) ? $_POST['option_date'] : ( isset($transaction['option_date']) ? $transaction['option_date'] : ''); ?>" /-->  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Commission</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="commission" name="commission" placeholder="Enter Commission" value="<?= isset($_POST['commission']) ? $_POST['commission'] : ( isset($transaction['commission']) ? $transaction['commission'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm "></div> 
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Co-Broke Commission</label>
                <div class="col-md-7">
				  <input type="text" class="form-control input-sm" id="co-broke-commission" name="co_broke_commission" placeholder="Enter Co-Broke Commission" value="<?= isset($_POST['co_broke_commission']) ? $_POST['co_broke_commission'] : ( isset($transaction['co_broke_commission']) ? $transaction['co_broke_commission'] : ''); ?>" />                  
				</div>
              </div>
              <div class="clearfix sp-margin-sm "></div> 
              <div class="form-group">
                <label for="name" class="col-md-4 control-label">Internal Commission</label>
                <div class="col-md-7">
					  <input type="text" class="form-control input-sm" id="internal-commission" name="internal_commission" placeholder="Enter Internal Commission" value="<?= isset($_POST['internal_commission']) ? $_POST['internal_commission'] : ( isset($transaction['internal_commission']) ? $transaction['internal_commission'] : ''); ?>" />                  
                </div>
              </div>
              <div class="clearfix sp-margin-sm "></div> 
              
            
            </div>
			
			
			
			<div class = "col-md-12">
				<div class="panel panel-default tab-panel">
				  <div class="panel-heading">
					  Upload Files
				  </div>
				  <div class="panel-body">
					<div class="row-fluid table-responsive" id="tbl-payment-detail">
						<div class="col-md-12">
							<table class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" id='detail-table'>
							  <thead>
								<tr>
								  <th style="width:20%">File</th>
								  <th style="width:20%">Path</th>
								  <th style="width:20%">Action</th>
								</tr>
							  </thead>
							  <tbody> 
								<?php if( isset($details) ) { 
								  foreach($details as $key=>$pd) {
								?>
								  <!-- <tr class="id-<?= $pd['transaction_file_id'] ?>">
									<td class="small-tbl-column"><?=$pd['file_name'] ?></td>
									<td><?= $pd['file_path'] ?></td>
									<td> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td>
								  </tr> -->
								<?php
								  }
								} ?>
							  </tbody>
							  <tfoot>
								  <tr id="detail-row">
												
								  
								  <form id="detail-form">
									<td>
									
									  <!--input type="hidden" id="hid-edit-id" name="hid_edit_id" /-->
									  
										<input type="file" name="images[]" id ="img_select">
										
									</td>
									<td>
										<input type="text" class="form-control input-sm" id="path" name="path" placeholder="Upload File" value="" readonly>
										<input type="hidden" class="form-control input-sm" id="file-name" name="file_name" placeholder="Enter Qty" value="">
									
									</td>
									<td>

									 
									 <!--input type="file" name="upload_file1" class="btn btn-default" id="upload_file1" readonly="true"/-->
									  <span id="detail-add"><a href="#" id="ico-add"><i class="fa fa-plus ico"></i></a></span>
									  <span id="detail-update" style="display: none;"><a href="#" id="ico-update" ><i class="fa fa-save ico"></i></a> / <a href="#" id="ico-cancel" ><i class="fa fa-eraser ico"></i></a></span>
									</td>
								  </form>
								  
								  </tr>
								  <tr id="total-row">
								   
								  </tr>
								 
							  </tfoot>
							</table>      
						</div>
					</div>    
				  </div>
				</div>
			</div>

			
            <div class="clearfix sp-margin-lg"></div>
                      
            <div class="clearfix sp-margin-sm"></div>
            <div class="form-group">
                <div class="col-xs-12 text-right">
                  <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
                </div>
            </div>
			<div class="clearfix sp-margin-md"></div>
          <!-- </form> -->
      </div>

</section>

