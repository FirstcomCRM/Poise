<?php  
    $action_url =  ($action == 'new') ? base_url() ."announcement/create" : base_url()."announcement/edit/".$announcement['announce_id'];
?>
<link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery.textcomplete.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.theme.css" rel="stylesheet">
<script src="<?= base_url();?>js/jquery.textcomplete.min.js"></script>
<!--script src="<?= base_url();?>js/DT_bootstrap.js"></script-->

<!--script src="<?= base_url();?>js/jquery_1.5.2.js"></script>
<script src="<?= base_url();?>js/jquery-1.11.1.js"></script-->
<!--script src="<?= base_url();?>js/select2-4.js"></script-->
<script src="<?= base_url();?>js/jquery.autosize.min.js"></script>
<!--script src="<?= base_url();?>js/vpb_uploader.js"></script-->
<!-- <script src="<?= base_url();?>js/ckeditor/ckeditor.js"></script> -->

<script>
  var burl = "<?= base_url() ?>";
 

  
  <?php if( isset($details) ) { ?>
    var detail_arr = <?= json_encode($details); ?>;   
  <?php } ?>

   $( document ).ready(function() {
		//	loadsubcategory();
             
    $("#announcement-date").datepicker({
        format: "yyyy-mm-dd",
        autoclose :true,
    }); 
/*
    $("#delivery-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#announcement-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 
	
	 $("#payment-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 
 */
 
	
		
	
  });

 
 function resetForm() {
		$("#detail-form").each(function(){
		  this.reset();
		});

		$('#description').trigger('autosize.resize');
		$('#detail-add').show();
		$('#detail-update').hide();

	  }
	  
	  function setfilename(val)
		  {
			  alert(val);
			//var fileName = val.substr(val.lastIndexOf("\\")+1, val.length);
			//document.getElementById("main_img_preview").value = fileName;
		  } 
  /* function FileUploadDone(e, data) {
for (x in data._response.result.files){
    if (data._response.result.files[x].error == "")
        alert(data._response.result.files[x].name);
    else
        alert(data._response.result.files[x].error);
}} */

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



</script>

<?php 

/* if($is_quotation==true){
	
	$page = 'quotation';
	$readonly= '';
}
else{
	$page = 'announcement';
	$readonly= 'readonly';
}
 
 
 use the following if needed:
 <div class="col-md-1 req-star">*</div>
 <div class="clearfix sp-margin-sm"></div> 
 
 
 
 
 */

if ( $action == "new" ) { ?>
  
   <!--script src="<?= base_url();?>js/js/main.js"></script-->
   <script src="<?= base_url();?>js/announcement_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/announcement_edit.js"></script>
  
<?php } ?>
<!--script src="<?= base_url();?>js/vpb_uploader.js"></script-->
<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= base_url().'announcement'; ?>">Announcement</a></li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?> Announcement</li>
		</ol>
		<div class="box"> 
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Announcement</h3>
			 <div class="pull-right">
                <a href="<?= base_url().'announcement'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
	<div class ="col-md-6">
		
		  <div class="box-body">
			<div class="form-group">
                <input type="hidden" id="hid-submitted" name="hid_submitted" value="1" />
                <input type="hidden" id="hid-announcement-id" name="hid_announcement_id" value="<?= (isset($announcement['announce_id'])) ? $announcement['announce_id'] : ''; ?>" />
                <label for="name" class="col-md-3 control-label">Title</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="announcement-title" name="announcement_title" placeholder="Enter Announcement Title" value="<?= isset($_POST['announcement_title']) ? $_POST['announcement_title'] : ( isset($announcement['announce_title']) ? $announcement['announce_title'] : '') ; ?>">
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
            </div> 
			<div class="sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Date</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="announcement-date" name="announcement_date" placeholder="Enter Announcement Date" value="<?= isset($_POST['announcement_date']) ? $_POST['announcement_date'] : ( isset($announcement['announce_date']) ? $announcement['announce_date'] : '') ; ?>">
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
              </div>
			<div class="sp-margin-md"></div> 
            <div class="form-group">
			  <label for="name" class="col-md-3 control-label">Announcement</label>
			  <div class="col-sm-7">
				<textarea class="form-control input-sm" id="announcement-body" name="announcement_body" placeholder="Write your announcement" height = "20" width = "100" style="height: 20em;width: 37em;*/" required><?= isset($_POST['announcement_body']) ? $_POST['announcement_body'] : ( isset($announcement['announce_body']) ? $announcement['announce_body'] : '') ; ?></textarea>
			  </div>
			</div>   
			<div class="sp-margin-sm"></div> 			
			<div class="form-group">
				<form id ="image-form">
				  <label for="name" class="col-md-3 control-label">Image</label>
				  <div class="col-sm-7">
					<input type="file" name="main_image[]" id ="main_img_select" onchange="showMyImage(this);ValidateMainImage(this)">
					<input type="hidden" class="form-control input-sm" id="main-file-name" name="main_file_name" placeholder="Upload an Image" value="">
					<h5><em>Click on the image to remove</em></h5>
					<!--button type="submit" class="btn btn-primary" id="btn-clear">Clear</button-->

					<img id ="main-img-preview" src ="<?= ($action == 'edit') ? base_url().$announcement['announce_img'] : "" ?>" height=100 width = 100></img>
					
				  </div>
				</form>
				<form id ='image-form2' style ="display:none;">
				<?= ($action=='edit')? '<input type ="hidden" name ="main_new_file_name" id="main-new-file-name" value ="'.$announcement['announce_img'].'" >' : "" ?>
				
				</form>
			</div> 
			
			<!--input type ="file" name ="sample_files[]" multiple==-->
			
 				  				   
		  </div><!-- /.box-body -->

		  
		
	</div>
		
	<div class = "col-md-12">
	<div class="panel panel-default tab-panel">
              <div class="panel-heading">
                  Upload Files
              </div>
              <div class="panel-body">
                <div class="row-fluid table-responsive" id="tbl-payment-detail">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" id='detail-table'>
                          <thead>
                            <tr>
                              <th style="width:20%">File</th>
                              <th style="width:20%">Path</th>
                              <th style="width:20%">Action</th>
                            </tr>
                          </thead>
                          <tbody> 
                            <?php if( isset($details) ) { 
                              foreach($details as $key=>$pd) {
                            ?>
                              <!-- <tr class="id-<?= $pd['announce_file_id'] ?>">
                                <td class="small-tbl-column"><?=$pd['file_name'] ?></td>
                                <td><?= $pd['file_path'] ?></td>
                                <td> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td>
                              </tr> -->
                            <?php
                              }
                            } ?>
                          </tbody>
                          <tfoot>
                              <tr id="detail-row">
								  <form id="detail-form">
									<td>
									  <!--input type="hidden" id="hid-edit-id" name="hid_edit_id" /-->
										<input type="file" name="images[]" id ="img_select" onchange = "ValidateSingleInput(this);">
									</td>
									<td>
										<input type="text" class="form-control input-sm" id="path" name="path" placeholder="Enter Qty" value="" readonly>
										<input type="hidden" class="form-control input-sm" id="file-name" name="file_name" placeholder="Enter Qty" value="">
									</td>
									<td>
									 <!--input type="file" name="upload_file1" class="btn btn-default" id="upload_file1" readonly="true"/-->
									  <span id="detail-add"><a href="#" id="ico-add"><i class="fa fa-plus ico"></i></a></span>
									  <span id="detail-update" style="display: none;"><a href="#" id="ico-update" ><i class="fa fa-save ico"></i></a> / <a href="#" id="ico-cancel" ><i class="fa fa-eraser ico"></i></a></span>
									</td>
								  </form>
                              </tr>
                              <tr id="total-row">
                               
                              </tr>
                             
                          </tfoot>
                        </table>      
                    </div>
                </div>    
              </div>
            </div>
	</div>
			
		<div class="form-group">
                <div class="col-xs-12 text-right">
                  <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
                </div>
            </div>		
	</div>
	
	<!--div class = "col-md-12">
		<form name="form_id" id="form_id" action="javascript:void(0);" enctype="multipart/form-data" style="width:800px; margin-top:20px;">  
			<input type="file" name="vasplus_multiple_files" id="vasplus_multiple_files" multiple="multiple" style="padding:5px;"/>      
			<input type="submit" value="Upload" style="padding:5px;"/>
			<!--button type="submit" class="btn btn-primary" value="Upload">Upload</button-->
		

		<!--table class="table table-striped table-bordered" id="files-table">
			<thead>
				<tr>
					<th style="color:blue; text-align:center;">File Name</th>
					<th style="color:blue; text-align:center;">Status</th>
					<th style="color:blue; text-align:center;">File Size</th>
					<th style="color:blue; text-align:center;">Action</th>
				<tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
			</form>			
		<div class="box-footer">
			<button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
		</div>
	</div-->

</section>
