<?php  
    $action_url =  ($action == 'new') ? base_url() ."announcement/create" : base_url()."announcement/edit/".$announcement['announce_id'];
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
             
    $("#announcement-date").datepicker({
        format: "yyyy-mm-dd",
        autoclose :true,
    }); 
/*
    $("#delivery-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#announcement-date").datepicker({
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
	$page = 'announcement';
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
			<li><a href="<?= base_url().'announcement'; ?>">Announcement</a></li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?> Announcement</li>
		</ol>
		<div class="box"> 
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Announcement</h3>
			 <div class="pull-right">
                <a href="<?= base_url().'announcement'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
	<div class ="col-md-6">
		
		  <div class="box-body">
			<div class="form-group">
                <input type="hidden" id="hid-submitted" name="hid_submitted" value="1" />
                <input type="hidden" id="hid-announcement-id" name="hid_announcement_id" value="<?= (isset($announcement['announce_id'])) ? $announcement['announce_id'] : ''; ?>" />
                <label for="name" class="col-md-3 control-label">Title</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="announcement-title" name="announcement_title" placeholder="Enter Announcement Title" value="<?= isset($_POST['announcement_title']) ? $_POST['announcement_title'] : ( isset($announcement['announce_title']) ? $announcement['announce_title'] : '') ; ?>">
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
            </div> 
			<div class="sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Date</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="announcement-date" name="announcement_date" placeholder="Enter Announcement Date" value="<?= isset($_POST['announcement_date']) ? $_POST['announcement_date'] : ( isset($announcement['announce_date']) ? $announcement['announce_date'] : '') ; ?>">
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
              </div>
			<div class="sp-margin-md"></div> 
            <div class="form-group">
			  <label for="name" class="col-md-3 control-label">Announcement</label>
			  <div class="col-sm-7">
				<textarea class="form-control input-sm" id="announcement-body" name="announcement_body" placeholder="Write your announcement" height = "20" width = "100" style="height: 20em;width: 37em;*/" required><?= isset($_POST['announcement_body']) ? $_POST['announcement_body'] : ( isset($announcement['announce_body']) ? $announcement['announce_body'] : '') ; ?></textarea>
			  </div>
			</div>   
	
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
