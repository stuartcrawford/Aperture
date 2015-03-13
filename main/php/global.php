<?php

	$server = 'x10Hosting';

	// Enable sessions
    session_start();
	$existingSession = count($_SESSION); // Check if garbage collector has ran
	
    // Database Connection Credentials @Author: AM
	if ($server == 'XAMPP') {
		
		$dsn = 'mysql:host=127.0.0.1;dbname=aperture';
		$username = 'root';
		$password = '';
		$conn = null;
		
	}
	
	else if ($server == '000Webhost') {
	 
		$dsn = 'mysql:host=mysql1.000webhost.com;dbname=a3573307_ach';
		$username = 'a3573307_ach';
		$password = 'aperture4';
		$conn = null;
	
	}
	
	else if ($server == 'x10Hosting') {
	 
		$dsn = 'mysql:host=localhost;dbname=apertu99_ach';
		$username = 'apertu99_ach';
		$password = 'aperture4';
		$conn = null;
	
	}
    
    // Libraries
    include 'phpass-0.4.php'; // Password hashing framework
    include 'formvalidator.php'; // Validation class
    include 'CSRFGuard.php'; // CSRF prevention
   
	// Global sessions
	// Use 'Error' and 'Success' to store either the error or success message to pass from php script to original page (removed after page is rendered)
	// Use either session to determine the styling of the message but do not use both at one time
	if (!isset($_SESSION['Form']['Error'])) 									$_SESSION['Form']['Error'] = null;
	if (!isset($_SESSION['Form']['Success'])) 									$_SESSION['Form']['Success'] = null;
	// Use to detect general form submissions which don't require feedback
	if (!isset($_SESSION['Form']['Submit'])) 									$_SESSION['Form']['Submit'] = null;
	// Store variables in this branch which are removed when navigating away from the page, but remain when reloading the page
	if (!isset($_SESSION['Temp'])) 												$_SESSION['Temp'] = null;
	
    // Authentication Session Variables @Author: AM
    if (!isset($_SESSION['User']['ID'])) 										$_SESSION['User']['ID'] = null;
	if (!isset($_SESSION['User']['Forename'])) 									$_SESSION['User']['Forename'] = null;
    if (!isset($_SESSION['User']['AccessLvl'])) 								$_SESSION['User']['AccessLvl'] = null;
    if (!isset($_SESSION['User']['Activated'])) 								$_SESSION['User']['Activated'] = null;

	// Booking Session Variables @Author: AM + JC
   	// Use this for reservation timeout script and to redirect to reservation expired page
	if (!isset($_SESSION['Reservation']['Start'])) 								$_SESSION['Reservation']['Start'] = null;
	// Use this to redirect to page expired page
	if (!isset($_SESSION['Booking']['Step'])) 									$_SESSION['Booking']['Step'] = 0;
	if (!isset($_SESSION['Booking']['Aborted'])) 								$_SESSION['Booking']['Aborted'] = null;
	if (!isset($_SESSION['Booking']['Head']['ID'])) 							$_SESSION['Booking']['Head']['ID'] = null;
	if (!isset($_SESSION['Booking']['Head']['Pickup']['Location'])) 			$_SESSION['Booking']['Head']['Pickup']['Location'] = null;
	if (!isset($_SESSION['Booking']['Head']['Pickup']['Date'])) 				$_SESSION['Booking']['Head']['Pickup']['Date'] = null;
	if (!isset($_SESSION['Booking']['Head']['Pickup']['Time'])) 				$_SESSION['Booking']['Head']['Pickup']['Time'] = null;
	if (!isset($_SESSION['Booking']['Head']['Dropoff']['Location'])) 			$_SESSION['Booking']['Head']['Dropoff']['Location'] = null;
	if (!isset($_SESSION['Booking']['Head']['Dropoff']['Date'])) 				$_SESSION['Booking']['Head']['Dropoff']['Date'] = null;
	if (!isset($_SESSION['Booking']['Head']['Dropoff']['Time'])) 				$_SESSION['Booking']['Head']['Dropoff']['Time'] = null;
	if (!isset($_SESSION['Booking']['Vehicle']['ID'])) 				            $_SESSION['Booking']['Vehicle']['ID'] = null;
	if (!isset($_SESSION['Booking']['Vehicle']['Cost'])) 				        $_SESSION['Booking']['Vehicle']['Cost'] = null;
	if (!isset($_SESSION['Booking']['Detail']['InfantSeat']['Qty'])) 			$_SESSION['Booking']['Detail']['InfantSeat']['Qty'] = null;
   	if (!isset($_SESSION['Booking']['Detail']['ChildSeat']['Qty'])) 			$_SESSION['Booking']['Detail']['ChildSeat']['Qty'] = null;
   	if (!isset($_SESSION['Booking']['Detail']['BoosterSeat']['Qty'])) 			$_SESSION['Booking']['Detail']['BoosterSeat']['Qty'] = null;
   	if (!isset($_SESSION['Booking']['Detail']['SnowTires']['Qty'])) 			$_SESSION['Booking']['Detail']['SnowTires']['Qty'] = null;
   	if (!isset($_SESSION['Booking']['Detail']['GPS']['Qty'])) 					$_SESSION['Booking']['Detail']['GPS']['Qty'] = null;
	if (!isset($_SESSION['Booking']['Detail']['InfantSeat']['Cost'])) 			$_SESSION['Booking']['Detail']['InfantSeat']['Cost'] = null;
   	if (!isset($_SESSION['Booking']['Detail']['ChildSeat']['Cost'])) 			$_SESSION['Booking']['Detail']['ChildSeat']['Cost'] = null;
   	if (!isset($_SESSION['Booking']['Detail']['BoosterSeat']['Cost'])) 			$_SESSION['Booking']['Detail']['BoosterSeat']['Cost'] = null;
   	if (!isset($_SESSION['Booking']['Detail']['SnowTires']['Cost'])) 			$_SESSION['Booking']['Detail']['SnowTires']['Cost'] = null;
   	if (!isset($_SESSION['Booking']['Detail']['GPS']['Cost'])) 					$_SESSION['Booking']['Detail']['GPS']['Cost'] = null;
	
	// Payment Session Variables @Author: AM
	if (!isset($_SESSION['User']['APIToken'])) 									$_SESSION['User']['APIToken'] = null;
	if (!isset($_SESSION['Invoice']['Number'])) 								$_SESSION['Invoice']['Number'] = null;
	if (!isset($_SESSION['Invoice']['Description'])) 							$_SESSION['Invoice']['Description'] = null;
	if (!isset($_SESSION['TotalPrice'])) 										$_SESSION['TotalPrice'] = null;
   
   // Staff Joyride Tour
   if (!isset($_SESSION['staffjoyride']))										$_SESSION['staffjoyride'] = 'on';
   
	// Global Variables
   	$mobileAgent = false;
	$sessionTimeout = 60 * 60; // 1 hour
	$pingInterval = 1000 * 60 * 5; // 5 minutes (js)
	$loginMax = 5;
	$loginTimeout = 60 * 10; // 10 minutes (max login attempts)
	$reservationTimeout = 60 * 45; // 45 minutes
	$minAge = 23; // Minimum age for car hire service
	$minAdvancedBooking = 60 * 60 * 24; // Bookings must be made at least 24 hours in the future
	$maxAdvancedBooking = "+6M"; // Bookings can be made up to 6 months ahead
	
	// Image upload variables
	$allowed_filetypes = array('.jpg','.jpeg','.png'); // These will be the types of file that will pass the validation
	$max_filesize = 524288; // Maximum filesize in BYTES (524288 is 0.5MB)
	$max_filename_length = 100;
	$upload_path = '../img/vehicle/'; //Warning: changing the path to the vehicle folder will delete the old image when you upload a new image when editing a vehicle asset group
	
    // Server properties @Author: AM + JC
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http://' : 'https://'; //The HTTP(S) protocol
    $host = $_SERVER['HTTP_HOST'].'/'; // The website host  
    $script = $_SERVER['SCRIPT_NAME']; // Directory and script name
	$params = $_SERVER['QUERY_STRING']; // URL Parameters
	$currentUrl = $protocol . $host . $script . '?' . $params;
    $currentFile = basename($_SERVER['PHP_SELF']); // Get the filename of the current file
    $currentName = basename($_SERVER['PHP_SELF'], ".php"); // Get the filename of the current file without the extension
    if (isset($_SERVER["HTTP_REFERER"])) $referrer = $_SERVER["HTTP_REFERER"]; // Get the previous page or script
    $feedbackEmail = 'feedback@'.str_replace('www.', null, $_SERVER['HTTP_HOST']); // Email address which feedback is sent to
    $enquiriesEmail = 'enquiries@'.str_replace('www.', null, $_SERVER['HTTP_HOST']); // Email address which enquiries are sent to
    $serverEmail = 'noreply@'.str_replace('www.', null, $_SERVER['HTTP_HOST']); // Email address used in the from header of emails sent by the server
	
	// Server timezone @Added by JC
	$timezone = 'Europe/London';
	date_default_timezone_set($timezone); // Set the timezone in php
	$timezone = new DateTimeZone($timezone);
	$tzoffset = $timezone->getOffset(new DateTime("now")); // Offset in seconds
	if (strlen(round($tzoffset/3600)) == 1) $middle = '0'.round($tzoffset/3600); // Add trailing zero
	$tzoffset = ($tzoffset < 0 ? '-' : '+').$middle.':00'; // prints "+XX:00"
    
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
    
    // Payment API @Author AM
    $merchantEmail = 'merchant.computingprojects4@gmail.com'; // Merchant email
    $_SESSION['livePayment'] = false; // Live or test mode
	if (isset($_SESSION['Response']))				$Response = $_SESSION['Response'];
	if (isset($_SESSION['TotalAmount']))			$TotalAmount = $_SESSION['TotalAmount'];
	if (isset($_SESSION['InvoiceReference']))		$InvoiceReference = $_SESSION['InvoiceReference'];
   
    // Global functions
    function fetchdir($dir)		
	{
		//Fetch Directories
		//@Description: Fetches directory paths from global variables for use in html markup
		//@Author: AM + JC
		/**	
		 *	Example Usage
		 * 	// <img src="<?php fetchdir($img); ?>audi_a4_estate.png">
		 * 	// <a href="<?php fetchdir($gpages); ?>about.php">About</a>"
		 *
		 **/
		 
		$protocol = $GLOBALS['protocol'];
		$host = $GLOBALS['host'];
		($dir == $GLOBALS['apages'] || $dir == $GLOBALS['bpages'] || $dir == $GLOBALS['gpages'] ? $branch = $GLOBALS['pagebranch'] : $branch = $GLOBALS['branch']);
		echo $protocol.$host.$branch.$dir;
	}
	
	function fetchinline($dir)		
	{
		//Fetch Directories
		//@Description: Fetches directory paths from global variables for use within php functions
		//@Author: AM + JC
		/**	
		 *	Example Usage
		 * 	// <?php echo '<a href ="'.fetchdir($gpages).'index.php'" ?>Return to homepage</a>'; ?> 
		 *
		 **/
		 
		$protocol = $GLOBALS['protocol'];
		$host = $GLOBALS['host'];
		($dir == $GLOBALS['apages'] || $dir == $GLOBALS['bpages'] || $dir == $GLOBALS['gpages'] ? $branch = $GLOBALS['pagebranch'] : $branch = $GLOBALS['branch']);
		return $protocol.$host.$branch.$dir;
	}

    function detectmobile()		
    {
    	//Mobile Browser Detection
    	//@Author AM
    	/**
		 *	Example Usage 
		 * 	
		 *	if ($GLOBALS['$mobileAgent'] == true) {
		 * 		//Mobile Content show/process/style etc...
		 * 	} 
		 * 	else if ($GLOBALS['$mobileAgent'] == false) {
		 * 		//Mobile Content hide
		 * 	}
		 * 
    	**/
    	
    	$useragent = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		$GLOBALS['$mobileAgent'] = true;
    }
	
	function dbconnect()
	{
		//PDO Database Connection
		//@Author: AM
		try {
		$GLOBALS['conn'] = new PDO($GLOBALS['dsn'], $GLOBALS['username'], $GLOBALS['password']);
		$GLOBALS['conn']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$GLOBALS['conn']->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Disables emulated statements @Added by JC
		$GLOBALS['conn']->exec("set names utf8, time_zone = '".$GLOBALS['tzoffset']."'"); // Sets the character set to UTF-8 and the timezone in mysql @Added by JC
		} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
		}
	}

	function reservation_timeout() 
	{
	
		//@Title: Reservation Timeout
		//@Author: Alex McCormick
		/**
		 * Clears Booking Reservation After 45mins
		 **/
		 
		dbconnect();
		
		$timeout_seconds = $GLOBALS['reservationTimeout'];
		
		if (isset($_SESSION['Reservation']['Start']) && (time() - $_SESSION['Reservation']['Start'] > $timeout_seconds)) {
	
			//Delete Booking Head and Booking Details Entries From Database
			$stmt = $GLOBALS['conn'] ->prepare("DELETE FROM Booking_Header WHERE BookingHeadID = :BookingHeadID");	
			$stmt->execute(array('BookingHeadID' => $_SESSION['Booking']['Head']['ID']));
			$stmt = $GLOBALS['conn'] ->prepare("DELETE FROM Booking_Detail WHERE BookingHeadID = :BookingHeadID");	
			$stmt->execute(array('BookingHeadID' => $_SESSION['Booking']['Head']['ID']));
		
			//Clear Booking Session Data
			unset(	$_SESSION['Reservation'], $_SESSION['Booking']['Head'], $_SESSION['Booking']['Detail'], $_SESSION['Booking']['Extra'], 
					$_SESSION['Asset'], $_SESSION['Invoice'], $_SESSION['TotalPrice'],$_SESSION['TotalAmount'],
					$_SESSION['Response'],$_SESSION['User']['APIToken'], $_SESSION['ewayAPI']);
				
			//Redirect to Reservation Expired Page
			$_SESSION['Booking']['Aborted'] = true;
			$_SESSION['Booking']['Step'] = 0;
			Header('Location: '.$GLOBALS['protocol'].$GLOBALS['host'].$GLOBALS['pagebranch'].$GLOBALS['bpages'].'reservation_expired.php');
			exit();
		}
	}
	
	function fillInput()
	{
		//Form input filler, keeps inputs intact after invalid details have been submitted and an error message is displayed
		//and initially fills in values from the database if set
		//@Author: JC
		/**
			Example usage (use within html form markup):
			// Email input will be filled with details stored in $_SESSION['Form']['Email']
			<input type="text" name="email" id="email"<?php fillInput('Email'); ?>/>
			// Username input will be filled with details stored in $_SESSION['Form']['UserName'] if set, or in $row['UserName'] if set when the session is not set
			<input type="text" name="username" id="username"<?php fillInput('UserName','UserName'); ?>/>
			
			Further notes:
			// Sessions storing the inputs must have only 2 branches with the first branch being 'Form':
			$_SESSION['Form']['UserName']
			// NOT like this:
			$_SESSION['Form']['User']['Name']
			// If concatenating with echo'd code, use commas instead of periods to join:
			echo '<input type="text" name="email" id="email"',fillInput('Email'),'/>';
			// NOT like this:
			echo '<input type="text" name="email" id="email"'.fillInput('Email').'/>';
		**/
		$arg_list = func_get_args(); //$branch,$dbColumn
		if (!isset($arg_list[1])) $arg_list[1] = '';
		if (isset($_SESSION['Form'][$arg_list[0]]))
		{
			echo ' value="'.$_SESSION['Form'][$arg_list[0]].'"';
		}
		else if (isset($GLOBALS['row'][$arg_list[1]]))
		{
			echo ' value="'.$GLOBALS['row'][$arg_list[1]].'"';
		}
	}
	
	function fillInputDate()
	{
		//Form input date filler, keeps date inputs intact after invalid details have been submitted and an error message is displayed
		//and initially fills in values from the database if set. If the values are from the databse they are converted to the format mm/dd/yyyy
		//@Author: JC
		/**
			Example usage (use within html form markup):
			// DOB input will be filled with details stored in $_SESSION['Form']['DOB'] if set, or filled with the reformatted value in $row['DOB'] if set when the session is not set
			<input type="text" name="dob" id="dob"<?php fillInputDate('DOB','DOB'); ?>/>
			
			Further notes:
			// Sessions storing the inputs must have only 2 branches with the first branch being 'Form':
			$_SESSION['Form']['DOB']
			// NOT like this:
			$_SESSION['Form']['D']['O']['B']
			// If concatenating with echo'd code, use commas instead of periods to join:
			echo '<input type="text" name="dob" id="dob"',fillInputDate('DOB','DOB'),'/>';
			// NOT like this:
			echo '<input type="text" name="dob" id="dob"'.fillInputDate('DOB','DOB').'/>';
		**/
		$arg_list = func_get_args(); //$branch,$dbColumn
		if (!isset($arg_list[1])) $arg_list[1] = '';
		if (isset($_SESSION['Form'][$arg_list[0]]))
		{
			echo ' value="'.$_SESSION['Form'][$arg_list[0]].'"';
		}
		else if (isset($GLOBALS['row'][$arg_list[1]]))
		{
			$date = new DateTime($GLOBALS['row'][$arg_list[1]]);
			echo ' value="'.$date->format('m/d/Y').'"';
		}
	}
	
	function fillTextarea()
	{
		//Form textarea filler, keeps textarea inputs intact after invalid details have been submitted and an error message is displayed
		//and initially fills in values from the database if set
		//@Author: JC
		/**
			Example usage (use within html form markup):
			// Message input will be filled with details stored in $_SESSION['Form']['Message'] if set, or in $row['Message'] if set when the session is not set
			<textarea name="msg" id="msg"><?php fillTextarea('Message','Message'); ?></textarea>
			
			Further notes:
			// Sessions storing the inputs must have only 2 branches with the first branch being 'Form':
			$_SESSION['Form']['MyMessage']
			// NOT like this:
			$_SESSION['Form']['My']['Message']
			// If concatenating with echo'd code, use commas instead of periods to join:
			echo '<textarea name="msg" id="msg">',fillTextarea('Message','Message'),'</textarea>';
			// NOT like this:
			echo '<textarea name="msg" id="msg">'.fillTextarea('Message','Message').'</textarea>';
		**/
		$arg_list = func_get_args(); //$branch,$dbColumn
		if (!isset($arg_list[1])) $arg_list[1] = '';
		if (isset($_SESSION['Form'][$arg_list[0]]))
		{
			echo $_SESSION['Form'][$arg_list[0]];
		}
		else if (isset($GLOBALS['row'][$arg_list[1]]))
		{
			echo $GLOBALS['row'][$arg_list[1]];
		}
	}
	
	function fillSelect() // update example usage and extra arg
	{
		//Form input filler, keeps inputs intact after invalid details have been submitted and an error message is displayed
		//@Author: JC
		/**
			Example usage (use within html form markup):
			// Title dropdown will select the value stored in $_SESSION['Form']['Title']
			<select name="title" id="title">
				<option<?php fillSelect('Title','Mr','',true); ?> value="Mr">Mr</option>
				<option<?php fillSelect('Title','Mrs'); ?> value="Mrs>Mrs</option>
				<option<?php fillSelect('Title','Miss'); ?> value="Miss">Miss</option>
			</select>
			// Title dropdown will select the value stored in $_SESSION['Form']['Title'] if set, or in $row['Title'] if set when the session is not set
			<select name="title" id="title">
				<option<?php fillSelect('Title','Mr','Title',true); ?> value="Mr">Mr</option>
				<option<?php fillSelect('Title','Mrs','Title'); ?> value="Mrs>Mrs</option>
				<option<?php fillSelect('Title','Miss','Title'); ?> value="Miss">Miss</option>
			</select>
			
			Further notes:
			// Sessions storing the inputs must have only 2 branches with the first branch being 'Form':
			$_SESSION['Form']['CustTitle']
			// NOT like this:
			$_SESSION['Form']['Cust']['Title']
			// If concatenating with echo'd code, use commas instead of periods to join:
			echo '<option',fillSelect('Title','Mr',true),' value="Mr">Mr</option>';
			// NOT like this:
			echo '<option'.fillSelect('Title','Mr',true).' value="Mr">Mr</option>';
		**/
		$arg_list = func_get_args(); //$branch,$value,$dbColumn,$default
		if (!isset($arg_list[2])) $arg_list[2] = '';
		if (!isset($arg_list[3])) $arg_list[3] = false;
		if (!isset($_SESSION['Form'][$arg_list[0]]) && $arg_list[3] == true && $arg_list[2] == '')
		{
			echo ' SELECTED';
		}
		else if (isset($_SESSION['Form'][$arg_list[0]]) && $_SESSION['Form'][$arg_list[0]] == $arg_list[1])
		{
			echo ' SELECTED';
		}
		else if (isset($GLOBALS['row'][$arg_list[2]]) && $GLOBALS['row'][$arg_list[2]] == $arg_list[1])
		{
			echo ' SELECTED';
		}
	}
	
	function setpermissions()
	{
		//Authentication redirection function
		//@Author: JC
		/**
			Example usage:
			// Allow only admin, staff and customers to view the page
			setpermissions('admin','staff','customer');
			// Allow only admins to view the page
			setpermissions('admin');
		**/
		$arg_list = func_get_args();
		$users = array('admin','staff','customer');
		$allow = false;
		
		if ($_SESSION['User']['AccessLvl'] == null)
		{
			// User is not logged in
			header('Location: '.fetchinline($GLOBALS['gpages']).'login.php');
			exit;
		}
		if ($_SESSION['User']['Activated'] == 'false')
		{
			// User is not activated
			header('Location: '.fetchinline($GLOBALS['gpages']).'account_activation.php');
			exit;
		}
		for ($i = 0; $i < 3; $i++)
		{
			if ($_SESSION['User']['AccessLvl'] == $i + 1)
			{
				foreach ($arg_list as $arg)
				{
					if (strtolower($arg) == $users[$i])
					{
						$allow = true;
					}
				}
				if ($allow == false)
				{
					// User does not have correct access level to view the page
					header('Location: '.fetchinline($GLOBALS['gpages']).'index.php');
					exit;
				}
			}
		}
	}
	
	function loginTimeout()
	{
		//Login timeout function
		//Author: JC
		if ($GLOBALS['existingSession'] == 0 && isset($_SERVER['HTTP_REFERER']))
		{
			// Fallback when the session has been deleted due to a lengthly period without connectivity, redirects to session expired page
			$uri = parse_url($_SERVER['HTTP_REFERER']);
			// Check the referrer is from the same server, referrer is required to prevent redirect when first entering the site
			//if ($_SERVER['HTTP_REFERER'] != $GLOBALS['protocol'].$_SERVER['HTTP_HOST']) // Prevents session expired if connected through proxy, rewritten urls must not rewrite to host name exactly
			if ($uri['host'] == $_SERVER['HTTP_HOST'] && count($_POST)) // Check the host so it still goes to authentication error on csrf attack
			{
				session_regenerate_id(); // Prevents session fixation
				csrfguard_regenerate_token(); // Get new CSRF token
				$_SESSION['Form']['Submit'] = true;
				header('Location: '.fetchinline($GLOBALS['gpages']).'session_expired.php');
				exit;
			}
		}
		else if(isset($_SESSION['timeout']) && $_SESSION['User']['AccessLvl'] != null)
		{
			// Login timeout functionality if session has not been deleted by the garbage collector
			$inactive = $GLOBALS['sessionTimeout'];
			$session_life = time() - $_SESSION['timeout'];
			if($session_life > $inactive)
			{
				// Clear booking
				dbconnect();
				$stmt = $GLOBALS['conn']->prepare("SELECT * FROM Booking_Header WHERE UserID = :UserID AND STATUS = 4;");
				$stmt->execute(array('UserID' => $_SESSION['User']['ID']));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if($row) {
					$stmt = $GLOBALS['conn']->prepare("DELETE FROM Booking_Header WHERE BookingHeadID = :BookingHeadID");	
					$stmt->execute(array('BookingHeadID' => $row['BookingHeadID']));
					$stmt = $GLOBALS['conn']->prepare("DELETE FROM Booking_Detail WHERE BookingHeadID = :BookingHeadID");	
					$stmt->execute(array('BookingHeadID' => $row['BookingHeadID']));
				}
				
				session_regenerate_id(); // Prevents session fixation
				session_unset(); // Empty the session, unauthenticating the user
				csrfguard_regenerate_token(); // Get new CSRF token
				$_SESSION['Form']['Submit'] = true;
				header('Location: '.fetchinline($GLOBALS['gpages']).'logged_out.php');
				exit;
			}
		}
		$_SESSION['timeout'] = time();
	}
	loginTimeout();
	
	function csrfguard_start()
	{
		//CSRF prevention and further authentication redirection
		//@Author: JC
		$safepages = array('contact.php','submit_enquiry.php','forgotten_password.php','logout.php');
		$count = count($safepages);
		$safe = false;
		for ($i = 0; $i < $count; $i++)
		{
			if ($GLOBALS['currentFile'] == $safepages[$i])
			{
				$safe = true;
				break;
			}
		}
		if ($safe == false)
		{
			if ($GLOBALS['existingSession'] == 0)
			{
				csrfguard_regenerate_token(); // Get new CSRF token
			}
			if (count($_POST))
			{
				// Check for invalid CSRF token
				if (!isset($_POST['CSRFToken']) || !csrfguard_validate_token($_POST['CSRFToken']))
				{
					// Check if script was loaded via ajax
					if (!isset($_POST['ajax']))
					{
						$_SESSION['Form']['Submit'] = true;
						header('Location: '.fetchinline($GLOBALS['gpages']).'authentication_error.php');
						exit;
					}
					else
					{
						$_SESSION['Form']['Submit'] = true;
						echo 'Redirect';
						exit;
					}
				}
			}
			ob_start();
			register_shutdown_function('csrfguard_inject');	// Inject CSRF tokens in forms
		}
	}
	csrfguard_start();
	
	function removeTemp()
	{
		//Removes sessions stored in the temp branch when navigating away from a page
		//@Author: JC
		$currPath = str_replace(basename($_SERVER['PHP_SELF']),'',$_SERVER['PHP_SELF']);
		if (isset($_SESSION['LastFile']))
		{
			if ($_SESSION['LastFile'] != $_SERVER['PHP_SELF'] && $currPath != '/'.$GLOBALS['branch'].$GLOBALS['php'])
			{
				$_SESSION['LastFile'] = $_SERVER['PHP_SELF'];
				unset($_SESSION['Temp']);
			}
		}
		else
		{
			$_SESSION['LastFile'] = $_SERVER['PHP_SELF'];
		}
	}
	removeTemp();
?>