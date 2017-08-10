<section class="content-header">
  <h1>
	Dashboard
  </h1>
  <ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">Dashboard</li>
  </ol>
</section>

        <!-- Main content -->
<section class="content" style = "height:72em; max-height:auto;">
   <div class="col-md-12">
              <!-- Box Comment -->
	<?php if (!empty($announcements)): ?>
     <?php foreach ($announcements as $announcement): ?>
	  <div class="box box-widget">
	   
		<div class='box-header with-border'>
		  <div class='user-block'>
			<img class='img-circle' src='<?=base_url()?>dist/img/user1-128x128.jpg' alt='user image'>
			<!--span class='username'><a href="#">Jonathan Burke Jr.</a></span-->
			<span class='description'><?=$announcement['announce_date']?></span>
		  </div><!-- /.user-block -->
		  <!--div class='box-tools'>
			<button class='btn btn-box-tool' data-toggle='tooltip' title='Mark as read'><i class='fa fa-circle-o'></i></button>
			<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
			<button class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
		  </div--><!-- /.box-tools -->
		</div><!-- /.box-header -->
		<div class='box-body'>
		  <!-- post text -->
		  <?//=$announcement['announce_body'];?>

		  <!-- Attachment -->
		  <div class="attachment-block clearfix">
			<img class="attachment-img" src='<?=base_url().$announcement['announce_img']?>' alt="attachment image">
			<div class="attachment-pushed">
			  <h4 class="attachment-heading"><a href="#"><?=$announcement['announce_title'];?></a></h4>
			  <div class="attachment-text">
				<?=$announcement['announce_body'];?> <!--a href="#">more</a-->
			  </div><!-- /.attachment-text -->
			</div><!-- /.attachment-pushed -->
		  </div><!-- /.attachment-block -->
		  
		</div><!-- /.box-body -->
		
		
	  </div><!-- /.box --> 
	  <?php endforeach ?>
         <?php endif ?>
    </div><!-- /.col -->
</section>