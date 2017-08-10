<?php  
    $action_url =  ($action == 'new') ? base_url() ."announcement/create" : base_url()."announcement/edit/".$announcement['announce_id'];
?>
<link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery.textcomplete.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.theme.css" rel="stylesheet">
<script src="<?= base_url();?>js/jquery.textcomplete.min.js"></script>
<script src="<?= base_url();?>js/DT_bootstrap.js"></script>

<!--script src="<?= base_url();?>js/jquery_1.5.2.js"></script>
<script src="<?= base_url();?>js/jquery-1.11.1.js"></script-->
<!--script src="<?= base_url();?>js/select2-4.js"></script-->
<script src="<?= base_url();?>js/jquery.autosize.min.js"></script>
<!-- <script src="<?= base_url();?>js/ckeditor/ckeditor.js"></script> -->
<script>
  var burl = "<?= base_url() ?>";
 

  
  <?php if( isset($details) ) { ?>
    var detail_arr = <?= json_encode($details); ?>;   
  <?php } ?>
  
   <?php if( isset($payment_details) ) { ?>
    var payment_detail_arr = <?= json_encode($payment_details); ?>;   
  <?php } ?>

 
  
  
  
   $( document ).ready(function() {
		//	loadsubcategory();
/*             
    $("#date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

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

	// Call the main function
	new vpb_multiple_file_uploader
	({
		vpb_form_id: "form_id", // Form ID
		autoSubmit: true,
		vpb_server_url: "upload" 
	});
	
	
	
 
    
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
 */

if ( $action == "new" ) { ?>
  <script src="<?= base_url();?>js/announcement_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/announcement_edit.js"></script>
<?php } ?>
<script src="<?= base_url();?>js/vpb_uploader.js"></script>
<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="breadcrumb">
        <i class="fa fa-windows bc-icon"></i>Dashboard <i class="fa fa-angle-double-right bc-arrow"></i> <i class="fa fa-paint-brush bc-icon"></i>Project <i class="fa fa-angle-double-right bc-arrow"></i> <?= ($action == 'new') ? "Add " : "Edit " ?> Announcement
      </div>
      <div class="form-panel" id="main-content-area">
        <h2><span><?= ($action == 'new') ? "Add " : "Edit " ?> Announcement</span></h2>
          <div class="clearfix sp-margin-md"></div>
          <div class="row-fluid">
              <div class="col-md-12 text-right"><a href="<?= base_url().'announcement'; ?>" class="btn btn-default btn-create"><i class="fa fa-arrow-left ico-btn"></i>Back</a></div>
          </div>
          <!-- <form announcement="form" id="announcement-form" method="post" action="<?= $action_url ?>"> -->
            <?php if(validation_errors()) { ?>
                <div class="clearfix sp-margin-sm"></div>
                <div class="alert alert-danger"> <?= validation_errors(); ?> </div>  
            <?php } ?>
            <div class="clearfix sp-margin-md"></div>
            <div class="col-md-6">
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
              
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Announcement</label>
                <div class="col-md-7">
                  <!--input type="text" class="form-control input-sm" id="announcement-body" name="announcement_body" placeholder="Enter Department" value="<?php//= isset($_POST['department']) ? $_POST['department'] : ( isset($announcement['department']) ? $announcement['department'] : ''); ?>"-->
				  <textarea class="form-control input-sm" id="announcement-body" name="announcement_body" placeholder="Write your announcement" style="height: 40em;width: 70em;" required><?= isset($_POST['announcement_body']) ? $_POST['announcement_body'] : ( isset($announcement['announce_body']) ? $announcement['announce_body'] : '') ; ?></textarea>				  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div>
			  <div class="form-group">
                <!--label for="name" class="col-md-3 control-label">Photo</label-->
				<!--form role="form" method="post" enctype = "multipart/form-data">
                <div class="col-md-7">
				<?php if ($action=='edit'){?>
					<img src = "<?//= (isset($announcement['announce_img'])) ? base_url().$announcement['announce_img'] : ''; ?>" name="maid_img" height="100" width="100">
				<?php } ?>
				</form-->	
					<?php //echo form_open_multipart('announcement/do_upload/');?>
					<!--h6>.jpg files only *to be updated<h6>
					 <input type="file" name="userfile" size="20" />
					
					<br />

					<input type="submit" value="upload"></input>
					<input type="hidden" name="image" value="<?//= isset($announcement['announce_img']) ? 'ID-'.$announcement['announce_id'] :'' ?>">
					<input type="hidden" name="announce_id" value="<?//= (isset($announcement['announce_id'])) ? $announcement['announce_id'] : ''?>">
					<input type="hidden" name="announce_img_path" value="<?//= (isset($announcement['announce_id'])) ? "public/announcement_images/ID-".$announcement['announce_id'].".jpg" :'' ?>"-->
					
				</div>
						
              </div>
			  
			  
			  <div class="clearfix sp-margin-lg"></div>
			
			</div>
			<div class="panel panel-default tab-panel">
              <div class="panel-heading">
                  Uploaded Files
              </div>
              <div class="panel-body">
				<div class="row-fluid table-responsive" id="tbl-files">
					<div class="col-md-12">
					  <div class="form-group">
							<form name="form_id" id="form_id" action="javascript:void(0);" enctype="multipart/form-data" style="width:800px; margin-top:20px;">  
								<input type="file" name="vasplus_multiple_files" id="vasplus_multiple_files" multiple="multiple" style="padding:5px;"/>      
								<input type="submit" value="Upload" style="padding:5px;"/>
							</form>
						<table class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" id='files-table'>
						  <thead>
							<tr>
							  <th>File Name</th>
							  <th>Status</th>
							  <th>File Size</th>
							  <th>Action</th>
							</tr>
						  </thead>
						  <tbody> 

						  </tbody>
						</table>    
					  </div>          
					</div>	

				</div>
												
												
													
									<div class="clearfix sp-margin-sm"></div>
									<div class="form-group">
										<div class="col-xs-12 text-right">
											<button type="submit" class="btn btn-mtac" id="btn-submit"><i class="fa fa-save ico-btn"></i><?php echo ($action == 'new') ? "Save" : "Update" ?></button>
										</div>
									</div>
						  <!-- </form> -->
				</div>
			</div>
    </div>
  </section>
</section>

