<link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet">
<!--script src="<?= base_url();?>js/select2-4.js"></script-->
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

     var tbl = $('#property-table').dataTable({
        "processing": true,
        "serverSide": true,
        'iDisplayLength': 20,
        "order": [[ 1, "desc" ]], // Default Sroting by Desc
        "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
        "ajax": {
            "url": "<?= base_url() ?>property/index/dt",
            "type": "POST",
            "data" : { 
              'title'    :  function ( d ) { return $("#title").val(); },
              'start_date'      :  function ( d ) { return $("#start-date").val(); },
              'end_date'        :  function ( d ) { return $("#end-date").val(); },
              'property_status':  function ( d ) { return $("#property-status").val(); },
            }
        },
        "columns": [
            { "data": "no", "orderable": false, "bSearchable": false },
			{ "data": "property_img",
				"render": function(data, type, row) {
					if (data==0 || data=="" || !data ){
						data = '';
						return data;
					}
					else{
						return '<img height ="100" width="100" src="'+data+'" /> ';
					}
				}
			},
            { "data": "property_title"},
            { "data": "district"},
            { "data": "category"},
            { "data": "address"},
            { "data": "property_price"},
            { "data": "property_status",
				/* "render": function(data, type, row) {
					if (data == "Pending"){
						
						//data = 'KUPAL';
						//return data;
						
						  $("btn-approve").css("display", "inline-block");
					}
					else{
						//return data;
						$(".btn-approve").css("display", "none");
						//return '<img height ="100" width="100" src="'+data+'" /> ';
						
					}
					return data;
				} */
			},
            { "data": "date_added"},
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

	/*  $('#btn-view').click(function(e) {  
        e.preventDefault();
      //var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'property/view/' + 1,
        data :  { },
        success: function(data){ 
          //$('#view-property-detail').html(data); 
           $("#viewModal").modal('show');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
      });
    }); */

	
	
    $('#tbl-property').on('click', '.btn-view', function(e) { 
      e.preventDefault();
	  var url =  $(this).attr("href");
      var id = getIdfromURL($(this).attr('href'));
	  var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
      $('#hid-delete-id').val(del_id);
      $.ajax({
        type :  "POST",
        url  :  burl + 'property/view/' + id,
        data :  { },
        success: function(data){ 
          $('#view-property-detail').html(data); 
           $("#viewModal").modal('show');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
      });
    });

	
	
	$('#tbl-property').on('click', '.btn-reject', function(e) { 
      e.preventDefault();
	  var check_id = getIdfromURL($(this).attr('href'));
	  if(check_id){
		$.ajax({
			url: 'property/aj_check_status/'+check_id,
			type: "post",
			//data: { },
			dataType: 'json',
			success: function(data){  

				if(data.data[0].property_status =="Rejected"){
					alert('This Property is already rejected');
				}

				else{
					
					var xx = confirm("Reject this property?");
					if (xx == true){
					  
						$("#viewModal").modal('hide'); 
						// alert(url);
						var url = 'property/edit_status/' + check_id + '/reject';
						//alert(url);
						deleteAjax(url, tbl);
					}else{
					  
					}  
				}
			}
		});
	  }
	  else{
		alert('error in getting data');
	  }
		  
	});
	
	
	$('#tbl-property').on('click', '.btn-approve', function(e) { 
      e.preventDefault();
	  var check_id = getIdfromURL($(this).attr('href'));
	  if(check_id){
	  //var check_url ='property/check_status/' + id;
		$.ajax({
			url: 'property/aj_check_status/'+check_id,
			type: "post",
			//data: { },
			dataType: 'json',
			success: function(data){  

				if(data.data[0].property_status =="Sold/Approved" || data.data[0].property_status =="Sold" || data.data[0].property_status =="Published" ){
					//alert(data.data[0].property_status);
					alert('This Property is already approved');
				}
				else if (data.data[0].property_status =="Rejected"){
					var xx = confirm("Approve this property?");
					if (xx == true){
						  
						$("#viewModal").modal('hide'); 
						 // alert(url);
						var url = 'property/edit_status/' + check_id + '/publish';
						//alert(url);
						deleteAjax(url, tbl);
					}else{
						  
					}
					
				}
				else if (data.data[0].property_status =="Pending"){
					//var id = getIdfromURL($(this).attr('href'));
					var xx = confirm("Approve this property?");
					if (xx == true){
						  
						$("#viewModal").modal('hide'); 
						 // alert(url);
						var url = 'property/edit_status/' + check_id + '/publish';
						//alert(url);
						deleteAjax(url, tbl);
					}else{
						  
					}
				}	 
			}
		});

	  }else{
		  alert('error in getting data');
		  
	  }

    });

    //Quick Delete
    $('#tbl-property').on('click', '.delete-link', function(e) {
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
      var url = 'property/delete/' + del_id;
      deleteAjax(url, tbl);
    });
	
	$("#btn-confirm-approve").click(function(e){
		
		 e.preventDefault();
		
	//  var xx = $('#hid-delete-id').val();
      var del_id= $('#hid-delete-id').val();

	  
	  var xx = confirm("Approve this property?");
	  if (xx == true){
		  
		   $("#viewModal").modal('hide'); 
		 // 
		 var url = 'property/edit_status/' + del_id + '/TRUE';
		   //deleteAjax(url, tbl);
		  // alert(url);
	  }else{
		  
	  }
	  
	  
	  
    });
	
	$("#btn-confirm-reject").click(function(){
      var del_id= $('#hid-delete-id').val();
      //$("#myModal").modal('hide');  
      //var url = 'property/delete/' + del_id;
      //deleteAjax(url, tbl);
	  
	  var xx = confirm("Reject this property?");
	  if (xx == true){
		  
		   $("#viewModal").modal('hide'); 
		 // 
		 var url = 'property/edit_status/' + del_id + '/FALSE';
		   //deleteAjax(url, tbl);
		   alert(url);
	  }else{
		  
	  }
	  
	  
	  
    });
	
	
	
/* 	$('#tbl-property').on('click', '.btn-view', function(e) {
	 
	 
	 
	
	}); */
	
	
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

    $('#tbl-property').on('click', '.clone-link', function(e) {
      e.preventDefault();
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
<div class="modal fade" id="myModal" tabindex="-1" property="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body">
        <span>Are you sure to delete this record?</span>
        <input type="hidden" name="hid-id" id="hid-delete-id" value='<?php //$property['property_id']?>'/>
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
<div class="modal fade" id="confirmModal" tabindex="-1" property="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body">
        <span>This property has already generated invoice. Add another?</span>
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
        <h4 class="modal-title" id="myModalLabel">View Property Detail</h4>
      </div>
      <div class="modal-body" id="view-property-detail">
                
      </div>
      <div class="modal-footer">
	  
        <button type="button" class="btn btn-default" id='btn-confirm-approve'>Approve</button>
        <button type="button" class="btn btn-default" id='btn-confirm-reject'>Reject</button>
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
			<li class="active">Property</li>
		</ol>
		<div class="box">
          <div class="box-header">
             <h3 class="box-title">Property</h3>
			 <div class="pull-right">
                      <a href="<?= base_url().'property/create'; ?>" class="btn btn-default btn-flat">Add</a>
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
						<form id="search-form" method="post" action="<?= base_url().'property/index'; ?>" />   
                            <div class="form-group">
                              <!--div class="col-md-2 col-search">
                                <input type="text" class="form-control input-sm" name="property_no" id="property-no" placeholder="Search Title" />
                              </div-->
							  <div class="col-md-2 col-search">
                                <input type="text" class="form-control input-sm" name="title" id="title" placeholder="Search Title" />
                              </div>
                              <div class="col-md-2 col-search">
                                <input type="text" class="form-control input-sm" name="start_date" id="start-date" placeholder="Search Start Date" />
                              </div> 
                              <div class="col-md-2 col-search">
                                <input type="text" class="form-control input-sm" name="end_date" id="end-date" placeholder="Search End Date" />
                              </div>
                              <div class="col-md-2 col-search">
                                <select class="form-control input-sm" name="status" id="property-status">
                                  <option value="" selected>Select Status</option>
                                  <?php if ($this->session->userdata('role_id')!=1 ){ ?>
									<option value="Draft">Draft</option>
                                  <?php }?>
								  <option value="Pending" selected>Pending</option>
                                  <option value="Sold">Sold</option>
                                  <option value="Sold/Approved">Approved</option>
                                  <option value="Published">Published</option>
                                  <option value="Rejected">Rejected</option>
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
			
				<div class="success-alert-area"> </div>
				<?php if(isset($msg) && $msg != '') { ?>
					<div class="alert alert-success"><a href='#' class='close' data-dismiss='alert'>&times;</a><?= $msg; ?></div>
				<?php } ?>
				<div class="row-fluid table-responsive" id="tbl-property">
					<div class = "col-md-12">
						<table class="table table-striped table-bordered dataTable no-footer" id = 'property-table'>
							<thead>
							  <tr>
								  <th>No</th>
								  <th>Image</th>
								  <th>Title</th>
								  <th>District</th>
								  <th>Category</th>
								  <th>Address</th>
								  <th>Price</th>
								  <th>Status</th>
								  <th>Date</th>
								  <th>Action</th>       
							  </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
							  <tr>
								  <th>No</th>
								  <th>Image</th>
								  <th>Title</th>
								  <th>District</th>
								  <th>Category</th>
								  <th>Address</th>
								  <th>Price</th>
								  <th>Status</th>
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