<?php  
    $action_url =  ($action == 'new') ? base_url() ."user/create" : base_url()."user/edit/".$user['user_id'];
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
<!-- <script src="<?= base_url();?>js/ckeditor/ckeditor.js"></script> -->>
<script>
  var burl = "<?= base_url() ?>";


  $(function() {
	  

	  //getBelongTo();
	 getLevels();
	// getBelongto();
	 
/* 	var found = [];
$('select option').each(function() {
  $(this).prevAll('option[value="' + this.value + '"]').remove();
}); */
	 
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

	$('select[name="team_id"]').on('change', function() {
		//getClientinfo();
        getLevels();
    }); 
	$('select[name="level"]').on('change', function() {
		//getClientinfo();
		var xx = $('#level').val();
		var y = xx -1;
        getBelongto(xx);
    }); 
	
  });

  function getLevels(){

	  var stateID = $('#team-id').val();
	  var level = $('#level').val();
	  
            if(stateID) {
                $.ajax({
                    url: burl + 'user/aj_getTeamTierDetail/'+stateID,
                    type: "post",
                    dataType: "json",
                    success:function(data) {
						//console.log(url);
						
						
						<?php if ($action=='new'){?>
							
							$('select[name="level"]').empty();
							$('select[name="level"]').append('<option value="0">'+ 'Select Level' +'</option>');
							for(var x = 0; x <data.data[0].levels; x++){
								var y = x+1;
								$('select[name="level"]').append('<option value="'+ y +'">Level '+y+'</option>');
							}
						<?php }else if($action=='edit'){ ?>
							//svar x = {};
							$('select[name="level"]').empty();
							$('select[name="level"]').append('<option value="0">'+ 'Select Level' +'</option>');
							//$('select[name="level"]').append('<option selected value="<?=$user['level']==0 ? 0 : $user['level'] ?>"> <?= $user['level']==0 ? "Select Level" : "Level ".$user['level']?></option>');
							//if(level==1){
							for(var x = 0; x <data.data[0].levels; x++){
								var y = x+1;
								if (y==<?=$user['level'];?>){
									$('select[name="level"]').append('<option selected value="'+ y +'">Level '+y+'</option>');
									//$('select[name="level"]').append('<option selected value="<?=$user['level']==0 ? 0 : $user['level'] ?>"> <?= $user['level']==0 ? "Select Level" : "Level ".$user['level']?></option>');
								}
								else{
									$('select[name="level"]').append('<option value="'+ y +'">Level '+y+'</option>');
								}
							}	
							getBelongto(y-1);	
							//}
						<?php }?>
						
						
						
						
						
                       // $('select[name="level"]').empty();
						//$('select[name="level"]').append('<option value="">'+ 'Select Level' +'</option>');
						//console.log(data.data[0].levels);
						//var x2 = document.getElementById("level").length;
						//console.log(x2);
						/* for(var x = 0; x <data.data[0].levels; x++){
							//x=+1;
							var y = x+1;
							if (y==<?=$user['level'];?>){
							$('select[name="level"]').append('<option selected value="'+ y +'">Level '+y+'</option>');
							//$('select[name="level"]').append('<option selected value="<?=$user['level']==0 ? 0 : $user['level'] ?>"> <?= $user['level']==0 ? "Select Level" : "Level ".$user['level']?></option>');
							}
							else{
							$('select[name="level"]').append('<option value="'+ y +'">Level '+y+'</option>');
							}
						} */
						
                    }
                });

			}
			else{

                $('select[name="level"]').append('<option value="">'+ 'Select Level' +'</option>');
            }

	}
	
	
	
	
	function getBelongto(y){
	  var teamID = $('#team-id').val();
	  var stateID = y;
	  //alert(stateID);
	  var connectLevel = stateID - 1;
	  //alert(teamID);
	  //alert(connectLevel);
            if(stateID) {
                $.ajax({
                    url: burl + 'user/aj_getUserBelongto/'+teamID+'/'+connectLevel,
                    type: "post",
                    dataType: "json",
                    success:function(data) {
						
						
						
						<?php if ($action=='new'){?>
							
							$('select[name="user_belong_to"]').empty();
							$('select[name="user_belong_to"]').append('<option value="0">'+ 'Select User Belong To' +'</option>');
							for(var x = 0; x <data.count; x++){
							//x=+1;
								//var y = data.data[x].user_belong_to;
									$('select[name="user_belong_to"]').append('<option value="'+ data.data[x].user_id +'">'+ data.data[x].name +'</option>');
								//}

							}
							
						<?php }else if($action=='edit'){ ?>
							var x = {};
							$('select[name="user_belong_to"]').empty();
							$('select[name="user_belong_to"]').append('<option value="0">'+ 'Select User Belong To' +'</option>');
							//$('select[name="level"]').append('<option selected value="<?=$user['level']==0 ? 0 : $user['level'] ?>"> <?= $user['level']==0 ? "Select Level" : "Level ".$user['level']?></option>');
							for(var x = 0; x <data.count; x++){
							//x=+1;
								var y = data.data[x].user_id;
								//$('select[name="level"]').append('<option value="'+ y +'">Level '+y+'</option>');
								if (y==<?=$user['user_belong_to'];?>){
									$('select[name="user_belong_to"]').append('<option selected value="'+ data.data[x].user_id +'">'+ data.data[x].name +'</option>');
								//$('select[name="level"]').append('<option selected value="<?=$user['level']==0 ? 0 : $user['level'] ?>"> <?= $user['level']==0 ? "Select Level" : "Level ".$user['level']?></option>');
								}
								else{
									//alert('this is'+y);
									//$('select[name="user_belong_to"]').append('<option value="'+  +'">'+ data.data[x].name +'</option>');
									$('select[name="user_belong_to"]').append('<option value="'+ data.data[x].user_id +'">'+ data.data[x].name +'</option>');
								}

							}
						<?php }?>

						
						//console.log(url);
						// $('select[name="user_belong_to"]').empty();
						//	$('select[name="user_belong_to"]').append('<option value="0">'+ 'Select User Belong To' +'</option>');

					/* 	for(var x = 0; x <data.count; x++){
							//x=+1;
							var y = data.data[x].user_belong_to;
							//$('select[name="level"]').append('<option value="'+ y +'">Level '+y+'</option>');
							if (y==<?=$user['user_belong_to'];?>){
								$('select[name="user_belong_to"]').append('<option selected value="'+ data.data[x].user_id +'">'+ data.data[x].name +'</option>');
							//$('select[name="level"]').append('<option selected value="<?=$user['level']==0 ? 0 : $user['level'] ?>"> <?= $user['level']==0 ? "Select Level" : "Level ".$user['level']?></option>');
							}
							else{
								//alert('this is'+y);
								//$('select[name="user_belong_to"]').append('<option value="'+  +'">'+ data.data[x].name +'</option>');
								$('select[name="user_belong_to"]').append('<option value="'+ data.data[x].user_id +'">'+ data.data[x].name +'</option>');
							}

						} */
						
                    }
                });

			}
			else{

                $('select[name="level"]').append('<option value="">'+ 'Select Level' +'</option>');
            }

	}
  
  function resetForm() {
		$("#detail-form").each(function(){
		  this.reset();
		});

		$('#description').trigger('autosize.resize');
		$('#detail-add').show();
		$('#detail-update').hide();

	  }
  
  
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
	
/* 	function removeDuplicateOptions(selectNode) {
    if (typeof selectNode === "string") {
        selectNode = document.getElementById(selectNode);
    }

    var seen = {},
        options = [].slice.call(selectNode.options),
        length = options.length,
        previous,
        option,
        value,
        text,
        i;

    for (i = 0; i < length; i += 1) {
        option = options[i];
        value = option.value,
        text = option.firstChild.nodeValue;
        previous = seen[value];
        if (typeof previous === "string" && text === previous) {
            selectNode.removeChild(option);
        } else {
            seen[value] = text;
        }
    }
}
removeDuplicateOptions("level"); */

	

</script>

<?php if ( $action == "new" ) { ?>
  <script src="<?= base_url();?>js/user_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/user_edit.js"></script>
<?php } ?>
<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= base_url().'user'; ?>"></i> User</a></li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?>User</li>
		</ol>
		<div class="box">
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>User</h3>
			 <div class="pull-right">
                      <a href="<?= base_url().'user'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
            <div class="col-md-6">
              <input type="hidden" id="submiturl" name="submiturl" value="">
					 <div class="form-group">
						 <input type="hidden" id="hid-user-id" name="hid_user_id" value="<?= (isset($user)) ? $user['user_id'] : ''; ?>" />
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
										else if( isset($user['role_id']) && ($user['role_id'] == $role['role_id']) ) {
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
						<label for="role" class="col-md-4 control-label">Team</label>
						<div class="col-md-6">
							<select class="form-control input-sm" name="team_id" id="team-id" placeholder="Select Team" required>
								<option value="" selected>Select Team</option>
								<?php if($teams != '') { ?>
									<?php foreach($teams as $team) {  
										if( isset($_POST['team_id']) && ($_POST['team_id'] == $team['team_id']) ) {
											echo "<option selected value='". $team['team_id'] . "' >" . $team['name'] . "</option>";
										}
										else if( isset($user['team_id']) && ($user['team_id'] == $team['team_id']) ) {
											echo "<option selected value='". $team['team_id'] . "' >" . $team['name'] . "</option>";
										}
										else {
											echo "<option value='". $team['team_id'] . "' >" . $team['name'] . "</option>";
										}
									} ?>
								<?php } ?>
							</select>    
						</div>
						<div class="col-md-1 req-star">*</div>
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="role" class="col-md-4 control-label">Level</label>
						<div class="col-md-6">
							<select class="form-control input-sm" name="level" id="level" placeholder="Select Level">
								<?php// if($teams != '') { ?>
									<?php /* foreach($teams as $team) {  
										if( isset($_POST['level']) && ($_POST['level'] == $role['level']) ) {
											echo "<option selected value='". $role['role_id'] . "' >" . $role['name'] . "</option>";
										}
										else if( isset($user['role_id']) && ($user['role_id'] == $role['role_id']) ) {
											echo "<option selected value='". $role['role_id'] . "' >" . $role['name'] . "</option>";
										}
										else {
											echo "<option value='". $role['role_id'] . "' >" . $role['name'] . "</option>";
										} */
									//} ?>
								<?php //} ?>
							</select>    
						</div>
						<div class="col-md-1 req-star">*</div>
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="role" class="col-md-4 control-label">Belong To</label>
						<div class="col-md-6">
							<select class="form-control input-sm" name="user_belong_to" id="user-belong-to" placeholder="User Belong To">
								
							</select>    
						</div>
						<div class="col-md-1 req-star">*</div>
					</div>
					<div id ='image-form2'>
						<input type ="hidden" name ="user_img" id="img-new-file-name" value ="<?php echo isset($_POST['img_new_file_name']) ? $_POST['img_new_file_name'] : ( isset($user['user_img']) ? $user['user_img'] : '') ; ?>" >
					</div>
            </div>
            <div class="col-md-6">
			  <div class="form-group">
						<label for="username" class="col-md-4 control-label">Email</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="email" name="email" placeholder="Enter Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ( isset($user['email']) ? $user['email'] : '') ; ?>">
						</div>
						<div class="col-md-1 req-star">*</div>
					</div>
					<div class="clearfix sp-margin-sm"></div>
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
						   <!--textarea class="form-control input-sm" id="address" name="address" placeholder="Enter Address"><?//= isset($_POST['address']) ? $_POST['address'] : ( isset($user['address']) ? $user['address'] : '') ; ?></textarea-->
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
							<input type="text" class="form-control input-sm" id="commission" name="commission" placeholder="Enter Commission" value="<?= isset($_POST['commission']) ? $_POST['commission'] : ( isset($user['commission']) ? $user['commission'] : '') ; ?>">
						</div>
					   <!--div class="col-md-1 req-star">*</div-->
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="username" class="col-md-4 control-label">Co-Broke Commission</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="co-broke-commission" name="co_broke_commission" placeholder="Enter Co-Broke Commission" value="<?= isset($_POST['co_broke_commission']) ? $_POST['co_broke_commission'] : ( isset($user['co_broke_commission']) ? $user['co_broke_commission'] : '') ; ?>">
						</div>
					   <!--div class="col-md-1 req-star">*</div-->
					</div>
					<div class="clearfix sp-margin-sm"></div>
					<div class="form-group">
						<label for="username" class="col-md-4 control-label">Internal Commission</label>
						<div class="col-md-6">
							<input type="text" class="form-control input-sm" id="internal-commission" name="internal_commission" placeholder="Enter Internal Commission" value="<?= isset($_POST['internal_commission']) ? $_POST['internal_commission'] : ( isset($user['internal_commission']) ? $user['internal_commission'] : '') ; ?>">
						</div>
					   <!--div class="col-md-1 req-star">*</div-->
					</div>
					<div id ='cv-form2'>
						<input type ="hidden" name ="cv" id="cv-new-file-name" value ="<?php echo isset($_POST['img_new_file_name']) ? $_POST['img_new_file_name'] : ( isset($user['cv']) ? $user['cv'] : '') ; ?>" >
					</div>
					<div class="clearfix sp-margin-sm"></div> 
					
					<div class="clearfix sp-margin-sm"></div>
              
            
            </div>
			
			
	
			
            
            
          <!-- </form> -->
      </div>
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
								
								
								<img id ="main-img-preview" class= " img-responsive" name = "user_img" src ="<?php echo isset($_POST['user_img']) ? base_url().$_POST['user_img'] : ( isset($user['user_img']) ? base_url().$user['user_img'] : '') ; ?>" height=100 width = 100></img>
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
								<input type ="text" class="form-control input-sm" name ="cv" id="cv-new-file-name" value ="<?php echo isset($_POST['user_img']) ? base_url().'/'.$_POST['cv'] : ( isset($user['cv']) ? base_url().'/'.$user['cv'] : '') ; ?>" readonly>
							  </div>

							</div>
						</form>
					</div>
				<div class="clearfix sp-margin-lg"></div>
			<div class="form-group">
                <div class="col-xs-12 text-right">
                  <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
                </div>
            </div>			
            <div class="clearfix sp-margin-sm"></div>
		</div>
</section>
