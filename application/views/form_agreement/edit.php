<?php  
    $action_url =  ($action == 'new') ? base_url() ."form_agreement/create" : base_url()."form_agreement/edit/".$form_agreement['form_id'];
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
<!-- <script src="<?= base_url();?>js/ckeditor/ckeditor.js"></script> -->
<script>
  var burl = "<?= base_url() ?>";
 

  
  <?php if( isset($details) ) { ?>
    var detail_arr = <?= json_encode($details); ?>;   
  <?php } ?>
  
  
  
  
  
   $( document ).ready(function() {
		//	loadsubcategory();
             
    $("#form_agreement-date").datepicker({
        format: "yyyy-mm-dd",
        autoclose :true,
    }); 
/*
    $("#delivery-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#form_agreement-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 
	
	 $("#payment-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 
 */

	// Call the main function
	/* new vpb_multiple_file_uploader
	({
		vpb_form_id: "form_id", // Form ID
		autoSubmit: true,
		vpb_server_url: "upload" 
	}); */
	
	   $("input[id^='upload_file']").each(function() {

            var id = parseInt(this.id.replace("upload_file", ""));
            
            $("#upload_file" + id).change(function() {
                if ($("#upload_file" + id).val() !== "") {
                    $("#moreImageUploadLink").show();
                }
            });
        });
	
		var upload_number = 2;
        $('#attachMore').click(function() {
            //add more file
            var moreUploadTag = '';
            moreUploadTag += '<div class="element"><label for="upload_file"' + upload_number + '>Upload File ' + upload_number + '</label>';
            moreUploadTag += '<input type="file" id="upload_file' + upload_number + '" name="upload_file' + upload_number + '"/>';
            moreUploadTag += '&nbsp;<a href="javascript:del_file(' + upload_number + ')" style="cursor:pointer;" onclick="return confirm(\"Are you really want to delete ?\")">Delete ' + upload_number + '</a></div>';
            $('<dl id="delete_file' + upload_number + '">' + moreUploadTag + '</dl>').fadeIn('slow').appendTo('#moreImageUpload');
            upload_number++;
        });
		
		 function del_file(eleId) {
				var ele = document.getElementById("delete_file" + eleId);
				ele.parentNode.removeChild(ele);
			}
    
  });

 
 function resetForm() {
    $("#detail-form").each(function(){
      this.reset();
    });

    //$('#description').trigger('autosize.resize');
    $('#detail-add').show();
    $('#detail-update').hide();

  }
  
</script>

<?php 

/* if($is_quotation==true){
	
	$page = 'quotation';
	$readonly= '';
}
else{
	$page = 'form_agreement';
	$readonly= 'readonly';
}
 
 
 use the following if needed:
 <div class="col-md-1 req-star">*</div>
 <div class="clearfix sp-margin-sm"></div> 
 
 
 
 
 */

if ( $action == "new" ) { ?>
  <script src="<?= base_url();?>js/form_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/form_edit.js"></script>
<?php } ?>
<!--script src="<?= base_url();?>js/vpb_uploader.js"></script-->
<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= base_url().'form_agreement'; ?>">Form Agreement</a></li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?> Form Agreement</li>
		</ol>
		<div class="box"> 
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Form Agreement</h3>
			 <div class="pull-right">
                <a href="<?= base_url().'form_agreement'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
	<div class ="col-md-6">
		
		  <div class="box-body">
			<div class="form-group">
                <input type="hidden" id="hid-submitted" name="hid_submitted" value="1" />
                <input type="hidden" id="hid-form-id" name="hid_form_id" value="<?= (isset($form_agreement['form_id'])) ? $form_agreement['form_id'] : ''; ?>" />
                <label for="name" class="col-md-3 control-label">Title</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="form-title" name="form_title" placeholder="Enter Form Title" value="<?= isset($_POST['form_title']) ? $_POST['form_title'] : ( isset($form_agreement['form_name']) ? $form_agreement['form_name'] : '') ; ?>">
                </div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
            </div> 
			<div class="sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Category</label>
                <div class="col-md-7">
                    <!--input type="text" class="form-control input-sm" id="form_agreement-date" name="form_agreement_date" placeholder="Enter form_agreement Date" value="<?= isset($_POST['form_agreement_date']) ? $_POST['form_agreement_date'] : ( isset($form_agreement['announce_date']) ? $form_agreement['announce_date'] : '') ; ?>"-->
                
				 <select class="form-control input-sm" name="category_id" id="category-id" placeholder="Select Category" required>
                    <option value="" selected>Select Category</option>
                    <?php if( isset($categories) && $categories != '') {
							foreach($categories as $category) {
								//if( (isset($_POST['category_id']) && ($_POST['category_id'] == $category['form_category_id'])) ) {
								if( (isset($_POST['category_id']) && ($_POST['category_id'] == $category['form_category_id']))|| (isset($form_agreement['category_id']) ) ) {	
									echo "<option selected value='". $category['form_category_id']. "' >" . $category['name'] . "</option>";
								}
								else {  
									echo "<option value='". $category['form_category_id']. "' >" . $category['name'] . "</option>";
								}
							}
						} 
					?>
                  </select> 
				
				
				</div>
                <!-- <div class="col-md-3"><label class="checkbox-inline"><input id="chk-mf" type="checkbox" value="1" />Revised</label></div> -->
                <div class="col-md-1 req-star">*</div>
              </div>
			<div class="sp-margin-md"></div> 
          


 				  				   
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
						  <!-- <tr class="id-<?= $pd['form_file_id'] ?>">
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
							  
								<input type="file" name="images[]" id ="img_select">
								
							</td>
							<td>
								<input type="text" class="form-control input-sm" id="path" name="path" placeholder="Upload File" value="" readonly>
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
		
		<div class="box-footer">
			<button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
		</div>
		
	</div>
	</div>
</div>
</section>
