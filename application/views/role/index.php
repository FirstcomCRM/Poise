<script>
  var burl = "<?= base_url() ?>";

  $(function(){
     var tbl = $('#role-table').dataTable({
        "processing": true,
        "serverSide": true,
        'iDisplayLength': 20,
        //'bFilter': false, 
        //'bInfo': false,
        "order": [[ 1, "asc" ]], // Default Sroting by Desc
        "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
        "ajax": {
            "url": "<?= base_url() ?>role/index/dt",
            "type": "POST",
            "data" : { }
        },
        "columns": [
            { "data": "no", "orderable": false, "bSearchable": false },
            { "data": "name"},
            { "data": "description"},
            { "data": "action", "orderable": false, "bSearchable": false }
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
      var url = 'role/create';
      $('#submiturl').val(url);
      $('.quick-action').html('Add');
      $("#quickModal").modal('show');
    });

    // Quick Edit
    $('#tbl-role').on('click', '.edit-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      var editurl = 'role/aj_edit/' + id;
      getEditvalues(editurl);
      var updateurl = 'role/edit/' + id;
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
    $('#tbl-role').on('click', '.delete-link', function(e) {
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
      var url = 'role/delete/' + del_id;
      deleteAjax(url, tbl);
    });
    
  });
</script>
<!-- Modal (For Confirm Delete)-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
<div class="modal fade" id="quickModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><span class="quick-action"></span> Role</h4>
      </div>
      <div class="modal-body">
        <form role="form" id="quick-form">
            <div class="clearfix sp-margin-sm"></div>
              <div class="alert alert-danger err-display" style="display:none;"> </div> 
            <div class="clearfix sp-margin-md"></div>
            <input type="hidden" id="submiturl" name="submiturl" value="">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">Name</label>
                <div class="col-md-6">
                    <input type="text" class="form-control input-sm" id="name" name="name" placeholder="Enter role Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ( isset($role['name']) ? $role['name'] : '') ; ?>" required>
                </div>
                <div class="col-md-1 req-star">*</div>
            </div>   
            <div class="clearfix sp-margin-sm"></div>  
            <div class="form-group">
                <label for="description" class="col-md-2 control-label">Description</label>
                <div class="col-md-6">
                    <textarea class="form-control input-sm" id="description" name="description" placeholder="Enter Description"><?php echo isset($_POST['description']) ? $_POST['description'] : ( isset($role['description']) ? $role['description'] : '') ; ?></textarea>
                </div>
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
			<li class="active">Role</li>
		</ol>
		<div class="box">		  
          <div class="box-header">
             <h3 class="box-title">Role</h3>
			 <div class="pull-right">
				<!--a href="<?= base_url().'role/create'; ?>" class="btn btn-default btn-flat"><i class="fa fa-plus ico-btn"></i>Add</a-->
				<a href="#" id="quick-add" class="btn btn-default btn-create"><i class="fa fa-plus ico-btn"></i> Add</a>
			</div>
           </div><!-- /.box-header -->
			<div class="box-body">
				<div class="success-alert-area"> </div>
				<?php if(isset($msg) && $msg != '') { ?>
					<div class="alert alert-success"><a href='#' class='close' data-dismiss='alert'>&times;</a><?= $msg; ?></div>
				<?php } ?>
				<div class="row-fluid table-responsive" id="tbl-project">
					<div class = "col-md-12">
						<table class="table table-striped table-bordered dataTable no-footer" id = 'role-table'>
							<thead>
							  <tr>
								<th>No</th>
								<th>Name</th>
								<th>Description</th>
								<th>Action</th>       
							  </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
							  <tr>
								<th>No</th>
								<th>Name</th>
								<th>Description</th>
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