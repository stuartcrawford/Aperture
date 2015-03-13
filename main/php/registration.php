<?php
	include('global.php');
	dbconnect();
	
	if (isset($_POST['submit']))
	{
		// Copy inputs to session in case of error
		$_SESSION['Form']['Title'] = $_POST['title'];
		$_SESSION['Form']['FirstName'] = $_POST['fname'];
		$_SESSION['Form']['LastName'] = $_POST['lname'];
		$_SESSION['Form']['Add1'] = $_POST['Add1'];
		$_SESSION['Form']['Add2'] = $_POST['Add2'];
		$_SESSION['Form']['Add3'] = $_POST['Add3'];
		$_SESSION['Form']['Postcode'] = $_POST['Postcode'];
		$_SESSION['Form']['Telephone'] = $_POST['Telephone'];
		$_SESSION['Form']['Mobile'] = $_POST['Mobile'];
		$_SESSION['Form']['LicenseNo'] = $_POST['LicenseNo'];
		$_SESSION['Form']['Age'] = $_POST['age'];
		$_SESSION['Form']['SQ1'] = $_POST['q1'];
		$_SESSION['Form']['SQ2'] = $_POST['q2'];
		$_SESSION['Form']['SQA1'] = $_POST['a1'];
		$_SESSION['Form']['SQA2'] = $_POST['a2'];
		$_SESSION['Form']['Email'] = $_POST['Email'];
		
		// Validation
		$form[0] = new formValidator('registration','submit');
		$form[0] -> selected('title','You must select a title');
		$form[0] -> notEmpty('fname','You must enter a first name');
		$form[0] -> notEmpty('lname','You must enter a last name');
		$form[0] -> notEmpty('Add1','You must fill in address line 1');
		$form[0] -> notEmpty('Postcode','You must enter a postcode');
		$form[0] -> validateTelephone('Telephone','Telephone number must have exactly 11 digits and must contain only numbers and spaces');
		$form[0] -> limitSpaces('Telephone',2,'Telephone number cannot contain more than 2 spaces');
		$form[0] -> validateTelephone('Mobile','Mobile number must have exactly 11 digits and must contain only numbers');
		$form[0] -> limitSpaces('Mobile',0,'Mobile number cannot contain spaces');
		$form[0] -> notEmptyGroup('Telephone','Mobile','You must enter a telephone or mobile number');
		$form[0] -> minLength('LicenseNo',16,'License number must be 16 characters long');
		$form[0] -> maxLength('LicenseNo',16,'License number must be 16 characters long');
		$form[0] -> limitSpaces('LicenseNo',0,'License number cannot contain spaces');
		$form[0] -> selected('age','You must select your age');
		$form[0] -> validateRange('age',$minAge,null,'You must be 23 or over to hire a car',null);
		$form[0] -> notEmpty('a1','You must enter an answer for security question 1');
		$form[0] -> notMatch('q1','q2','You cannot choose the same question for both security questions');
		$form[0] -> notEmpty('a2','You must enter an answer for security question 2');
		$form[0] -> notEmpty('Email','You must enter an email');
		$form[0] -> validateEmail('Email','You must enter a valid email');
		$form[0] -> notEmpty('Password','You must enter a password');
		$form[0] -> minLength('Password',8,'Password must be at least 8 characters long');
		$form[0] -> limitSpaces('Password',0,'Password must not contain spaces');
		$form[0] -> notEmpty('Password2',null);
		$form[0] -> fieldMatch('Password','Password2','Passwords do not match');
		
		if ($form[0] -> valid() !== true)
		{
			$_SESSION['Form']['Error'] = $form[0] -> valid();
			header('Location: '.fetchinline($gpages).$currentFile);
			exit;
		}
	
		$Activation = md5( rand(0,1000) ); // Generate random 32 character Activation and assign it to a local variable.
		//$password = rand(1000,5000); // Generate random number between 1000 and 5000 and assign it to a local variable.
		//Example output: 4568 
		
		$Title = $_POST['title'];
		$FirstName = trim($_POST['fname']);
		$LastName = trim($_POST['lname']);
		$Add1 = trim($_POST['Add1']);
		$Add2 = trim($_POST['Add2']);
		$Add3 = trim($_POST['Add3']);
		$Postcode = $_POST['Postcode'];
		$Telephone = $_POST['Telephone'];
		$Mobile = $_POST['Mobile'];
		$LicenseNo = $_POST['LicenseNo'];
		$SQ1 = $_POST['q1'];
		$SQ2 = $_POST['q2'];
		$SQA1 = $_POST['a1'];
		$SQA2 = $_POST['a2'];
		$Email = $_POST['Email'];
		$t_hasher = new PasswordHash(8, FALSE);
		$Password = $_POST['Password'];
		$Hash = $t_hasher->HashPassword($Password);
		
		//message
		//Return Success - Valid Email
		//$msg = 'Your account has been made,<br>please verify it by clicking the activation link that has been sent to your Email.';

		$to      = $Email; // Send Email to our user
		$subject = 'Registration Confirmation'; // Give the Email a subject 
		$message =
'Thanks for signing up!
Your account has been created, you can login with the following credentials after you have activated your account by clicking the link below.

------------------------
Email: '.$Email.'
Password: '.$Password.'
------------------------

Please click this link to activate your account:

'.fetchinline($php).'activation.php?Email='.$Email.'&Activation='.$Activation;
		// Our message above including the link
		$header = 'From:'.$serverEmail."\r\n"; // Set from header
		
		//Check email is unique TODO: use trigger instead once implemented
		$stmt = $conn->prepare("SELECT UserID FROM User WHERE Email=:Email");
		$stmt->execute(array('Email' => $Email));
		$exists = $stmt->fetchColumn();
		if (!$exists)
		{
			if (mail($to, $subject, $message, $header)) // Send our Email
			{
				//PDO PREPARED STATEMENT
				$stmt = $conn->prepare
				("INSERT INTO User
				(UserID, Status, CreationDate,
				AccessLvl, Email, Password,
				DOB, Title, FirstName,
				LastName, Add1, Add2,
				Add3, Postcode, Telephone,
				Mobile, LicenseNo, NextLogin,
				LoginAttempts, Activation, SQ1,
				SQ2, SQA1, SQA2,
				APIToken)
				VALUES 
				(null, 2, now(),
				3, :Email, :Password,
				null, :Title, :FirstName,
				:LastName, :Add1, :Add2,
				:Add3, :Postcode, :Telephone,
				:Mobile, :LicenseNo, now(),
				0, :Activation, :SQ1,
				:SQ2, :SQA1, :SQA2,
				null)");
				
				$stmt->bindParam('Email',$Email);
				$stmt->bindParam('Password',$Hash);
				$stmt->bindParam('Title',$Title);
				$stmt->bindParam('FirstName',$FirstName);
				$stmt->bindParam('LastName',$LastName);
				$stmt->bindParam('Add1',$Add1);
				$stmt->bindParam('Add2',$Add2);
				$stmt->bindParam('Add3',$Add3);
				$stmt->bindParam('Postcode',$Postcode);
				$stmt->bindParam('Telephone',$Telephone);
				$stmt->bindParam('Mobile',$Mobile);
				$stmt->bindParam('LicenseNo',$LicenseNo);
				$stmt->bindParam('Activation',$Activation);
				$stmt->bindParam('SQ1',$SQ1);
				$stmt->bindParam('SQ2',$SQ2);
				$stmt->bindParam('SQA1',$SQA1);
				$stmt->bindParam('SQA2',$SQA2);
				$stmt->execute();
				
				// Log in the user as an unactivated user
				session_regenerate_id(); // Prevents session fixation
				csrfguard_regenerate_token(); // Get new CSRF token
				$_SESSION['User']['ID'] = $conn->lastInsertId();
				$_SESSION['User']['Forename'] = $FirstName;
				$_SESSION['User']['AccessLvl'] = 3;
				$_SESSION['User']['Activated'] = 'false';
				header('Location: '.fetchinline($gpages).'account_activation.php');
			}
			else // Email could not be sent
			{
				$_SESSION['Form']['Error'] = "The registration email could not be sent. Please try again later";
				header('Location: '.fetchinline($gpages).$currentFile);
			}
		}
		else // Email already registered
		{
			$_SESSION['Form']['Error'] = "The email address specified is already associated with an account";
			header('Location: '.fetchinline($gpages).$currentFile);
		}
	}
?>