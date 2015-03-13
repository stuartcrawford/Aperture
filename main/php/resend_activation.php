<?php
	include 'global.php';
	dbconnect();
	
	if (isset($_POST['submit']))
	{
		if ($_SESSION['User']['Activated'] == 'false')
		{
			$stmt = $conn->prepare("SELECT Email,Activation FROM User WHERE UserID=:id");
			$stmt->bindParam(':id',$_SESSION['User']['ID']);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$to      = $row['Email']; // Send Email to our user
			$subject = 'Email Verification'; // Give the Email a subject 
			$message =
'Thanks for signing up!
Your account has been created. Please click this link to activate your account:

'.fetchinline($php).'activation.php?Email='.$row['Email'].'&Activation='.$row['Activation'];
			// Our message above including the link
			$header = 'From:'.$serverEmail."\r\n"; // Set from header

			if (mail($to, $subject, $message, $header)) // Send our Email
			{
				// Success
				$_SESSION['Form']['Success'] = 'The activation email has been resent';
				header('Location: '.fetchinline($gpages).'account_activation.php');
			}
			else
			{
				// Error
				$_SESSION['Form']['Error'] = 'The activation email could not be resent. Please try again later';
				header('Location: '.fetchinline($gpages).'account_activation.php');
			}
		}
		else
		{
			$_SESSION['Form']['Submit'] = true;
			header('Location: '.fetchinline($gpages).'authentication_error.php');
		}
	}
?>