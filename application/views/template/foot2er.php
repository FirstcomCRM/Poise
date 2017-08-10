      <!--footer start-->
      <footer class="site-footer">
          <div class="text-center">
              &#169; <?php echo date('Y');?> Poise Real Estate Ltd : Powered by Firstcom Solutions.
              <a href="#" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>
  <script>
    $(function() {
      $('#main-content-area').css('min-height', $('#sidebar').innerHeight() - $('.breadcrumb').outerHeight() - $('.site-footer').outerHeight() - 85);
    });
  </script>
  <!--Datatable-->
  <link href="<?= base_url();?>css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link href="<?= base_url();?>css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="<?= base_url();?>css/jquery.dataTables.bootstrap.css" rel="stylesheet">
  <!-- <link href="<?= base_url();?>css/dataTables.bootstrap.min.css" rel="stylesheet"> -->
  <script src="<?= base_url();?>js/jquery.dataTables.js"></script>
  <script src="<?= base_url();?>js/dataTables.bootstrap.min.js"></script>
  <script src="<?= base_url();?>js/bootstrap-datepicker.js"></script>
  <script src="http://cdn.datatables.net/plug-ins/1.10.9/pagination/input.js"></script>
  
  <!-- Custom -->
  <script class="include" type="text/javascript" src="<?= base_url(); ?>js/jquery.dcjqaccordion.2.7.js"></script>
  <script src="<?= base_url(); ?>js/jquery.scrollTo.min.js"></script>
  <script src="<?= base_url(); ?>js/jquery.nicescroll.js" type="text/javascript"></script> 
</body>
</html>