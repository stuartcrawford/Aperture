<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	setpermissions('admin','staff','customer');

	if ($_SESSION['Booking']['Step'] < 5)
	{
		if(isset($referrer))
		{
			Header ('Location: '.fetchinline($bpages).'page_expired.php');
			exit;
		}
		else
		{
			Header ('Location: '.fetchinline($gpages).'index.php');
			exit;
		}
	}
	
	unset($_SESSION['Reservation'], $_SESSION['Booking'], $_SESSION['Asset'], $_SESSION['Invoice'], $_SESSION['TotalPrice'],$_SESSION['Response'],$_SESSION['User']['APIToken'], $_SESSION['ewayAPI']);
	
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
		
		<!-- Payment Declined API Error Page
  	 	     @Author:Alex McCormick 
		     @Date: 30.03.2013 			-->  	
		<?php if (isset($lblError)) {?>
    	<div id="first_row" class="row">
  			<div class="large-12 column">
  				<h2>Payment Declined</h2>
    			<div id="error">
        			<label style="color:red"><?php echo $lblError ?></label>
    			</div>
    		</div>
    	</div>
    	<div class="row">
  			<div class="large-12 column"><a href="<?php fetchdir($gpages); ?>index.php">Return to homepage</a></div>		
  		</div>
  		<!-- End Payment Declined API Error Page -->
  		
		<?php } else { ?>
			
		<!-- Start Booking Complete Page
  	 	     @Author:Josh Chan 
		     @Date: 21.03.2013 -->			     		
  		<div id="first_row" class="row">
  			<div class="large-12 column">
				<h2>Booking Complete</h2>
				<label><strong>Progress:</strong></label>
				<div class="progress large-12 success round" style="30px"><span class="meter" style="width: 100%"></span></div></div>
			</div>  					 					
  		</div>
		<div class="row">
  			<div class="large-12 column">
  				<?php
  					//Fetch User Email Address
  					dbconnect();
  					
  					$id = $_SESSION['User']['ID'];	  	
					$stmt = $conn->prepare("SELECT Email FROM User WHERE UserID = :id LIMIT 1");	
					$stmt->execute(array('id' => $id));
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row ) {
  				?>
  				<p>Your booking was completed successfully! A confirmation email has been sent to <strong><?php echo $row['Email'];	}?></strong>. We hope you have a nice day and look forward to seeing you on collection day!</p></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column" id="byesign"><img src="<?php fetchdir($img); ?>byesign.jpg" alt="See you later" title="See you later"/></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column"><a href="<?php fetchdir($gpages); ?>index.php">Return to homepage</a></div>		
  		</div>
  		<!-- End Booking Complete Page --> 
  		
		<?php } ?>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>