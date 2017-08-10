<!--link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet"-->
<!--script src="<?= base_url();?>js/select2-4.js"></script>
<script src="<?= base_url(); ?>bootstrap/js/jquery-1.11.1.js"></script>
<!--script src="<?= base_url(); ?>bootstrap/js/bootstrap.min.js"></script-->
<script>
  var burl = "<?= base_url() ?>";

  $(function(){

    $("#start-date").datepicker({
        format: "yyyy/mm/dd",
        autoclose :true,
    }); 

    $("#end-date").datepicker({
        format: "yyyy/mm/dd",
        autoclose :true,
    }); 

     var tbl = $('#project-table').dataTable({
        "processing": true,
        "serverSide": true,
        'iDisplayLength': 20,
        "order": [[ 1, "desc" ]], // Default Sroting by Desc
        "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
        "ajax": {
            "url": "<?= base_url() ?>project/index/dt",
            "type": "POST",
            "data" : { 
              'project_code'    :  function ( d ) { return $("#project-code").val(); },
              'start_date'      :  function ( d ) { return $("#start-date").val(); },
              'end_date'        :  function ( d ) { return $("#end-date").val(); },
              'description'     :  function ( d ) { return $("#description").val(); },
              'project_type'	:  function ( d ) { return $("#project-type").val(); },
            }
        },
        "columns": [
            { "data": "no", "orderable": false, "bSearchable": false },
            { "data": "project_code"},
            { "data": "description"},
            { "data": "project_ic"},
            { "data": "date"},
            { "data": "category"},
            { "data": "project_type"},
            { "data": "project_status"},
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

    $('#tbl-project').on('click', '.view-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'project/view/' + id,
        data :  { },
        success: function(data){ 
          $('#view-project-detail').html(data); 
           $("#viewModal").modal('show');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
      });
    });

    //Quick Delete
    $('#tbl-project').on('click', '.delete-link', function(e) {
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
      var url = 'project/delete/' + del_id;
      deleteAjax(url, tbl);
    });
	
	
	$("#btn-confirm-yes2").click(function(){
		
		var check_id = getIdfromURL($('.has-invoice').attr('href'));
		
		
		//var del_id= $('#hid-delete-id').val();
		$("#confirmModal").modal('hide');  
		window.location.href = "invoice/create/"+check_id;
      //deleteAjax(url, tbl);
    });
	
	
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

    $('#tbl-project').on('click', '.clone-link', function(e) {
      e.preventDefault();
    });

	$('#tbl-project').on('click', '.has-invoice', function(e) {
		 e.preventDefault();
      //alert();
	  //var url =  $(this).attr("href");
     // var check_id = url.substring(url.lastIndexOf("/") + 1, url.length);
	  
		
	  var check_id = getIdfromURL($(this).attr('href'));
	  //alert(check_id);
	 /*  var stateID = $(this).val();*/
            if(check_id) {
                $.ajax({
                    url: 'project/aj_hasInvoice/'+check_id,
                    type: "post",
                    dataType: "json",
                    success:function(data) {
						console.log(data);
					
						if(data.count>0){
							 $("#confirmModal").modal('show');
							/* var xx = confirm('This project already has generated invoice. Add another?');
							if (xx ==true){
								window.location.href = "invoice/create/"+check_id;
							} */
							
						}
						else{
							window.location.href = "invoice/create/"+check_id;
						}
                    }
                });
            }else{
				
				alert('ERROR!!');
			}
	   
	  
	  
	  
	  
	  
    });

  });

  $(document).ajaxComplete(function(event, xhr, settings) {
    $('.pop').popover({html:true});
  });
</script>
<style>
  #signature1, #signature2, #signature3  {
    border: 1px dashed black;
    background-color:#ededed;
  }
</style>
<!-- Modal (For Confirm Delete)-->
<div class="modal fade" id="myModal" tabindex="-1" project="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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


<!-- Modal (For Confirm Add Extra Invoice)-->
<div class="modal fade" id="confirmModal" tabindex="-1" project="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body">
        <span>This project has already generated invoice. Add another?</span>
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
        <h4 class="modal-title" id="myModalLabel">View Project Detail</h4>
      </div>
      <div class="modal-body" id="view-project-detail">
                
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
			<li class="active">Project</li>
		</ol>
		<div class="box">		  
          <div class="box-header">
             <h3 class="box-title">Project</h3>
			 <div class="pull-right">
				<a href="<?= base_url().'project/create'; ?>" class="btn btn-default btn-flat"><i class="fa fa-plus ico-btn"></i>Add</a>
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
                                <input type="text" class="form-control input-sm" name="project_code" id="project-code" placeholder="Search Project Code" />
                              </div>
                              <div class="col-md-2 col-search">
                                <input type="text" class="form-control input-sm" name="start_date" id="start-date" placeholder="Search Start Date" />
                              </div> 
                              <div class="col-md-2 col-search">
                                <input type="text" class="form-control input-sm" name="end_date" id="end-date" placeholder="Search End Date" />
                              </div>
							  <div class="col-md-2 col-search">
                                <input type="text" class="form-control input-sm" name="description" id="description" placeholder="Search Description" />
                              </div> 
							  
                              <div class="col-md-2 col-search">
                                <select class="form-control input-sm" name="project_type" id="project-type">
                                  <option value="" selected>Select Status</option>
                                  <option value="Local">Local</option>
                                  <option value="Oversea">Oversea</option>
                                </select> 
                              </div> 
                              <!-- <div class="clearfix sp-margin-sm"></div>
                              <div class="col-md-10"></div> -->
                              <div class="col-md-2 col-search" style="padding-right: 0px;">
                                <button type="submit" class="btn btn-default btn-sm" id="btn-submit"><i class="fa fa-search ico-btn"></i>Search</button>
                              </div>                            
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
				<div class="row-fluid table-responsive" id="tbl-project">
					<div class = "col-md-12">
						<table class="table table-striped table-bordered dataTable no-footer" id = 'project-table'>
							<thead>
							  <tr>
								<th>No</th>
								<th>Project Code</th>
								<th>Description</th>
								<th>Project IC</th>
								<th>Date</th>
								<th>Category</th>
								<th>Project Type</th>
								<th>Status</th>
								<th>Action</th>       
							  </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
							  <tr>
								<th>No</th>
								<th>Project Code</th>
								<th>Description</th>
								<th>Project IC</th>
								<th>Date</th>
								<th>Category</th>
								<th>Project Type</th>
								<th>Status</th>
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