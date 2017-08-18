<?php  
    $action_url =  ($action == 'new') ? base_url() ."tier_commission/create" : base_url()."tier_commission/edit/".$tier_commission['tier_commission_id'];
?>
<link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery.textcomplete.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.theme.css" rel="stylesheet">
<script src="<?= base_url();?>js/jquery.textcomplete.min.js"></script>
<!--script src="<?= base_url();?>js/DT_bootstrap.js"></script-->

<!--script src="<?= base_url();?>js/jquery_1.5.2.js"></script>
<script src="<?= base_url();?>js/jquery-1.11.1.js"></script-->
<!--script src="<?= base_url();?>js/select2-4.js"></script-->
<script src="<?= base_url();?>js/jquery.autosize.min.js"></script>
<!--script src="<?= base_url();?>js/vpb_uploader.js"></script-->
<!-- <script src="<?= base_url();?>js/ckeditor/ckeditor.js"></script> -->

<script>
  var burl = "<?= base_url() ?>";
 

  
  <?php if( isset($details) ) { ?>
    var detail_arr = <?= json_encode($details); ?>;   
  <?php } ?>

   $( document ).ready(function() {
		//	loadsubcategory();
             
    $("#tier_commission-date").datepicker({
        format: "yyyy-mm-dd",
        autoclose :true,
    }); 
/*
    $("#delivery-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#tier_commission-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 
	
	 $("#payment-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 
 */
 
	
		
	
  });

 




</script>

<?php 

/* if($is_quotation==true){
	
	$page = 'quotation';
	$readonly= '';
}
else{
	$page = 'tier_commission';
	$readonly= 'readonly';
}
 
 
 use the following if needed:
 <div class="col-md-1 req-star">*</div>
 <div class="clearfix sp-margin-sm"></div> 
 
 
 
 
 */

if ( $action == "new" ) { ?>
  
   <!--script src="<?= base_url();?>js/js/main.js"></script-->
   <script src="<?= base_url();?>js/tier_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/tier_edit.js"></script>
  
<?php } ?>
<!--script src="<?= base_url();?>js/vpb_uploader.js"></script-->
<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= base_url().'tier_commission'; ?>">Tier Commission</a></li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?> Tier Commission</li>
		</ol>
		<div class="box"> 
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Tier Commission</h3>
			 <div class="pull-right">
                <a href="<?= base_url().'tier_commission'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
	<div class ="col-md-6">
		
		  <div class="box-body">
			<div class="form-group">
                <input type="hidden" id="hid-submitted" name="hid_submitted" value="1" />
                <input type="hidden" id="hid-tier_commission-id" name="hid_tier_commission_id" value="<?= (isset($tier_commission['tier_commission_id'])) ? $tier_commission['tier_commission_id'] : ''; ?>" />
                <label for="name" class="col-md-3 control-label">User</label>
                <div class="col-md-7">
				
					<select class="form-control input-sm" name="user_id" id="user-id" placeholder="Select User" required>
                        <option value="" selected>Select User</option>
                        <?php if($users != '') { ?>
                            <?php foreach($users as $user) {  
                                 if( isset($_POST['user_id']) && ($_POST['user_id'] == $user['user_id']) ) {
                                    echo "<option selected value='". $user['user_id'] . "' >" . $user['name'] . "</option>";
                                }
                                else  if( isset($tier_commission['user_id']) && ($tier_commission['user_id'] == $user['user_id']) ) {
                                    echo "<option selected value='". $user['user_id'] . "' >" . $user['name'] . "</option>";
                                }
                               else {
                                    echo "<option value='". $user['user_id'] . "' >" . $user['name'] . "</option>";
                               }
                            } ?>
                        <?php } ?>
                    </select>    
				
				
				
				
                    <!--input type="text" class="form-control input-sm" id="user-id" name="user_id" placeholder="Enter Tier Commission Title" value="<?= isset($_POST['user_id']) ? $_POST['user_id'] : ( isset($tier_commission['username']) ? $tier_commission['username'] : '') ; ?>"-->
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
            </div> 
			<div class="sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Level</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="level" name="level" placeholder="Enter Tier Commission Date" value="<?= isset($_POST['level']) ? $_POST['level'] : ( isset($tier_commission['level']) ? $tier_commission['level'] : '') ; ?>">
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
              </div>
			<div class="sp-margin-md"></div> 
            
	
			<!--input type ="file" name ="sample_files[]" multiple==-->
			
 				  				   
		  </div><!-- /.box-body -->

		  
		
	</div>
		

			
		<div class="form-group">
                <div class="col-xs-12 text-right">
                  <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
                </div>
            </div>		
	</div>
	
	

</section>
