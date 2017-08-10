
<?php //$revised = ($transaction['revised'] == 1) ? "[ Revised ]" : ''; ?>
<script>
  <?php if($this->session->userdata('role_id') != 1) { ?>
      //$('.commission-area').hide();
    <?php } ?>
</script> 
<div class="row">
  <div class="form-panel view-box" id="main-content-area">
    <div class = "content-title"><span>View Transaction Detail</span></div>
	<input type="hidden" name="hid-id" id="hid-delete-id" value="<?php $transaction['case_id']?>"/>
        <div class="clearfix sp-margin-lg"></div>
        <div class="col-md-6">
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Case No :</label>
              <div class="col-md-7"><?= $transaction['case_no'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Property Title :</label>
              <div class="col-md-7"><?= $transaction['property_title'] ?> </div>
          </div>  
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Amount :</label>
              <div class="col-md-7"><?= $transaction['amount'] ?> </div>
          </div>  
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Transaction Date :</label>
            <div class="col-md-7"><?= $transaction['transact_date'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Property Type :</label>
            <div class="col-md-7"><?= $transaction['property_type'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div>
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Address :</label>
            <div class="col-md-7"><?= $transaction['address'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div>
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Price :</label>
            <div class="col-md-7"><?= $transaction['price'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">GST :</label>
            <div class="col-md-7"><?= $transaction['gst'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div>
		  <div class="form-group">
            <label for="name" class="col-md-5 control-label">Owner Name :</label>
            <div class="col-md-7"><?= $transaction['owner_name'] ?></div>
          </div>
        </div>
        <div class="col-md-6">
           <div class="form-group">
            <label for="name" class="col-md-5 control-label">Owner Name :</label>
            <div class="col-md-7"><?= $transaction['owner_name'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
           <div class="form-group">
            <label for="name" class="col-md-5 control-label">Buyer Name :</label>
            <div class="col-md-7"><?= $transaction['buyer_name'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Co-Broke Agent :</label>
            <div class="col-md-7"><?= $transaction['co_broke_agent'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Co-Broke Agency :</label>
            <div class="col-md-7"><?= $transaction['co_broke_agency'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Contract Date :</label>
            <div class="col-md-7"><?= $transaction['contract_date'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Option Date :</label>
            <div class="col-md-7"><?= $transaction['option_date'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Sale Person :</label>
            <div class="col-md-7"><?= $transaction['user_id'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Commission :</label>
            <div class="col-md-7"><?= $transaction['commission'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Co-Broke Commission :</label>
            <div class="col-md-7"><?= $transaction['co_broke_commission'] ?></div>
          </div>
		   <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Internal Commission :</label>
            <div class="col-md-7"><?= $transaction['internal_commission'] ?></div>
          </div>
        </div>
			<div class="clearfix sp-margin-lg"></div>
         <div class="col-md-12">
         <div class="panel panel-default tab-panel">
          <div class="panel-heading">
              Uploaded Files
          </div>
          <div class="panel-body">
            <div class="row-fluid table-responsive" id="tbl-detail">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" id='detail-table'>
                      <thead>
                        <tr>
                          <th style="width:20%">File</th>
                          <th style="width:20%">Path</th>
						
                        </tr>
                      </thead>
                      <tbody> 
                        <?php if( isset($details) ) { 
                          foreach($details as $key=>$pd) {
                        ?>
                          <tr class="id-<?= $pd['transaction_file_id'] ?>">
                     
                            <td><a href ="<?=base_url(). $pd['file_path'] ?>" target="_blank"><?= $pd['file_name'] ?></a></td>
                            <td><?=$pd['new_file_name']?></td>
                          
                          </tr>
                        <?php
                          }
                        } ?>
                      </tbody>
                      <tfoot>
                       
                      </tfoot>
                    </table>      
                </div>
            </div>    
          </div>
        </div>    
		</div>         
        <div class="clearfix sp-margin-sm"></div>
  </div>
</div>

