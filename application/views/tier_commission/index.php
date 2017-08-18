<script>
  var burl = "<?= base_url() ?>";

  $(function(){
     var tbl = $('#tier-commission-table').dataTable({
        "processing": true,
        "serverSide": true,
        'iDisplayLength': 20,
        //'bFilter': false, 
        //'bInfo': false,
        "order": [[ 1, "asc" ]], // Default Sroting by Desc
        "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
        "ajax": {
            "url": "<?= base_url() ?>tier_commission/index/dt",
            "type": "POST",
            "data" : { 
             'category'    :  function ( d ) { return $("#category").val(); },
             //'start_date'      :  function ( d ) { return $("#start-date").val(); },
              //'end_date'      :  function ( d ) { return $("#end-date").val(); },
            }
        },
        "columns": [
            { "data": "no", "orderable": false, "bSearchable": false },
            { "data": "username"},
            { "data": "level"},
            { "data": "action", "orderable": false, "bSearchable": false, "className": 'col_act_md' }
        ],
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
            // For auto numbering at 'No' column
            var start = tbl.api().page.info().start;
            $('td:eq(0)',nRow).html(start + iDisplayIndex + 1);
        },
    });

   
   
    //Quick Delete
    $('#tbl-tier-commission').on('click', '.delete-link', function(e) {
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
      var url = 'tier_commission/delete/' + del_id;
      deleteAjax(url, tbl);
    });
    
  });
</script>


<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Tier Commission Management</li>
		</ol>
		<div class="box">		  
          <div class="box-header">
             <h3 class="box-title">Tier Commission Management</h3>
			 <div class="pull-right">
				<!--a href="<?= base_url().'tier_commission/create'; ?>" class="btn btn-default btn-flat"><i class="fa fa-plus ico-btn"></i>Add</a-->
				<a href="#" id="quick-add" class="btn btn-default btn-create"><i class="fa fa-plus ico-btn"></i> Add</a>
			</div>
           </div><!-- /.box-header -->
			<div class="box-body">
				<div class="row-fluid search-area">
				  <div class="panel-group" id="accordion">
					<div class="panel panel-default">
					  <div class="panel-heading">
						<h4 class="panel-title">
						  <a data-toggle="collapse" data-parent="#accordion" href="#search">Filter</a>
						</h4>
					  </div>
					  <div id="search" class="panel-collapse collapse">
						<div class="panel-body">
						  <form id="search-form" method="post" action="<?= base_url().'project/index'; ?>" />   
							<div class="form-group">
							  <div class="col-md-2 col-search">
								<input type="text" class="form-control input-sm" name="category" id="category" placeholder="Search Tier Commission" />
							  </div>
							  <!--div class="col-md-2 col-search">
								<input type="text" class="form-control input-sm" name="start_date" id="start-date" placeholder="Search Start Date" />
							  </div> 
							  <div class="col-md-2 col-search">
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
					</div>
				  </div>             
				</div>
				<div class="success-alert-area"> </div>
				<?php if(isset($msg) && $msg != '') { ?>
					<div class="alert alert-success"><a href='#' class='close' data-dismiss='alert'>&times;</a><?= $msg; ?></div>
				<?php } ?>
				<div class="row-fluid table-responsive" id="tbl-tier-commission">
					<div class = "col-md-12">
						<table class="table table-striped table-bordered dataTable no-footer" id = 'tier-commission-table'>
							<thead>
							  <tr>
								<th>No</th>
								<th>User</th>
								<th>Level</th>
								<th>Action</th>       
							  </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
							  <tr>
								<th>No</th>
								<th>User</th>
								<th>Level</th>
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