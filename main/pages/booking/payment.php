<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	setpermissions('admin','staff','customer');

	reservation_timeout();

	if ($_SESSION['Booking']['Step'] < 4)
	{
		if(isset($referrer))
		{
			Header ('Location: '.fetchinline($bpages).'page_expired.php');
		}
		else
		{
			Header ('Location: '.fetchinline($gpages).'index.php');
		}
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
	<div id="first_row" class="row">
		<div class="large-8 columns">
			<h2>Payment Details</h2>				
    		<form id="form1" class="custom" action="<?php fetchdir($php)?>create_booking.php" method='post'>
    			<input type='hidden' name='EWAY_FORMACTION' value="<?php echo $Response->FormActionURL ?>" />
        		<input type='hidden' name='EWAY_ACCESSCODE' value="<?php echo $Response->AccessCode ?>" />
        		<div class="row">
        			<div class="large-12 small-10 columns">
        				<label for="EWAY_CARDNAME">Card Holder</label>
        				<input type='text' name='EWAY_CARDNAME' id='EWAY_CARDNAME' value="<?php echo (isset($Response->Customer->CardName) && !empty($Response->Customer->CardName) ? $Response->Customer->CardName:"") ?>" />
        			</div>
        		</div>
        		<div class="row">
        			<div class="large-12 small-10 columns">
           				<label for="EWAY_CARDNUMBER">Card Number</label>
               			<input type='text' name='EWAY_CARDNUMBER' id='EWAY_CARDNUMBER' value="<?php echo (isset($Response->Customer->CardNumber) && !empty($Response->Customer->CardNumber)  ? $Response->Customer->CardNumber:"") ?>" />
          			</div>
          		</div>
          		<div class="row">
          			<div class="large-12 small-10 columns">
                		<img id="card_types" src="<?php fetchdir($img); ?>card_types.jpg" alt="cardtypes" />
                	</div>
          		</div>
          		<label for="EWAY_CARDSTARTMONTH">Valid From Date</label>
         		<div id="card_date_row" class="row">
         			<div class="fields"><div class="large-3 small-5 columns">
                        <select ID="EWAY_CARDSTARTMONTH" name="EWAY_CARDSTARTMONTH">
                        	<?php
                      			if (isset($Response->Customer->CardStartMonth)&& !empty($Response->Customer->CardStartMonth )) {
                      				$expiry_month = $Response->Customer->CardStartMonth;
                      			} else {
                       				$expiry_month = "";//date('m');
                          		}
                     			echo  "<option></option>";
                                   
                         		for($i = 1; $i <= 12; $i++) {
                          			$s = sprintf('%02d', $i);
                      				echo "<option value='$s'";
                        			if ( $expiry_month == $i ) {
                                 		echo " selected='selected'";
                          			}
                                	echo ">$s</option>\n";
                          		}
                          	?>
                      	</select>
                 	</div>
                 	<div class="large-3 small-5 columns">
                    	<select ID="EWAY_CARDSTARTYEAR" name="EWAY_CARDSTARTYEAR">
                        	<?php
                            	$i = date("y");
                               	$j = $i-11;
                               	echo  "<option></option>";
                                for ($i; $i >= $j; $i--) {
                                	$year = sprintf('%02d', $i);
                                    echo "<option value='$year'";
                                    if (isset($Response->Customer->CardStartYear)) {
                                    	if ( $Response->Customer->CardStartYear == $year ) {
                                        	echo " selected='selected'";
                                      	}
                                	}
                                    echo ">$year</option>\n";
                           		}
                         	?>
           				</select>
                 	</div>
                 	<div class="small-6 columns">
                	</div>
				</div></div>    		
          		<label for="EWAY_CARDEXPIRYMONTH">Expiry Date</label>
                <div id="card_date_row" class="row">
                	<div class="large-3 small-5 columns">
                		<select ID="EWAY_CARDEXPIRYMONTH" name="EWAY_CARDEXPIRYMONTH">
         	  				<?php
            	        		if (isset($Response->Customer->CardExpiryMonth)&& !empty($Response->Customer->CardExpiryMonth)) {
                	        		$expiry_month = $Response->Customer->CardExpiryMonth;
               					} else {
      			        	   		$expiry_month = date('m');
                        		}
                               	for($i = 1; $i <= 12; $i++) {
                                	$s = sprintf('%02d', $i);
                                    echo "<option value='$s'";
                                    if ( $expiry_month == $i ) {
                             			echo " selected='selected'";
                                    }
                                    echo ">$s</option>\n";
                                }
                        	?>
               			</select>
                   	</div>
                    <div class="large-3 small-5 columns">
               			<select ID="EWAY_CARDEXPIRYYEAR" name="EWAY_CARDEXPIRYYEAR">
                    		<?php
                        		$i = date("y");
                               	$j = $i+11;
                                for ($i; $i <= $j; $i++) {
                                	echo "<option value='$i'";
                                	if ( isset($Response->Customer->CardExpiryYear) && $Response->Customer->CardExpiryYear == $i ) {
                                  		echo " selected='selected'";
                             		}
                                	echo ">$i</option>\n";
                            	}
                        	?>
                    	</select>
                   	</div>
                  	<div class="small-6 columns">
       				</div>
           		</div>        		
          		<div class="row">
           			<div class="large-3 columns">
       					<label for="EWAY_CARDISSUENUMBER">Issue Number</label>
                		<input type='text' name='EWAY_CARDISSUENUMBER' id='EWAY_CARDISSUENUMBER' value="<?php echo (isset($Response->Customer->CardIssueNumber) && !empty($Response->Customer->CardIssueNumber) ? $Response->Customer->CardIssueNumber:"") ?>" maxlength="2" style="width:40px;"/> <!-- This field is optional but highly recommended -->
                	</div>
                </div>
                <div class="row">
          			<div class="large-3 columns">
                		<label for="EWAY_CARDCVN">CVN</label>
                		<input type='text' name='EWAY_CARDCVN' id='EWAY_CARDCVN' value="" maxlength="4" style="width:40px;" autocomplete="off"/> <!-- This field is optional but highly recommended -->
                	</div>
                </div>
                <div class="row">
                	<div class="large-12 columns">
                		<ul class="button-group">
             				<li><input class="button" type='submit' ID="btnSubmit" name='booking5' value="Submit" /></li>
    						<li><input class="button" type='submit' name='cancel' value="Cancel"/></li>
    					</ul>
    				</div>
    			</div>
    		</form>
    	</div>
	</div>
    <!-- End Main Content -->
    
    <!-- Footer -->
 	<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 	<!-- End Footer -->	
</body>
</html>