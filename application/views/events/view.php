<div class="row">
  <div class="form-panel view-box" id="main-content-area">
    <h2><span>View Announcement Detail</span></h2>
        <div class="clearfix sp-margin-lg"></div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Title :</label>
            <div class="col-md-7"><?= $announcement['announce_title']; ?></div>
          </div> 
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Announcement :</label>
              <div class="col-md-7"><?= $announcement['announce_body'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Image :</label>
              <div class="col-md-7"><?= $announcement['announce_img'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div>  
        </div>
   
               
        <div class="clearfix sp-margin-sm"></div>
  </div>
</div>

