<?php
	session_start();
	
	// Server properties @Author: AM + JC
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http://' : 'https://'; //The HTTP(S) protocol
    $host = $_SERVER['HTTP_HOST'].'/'; // The website host  
    
    // Directories @Author AM
    $branch = "pdc13/main/"; // Dirs in URL from host to main folder
	$pagebranch = "pdc13/"; // URL branch for hyperlinks
	$js = "js/";
	$jsframework = "js/framework/";
	$jsfootable = 'js/footable/';
	$icon = "icon/";
	$css = "css/";
	$cssframework = "css/framework/";
	$img = "img/";
	$vimg = "img/vehicle/";
	$imgfootable = 'img/footable/';
	$php = "php/";
	$apages = "account/";
	$bpages = "booking/";
	$gpages = "general/";
    
    // Global functions
    function fetchdir($dir)		
	{
		//Fetch Directories
		//@Author: AM + JC
		$protocol = $GLOBALS['protocol'];
		$host = $GLOBALS['host'];
		($dir == $GLOBALS['apages'] || $dir == $GLOBALS['bpages'] || $dir == $GLOBALS['gpages'] ? $branch = $GLOBALS['pagebranch'] : $branch = $GLOBALS['branch']);
		echo $protocol.$host.$branch.$dir;
	}
?>
<!DOCTYPE html>
<?php include realpath(dirname(__FILE__)."/../../php/ie_js_fix.php"); ?>
	<head>
		<?php include realpath(dirname(__FILE__)."/../../php/head.php"); ?>	
	</head>
	<body>
		<!-- Header -->
		<?php include realpath(dirname(__FILE__)."/../../php/header.php"); ?>
		<!-- End Header -->
			
		<!-- Main Content -->  
				
  		<div id="first_row" class="row" style="margin-top:5em">
  			<div class="large-12 column"><h2>Fake Login</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-6 column">
				<form action="<?php fetchdir($php); ?>login.php" method="post" id="login" name="login" class="custom">
					<label for="inputemail">Email</label><input type="text" name="inputemail" id="inputemail"/>
					<label for="inputpassword">Password</label><input type="password" name="inputpassword" id="inputpassword"/>
					<input type="hidden" name="email" id="email" value="joebloggs@gmail.com"/>
					<input type="hidden" name="password" id="password" value="iamevil"/>
					<input type="submit" class="radius button" name="submit" value="Login"/>
				</form>
			</div>		
  		</div>
  		<div class="row">
  			<div class="large-12 column">I haven't got an account yet <a href="<?php fetchdir($gpages); ?>registration.php">take me to registration!</a></div>		
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>