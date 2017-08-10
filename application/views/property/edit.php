<?php  
    $action_url =  ($action == 'new') ? base_url() ."property/create" : base_url()."property/edit/".$property['property_id'];
?>
<link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery.textcomplete.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.css" rel="stylesheet">
<link href="<?= base_url();?>css/jquery-ui.theme.css" rel="stylesheet">
<script src="<?= base_url();?>js/jquery.textcomplete.min.js"></script>
<!--script src="<?= base_url();?>js/select2-4.js"></script-->
<script src="<?= base_url();?>js/jquery.autosize.min.js"></script>
<script src="<?= base_url();?>js/jquery-ui.js"></script>
<!-- <script src="<?= base_url();?>js/ckeditor/ckeditor.js"></script> -->
<script src="<?= base_url(); ?>js/js/main_property.js"></script>
<script>
  var burl = "<?= base_url() ?>";
  

  $(function() {

  /*   $("#date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#delivery-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#invoice-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    });  */

    //$("#stock-id").select2();



    /*$("#client-id").select2({
      minimumInputLength: 2,
      ajax: {
        url: "<?= base_url(); ?>" + "client/aj_getClients",
        dataType: 'json',
        data: function (term, page) {
          return {
            q : term
          };
        },
        processResults: function (data, page) {
          return { results: data };
        },
      },  
    });*/

   


  });

  

</script>

<?php if ( $action == "new" ) { ?>
  <script src="<?= base_url();?>js/property_add.js"></script>
<?php } elseif ( $action == "edit" ) { ?>
  <script src="<?= base_url();?>js/property_edit.js"></script>
<?php } ?>
<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= base_url().'property'; ?>"></i> Property</a></li>
			<li class="active"><?= ($action == 'new') ? "Add " : "Edit " ?>Property</li>
		</ol>
		<div class="box">
          <div class="box-header">
             <h3 class="box-title"><?= ($action == 'new') ? "Add " : "Edit " ?>Property</h3>
			 <div class="pull-right">
                      <a href="<?= base_url().'property'; ?>" class="btn btn-default btn-flat">Back</a>
             </div>
           </div><!-- /.box-header -->
		</div>
	</div>
            <div class="col-md-6">
              <div class="form-group">
                <input type="hidden" id="hid-property-id" name="hid_property_id" value="<?= (isset($property)) ? $property['property_id'] : ''; ?>" />
                <label for="name" class="col-md-3 control-label">Property Title</label>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" id="property-title" name="property_title" placeholder="Enter Property Title" value="<?= isset($_POST['property_title']) ? $_POST['property_title'] : ( isset($property['property_title']) ? $property['property_title'] : '') ; ?>"/>
					
			   </div>
           
                <div class="col-md-1 req-star">*</div>
              </div> 
              <div class="clearfix sp-margin-sm"></div>  
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Tenure</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="tenure" name="tenure" placeholder="Enter Tenure" value="<?= isset($_POST['tenure']) ? $_POST['tenure'] : ( isset($property['tenure']) ? $property['tenure'] : '') ; ?>">      
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div>  
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Location</label>
                  <div class="col-md-7">
					<select class="form-control input-sm" name="location" id="location" placeholder="Select Location">
						<option value="" selected>Select Location</option>
						<option value="Location 1" <?= ( (isset($_POST['location']) && $_POST['location'] == 'location 1') || (isset($property['location']) && $property['location'] == 'Location 1') ) ? 'selected' : ''; ?>> Location 1</option>
						<option value="Location 2" <?= ( (isset($_POST['location']) && $_POST['location'] == 'location 2') || (isset($property['location']) && $property['location'] == 'Location 2') ) ? 'selected' : ''; ?>> Location 2</option>
					</select>
                  </div>
                  <div class="col-md-1 req-star">*</div>
              </div>  
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">District</label>
                <div class="col-md-7">
				
					<select class="form-control input-sm" name="district" id="district" placeholder="Select District">
						<option value="" selected>Select District</option>
						<option value="District 1" <?= ( (isset($_POST['district']) && $_POST['district'] == 'District 1') || (isset($property['district']) && $property['district'] == 'District 1') ) ? 'selected' : ''; ?> >District 1</option>
						<option value="District 2" <?= ( (isset($_POST['district']) && $_POST['district'] == 'District 2') || (isset($property['district']) && $property['district'] == 'District 2') ) ? 'selected' : ''; ?> >District 2</option>
					</select>
				
                  <!--input type="text" class="form-control input-sm" id="contact" name="contact" placeholder="Enter Contact" value="<?//= isset($_POST['contact']) ? $_POST['contact'] : ( isset($property['contact']) ? $property['contact'] : ''); ?>" /-->
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div>  
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Category</label>
                <div class="col-md-7">
					<select class="form-control input-sm" name="category" id="category" placeholder="Select Category">
						<option value="" selected>Select Category</option>
						<option value="New Project" <?= ( (isset($_POST['category']) && $_POST['category'] == 'New Project') || (isset($property['category']) && $property['category'] == 'New Project') ) ? 'selected' : ''; ?> >New Project</option>
						<option value="Sell" <?= ( (isset($_POST['category']) && $_POST['category'] == 'Sell') || (isset($property['category']) && $property['category'] == 'Sell') ) ? 'selected' : ''; ?> >Sell</option>
						<option value="Rent" <?= ( (isset($_POST['category']) && $_POST['category'] == 'Rent') || (isset($property['category']) && $property['category'] == 'Rent') ) ? 'selected' : ''; ?> >Rent</option>
					</select>
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
			  <div class="form-group">
                <label for="name" class="col-md-3 control-label">Address</label>
                <div class="col-md-7">
                  <textarea class="form-control input-sm" id="address" name="address" placeholder="Enter Address"><?= isset($_POST['address']) ? $_POST['address'] : ( isset($property['address']) ? $property['address'] : ''); ?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Unit Size</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="unit-size" name="unit_size" placeholder="Enter Unit Size" value="<?= isset($_POST['unit_size']) ? $_POST['unit_size'] : ( isset($property['unit_size']) ? $property['unit_size'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div>
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Land Area</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="land-area" name="land_area" placeholder="Enter Land Area" value="<?= isset($_POST['land_area']) ? $_POST['land_area'] : ( isset($property['unit_size']) ? $property['land_area'] : ''); ?>" />  
                </div>
              </div>
			  
              <div class="clearfix sp-margin-sm"></div>
              <!--div class="form-group">
                <label for="name" class="col-md-3 control-label">Status</label>
                <div class="col-md-7">
                  <select class="form-control input-sm" name="status" id="property-status">
                    <option value="" selected>Select Status</option>
                    <option value="K.I.V" <?= ( (isset($_POST['property_status']) && $_POST['property_status'] == 'K.I.V') || (isset($property['property_status']) && $property['property_status'] == 'K.I.V') ) ? 'selected' : ''; ?> >K.I.V</option>
                    <option value="W.I.P" <?= ( (isset($_POST['property_status']) && $_POST['property_status'] == 'W.I.P') || (isset($property['property_status']) && $property['property_status'] == 'W.I.V') ) ? 'selected' : ''; ?> >W.I.P</option>
                    <option value="Completed" <?= ( (isset($_POST['property_status']) && $_POST['property_status'] == 'Completed') || (isset($property['property_status']) && $property['property_status'] == 'Completed') ) ? 'selected' : ''; ?> >Completed</option>
                    <option value="Cancelled" <?= ( (isset($_POST['property_status']) && $_POST['property_status'] == 'Cancelled') || (isset($property['property_status']) && $property['property_status'] == 'Cancelled') ) ? 'selected' : ''; ?> >Cancelled</option>
                  </select>  
                </div>
              </div-->
              <div class="clearfix sp-margin-sm"></div> 
            </div>
            <div class="col-md-6">
			  <div class="form-group">
                <label for="name" class="col-md-3 control-label">No. of Bedrooms</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="bedrooms" name="bedrooms" placeholder="Enter No of Bedrooms" value="<?= isset($_POST['bedrooms']) ? $_POST['bedrooms'] : ( isset($property['bedrooms']) ? $property['bedrooms'] : ''); ?>" />  
                </div>
              </div>
			  <div class="clearfix sp-margin-sm"></div>
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Property Price</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="property-price" name="property_price" placeholder="Enter Property Price" value="<?= isset($_POST['property_price']) ? $_POST['property_price'] : ( isset($property['property_price']) ? $property['property_price'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Price Currency</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="price-currency" name="price_currency" placeholder="Enter Price Currency" value="<?= isset($_POST['price_currency']) ? $_POST['price_currency'] : ( isset($property['price_currency']) ? $property['price_currency'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Meta Description</label>
                <div class="col-md-7">
                  <textarea class="form-control input-sm" id="meta-description" name="meta_description" placeholder="Enter Meta Description"><?= isset($_POST['meta_description']) ? $_POST['meta_description'] : ( isset($property['meta_description']) ? $property['meta_description'] : ''); ?></textarea>
                </div>
              </div>
              <div class="clearfix sp-margin-sm"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Meta Robots Index</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="meta-robots-index" name="meta_robots_index" placeholder="Enter Meta Robots Index" value="<?= isset($_POST['meta_robots_index']) ? $_POST['meta_robots_index'] : ( isset($property['meta_robots_index']) ? $property['meta_robots_index'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm commission-area"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Meta Robots Follow</label>
                <div class="col-md-7">
                  <input type="text" class="form-control input-sm" id="meta-robots-follow" name="meta_robots_follow" placeholder="Enter Meta Robots Follow" value="<?= isset($_POST['meta_robots_follow']) ? $_POST['meta_robots_follow'] : ( isset($property['meta_robots_follow']) ? $property['meta_robots_follow'] : ''); ?>" />  
                </div>
              </div>
              <div class="clearfix sp-margin-sm commission-area"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Meta Keywords</label>
                <div class="col-md-7">
                  <textarea class="form-control input-sm" id="meta-keywords" name="meta_keywords" placeholder="Enter Meta Description"><?= isset($_POST['meta_description']) ? $_POST['meta_description'] : ( isset($property['meta_description']) ? $property['meta_description'] : ''); ?></textarea>
                </div>
              </div>
              <div class="clearfix sp-margin-sm commission-area"></div> 
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Status</label>
                <div class="col-md-7">
					<select class="form-control input-sm" name="property_status" id="property-status" placeholder="Select Status">
						<option value="" selected>Select Status</option>
						<option value="Draft" <?= ( (isset($_POST['property_status']) && $_POST['property_status'] == 'Draft') || (isset($property['property_status']) && $property['property_status'] == 'Draft') ) ? 'selected' : ''; ?>> Draft</option>
						<option value="Pending" <?= ( (isset($_POST['property_status']) && $_POST['property_status'] == 'Pending') || (isset($property['property_status']) && $property['property_status'] == 'Pending') ) ? 'selected' : ''; ?>> Pending</option>
						<!--option value="" <?= ( (isset($_POST['location']) && $_POST['location'] == 'location 2') || (isset($property['location']) && $property['location'] == 'Location 2') ) ? 'selected' : ''; ?>> Location 2</option>
						<option value="Location 2" <?= ( (isset($_POST['location']) && $_POST['location'] == 'location 2') || (isset($property['location']) && $property['location'] == 'Location 2') ) ? 'selected' : ''; ?>> Location 2</option-->
					</select>
                </div>
              </div>
              <div class="clearfix sp-margin-sm commission-area"></div> 
              
            
            </div>
            <div class="clearfix sp-margin-lg"></div>
			<div class="col-md-12">
    
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>
	
		<div class="col-xs-12">
		  <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
		</div>
	
		
</div>				
            <div class="clearfix sp-margin-sm"></div>
            
          <!-- </form> -->
      </div>
    
		
	
	
	
	
	
  </section>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<Script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
			<input type = "hidden" id ="filenames" name="filenames" value ="{%=file.name%}"/>
            <p class="name" id="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% }%}
</script>

