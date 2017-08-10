<?php// $revised = ($project['revised'] == 1) ? "[ Revised ]" : ''; ?>
<script>
  <?php if($this->session->userdata('role_id') != 1) { ?>
      $('.commission-area').hide();
    <?php } ?>
</script> 
<div class="row">
  <div class="form-panel view-box" id="main-content-area">
    <div class="content-title"><span>View Project Detail</span></div>
        <div class="clearfix sp-margin-lg"></div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Project Code :</label>
            <div class="col-md-7"><?= $project['project_code'] ?></div>
          </div> 
          <div class="clearfix sp-margin-sm"></div> 
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Description :</label>
              <div class="col-md-7"><?= $project['description'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Project IC and Contact :</label>
              <div class="col-md-7"><?= $project['project_ic'] ?> </div>
          </div>  
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Date :</label>
              <div class="col-md-7"><?= date('Y/m/d', strtotime($project['date'])) ?> </div>
          </div>  
         
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Category :</label>
            <div class="col-md-7"><?= $project['category'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Project Type :</label>
            <div class="col-md-7"><?= $project['project_type'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm commission-area"></div> 
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Status :</label>
            <div class="col-md-7"><?= $project['project_status'] ?></div>
          </div>
          
          
        </div>
        <div class="clearfix sp-margin-lg"></div>
           
        <div class="clearfix sp-margin-sm"></div>
  </div>
</div>

