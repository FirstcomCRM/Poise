<?php 
	$action_url =  ($action == 'new') ? base_url()."admin/create" : base_url()."admin/edit/".$admin['admin_id'];
?>
<script>
    $( document ).ready(function() {
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

       $('#btn-submit').click(function(e){
            e.preventDefault();
            if($('#password').val() != $('#repassword').val()) {
                $('#password, #repassword').closest('.form-group').addClass('has-error');
            }
            else {
                $('#admin-form').submit();    
            }

        }); 
    });
</script>

<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="breadcrumb">
        <i class="fa fa-windows bc-icon"></i>Dashboard <i class="fa fa-angle-double-right bc-arrow"></i> <i class="fa fa-users bc-icon"></i>User <i class="fa fa-angle-double-right bc-arrow"></i> <i class="fa fa-user-secret bc-icon"></i>Admin User <i class="fa fa-angle-double-right bc-arrow"></i> <?= ($action == 'new') ? "Add " : "Edit " ?> User
      </div>
      <div class="form-panel" id="main-content-area">
        <h2><span><?= ($action == 'new') ? "Add " : "Edit " ?> Admin User</span></h2>
        <div class="clearfix sp-margin-md"></div>
        <div class="row-fluid">
            <div class="col-md-12 text-right"><a href="<?= base_url().'admin'; ?>" class="btn btn-default btn-create"><i class="fa fa-arrow-left ico-btn"></i>Back</a></div>
        </div> 
        <form admin="form" id="admin-form" method="post" action="<?= $action_url ?>">
            <?php if(validation_errors()) { ?>
                <div class="clearfix sp-margin-sm"></div>
                <div class="alert alert-danger"> <?php echo validation_errors(); ?> </div>  
            <?php } ?>
            <div class="clearfix sp-margin-md"></div>  
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">Name</label>
                <div class="col-md-5">
                    <input type="text" class="form-control input-sm" id="name" name="name" placeholder="Enter admin Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ( isset($admin['name']) ? $admin['name'] : '') ; ?>">
                </div>
                <div class="col-md-1 req-star">*</div>
            </div>
            <div class="clearfix sp-margin-sm"></div>
            <div class="form-group">
                <label for="username" class="col-md-2 control-label">Username</label>
                <div class="col-md-5">
                    <input type="text" class="form-control input-sm" id="username" name="username" placeholder="Enter Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ( isset($admin['username']) ? $admin['username'] : '') ; ?>">
                </div>
                <div class="col-md-1 req-star">*</div>
            </div>
            <div class="clearfix sp-margin-sm"></div>
            <div class="form-group">
                <label for="password" class="col-md-2 control-label">Password</label>
                <div class="col-md-5">
                    <input type="password" class="form-control input-sm" id="password" name="password" placeholder="Enter Password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ; ?>">
                </div>
                <div class="col-md-1 req-star">*</div>
            </div>
            <div class="clearfix sp-margin-sm"></div>
            <div class="form-group">
                <label for="repassword" class="col-md-2 control-label">ReEnter Password</label>
                <div class="col-md-5">
                    <input type="password" class="form-control input-sm" id="repassword" name="repassword" placeholder="Enter Password Again" value="<?php echo isset($_POST['repassword']) ? $_POST['repassword'] : '' ; ?>">
                </div>
                <div class="col-md-1 req-star">*</div>
            </div>    
            <div class="clearfix sp-margin-sm"></div>
            <div class="form-group">
                <label for="role" class="col-md-2 control-label">Role</label>
                <div class="col-md-5">
                    <select class="form-control input-sm" name="role_id" id="role-id">
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
                <label for="dob" class="col-md-2 control-label"></label>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-mtac" id="btn-submit"><?php echo ($action == 'new') ? "Save" : "Update" ?></button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </section>
</section>