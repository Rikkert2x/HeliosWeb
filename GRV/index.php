<?php

	// Loading Files
	require_once ('../assets/php/load.php');
	CreateHtmlHead();
	CreateHtmlNavbar();

	// Loading User Variable
	$q = GetGet ( 'q' );
  $f = substr ( $_ART ['HEL_SACHNUMMER'], 0, - strlen ( $q ) ) . $q ;

?>

<?php include( '../assets/php/navbar.php' ) ; ?>

  <!-- Page Content -->
  <div class="container" style="margin-top:20px;margin-bottom:20px;">
    <br>
    <form role="form" action="">
      <div class="form-group">
        <label>GRV nummer</label> <input type="text" class="form-control" name="q" placeholder="0000000000">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
      <a class="btn btn-default" href="<?php echo $_SERVER['REQUEST_URI']; ?>&download=true" target="_blank" id="DownloadButton" q="<?php echo $f ; ?>"><i class="glyphicons glyphicons-file"></i></a>
			<a class="btn btn-default" href="<?php echo $_SERVER['REQUEST_URI']; ?>" ><i class="glyphicons glyphicons-refresh"></i></a>
      </form>
    <br>
<?php echo Module_GRV('    '); ?>
  </div>
	
<script>
  $(document).ready(function() {	
    if( $("#dataTable").length == 0 ){
      $('a#DownloadButton').remove()
    }
  });
</script>
</body>
</html>