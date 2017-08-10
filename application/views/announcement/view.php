<div class="row">
  <div class="form-panel view-box" id="main-content-area">
    <h2>View Announcement Detail</h2>
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
          <!--div class="form-group">
              <label for="name" class="col-md-5 control-label">Image :</label>
              <div class="col-md-7"><?= $announcement['announce_img'] ?> </div>
          </div-->
          <div class="clearfix sp-margin-sm"></div>  
        </div>
		<div class="col-md-12">
         <div class="panel panel-default tab-panel">
          <div class="panel-heading">
              Uploaded Files
          </div>
          <div class="panel-body">
            <div class="row-fluid table-responsive" id="tbl-detail">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" id='detail-table'>
                      <thead>
                        <tr>
                          <th style="width:20%">File</th>
                          <th style="width:20%">Path</th>
						
                        </tr>
                      </thead>
                      <tbody> 
                        <?php if( isset($details) ) { 
                          foreach($details as $key=>$pd) {
                        ?>
                          <tr class="id-<?= $pd['announce_file_id'] ?>">
                     
                            <td><a href ="<?=base_url(). $pd['file_path'] ?>" target="_blank"><?= $pd['file_name'] ?></a></td>
                            <td><?=$pd['new_file_name']?></td>
                          
                          </tr>
                        <?php
                          }
                        } ?>
                      </tbody>
                      <tfoot>
                       
                      </tfoot>
                    </table>      
                </div>
            </div>    
          </div>
        </div>    
		</div>		
        <div class="clearfix sp-margin-sm"></div>
  </div>
</div>

