<?php  
    $action_url =  ($action == 'new') ? base_url() ."project/create" : base_url()."project/edit/".$project['project_id'];
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
 
  $(function() {

    $("#date").datepicker({
        format: "yyyy/mm/dd",
        autoclose :true,
    }); 

    /* $("#delivery-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#invoice-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    });  */

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
  <script src="<?= base_url();?>js/project_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/project_edit.js"></script>
<?php } ?>
<script src="<?= base_url();?>js/vpb_uploader.js"></script>
<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Project</li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?> Project</li>
		</ol>
		<div class="box">
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Project</h3>
			 <div class="pull-right">
                      <a href="<?= base_url().'project'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
	<div class ="col-md-6">
		
		  <div class="box-body">
			<div class="form-group">
                <!--input type="hidden" id="hid-submitted" name="hid_submitted" value="1" /-->
                <input type="hidden" id="hid-project-id" name="hid_project_id" value="<?= (isset($project['project_id'])) ? $project['project_id'] : ''; ?>" />
                <label for="name" class="col-md-3 control-label">Project Code</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="project-code" name="project_code" placeholder="Enter Project Code" value="<?= isset($_POST['project_code']) ? $_POST['project_code'] : ( isset($project['project_code']) ? $project['project_code'] : $project_code) ; ?>" readonly>
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
            </div> 
			<div class="sp-margin-sm"></div> 
			<div class="form-group">
				<label for="name" class="col-md-3 control-label">Description</label>
				<div class="col-md-7">
					<input type="text" class="form-control input-sm" id="description" name="description" placeholder="Enter Description" value="<?= isset($_POST['description']) ? $_POST['description'] : ( isset($project['description']) ? $project['description'] : ''); ?>" />  
				</div>
				<!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
				<div class="col-md-1 req-star">*</div>
			</div>
			<div class="sp-margin-sm"></div> 
            <div class="form-group">
			  <label for="name" class="col-md-3 control-label">Project IC and Contact</label>
			  <div class="col-md-7">
				 <input type="text" class="form-control input-sm" id="project-ic" name="project_ic" placeholder="Enter Project IC" value="<?= isset($_POST['project_ic']) ? $_POST['project_ic'] : ( isset($project['project_ic']) ? $project['project_ic'] : ''); ?>" />  
			  </div>
			</div>       
			<div class="sp-margin-sm"></div> 
			<div class="sp-margin-sm"></div> 
            <div class="form-group">
			  <label for="name" class="col-md-3 control-label">Date</label>
			  <div class="col-md-7">
				  <input type="text" class="form-control input-sm" id="date" name="date" placeholder="Select Date" value="<?= isset($_POST['date']) ? $_POST['date'] : ( isset($project['date']) ? date('Y/m/d', strtotime($project['date'])) : date('Y/m/d')) ; ?>">
			  </div>
			</div>
			<div class="sp-margin-sm"></div> 
			
		  </div><!-- /.box-body -->
		
	</div>
	<div class="sp-margin-sm"></div> 
	<div class ="col-md-6">
		
		  <div class="box-body">
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Category</label>
                <div class="col-md-7">
                   <input type="text" class="form-control input-sm" id="category" name="category" placeholder="Enter Category" value="<?= isset($_POST['category']) ? $_POST['category'] : ( isset($project['category']) ? $project['category'] : ''); ?>" />  
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
              </div>
			<div class="sp-margin-sm"></div> 
            <div class="form-group">
			  <label for="name" class="col-md-3 control-label">Project Type</label>
			  <div class="col-sm-7">
				 <select class="form-control input-sm" name="project_type" id="project-type">
					<option value="" selected>Select Project Type</option>
					<option value="Local" <?= ( (isset($_POST['project_type']) && $_POST['project_type'] == 'Local') || (isset($project['project_type']) && $project['project_type'] == 'Local') ) ? 'selected' : ''; ?> >Local</option>
					<option value="Oversea" <?= ( (isset($_POST['project_type']) && $_POST['project_type'] == 'Oversea') || (isset($project['project_type']) && $project['project_type'] == 'Oversea') ) ? 'selected' : ''; ?> >Oversea</option>
				</select> 
			  </div>
			</div>       
			<div class="sp-margin-sm"></div> 
            <div class="form-group">
			  <label for="name" class="col-md-3 control-label">Status</label>
			  <div class="col-sm-7">
				   <input type="text" class="form-control input-sm" id="status" name="status" placeholder="Enter Status" value="<?= isset($_POST['status']) ? $_POST['status'] : ( isset($project['project_status']) ? $project['project_status'] : ''); ?>" />  
			  </div>
			</div>

 				  				   
		  </div><!-- /.box-body -->

		 
		
	</div> 
	<div class="clearfix sp-margin-lg"></div>
	<div class="cleasp-margin-sm"></div>
	 <div class="form-group">
		<div class="col-xs-12 text-right">
		  <button type="submit" class="btn btn-primary" id="btn-submit"><?php echo ($action == 'new') ? "Save" : "Update" ?></button>
		</div>
	</div>
</div>

</section>

