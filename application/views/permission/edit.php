<?php  
    $action_url =  ($action == 'new') ? base_url() ."permission/create" : base_url()."permission/edit/".$role_id;
?>

<script type="text/javascript">
    $(document).ready(function() {
      $('.chk-all').each(function(){
        var cls_name = $(this).parent().parent().next().attr('class');
        handleParentchk(cls_name);
      });

      $('.chk-all').change(function() {
        if($(this).is(':checked')) {
          var cls_name = $(this).parent().parent().next().attr('class');
          $("."+cls_name).find(':checkbox').each(function(){
            $(this).prop('checked', true);
          });
        }
        else {
          var cls_name = $(this).parent().parent().next().attr('class');
          $("."+cls_name).find(':checkbox').each(function(){
            $(this).prop('checked', false);
          });  
        }  
      });

      $('.chk-child').change(function() {
        var cls_name = $(this).parent().parent().attr('class');
        handleParentchk(cls_name);
      
      });

      function handleParentchk(cls_name) {
        var no_chkbx = $("."+cls_name+" input:checkbox").length;
        var no_chked_chkbx = $("."+cls_name+" input:checkbox:checked").length;
        
        if(no_chkbx == no_chked_chkbx) {
          $("."+cls_name).prev().children().children().prop('checked', true);
        }
        else {
          $("."+cls_name).prev().children().children().prop('checked', false);  
        }  
      }
    });
</script>

<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= base_url().'permission'; ?>"></i> Permission</a></li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?>Permission</li>
		</ol>
		<div class="box">
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Permission</h3>
			 <div class="pull-right">
                      <a href="<?= base_url().'permission'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
			<form permission="form" id="permission-form" method="post" action="<?= $action_url ?>">
            <div class="form-group">
                <label for="role" class="col-md-2 control-label">Role</label>
                <div class="col-md-5">
                    <select class="form-control input-sm" name="role_id" id="role-id" required>
                        <option value="" selected>Select Role</option>
                        <?php if($roles != '') { ?>
                            <?php foreach($roles as $role) {  
                                if( isset($_POST['role_id']) && ($_POST['role_id'] == $role['role_id']) ) {
                                    echo "<option selected value='". $role['role_id'] . "' >" . $role['name'] . "</option>";
                                }
                                else if( isset($role_id) && ($role_id == $role['role_id']) ) {
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
                <label for="permission" class="col-md-2 control-label">Permission</label>
                <div class="col-md-10">
                   <?php 
                      // $i and $sp_margin_arr (use for sprate margin)
                      $i = 1; $sp_margin_arr =array();//$sp_margin_arr = array(5, 7, 10, 14, 15, 16, 17); 
                      foreach($controllers as $key=>$methods) {
                        $checked = '';
                        $key = ( $key == 'admin' ) ? 'System User' :  $key;
                        echo "<div><label class='checkbox-inline perm-level'><input type='checkbox' class='chk-all'/>" . ucfirst($key) . "</label></div>";
                        echo "<div class='chk-gp-".$key."'>";
                        foreach($methods as $method) {
                          //For Edit Checked
                          if(isset($permission) && count($permission) > 0 ) {
                            foreach($permission as $perm) {
                              if($perm['controller'] == $key) {
                                $arr = explode(', ', $perm['permission']);
                                $checked = (in_array($method,  $arr)) ? 'checked' : '';
                              }
                            }
                          }
                          $methodName = ($method == 'index') ? 'list' : ( ($method == 'view') ? 'Detail' : $method );
                          echo "<label class='checkbox-inline'>";
                          echo "<input class='chk-child' type='checkbox' name='" . $key . '[' . $method . "]' value='1' " . $checked . "/>" . ucfirst($methodName);
                          echo "</label>";
                        }
                        echo "</div>";
                        echo "<div class='clearfix sp-margin-md'></div> ";
                        if( in_array($i, $sp_margin_arr) ) {
                          echo "<div class='clearfix sp-margin-lg'></div> ";
                        }
                        $i++;
                      }
                    ?>
                </div> 
            </div>
            <div class="clearfix sp-margin-sm"></div>
            <div class="form-group">
                <label for="dob" class="col-md-2 control-label"></label>
                <div class="col-md-5">
					 <button type="submit" class="btn btn-primary" ><?php echo ($action == 'new') ? "Save" : "Update" ?></button>
				</div>
            </div>
          </form>
      </div>
</section>

