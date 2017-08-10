<?php  
    $action_url =  ($action == 'new') ? base_url() ."user/create" : base_url()."user/edit/".$user['user_id'];
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
 
 
	 $('#repassword').focusout(function(){
      if($('#password').val() != $('#repassword').val()) {
         $('#password, #repassword').closest('.form-group').addClass('has-error');
      }   
      else {
          $('#password, #repassword').closest('.form-group').removeClass('has-error');
      }
    }); 

    $('#password').focusout(function(){
      if( $('#repassword').val() != '' ) {
          if($('#password').val() != $('#repassword').val()) {
             $('#password, #repassword').closest('.form-group').addClass('has-error');
          }   
          else {
              $('#password, #repassword').closest('.form-group').removeClass('has-error');
          }
      }
    });   
 
	
		
	
  });

 
 function resetForm() {
		$("#detail-form").each(function(){
		  this.reset();
		});

		$('#description').trigger('autosize.resize');
		$('#detail-add').show();
		$('#detail-update').hide();

	  }
	  
	  function setfilename(val)
		  {
			  alert(val);
			//var fileName = val.substr(val.lastIndexOf("\\")+1, val.length);
			//document.getElementById("main_img_preview").value = fileName;
		  } 
  /* function FileUploadDone(e, data) {
for (x in data._response.result.files){
    if (data._response.result.files[x].error == "")
        alert(data._response.result.files[x].name);
    else
        alert(data._response.result.files[x].error);
}} */

	function showMyImage(fileInput) {
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                continue;
            }           
            var img=document.getElementById("main-img-preview");            
            img.file = file;
			//alert($('#main-file-name').val(files));
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
        }    
    }



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
   <script src="<?= base_url();?>js/user_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/user_edit.js"></script>
  
<?php } ?>
<!--script src="<?= base_url();?>js/vpb_uploader.js"></script-->
<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= base_url().'user'; ?>">User Management</a></li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?> User</li>
		</ol>
		<div class="box"> 
          <div class="box-header">
             <h3 class="box-title"><span class="quick-action"><?= ($action == 'new') ? "Add " : "Edit " ?></span>User</h3>
			 <div class="pull-right">
                <a href="<?= base_url().'user'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
	<div class ="col-md-12">
		
		  <div class="box-body">
			<form id ="quick-form">
			<div class ="col-md-6">
					
				<div class="alert alert-danger err-display" style="display:none;"> </div> 
				
				<input type="hidden" id="submiturl" name="submiturl" value="">
				 <div class="form-group">
					<label for="name" class="col-md-4 control-label">Name</label>
					<div class="col-md-6">
						<input type="text" class="form-control input-sm" id="name" name="name" placeholder="Enter admin Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ( isset($user['name']) ? $user['name'] : '') ; ?>" required>
					</div>
					<div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="username" class="col-md-4 control-label">Username</label>
					<div class="col-md-6">
						<input type="text" class="form-control input-sm" id="username" name="username" placeholder="Enter Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ( isset($user['username']) ? $user['username'] : '') ; ?>">
					</div>
					<div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="password" class="col-md-4 control-label">Password</label>
					<div class="col-md-6">
						<input type="password" class="form-control input-sm" id="password" name="password" placeholder="Enter Password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ; ?>" >
					</div>
					<div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="repassword" class="col-md-4 control-label">ReEnter Password</label>
					<div class="col-md-6">
						<input type="password" class="form-control input-sm" id="repassword" name="repassword" placeholder="Enter Password Again" value="<?php echo isset($_POST['repassword']) ? $_POST['repassword'] : '' ; ?>" >
					</div>
					<div class="col-md-1 req-star">*</div>
				</div>    
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="role" class="col-md-4 control-label">Role</label>
					<div class="col-md-6">
						<select class="form-control input-sm" name="role_id" id="role-id" placeholder="Select Role" required>
							<option value="" selected>Select Role</option>
							<?php if($roles != '') { ?>
								<?php foreach($roles as $role) {  
									if( isset($_POST['role_id']) && ($_POST['role_id'] == $role['role_id']) ) {
										echo "<option selected value='". $role['role_id'] . "' >" . $role['name'] . "</option>";
									}
									else if( isset($admin['role_id']) && ($admin['role_id'] == $role['role_id']) ) {
										echo "<option selected value='". $role['role_id'] . "' >" . $role['name'] . "</option>";
									}
									else {
										echo "<option value='". $role['role_id'] . "' >" . $role['name'] . "</option>";
									}
								} ?>
							<?php } ?>
						</select>    
					</div>
					<div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="username" class="col-md-4 control-label">Email</label>
					<div class="col-md-6">
						<input type="text" class="form-control input-sm" id="email" name="email" placeholder="Enter Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ( isset($user['email']) ? $user['email'] : '') ; ?>">
					</div>
					<div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<form id ="image-form">
					  <label for="name" class="col-md-4 control-label">Image</label>
					  <div class="col-md-6">
						<input type="file" name="main_image[]" id ="main_img_select" onchange="showMyImage(this);ValidateMainImage(this)">
						<input type="hidden" class="form-control input-sm" id="main-file-name" name="main_file_name" placeholder="Upload an Image" value="">
						<h5><em>Click on the image to remove</em></h5>
						<!--button type="submit" class="btn btn-primary" id="btn-clear">Clear</button-->

						<img id ="main-img-preview" src ="<?= ($action == 'edit') ? base_url().$user['user_img'] : "" ?>" height=100 width = 100></img>
						
					  </div>
					</form>
					<form id ='image-form2' style ="display:none;">
					<?= ($action=='edit') ? '<input type ="hidden" name ="main_new_file_name" id="main-new-file-name" value ="'.$user['user_img'].'" >' :""  ?>
					
					</form>
				</div>
			</div> 
			
			<div class="col-md-6">
					
				<div class="form-group">
					<label for="username" class="col-md-4 control-label">Contact</label>
					<div class="col-md-6">
						<input type="text" class="form-control input-sm" id="contact" name="contact" placeholder="Enter Contact" value="<?php echo isset($_POST['contact']) ? $_POST['contact'] : ( isset($user['contact']) ? $user['contact'] : '') ; ?>">
					</div>
				   <!--  <div class="col-md-1 req-star">*</div> -->
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="username" class="col-md-4 control-label">NRIC</label>
					<div class="col-md-6">
						<input type="text" class="form-control input-sm" id="nric" name="nric" placeholder="Enter NRIC" value="<?php echo isset($_POST['nric']) ? $_POST['nric'] : ( isset($user['nric']) ? $user['nric'] : '') ; ?>">
					   <!--textarea class="form-control input-sm" id="address" name="address" placeholder="Enter Address"><?//= isset($_POST['address']) ? $_POST['address'] : ( isset($engineer['address']) ? $engineer['address'] : '') ; ?></textarea-->
					</div>
				   <div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="username" class="col-md-4 control-label">CEA No</label>
					<div class="col-md-6">
						<input type="text" class="form-control input-sm" id="cea-no" name="cea_no" placeholder="Enter CEA No" value="<?= isset($_POST['cea_no']) ? $_POST['cea_no'] : ( isset($user['cea_no']) ? $user['cea_no'] : '') ; ?>">
					</div>
				   <div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="username" class="col-md-4 control-label">Commission</label>
					<div class="col-md-6">
						<input type="text" class="form-control input-sm" id="commission" name="commission" placeholder="Enter CEA No" value="<?= isset($_POST['commission']) ? $_POST['commission'] : ( isset($user['commission']) ? $user['commission'] : '') ; ?>">
					</div>
				   <div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="username" class="col-md-4 control-label">Co-Broke Commission</label>
					<div class="col-md-6">
						<input type="text" class="form-control input-sm" id="co-broke-commission" name="co_broke_commission" placeholder="Enter Co-Broke Commission" value="<?= isset($_POST['co_broke_commission']) ? $_POST['co_broke_commission'] : ( isset($user['co_broke_commission']) ? $user['co_broke_commission'] : '') ; ?>">
					</div>
				   <div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div>
				<div class="form-group">
					<label for="username" class="col-md-4 control-label">Internal Commission</label>
					<div class="col-md-6">
						<input type="text" class="form-control input-sm" id="internal-commission" name="internal_commission" placeholder="Enter Internal Commission" value="<?= isset($_POST['internal_commission']) ? $_POST['internal_commission'] : ( isset($user['internal_commission']) ? $user['internal_commission'] : '') ; ?>">
					</div>
				   <div class="col-md-1 req-star">*</div>
				</div>
				<div class="clearfix sp-margin-sm"></div> 
				<!--div class="form-group">
					<label for="description" class="col-md-4 control-label"></label>
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary btn-mtac" id='btn-q-submit'><i class="fa fa-save ico-btn"></i>Submit</button>
					</div>
				</div-->
				<div class="clearfix sp-margin-sm"></div>
			</div>
			</form>
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
