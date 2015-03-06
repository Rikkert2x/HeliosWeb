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
        <label>ART nummer</label> <input type="text" class="form-control" name="q" placeholder="ART-000000000">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
      <!--<a class="btn btn-default" href="<?php echo $_SERVER['REQUEST_URI']; ?>&download=true" target="_blank" id="DownloadButton" q="<?php echo $f ; ?>"><i class="glyphicons glyphicons-file"></i></a>-->
      <a class="btn btn-default" href="<?php echo $_SERVER['REQUEST_URI']; ?>" ><i class="glyphicons glyphicons-refresh"></i></a>
      </form>
    <br>
<?php echo Module_ART_TREE('    '); ?>
  </div>

  <script src="assets/plugins/tree/tree.js"></script>
  <link href="assets/plugins/tree/tree.css" rel="stylesheet" />

<script>
  $(document).ready(function() {	
    $('.tree').treed();
		
		var CsvOutput = '' ;

		$.each($('.tree li'), function( index, element ){
			// Output to CSV
		});
		element = $('.tree ul li ul > li:has( ul )');
		$(element).addClass('branch2')
		/*
		$(element).css({
			left: "-18px"
		})
		console.log(
			$(element).before()
		);
		/**/
  });
</script>
</body>
</html>