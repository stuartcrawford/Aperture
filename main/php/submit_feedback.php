<?php
	include('global.php');
	dbconnect();
	
	if (isset($_POST['submitfeedback']))
	{
		if (!isset($_POST['ajax']))
		{
			// Copy inputs to session in case of error
			$_SESSION['Form']['msg'] = $_POST['msg'];
		}
		
		// Get the user's email address
		$stmt = $conn->prepare("SELECT Email,FirstName,LastName FROM User WHERE UserID=:id");
		$stmt->bindParam(':id',$_SESSION['User']['ID']);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// Send an email to the server's feedback mailbox
		$to = $feedbackEmail;
		$subject = trim('Feedback: '.$row['FirstName'].' '.$row['LastName']);
		$body = trim($_POST['msg']);
		$header = 'From: '.$row['Email'];
		if (mail($to, $subject, $body, $header))
		{
			if (!isset($_POST['ajax']))
			{
				$_SESSION['Form']['Success'] = "Your feedback was sent successfully";
				header("location: ".fetchinline($apages).'feedback.php');
				exit;
			}
			else
			{
				echo "Your feedback was sent successfully";
			}
		}
		else
		{
			if (!isset($_POST['ajax']))
			{
				$_SESSION['Form']['Error'] = "Your feedback could not be sent. Please try again later";
				header("location: ".fetchinline($apages).'feedback.php');
				exit;
			}
			else
			{
				echo "Your feedback could not be sent. Please try again later";
			}
		}
	}
?>