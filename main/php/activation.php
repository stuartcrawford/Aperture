<?php
	include 'global.php';
	dbconnect();
	
	if (isset($_GET['Email']) && !empty($_GET['Email']) AND isset($_GET['Activation']) && !empty($_GET['Activation']))
	{
		// Verify data
		$stmt = $conn->prepare("SELECT UserID FROM User WHERE Email=:Email AND Activation=:Activation AND Status=2");
		$stmt->execute(array('Email' => $_GET['Email'], 'Activation' => $_GET['Activation']));
		$match = $stmt->fetchColumn();
		
		if ($match)
		{
			// We have a match, activate the account
			$stmt2 = $conn->prepare("UPDATE User SET Status=1 WHERE Email=:Email");
			$stmt2->execute(array('Email' => $_GET['Email']));
			$_SESSION['User']['Activated'] = null;
			$_SESSION['Form']['Email'] = $_GET['Email'];
			if ($match == $_SESSION['User']['ID'])
			{
				$_SESSION['Form']['Auth'] = true;
			}
			header('Location: '.fetchinline($gpages).'registration_complete.php');
			//echo '<div class="statusmsg">Your account has been activated, you can now login</div>';
		}
		else
		{
			// No match -> invalid url or account has already been activated.
			//echo '<div class="statusmsg">The url is either invalid or you already have activated your account.</div>';
			$_SESSION['Form']['Error'] = true;
			header('Location: '.fetchinline($gpages).'registration_complete.php');
		}
	}
	else
	{
		// Invalid approach
		//echo '<div class="statusmsg">Invalid approach, please use the link that has been send to your email.</div>';
		header('Location: '.fetchinline($gpages).'index.php');
	}
?>