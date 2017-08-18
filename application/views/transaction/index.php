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

     var tbl = $('#transaction-table').dataTable({
        "processing": true,
        "serverSide": true,
        'iDisplayLength': 20,
        "order": [[ 1, "desc" ]], // Default Sroting by Desc
        "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
        "ajax": {
            "url": "<?= base_url() ?>transaction/index/dt",
            "type": "POST",
            "data" : { 
              'transaction_no'    :  function ( d ) { return $("#transaction-no").val(); },
              'start_date'      :  function ( d ) { return $("#start-date").val(); },
              'end_date'        :  function ( d ) { return $("#end-date").val(); },
              'property_status':  function ( d ) { return $("#property-status").val(); },
             // 'client_id'       :  function ( d ) { return $("#client-id").val(); },
            }
        },
        "columns": [
            { "data": "no", "orderable": false, "bSearchable": false },
            { "data": "case_no"},
            { "data": "property_title"},
            { "data": "transact_date"},
            { "data": "property_type"},
            { "data": "address"},
            { "data": "amount"},
            { "data": "owner_name"},
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
        url  :  burl + 'transaction/view/' + 1,
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

	
	
    $('#tbl-transaction').on('click', '.view-link', function(e) { 
      e.preventDefault();
	  var url =  $(this).attr("href");
      var id = getIdfromURL($(this).attr('href'));
	  var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
      $('#hid-delete-id').val(del_id);
      $.ajax({
        type :  "POST",
        url  :  burl + 'transaction/view/' + id,
        data :  { },
        success: function(data){ 
          $('#view-transact-detail').html(data); 
           $("#viewModal").modal('show');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
      });
    });

	
	
	$('#tbl-transaction').on('click', '.btn-reject', function(e) { 
      e.preventDefault();
	  var id = getIdfromURL($(this).attr('href'));
	   var xx = confirm("Reject this Transaction?");
	  if (xx == true){
		  
		   $("#viewModal").modal('hide'); 
		 // alert(url);
		 var url = 'transaction/edit_status/' + id + '/reject';
			//alert(url);
		  deleteAjax(url, tbl);
	  }else{
		  
		}
	});
	
	
	$('#tbl-transaction').on('click', '.btn-approve', function(e) { 
      e.preventDefault();
	  var id = getIdfromURL($(this).attr('href'));
	   var xx = confirm("Approve this Transaction?");
	  if (xx == true){
		  
		   $("#viewModal").modal('hide'); 
		  //alert(url);
		 var url = 'transaction/edit_status/' + id + '/approve';
		//	alert(url);
		  deleteAjax(url, tbl);
	  }else{
		  
	  }
	  
	  
	  
      /* var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'transaction/view/' + id,
        data :  { },
        success: function(data){ 
          $('#view-property-detail').html(data); 
           $("#viewModal").modal('show');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
      }); */
    });
	
	
	
	
	
	
	
    //Quick Delete
    $('#tbl-transaction').on('click', '.delete-link', function(e) {
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
      var url = 'transaction/delete/' + del_id;
      deleteAjax(url, tbl);
    });
	
	$("#btn-confirm-approve").click(function(e){
		
		 e.preventDefault();
		
	//  var xx = $('#hid-delete-id').val();
      var del_id= $('#hid-delete-id').val();

	  
	  var xx = confirm("Approve this Transaction?");
	  if (xx == true){
		  
		   $("#viewModal").modal('hide'); 
		 // 
		 var url = 'transaction/edit_status/' + del_id + '/approve';
		  deleteAjax(url, tbl);
		  // alert(url);
	  }else{
		  
	  }
	  
	  
	  
    });
	
	$("#btn-confirm-reject").click(function(){
      var del_id= $('#hid-delete-id').val();
      //$("#myModal").modal('hide');  
      //var url = 'transaction/delete/' + del_id;
      //deleteAjax(url, tbl);
	  
	  var xx = confirm("Reject this Transaction?");
	  if (xx == true){
		  
		   $("#viewModal").modal('hide'); 
		 // 
		 var url = 'transaction/edit_status/' + del_id + '/reject';
		   //deleteAjax(url, tbl);
		   alert(url);
	  }else{
		  
	  }
	  
	  
	  
    });
	
	
	
/* 	$('#tbl-transaction').on('click', '.btn-view', function(e) {
	 
	 
	 
	
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

    $('#tbl-transaction').on('click', '.clone-link', function(e) {
      e.preventDefault();
    });

	$('#tbl-transaction').on('click', '.has-invoice', function(e) {
		 e.preventDefault();
      //alert();
	  //var url =  $(this).attr("href");
     // var check_id = url.substring(url.lastIndexOf("/") + 1, url.length);
	  
		
	  var check_id = getIdfromURL($(this).attr('href'));
	  //alert(check_id);
	 /*  var stateID = $(this).val();*/
            if(check_id) {
                $.ajax({
                    url: 'transaction/aj_hasInvoice/'+check_id,
                    type: "post",
                    dataType: "json",
                    success:function(data) {
						console.log(data);
					
						if(data.count>0){
							 $("#confirmModal").modal('show');
							/* var xx = confirm('This property already has generated invoice. Add another?');
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
      <div class="modal-body" id="view-transact-detail">
                
      </div>
      <div class="modal-footer">
	  
        <!--button type="button" class="btn btn-default" id='btn-confirm-approve'>Approve</button>
        <button type="button" class="btn btn-default" id='btn-confirm-reject'>Reject</button-->
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End Model -->


<section class="content">
  <div class="row">
	<div class="col-xs-12">
		<div class="box">
          <div class="box-header">
             <h3 class="box-title">Property Transaction</h3>
             <h5 class="box-title">Case Submission</h5>
			 <div class="pull-right">
                      <a href="<?= base_url().'transaction/create'; ?>" class="btn btn-default btn-flat">Add</a>
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
                          <form id="search-form" method="post" action="<?= base_url().'property/index'; ?>" />   
                            <div class="form-group">
                              <div class="col-md-2 col-search">
                                <input type="text" class="form-control input-sm" name="transaction_no" id="transaction-no" placeholder="Search Transaction No" />
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
                                  <option value="Draft">Draft</option>
                                  <option value="Pending">Pending</option>
                                  <option value="Sold">Sold</option>
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
				<div class="row-fluid table-responsive" id="tbl-transaction">
					<div class = "col-md-12">
						<table class="table table-striped table-bordered dataTable no-footer" id = 'transaction-table'>
							<thead>
							  <tr>
								  <th>No</th>
								  <th>Case No</th>
								  <th>Property Title</th>
								  <th>Transaction Date</th>
								  <th>Property Type</th>
								  <th>Address</th>
								  <th>Amount</th>
								  <th>Owner Name</th>
								  <th>Action</th>         
							  </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
							  <tr>
								  <th>No</th>
								  <th>Case No</th>
								  <th>Property Title</th>
								  <th>Transaction Date</th>
								  <th>Property Type</th>
								  <th>Address</th>
								  <th>Amount</th>
								  <th>Owner Name</th>
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