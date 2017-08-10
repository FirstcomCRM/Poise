<?php  
    $action_url =  ($action == 'new') ? base_url() ."role/create" : base_url()."role/edit/".$role['role_id'];
?>

<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="breadcrumb">
        <i class="fa fa-windows bc-icon"></i>Dashboard <i class="fa fa-angle-double-right bc-arrow"></i> <i class="fa fa-users bc-icon"></i>User <i class="fa fa-angle-double-right bc-arrow"></i> <i class="fa fa-user-secret bc-icon"></i>Role <i class="fa fa-angle-double-right bc-arrow"></i> <?= ($action == 'new') ? "Add " : "Edit " ?> Role
      </div>
      <div class="form-panel" id="main-content-area">
        <h2><span><?= ($action == 'new') ? "Add " : "Edit " ?> Role</span></h2>
          <div class="clearfix sp-margin-md"></div>
          <div class="row-fluid">
              <div class="col-md-12 text-right"><a href="<?= base_url().'role'; ?>" class="btn btn-default btn-create"><i class="fa fa-arrow-left ico-btn"></i>Back</a></div>
          </div>
          <form role="form" id="role-form" method="post" action="<?= $action_url ?>">
            <?php if(validation_errors()) { ?>
                <div class="clearfix sp-margin-sm"></div>
                <div class="alert alert-danger"> <?= validation_errors(); ?> </div>  
            <?php } ?>
            <div class="clearfix sp-margin-md"></div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">Name</label>
                <div class="col-md-5">
                    <input type="text" class="form-control input-sm" id="name" name="name" placeholder="Enter role Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ( isset($role['name']) ? $role['name'] : '') ; ?>">
                </div>
                <div class="col-md-1 req-star">*</div>
            </div>
            <div class="clearfix sp-margin-sm"></div>  
            <div class="form-group">
                <label for="description" class="col-md-2 control-label">Description</label>
                <div class="col-md-5">
                    <textarea class="form-control input-sm" id="description" name="description" placeholder="Enter Description"><?php echo isset($_POST['description']) ? $_POST['description'] : ( isset($role['description']) ? $role['description'] : '') ; ?></textarea>
                </div>
            </div>
            <div class="clearfix sp-margin-sm"></div>
            <div class="form-group">
                <label for="dob" class="col-md-2 control-label"></label>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-mtac "><i class="fa fa-save ico-btn"></i><?php echo ($action == 'new') ? "Save" : "Update" ?></button>
                </div>
            </div>
          </form>
      </div>
    </div>
  </section>
</section>

