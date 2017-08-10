<!--link href="<?= base_url();?>css/jquery.textcomplete.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.theme.css" rel="stylesheet">
<script src="<?= base_url();?>js/jquery.textcomplete.min.js"></script>
<!--script src="<?= base_url();?>js/DT_bootstrap.js"></script-->

<!--script src="<?= base_url();?>js/jquery_1.5.2.js"></script>
<script src="<?= base_url();?>js/jquery-1.11.1.js"></script-->
<!--script src="<?= base_url();?>js/select2-4.js"></script-->
<!--script src="<?= base_url();?>js/jquery.autosize.min.js"></script-->

<script>

var burl = "<?= base_url() ?>";



//$( document ).ready(function() {
  $(function(){
    var tbl = $('#admin-table').dataTable({
        "processing": true,
        "serverSide": true,
        'iDisplayLength': 20,
        //'bFilter': false, 
        //'bInfo': false,
        "order": [[ 1, "asc" ]], // Default Sroting by Desc
        "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
        "ajax": {
            "url": "<?= base_url() ?>user/index/dt",
            "type": "POST",
            "data" : { }
        },
        "columns": [
            { "data": "no", "orderable": false, "bSearchable": false },
            { "data": "user_img",
					"render": function(data, type, row) {
						return '<img height ="100" width="100" src="'+data+'" /> ';
					}},
            { "data": "name"},
            { "data": "username"},
            { "data": "role" },
            { "data": "email"},
            { "data": "contact"},
			{ "data": "cv",
					"render": function(data, type, row) {
						if(data==0 || data ==""){
							return "N/A";
						}
						else{
							var file_name = data.substring(data.lastIndexOf("/") + 1, data.length);
							return '<a id="file-link" href="'+burl + data+'" >'+ file_name +'</a> ';
						}
						
					}},
            { "data": "action", "orderable": false, "bSearchable": false }
        ],
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
            // For auto numbering at 'No' column
            var start = tbl.api().page.info().start;
            $('td:eq(0)',nRow).html(start + iDisplayIndex + 1);
        },
    });
	
	$("#file-link").click(function(){
      var del_id= $('#hid-delete-id').val();
      $("#myModal").modal('hide');  
      var url = 'user/delete/' + del_id;
      deleteAjax(url, tbl);
    });
	
	
	
	
    //Quick Add
    $("#quick-add").click(function(e){
      e.preventDefault();
      var url = 'user/create';
      console.log($('#submiturl').val(url));
	  
      $('.quick-action').html('Add');
      $("#quickModal").modal('show');
    });

    //Quick Edit
    $('#tbl-admin').on('click', '.edit-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      var editurl = 'user/aj_edit/' + id;
      getEditvalues(editurl);
      var updateurl = 'user/edit/' + id;
      $('#submiturl').val(updateurl);
      $('.quick-action').html('Edit');
      $("#quickModal").modal('show');
    });

    // Quick Submit
    $("#btn-q-submit").click(function(e){
      e.preventDefault();
      var valid = validateForm('quick-form');
      if(valid) { 
        if ($('.quick-action').html() == 'Add') {
          if($('#password').val() != $('#repassword').val()) { 
            $('#password').parent().parent().addClass('has-error');
            $('#repassword').parent().parent().addClass('has-error');
            $('.err-display').html("Password and RePassword must be the same.");
            $('.err-display').show();
          }
          else if($('#password').val() == '') { 
            $('#password').parent().parent().addClass('has-error');
            $('.err-display').html("Please Enter Password.");
            $('.err-display').show();
          }
          else {
            var formdata = $("#quick-form").serializeObject();
            var url = $('#submiturl').val();
            quickAjaxsubmit(url, formdata, tbl);
			console.log(url, formdata, tbl);
			resetForm();
          }
        }
		
        else {
            var formdata = $("#quick-form").serializeObject();
            var url = $('#submiturl').val();
            quickAjaxsubmit(url, formdata, tbl);  
			console.log(url, formdata, tbl);
			resetForm();
        } 
      }   
    });

    //Quick Delete
    $('#tbl-admin').on('click', '.delete-link', function(e) {
      e.preventDefault();
      var url =  $(this).attr("href");
      var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
      $('#hid-delete-id').val(del_id);
      $("#myModal").modal('show');
     
    });

    //Quick Delete Confirm
    $("#btn-confirm-yes").click(function(){
      var del_id= $('#hid-delete-id').val();
      $("#myModal").modal('hide');  
      var url = 'user/delete/' + del_id;
      deleteAjax(url, tbl);
    });

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
    $("#quick-form").each(function(){
      this.reset();
    });
	$("#image-form").each(function(){
      this.reset();
    });
	/* $("#quick-form").each(function(){
      this.reset();
    }); */
   }
  
</script>
<script src="<?= base_url();?>js/user_add.js"></script>
<!-- Modal (For Confirm Delete)-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body">
        <span>Are you sure to delete this record?</span>
        <input type="hidden" name="hid-id" id="hid-delete-id" value=''/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" id='btn-confirm-yes'>Yes</button>
      </div>
    </div>
  </div>
</div>
<!-- End Model -->

<!-- Modal (Quick Add Model) -->
<div class="modal fade" id="quickModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md user-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><span class="quick-action"></span> User</h4>
      </div>
      <div class="modal-body">
        <form id="quick-form">
			<div class ="row"> 
			<div class="alert alert-danger err-display" style="display:none;"> </div> 
				<div class ="col-md-6">

					<input type="hidden" id="submiturl" name="submiturl" value="">
					 <div class="form-group">
						<label for="name" class="col-md-4 control-label">Name</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="name" name="name" placeholder="Enter admin Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ( isset($admin['name']) ? $admin['name'] : '') ; ?>" required>
						</div>
						<div class="col-md-1 req-star">*</div>
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="username" class="col-md-4 control-label">Username</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="username" name="username" placeholder="Enter Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ( isset($admin['username']) ? $admin['username'] : '') ; ?>">
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
							<input type="text" class="form-control input-sm" id="email" name="email" placeholder="Enter Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ( isset($engineer['email']) ? $engineer['email'] : '') ; ?>">
						</div>
						<div class="col-md-1 req-star">*</div>
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div id ='image-form2'>
						<input type ="hidden" name ="user_img" id="img-new-file-name" value ="<?php echo isset($_POST['img_new_file_name']) ? $_POST['img_new_file_name'] : ( isset($engineer['user_img']) ? $engineer['user_img'] : '') ; ?>" >
					</div>
				</div> 
			
				<div class="col-md-6">
					
					<div class="form-group">
						<label for="username" class="col-md-4 control-label">Contact</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="contact" name="contact" placeholder="Enter Contact" value="<?php echo isset($_POST['contact']) ? $_POST['contact'] : ( isset($engineer['contact']) ? $engineer['contact'] : '') ; ?>">
						</div>
					   <!--  <div class="col-md-1 req-star">*</div> -->
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="username" class="col-md-4 control-label">NRIC</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="nric" name="nric" placeholder="Enter NRIC" value="<?php echo isset($_POST['nric']) ? $_POST['nric'] : ( isset($engineer['nric']) ? $engineer['nric'] : '') ; ?>">
						   <!--textarea class="form-control input-sm" id="address" name="address" placeholder="Enter Address"><?//= isset($_POST['address']) ? $_POST['address'] : ( isset($engineer['address']) ? $engineer['address'] : '') ; ?></textarea-->
						</div>
					   <div class="col-md-1 req-star">*</div>
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="username" class="col-md-4 control-label">CEA No</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="cea-no" name="cea_no" placeholder="Enter CEA No" value="<?= isset($_POST['cea_no']) ? $_POST['cea_no'] : ( isset($engineer['cea_no']) ? $engineer['cea_no'] : '') ; ?>">
						</div>
					   <div class="col-md-1 req-star">*</div>
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="username" class="col-md-4 control-label">Commission</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="commission" name="commission" placeholder="Enter Commission" value="<?= isset($_POST['commission']) ? $_POST['commission'] : ( isset($engineer['commission']) ? $engineer['commission'] : '') ; ?>">
						</div>
					   <!--div class="col-md-1 req-star">*</div-->
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="username" class="col-md-4 control-label">Co-Broke Commission</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="co-broke-commission" name="co_broke_commission" placeholder="Enter Co-Broke Commission" value="<?= isset($_POST['co_broke_commission']) ? $_POST['co_broke_commission'] : ( isset($engineer['co_broke_commission']) ? $engineer['co_broke_commission'] : '') ; ?>">
						</div>
					   <!--div class="col-md-1 req-star">*</div-->
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="username" class="col-md-4 control-label">Internal Commission</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="internal-commission" name="internal_commission" placeholder="Enter Internal Commission" value="<?= isset($_POST['internal_commission']) ? $_POST['internal_commission'] : ( isset($engineer['internal_commission']) ? $engineer['internal_commission'] : '') ; ?>">
						</div>
					   <!--div class="col-md-1 req-star">*</div-->
					</div>
					<div id ='cv-form2'>
						<input type ="hidden" name ="cv" id="cv-new-file-name" value ="<?php echo isset($_POST['img_new_file_name']) ? $_POST['img_new_file_name'] : ( isset($engineer['cv']) ? $engineer['cv'] : '') ; ?>" >
					</div>
					<div class="clearfix sp-margin-sm"></div> 
					
					<div class="clearfix sp-margin-sm"></div>
				</div>
			</div>
        </form>
		
			
				<div class ="row">
					<div class="col-md-6">
						<form id="image-form" style="/*display:none;*/">
					
							<div class="form-group">
									<!--form id='form_image'-->
							  <label for="name" class="col-md-4 control-label">Image</label>
							  <div class="col-md-6">
								<input type="file" name="main_image[]" id='main_img_select' onchange ="ValidateMainImage(this);">
								<input type="hidden" class="form-control input-sm" id="main-file-name" name="main_file_name" placeholder="Upload an Image" value="">
								<h5><em>Click on the image to remove</em></h5>
								
								
								<img id ="main-img-preview" class= " img-responsive" name = "user_img" src ="<?php echo isset($_POST['user_img']) ? $_POST['user_img'] : ( isset($user['user_img']) ? $user['user_img'] : '') ; ?>" height=100 width = 100></img>
								<!--input type ="hidden" name ="user_img" id="img-new-file-name" value ="<?php echo isset($_POST['user_img']) ? $_POST['user_img'] : ( isset($engineer['user_img']) ? $engineer['user_img'] : '') ; ?>" -->
							  </div>
								
							</div>
						</form>
					</div>
				
					<div class="col-md-6">
						<form id="cv-form" style="/*display:none;*/">					
							<div class="form-group">
									<!--form id='form_image'-->
							  <label for="name" class="col-md-4 control-label">CV</label>
							  <div class="col-md-6">
								<input type="file" name="cv_files[]" id='main_cv_select' onchange ="ValidateSingleInput(this);">
								<input type="hidden" class="form-control input-sm" id="main-cv-name" name="main_cv_name" placeholder="Upload an Image" value="">
								<div class="clearfix sp-margin-md" style ="padding-top:0em;"></div>
								<h5><em>Current CV Uploaded</em></h5>
								<a name = "cv" name ="current" href =""></a>
								<input type ="text" class="form-control input-sm" name ="cv" id="cv-new-file-name" value ="" readonly>
							  </div>

							</div>
						</form>
					</div>
				
				</div>
					
				<!--input type="file" name="main_image[]" id='main_img_select'>
				<input type="hidden" class="form-control input-sm" id="main-file-name" name="main_file_name" placeholder="Upload an Image" value=""-->
			
	
			 <!--div class="col-xs-12 text-right">
				<button type="submit" class="btn btn-primary btn-mtac" id='btn-q-submit'><i class="fa fa-save ico-btn"></i>Submit</button>
			</div-->
	
        <input type="hidden" name="hid-id" id="hid-delete-id" value=''/>
      </div>
      <div class="modal-footer">
		<button type="submit" class="btn btn-primary btn-mtac" id='btn-q-submit'><i class="fa fa-save ico-btn"></i>Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick ="">Close</button>
        
      </div>
    </div>
  </div>
</div>
<!-- End Model -->

<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">User Management</li>
		</ol>
		<div class="box">		  
          <div class="box-header">
             <h3 class="box-title">User Management</h3>
			 <div class="pull-right">
				<!--a href="<?= base_url().'user/create'; ?>" class="btn btn-default btn-flat"><i class="fa fa-plus ico-btn"></i>Add</a-->
				<a href="#" id="quick-add" class="btn btn-default btn-create"><i class="fa fa-plus ico-btn"></i> Add</a>
			</div>
           </div><!-- /.box-header -->
			<div class="box-body">
				<div class="success-alert-area"> </div>
				<?php if(isset($msg) && $msg != '') { ?>
					<div class="alert alert-success"><a href='#' class='close' data-dismiss='alert'>&times;</a><?= $msg; ?></div>
				<?php } ?>
				<div class="row-fluid table-responsive" id="tbl-admin">
					<div class = "col-md-12">
						<table class="table table-striped table-bordered dataTable no-footer" id = 'admin-table'>
							<thead>
							  <tr>
								<th>No</th>
								<th>Image</th>
								<th>Name</th>
								<th>Username</th>
								<th>Role</th>
								<th>Email</th>
								<th>Contact</th>
								<th>CV</th>
								<th>Action</th>       
							  </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
							  <tr>
								<th>No</th>
								<th>Image</th>
								<th>Name</th>
								<th>Username</th>
								<th>Role</th>
								<th>Email</th>
								<th>Contact</th>
								<th>CV</th>
								<th>Action</th>       
							  </tr>
							</tfoot>
					  </table>
					</div>
				</div>
			</div><!-- /.box-body -->
        </div><!-- /.box -->
	</div>
  </div>
</section>