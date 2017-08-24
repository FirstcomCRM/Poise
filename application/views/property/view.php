<?php //$revised = ($property['revised'] == 1) ? "[ Revised ]" : ''; ?>
<script>
  <?php if($this->session->userdata('role_id') != 1) { ?>
      $('.commission-area').hide();
    <?php } ?>
</script> 
<div class="row">
  <div class="form-panel view-box" id="main-content-area">
    <h2><span>View property Detail</span></h2>
	<input type="hidden" name="hid-id" id="hid-delete-id" value="<?php $property['property_id']?>"/>
        <div class="clearfix sp-margin-lg"></div>
        <div class="col-md-6">
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Property Title :</label>
              <div class="col-md-7"><?= $property['property_title'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Tenure :</label>
              <div class="col-md-7"><?= $property['tenure'] ?> </div>
          </div>  
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Location :</label>
              <div class="col-md-7"><?= $property['location'] ?> </div>
          </div>  
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">District :</label>
            <div class="col-md-7"><?= $property['district'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Property Type :</label>
            <div class="col-md-7"><?= $property['category'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div>
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Address :</label>
            <div class="col-md-7"><?= $property['address'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div>
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Unit Size :</label>
            <div class="col-md-7"><?= $property['unit_size'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Land Area :</label>
            <div class="col-md-7"><?= $property['land_area'] ?></div>
          </div>
        </div>
        <div class="col-md-6">
           <div class="form-group">
            <label for="name" class="col-md-5 control-label">No. of Bedrooms :</label>
            <div class="col-md-7"><?= $property['no_of_bedrooms'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
           <div class="form-group">
            <label for="name" class="col-md-5 control-label">Property Price :</label>
            <div class="col-md-7"><?= $property['property_price'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Price Currency :</label>
            <div class="col-md-7"><?= $property['price_currency'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Land Area :</label>
            <div class="col-md-7"><?= $property['land_area'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Meta Description :</label>
            <div class="col-md-7"><?= $property['meta_description'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Meta Robots Index :</label>
            <div class="col-md-7"><?= $property['meta_robots_index'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Meta Robots Follow :</label>
            <div class="col-md-7"><?= $property['meta_robots_follow'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Meta Keywords :</label>
            <div class="col-md-7"><?= $property['meta_keywords'] ?></div>
          </div>
		  <div class="clearfix sp-margin-sm"></div> 
		   <div class="form-group">
            <label for="name" class="col-md-5 control-label">Status :</label>
            <div class="col-md-7"><?= $property['property_status'] ?></div>
          </div>
        </div>
        <div class="clearfix sp-margin-lg"></div>
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
                          <th style="width:20%">Preview</th>
                          <th style="width:20%">File</th>
						
                        </tr>
                      </thead>
                      <tbody> 
                        <?php if( isset($details) ) { 
                          foreach($details as $key=>$pd) {
                        ?>
                          <tr class="id-<?= $pd['property_file_id'] ?>">
                     
                            <td><a href ="<?=base_url(). $pd['file_path'] ?>" target="_blank"><img src ="<?= $pd['file_path'] ?>" height="100" width="100"/></a></td>
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

