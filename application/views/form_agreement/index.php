<script>
  var burl = "<?= base_url() ?>";

  $(function(){

    $("#start-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 

    $("#end-date").datepicker({
        format: "dd/mm/yyyy",
        autoclose :true,
    }); 
/* { "data": "announce_img",
					"render": function(data, type, row) {
						return '<img height ="100" width="100" src="'+data+'" /> ';
					}},*/
     var tbl = $('#dataTable').dataTable({
        "processing": true,
        "serverSide": true,
        'iDisplayLength': 20,
        "order": [[ 1, "desc" ]], // Default Sroting by Desc
        "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
        "ajax": {
            "url": "<?= base_url() ?>form_agreement/index/dt",
            "type": "POST",
            "data" : { 
             'form_title'    :  function ( d ) { return $("#form-title").val(); },
             'category_id'      :  function ( d ) { return $("#category-id").val(); },
            //  'end_date'      :  function ( d ) { return $("#end-date").val(); },
            }
        },
        "columns": [
            { "data": "no", "orderable": false, "bSearchable": false },
            { "data": "form_name"},
            { "data": "name"},
            { "data": "action", "orderable": false, "bSearchable": false, "className": 'col_act_md'}
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

    $('#tbl-form').on('click', '.view-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'form_agreement/view/' + id,
        data :  { },
        success: function(data){ 
          $('#view-form-detail').html(data); 
           $("#viewModal").modal('show');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
      });
    });

    //Quick Delete
    $('#tbl-form').on('click', '.delete-link', function(e) {
      e.preventDefault();
      var url =  $(this).attr("href");
      var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
      $('#hid-delete-id').val(del_id);
	  console.log(url);
      $("#myModal").modal('show');
    });

    //Quick Delete Confirm
    $("#btn-confirm-yes").click(function(){
      var del_id= $('#hid-delete-id').val();
      $("#myModal").modal('hide');  
      var url = 'form_agreement/delete/' + del_id;
	  alert(url);
      deleteAjax(url, tbl);
    });

   /*  $("#client-id").select2({
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
    }); */
  
  });
</script>
<div class="modal fade" id="myModal" tabindex="-1" form_agreement="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<!-- Modal (View Model) -->
<div class="modal fade" id="viewModal" tabindex="-1" class="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">View Form Detail</h4>
      </div>
      <div class="modal-body" id="view-form-detail">
                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<div class="box">
          <div class="box-header">
			<ol class="breadcrumb">
				<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Forms and Agreement</li>
			</ol>
             <h3 class="box-title">Forms and Agreement</h3>
			 <?php if ($this->session->userdata('role_id')==1 ){ ?>
			 <div class="pull-right">
				<a href="<?= base_url().'form_agreement/create'; ?>" class="btn btn-default btn-flat"><i class="fa fa-plus ico-btn"></i>Add</a>
			 </div>
			 <?php }?>
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
					<form id="search-form" method="post" action="<?= base_url().'form_agreement/index'; ?>" />   
						<div class="form-group">
						  <div class="col-md-2 col-search">
							<input type="text" class="form-control input-sm" name="form_title" id="form-title" placeholder="Search form" />
						  </div>
						  <div class="col-md-2 col-search">
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
						  <!--div class="col-md-2 col-search">
							<input type="text" class="form-control input-sm" name="end_date" id="end-date" placeholder="Search End Date" />
						  </div--->
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
			<div class="row-fluid table-responsive" id="tbl-form">
				<div class = "col-md-12">
					<table class="table table-striped table-bordered dataTable no-footer" id = 'dataTable'>
						<thead>
							<tr>
								<th>No</th>
								<th>Title</th>
								<th>Category</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th>No</th>
								<th>Title</th>
								<th>Date</th>
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