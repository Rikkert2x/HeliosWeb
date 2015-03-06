<?php
	$Member = UserLoggedIn();
?>

  <!-- Navigation -->
  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="http://wwsv05/HELiOS/">HELiOS h4x0r</a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="ART">Artikel kaart</a></li>
          <li><a href="GRV">Artikel code</a></li>
          <li><a href="ART_TREE">Artikel Tree</a></li>
        </ul>
      </div>
      <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
  </nav>

  <div class="container noprint" style="margin-top:70px;">
    <div style="position: absolute;" class="container">
<?php if( $Member === true ){ ?>
      <a class="btn btn-default pull-right" href="logout/"><i class="glyphicons x075 glyphicons-log-out"></i></a>
<?php }else{ ?>
      <a class="btn btn-default pull-right" href="login/"><i class="glyphicons x075 glyphicons-log-in"></i></a>
<?php } ?>
    </div>
  </div>