<?php

  session_start();
  ob_start();

	/* Loading Files */
	require_once ('../assets/php/Settings.php');
	require_once ('../assets/php/functions.php');
	
	$loginStatus = UserLogin('HeliosUser','HeliosPass') ;
	if( $loginStatus == true || UserLoggedIn() == true  ){
		if(!isset($_POST['HeliosRedirect'])){
			$url = str_replace('login/index.php','',strtolower($_SERVER['PHP_SELF'])) ;
		}else{
			$url = $_POST['HeliosRedirect'];
		}
		header("Location: $url");
	}
?>
<html lang="nl-nl" dir="ltr">

<head>
  <base href="/HELiOS/">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>HELiOS h4x0r - Login</title>

	<!-- CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/helios.css">
	<link rel="stylesheet" href="assets/css/login.css">

</head>
<body>

<div class="container">
	<div class="login-container">
		<div class="avatar"></div>
		<div class="form-box">
			<form action="" method="POST">
				<input type="hidden" name="HeliosRedirect" value="<?php echo $_SERVER['HTTP_REFERER'] ?>" />
				<input name="HeliosUser" type="text" placeholder="username">
				<input name="HeliosPass" type="password" placeholder="password">
				<button class="btn btn-info btn-block login" type="submit">Login</button>
			</form>
			<div><?php echo $loginStatus ?></div>
		</div>
	</div>
</div>

</body>
</html>