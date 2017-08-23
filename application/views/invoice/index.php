<!--link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet"-->
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

     var tbl = $('#invoice-table').dataTable({
        "processing": true,
        "serverSide": true,
        'iDisplayLength': 20,
        "order": [[ 1, "desc" ]], // Default Sroting by Desc
        "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
        "ajax": {
            "url": "<?= base_url() ?>invoice/index/dt",
            "type": "POST",
            "data" : { 
              'invoice_no'      :  function ( d ) { return $("#invoice-no").val(); },
              'transaction_no'      :  function ( d ) { return $("#transaction-no").val(); },
              'start_date'      :  function ( d ) { return $("#start-date").val(); },
              'end_date'        :  function ( d ) { return $("#end-date").val(); },
            }
        },
        "columns": [
            { "data": "no", "orderable": false, "bSearchable": false },
            { "data": "invoice_no"},
            { "data": "invoice_date"},
            { "data": "case_no"},
            { "data": "deliver_to"},
            { "data": "address"},
            { "data": "sub_total"},
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

    $('#tbl-invoice').on('click', '.view-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'invoice/view/' + id,
        data :  { },
        success: function(data){ 
          $('#view-invoice-detail').html(data); 
           $("#viewModal").modal('show');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
      });
    });

    //Quick Delete
    $('#tbl-invoice').on('click', '.delete-link', function(e) {
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
      var url = 'invoice/delete/' + del_id;
      deleteAjax(url, tbl);
    });

  /*   $("#client-id").select2({
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
<style>
  #signature1, #signature2, #signature3  {
    border: 1px dashed black;
    background-color:#ededed;
  }
</style>
<!-- Modal (For Confirm Delete)-->
<div class="modal fade" id="myModal" tabindex="-1" invoice="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
        <h4 class="modal-title" id="myModalLabel">View Invoice Detail</h4>
      </div>
      <div class="modal-body" id="view-invoice-detail">
                
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
			<li class="active">Invoice</li>
		</ol>
		<div class="box">
          <div class="box-header">
             <h3 class="box-title">Invoice</h3>
			  <?php if ($this->session->userdata('role_id')==1 ){ ?>
				 <div class="pull-right">
				  <a href="<?= base_url().'invoice/create'; ?>" class="btn btn-default btn-flat">Add</a>
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
					  <form id="search-form" method="post" action="<?= base_url().'property/index'; ?>" />   
						<div class="form-group">
						  <div class="col-md-2 col-search">
							<input type="text" class="form-control input-sm" name="invoice_no" id="invoice-no" placeholder="Search Invoice No" />
						  </div>
						  <div class="col-md-2 col-search">
							<input type="text" class="form-control input-sm" name="transaction_no" id="transaction-no" placeholder="Search Transaction No" />
						  </div>
						  <div class="col-md-2 col-search">
							<input type="text" class="form-control input-sm" name="start_date" id="start-date" placeholder="Search Start Date" />
						  </div> 
						  <div class="col-md-2 col-search">
							<input type="text" class="form-control input-sm" name="end_date" id="end-date" placeholder="Search End Date" />
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
				<div class="row-fluid table-responsive" id="tbl-invoice">
					<div class = "col-md-12">
						<table class="table table-striped table-bordered dataTable no-footer" id = 'invoice-table'>
							<thead>
							  <tr>
								 <th>No</th>
								 <th>Invoice No</th>
							     <th>Invoice Date</th>
							     <th>Transaction No</th>
							     <th>Deliver To</th>
							     <th>Address</th>
							     <th>Sub_Total</th>
							     <th>Action</th>
							  </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
							  <tr>
								 <th>No</th>
								 <th>Invoice No</th>
							     <th>Invoice Date</th>
							     <th>Transaction No</th>
							     <th>Deliver To</th>
							     <th>Address</th>
							     <th>Sub_Total</th>
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