<div class="form-group">
    <label for="role" class="col-md-2 control-label">Role</label>
    <div class="col-md-5"><?= $role; ?></div>
</div>
<div class="clearfix sp-margin-sm"></div>  
<div class="form-group">
    <label for="permission" class="col-md-2 control-label">Permission</label>
    <div class="col-md-10"> 
       <?php 
            foreach($permission as $perm) {

                $exp_perm = explode(', ', $perm['permission']);
                $perm_arr = array();

                $perm['controller'] = ( $perm['controller'] == 'admin' ) ? 'System User' :  $perm['controller'];
 
                foreach($exp_perm as $key=>$name) {
                    if($name == 'index') {
                        $perm_arr[] = 'View';
                    }
                    else if($name == 'view') {
                        $perm_arr[] = 'Detail';
                    }
                    else {
                        $perm_arr[] = ucfirst($name);
                    }

                }                
                echo "<div><label class='perm-level'>" . ucfirst($perm['controller']) . "</label></div>";
                echo "<div>" . implode(', ', $perm_arr) . "</div>";
                echo "<div class='clearfix sp-margin-lg'></div> ";
            }
        ?>
    </div> 
</div>
<div class="clearfix sp-margin-sm"></div> 