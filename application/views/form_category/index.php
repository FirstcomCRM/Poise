<script>
  var burl = "<?= base_url() ?>";

  $(function(){
     var tbl = $('#form-category-table').dataTable({
        "processing": true,
        "serverSide": true,
        'iDisplayLength': 20,
        //'bFilter': false, 
        //'bInfo': false,
        "order": [[ 1, "asc" ]], // Default Sroting by Desc
        "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
        "ajax": {
            "url": "<?= base_url() ?>form_category/index/dt",
            "type": "POST",
            "data" : { 
             'category'    :  function ( d ) { return $("#category").val(); },
             //'start_date'      :  function ( d ) { return $("#start-date").val(); },
              //'end_date'      :  function ( d ) { return $("#end-date").val(); },
            }
        },
        "columns": [
            { "data": "no", "orderable": false, "bSearchable": false },
            { "data": "name"},
            { "data": "action", "orderable": false, "bSearchable": false, "className": 'col_act_md' }
        ],
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
            // For auto numbering at 'No' column
            var start = tbl.api().page.info().start;
            $('td:eq(0)',nRow).html(start + iDisplayIndex + 1);
        },
    });

    $('#btn-submit').click(function(e) {  
        e.preventDefault();
        tbl.api().ajax.reload();
    });

    // Quick Add 
    $("#quick-add").click(function(e){
      e.preventDefault();
      var url = 'form_category/create';
      $('#submiturl').val(url);
      $('.quick-action').html('Add');
      $("#quickModal").modal('show');
    });

    // Quick Edit
    $('#tbl-category').on('click', '.edit-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      var editurl = 'form_category/aj_edit/' + id;
      getEditvalues(editurl);
      var updateurl = 'form_category/edit/' + id;
      $('#submiturl').val(updateurl);
      $('.quick-action').html('Edit');
      $("#quickModal").modal('show');
    });

    // Quick Submit
    $("#btn-q-submit").click(function(e){
      e.preventDefault();
      var valid = validateForm('quick-form');
      if(valid) {  
        var formdata = $("#quick-form").serializeObject();
        var url = $('#submiturl').val();
        quickAjaxsubmit(url, formdata, tbl);
      }   
    });

    //Quick Delete
    $('#tbl-category').on('click', '.delete-link', function(e) {
      e.preventDefault();
      var url =  $(this).attr("href");
      var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
      $('#hid-delete-id').val(del_id);
      $("#myModal").modal('show');
     
    });

    //Quick Delete Confirm
    $("#btn-confirm-yes").click(function(){
      var del_id= $('#hid-delete-id').val();
      $("#myModal").modal('hide');  
      var url = 'form_category/delete/' + del_id;
      deleteAjax(url, tbl);
    });
    
  });
</script>
<!-- Modal (For Confirm Delete)-->
<div class="modal fade" id="myModal" tabindex="-1" form_category="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body">
        <span>Are you sure to delete this record?</span>
        <input type="hidden" name="hid-id" id="hid-delete-id" value=''/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-mtac" id='btn-confirm-yes'>Yes</button>
      </div>
    </div>
  </div>
</div>
<!-- End Model -->

<!-- Modal (Quick Add Model) -->
<div class="modal fade" id="quickModal" tabindex="-1" form_category="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><span class="quick-action"></span> Form Category Management</h4>
      </div>
      <div class="modal-body">
        <form form_category="form" id="quick-form">
            <div class="clearfix sp-margin-sm"></div>
              <div class="alert alert-danger err-display" style="display:none;"> </div> 
            <div class="clearfix sp-margin-md"></div>
            <input type="hidden" id="submiturl" name="submiturl" value="">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">Name</label>
                <div class="col-md-6">
                    <input type="text" class="form-control input-sm" id="name" name="name" placeholder="Enter Form Category Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ( isset($form_category['name']) ? $form_category['name'] : '') ; ?>" required>
                </div>
                <div class="col-md-1 req-star">*</div>
            </div>   
            <div class="clearfix sp-margin-sm"></div> 
            <div class="form-group">
                <label for="description" class="col-md-2 control-label"></label>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-mtac" id='btn-q-submit'><i class="fa fa-save ico-btn"></i>Submit</button>
                </div>
            </div>
            <div class="clearfix sp-margin-sm"></div>
        </form>
        <input type="hidden" name="hid-id" id="hid-delete-id" value=''/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
<!-- End Model -->

<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Form Category Management</li>
		</ol>
		<div class="box">		  
          <div class="box-header">
             <h3 class="box-title">Form Category Management</h3>
			 <div class="pull-right">
				<!--a href="<?= base_url().'form_category/create'; ?>" class="btn btn-default btn-flat"><i class="fa fa-plus ico-btn"></i>Add</a-->
				<a href="#" id="quick-add" class="btn btn-default btn-create"><i class="fa fa-plus ico-btn"></i> Add</a>
			</div>
           </div><!-- /.box-header -->
			<div class="box-body">
				
				<div class="box">
					<div class="box-header with-border">
					  <h3 class="box-title">Filter</h3>

					  <div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
						  <i class="fa fa-minus"></i></button>
					  </div>
					</div>
					<div class="box-body">
						<form id="search-form" method="post" action="<?= base_url().'form_category/index'; ?>" />   
							<div class="form-group">
							  <div class="col-md-2 col-search">
								<input type="text" class="form-control input-sm" name="category" id="category" placeholder="Search Form Category" />
							  </div>
							  <div class="col-md-2 col-search" style="padding-right: 0px;">
								<button type="submit" class="btn btn-default btn-sm" id="btn-submit"><i class="fa fa-search ico-btn"></i>Search</button>
							  </div> 
							  <div class="clearfix sp-margin-sm"></div>
													  
							</div> 
						</form>
					</div>
				</div>
				<div class="success-alert-area"> </div>
				<?php if(isset($msg) && $msg != '') { ?>
					<div class="alert alert-success"><a href='#' class='close' data-dismiss='alert'>&times;</a><?= $msg; ?></div>
				<?php } ?>
				<div class="row-fluid table-responsive" id="tbl-category">
					<div class = "col-md-12">
						<table class="table table-striped table-bordered dataTable no-footer" id = 'form-category-table'>
							<thead>
							  <tr>
								<th>No</th>
								<th>Name</th>
								<th>Action</th>       
							  </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
							  <tr>
								<th>No</th>
								<th>Name</th>
								<th>Action</th>       
							  </tr>
							</tfoot>
					  </table>
					</div>
				</div>
			</div><!-- /.box-body -->
        </div><!-- /.box -->
	</div>
  </div>
</section>