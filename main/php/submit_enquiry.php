<?php
	include('global.php');
	dbconnect();
	
	if (isset($_POST['submit']))
	{
		// Copy inputs to session in case of error
		$_SESSION['Form']['Email'] = $_POST['email'];
		$_SESSION['Form']['Name'] = $_POST['name'];
		$_SESSION['Form']['msg'] = $_POST['msg'];

		// Send an email to the server's enquiry mailbox
		$to = $enquiriesEmail;
		$subject = trim('Feedback: '.$_POST['name']);
		$body = trim($_POST['msg']);
		$header = 'From: '.$_POST['email'];
		if (mail($to, $subject, $body, $header))
		{
			$_SESSION['Form']['Success'] = "Your message was sent successfully";
			header("location: ".fetchinline($gpages).'contact.php');
			exit;
		}
		else
		{
			$_SESSION['Form']['Error'] = "Your message could not be sent. Please try again later";
			header("location: ".fetchinline($gpages).'contact.php');
			exit;
		}
	}
?>