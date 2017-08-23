<!--link href="<?= base_url();?>css/select2/select2-4.css" rel="stylesheet">
<link href="<?= base_url();?>css/select2/select2-b3.css" rel="stylesheet"-->
<!--script src="<?= base_url();?>js/select2-4.js"></script-->

<!--link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet"-->
        <link href='<?=base_url()?>bootstrap/css/fullcalendar.css' rel='stylesheet' />
        <link href="<?=base_url()?>bootstrap/css/bootstrapValidator.min.css" rel="stylesheet" />        
        <link href="<?=base_url()?>bootstrap/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
        <!-- Custom css  -->
        <!--link href="<?php echo base_url();?>bootstrap/css/custom.css" rel="stylesheet" /-->

<script>
  var burl = "<?= base_url() ?>";
  var base_url = "<?= base_url() ?>";
  $(function(){

    $("#start-date").datepicker({
        format: "yyyy-mm-dd 00:00:00",
        autoclose :true,
    }); 

    $("#end-date").datepicker({
        format: "yyyy-mm-dd 00:00:00",
        autoclose :true,
    }); 

  
  });
</script>
<script src='<?php echo base_url();?>bootstrap/js/main.js'></script>
<style>
  #signature1, #signature2, #signature3  {
    border: 1px dashed black;
    background-color:#ededed;
  }
</style>



<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<ol class="breadcrumb">
				<li><a href="<?= base_url().'index.php'; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Events</li>
			</ol>
			<div class="box">
			
			  <div class="box-header">
				
				 <h3 class="box-title">Events</h3>
				   
				 <!--div class="pull-right">
					<a href="#" id="quick-add" class="btn btn-default btn-flat"><i class="fa fa-plus ico-btn"></i> Add</a>
					<!--a href="<?= base_url().'events/create'; ?>" class="btn btn-default btn-flat">Add</a-->
				 <!--/div-->
			  </div><!-- /.box-header -->
			  <div class="box-body">
	
				<div id='calendar' style = "color:#605ca8;"></div>
				<div class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title"></h4>
							</div>
							<div class="modal-body">
								<div class="error"></div>
								<form class="form-horizontal" id="crud-form">
								<input type="hidden" id="start" name ="start" class="form-control input-md" />
								<input type="hidden" id="end" name = "end" class="form-control input-md" />
									<div class="form-group">
										<label class="col-md-4 control-label" for="title">Title</label>
										<div class="col-md-4">
											<input id="title" name="title" type="text" class="form-control input-md" />
										</div>
									</div>                            
									<div class="form-group">
										<label class="col-md-4 control-label" for="description">Description</label>
										<div class="col-md-4">
											<textarea class="form-control" id="description" name="description"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label" for="color">Start Date</label>
										<div class="col-md-4">
											   <input  type="text" class="form-control input-sm" name="start_date" id="start-date" placeholder="Search Start Date" value="<?= isset($_POST['start_date']) ? $_POST['start_date'] : ( isset($events['start']) ? date('Y-m-d H:i:s', strtotime($events['start'])) : '') ; ?>"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label" for="color">End Date</label>
										<div class="col-md-4">
											   <input  type="text" class="form-control input-sm" name="end_date" id="end-date" placeholder="Search End Date" value="<?= isset($_POST['end_date']) ? $_POST['end_date'] : ( isset($events['end']) ? date('Y-m-d H:i:s', strtotime($events['end'])) : '') ; ?>"/>
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="col-md-4 control-label" for="color">Color</label>
										<div class="col-md-4">
											<input id="color" name="color" type="text" class="form-control input-md" readonly="readonly" />
											<span class="help-block">Click to pick a color</span>
										</div>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</div>
			  </div>	
			</div>	
		</div>	
	</div>	
</section>

