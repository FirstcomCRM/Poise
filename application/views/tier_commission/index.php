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
            { "data": "description"},
            { "data": "name"},
            { "data": "levels"},
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
      var url = 'tier_commission/create';
      $('#submiturl').val(url);
      $('.quick-action').html('Add');
      $("#quickModal").modal('show');
    });

    // Quick Edit
    $('#tbl-tier-commission').on('click', '.edit-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      var editurl = 'tier_commission/aj_edit/' + id;
      getEditvalues(editurl);
      var updateurl = 'tier_commission/edit/' + id;
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
  
  
  
  
  
  
    function loaduserteam() {
   //getClientinfo();
            var stateID = $("#team-id").val();
			 //alert('aj_getContactinfo/'+stateID);
            if(stateID) {
                $.ajax({
                    url: burl + 'user/aj_getTeamLevels/'+stateID,
                    type: "post",
                    dataType: "json",
                    success:function(data) {
					
                      /*   <?php if ($action=='new'){?> */
							
							('select[name="contact"]').empty();
							$('select[name="contact"]').append('<option value="">'+ 'Select Contact' +'</option>');
							
						/* <?php }else if($action=='edit'){ ?> */
							//$('select[name="contact"]').append('<option selected value="<?=$invoice['contact']?>"> <?= $invoice['contact_person']?></option>');
						
						/* <?php }?> */
						
						for(var x = 0; x < data.count; x++){
							$('select[name="contact"]').append('<option value="'+ data.data[x].client_detail_id +'">'+ data.data[x].name +'</option>');
						}
						
                    }
                });
            }else{
                $('select[name="contact"]').append('<option value="">'+ 'Select Contact' +'</option>');
            }
}
  
  
  
  
  
  
</script>
<!-- Modal (For Confirm Delete)-->
<div class="modal fade" id="myModal" tabindex="-1" tier_commission="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
<div class="modal fade" id="quickModal" tabindex="-1" tier_commission="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><span class="quick-action"></span> Tier Commission</h4>
      </div>
      <div class="modal-body">
        <form tier_commission="form" id="quick-form">
            <div class="clearfix sp-margin-sm"></div>
              <div class="alert alert-danger err-display" style="display:none;"> </div> 
            <div class="clearfix sp-margin-md"></div>
            <input type="hidden" id="submiturl" name="submiturl" value="">
			 <div class="clearfix sp-margin-sm"></div>
			<div class="form-group">
				<label for="name" class="col-md-3 control-label">Description</label>
				<div class="col-md-6">
					<input type="text" class="form-control input-sm" id="description" name="description" placeholder="Enter Description" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ( isset($admin['name']) ? $admin['name'] : '') ; ?>" required>
				</div>
				<div class="col-md-1 req-star">*</div>
			</div>
			<div class="clearfix sp-margin-sm"></div>
			<div class="form-group">
                <label for="role" class="col-md-3 control-label">Team</label>
                <div class="col-md-6">
                    <select class="form-control input-sm" name="team_id" id="team-id" placeholder="Select Team" required>
                        <option value="" selected>Select Team</option>
                        <?php if($users != '') { ?>
                            <?php foreach($teams as $team) {  
                                /* if( isset($_POST['user_id']) && ($_POST['user_id'] == $user['user_id']) ) {
                                    echo "<option selected value='". $user['user_id'] . "' >" . $user['name'] . "</option>";
                                }
                                else */ //if( isset($tier_commission['user_id']) && ($tier_commission['user_id'] == $user['user_id']) ) {
                                 //   echo "<option selected value='". $user['user_id'] . "' >" . $user['name'] . "</option>";
                               // }
                              //  else {
                                    echo "<option value='". $team['team_id'] . "' >" . $team['name'] . "</option>";
                               // }
                            } ?>
                        <?php } ?>
                    </select>    
                </div>
                <div class="col-md-1 req-star">*</div>
            </div>
			<div class="clearfix sp-margin-sm"></div>
			<div class="form-group">
				<label for="name" class="col-md-3 control-label">No. of Levels</label>
				<div class="col-md-6">
				  <!-- <input type="text" class="form-control input-sm" id="level" name="level" placeholder="Enter level" value="<?= isset($_POST['level']) ? $_POST['level'] : ( isset($tier_commission['level']) ? $tier_commission['level'] : '') ; ?>"> -->
				  <select class="form-control input-sm" name="levels" id="levels">
                    <option value="" selected>Select No. of Levels</option>
                    <option value="1" <?= ( (isset($_POST['levels']) && $_POST['levels'] == '1') || (isset($tier_commission['levels']) && $tier_commission['levels'] == '1') ) ? 'selected' : ''; ?> >1</option>
                    <option value="2" <?= ( (isset($_POST['levels']) && $_POST['levels'] == '2') || (isset($tier_commission['levels']) && $tier_commission['levels'] == '2') ) ? 'selected' : ''; ?> >2</option>
                    <option value="3" <?= ( (isset($_POST['levels']) && $_POST['levels'] == '3') || (isset($tier_commission['levels']) && $tier_commission['levels'] == '3') ) ? 'selected' : ''; ?> >3</option>
                    <option value="4" <?= ( (isset($_POST['levels']) && $_POST['levels'] == '4') || (isset($tier_commission['levels']) && $tier_commission['levels'] == '4') ) ? 'selected' : ''; ?> >4</option>
                    <option value="5" <?= ( (isset($_POST['levels']) && $_POST['levels'] == '5') || (isset($tier_commission['levels']) && $tier_commission['levels'] == '5') ) ? 'selected' : ''; ?> >5</option>
                    <option value="6" <?= ( (isset($_POST['levels']) && $_POST['levels'] == '6') || (isset($tier_commission['levels']) && $tier_commission['levels'] == '6') ) ? 'selected' : ''; ?> >6</option>
                    <option value="7" <?= ( (isset($_POST['levels']) && $_POST['levels'] == '7') || (isset($tier_commission['levels']) && $tier_commission['levels'] == '7') ) ? 'selected' : ''; ?> >7</option>
                  </select>  
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
								<th>Description</th>
								<th>Name</th>
								<th>Levels</th>
								<th>Action</th>       
							  </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
							  <tr>
								<th>No</th>
								<th>Description</th>
								<th>Name</th>
								<th>Levels</th>
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