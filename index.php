<?php

	/* Loading Files */
	require_once ('assets/php/load.php');
	CreateHtmlHead();
	CreateHtmlNavbar();

	
?>

  <!-- Page Content -->
  <div class="container">

    <!-- Page Heading -->
    <div class="row" style="margin-top:35px;">
      <div class="col-lg-12">
        <h1 class="page-header">Page Heading
          <small>Secondary Text</small>
        </h1>
      </div>
    </div>
    <!-- /.row -->

    <!-- Projects Row -->
    <div class="row">
      <div class="col-md-3 portfolio-item">
        <a href="ART">
          <img class="img-responsive" src="assets/img/400x300_ART.png" data="http://placehold.it/400x300/.png/&text=ART" alt="">
        </a>
      </div>
      <div class="col-md-3 portfolio-item">
        <a href="GRV">
          <img class="img-responsive" src="assets/img/400x300_GRV.png" alt="http://placehold.it/400x300/.png/&text=GRV">
        </a>
      </div>
      <div class="col-md-3 portfolio-item">
        <a href="ART_TREE">
          <img class="img-responsive" src="assets/img/400x300_ART_TREE.png" alt="http://placehold.it/400x300/.png/&text=ART%20TREE">
        </a>
      </div>
    </div>
    <!-- /.row -->
		
<?php if( $Member === true ){ ?>
    <!-- Projects Row -->
    <div class="row">
      <div class="col-md-3 portfolio-item">
        <a href="ART">
          <img class="img-responsive" src="assets/img/PrijsUpdate.png" alt="">
        </a>
      </div>
    </div>
    <!-- /.row -->

<?php } ?>    
    <hr>

  </div>
  <!-- /.container -->
  
</body>
</html>