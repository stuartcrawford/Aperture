<?php
	include('global.php');
	dbconnect();
	
	if (!is_null($_SESSION['User']['AccessLvl']))
	{
		if (!isset($_POST['ajax']))
		{
			$_SESSION['Form']['Submit'] = true;
			header('Location: '.fetchinline($GLOBALS['gpages']).'authentication_error.php');
		}
		else
		{
			$_SESSION['Form']['Submit'] = true;
			echo 'Redirect';
			exit;
		}
	}
	
	// Copy inputs to session in case of error
	$_SESSION['Form']['Email'] = $_POST['accountemail'];
	$_SESSION['Form']['Q1'] = $_POST['q1'];
	$_SESSION['Form']['Q2'] = $_POST['q2'];
	
	function genpwd($cnt)  
	{
		// Function to generate a random password of a set length
		$pwd = str_shuffle('abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#%$*'); // characters to be included for randomization, here you can add or delete the characters
		return substr($pwd,0,$cnt); // here specify the 2nd parameter as start position, it can be anything, default 0
	}

	if (isset($_POST['submitemail']))
	{
		// Executes when submitting the enter email form (ajax)
		$stmt = $conn->prepare("SELECT COUNT(UserID) FROM User WHERE Email=:email AND AccessLvl!=1 AND Status!=0");
		$stmt->bindParam(':email',$_POST['accountemail']);
		$stmt->execute();
		$exists = $stmt->fetchColumn();
		if ($exists)
		{
			// Success
			$_SESSION['Form']['SetEmail'] = $_POST['accountemail']; // Pass email to next form
			if (!isset($_POST['ajax']))
			{
				header('Location: '.fetchinline($gpages).'forgotten_password.php');
			}
		}
		else
		{
			// Error
			$_SESSION['Form']['Error'] = 'The email address you entered could not be found on the system';
			if (!isset($_POST['ajax']))
			{
				header('Location: '.fetchinline($gpages).'forgotten_password.php');
			}
		}
	}
	else if (isset($_POST['submit']))
	{
		// Executes when submitting the security questions form
		if ($_POST['accountemail'] == $_POST['setemail'])
		{
			$stmt = $conn->prepare("SELECT SQA1,SQA2 FROM User WHERE Email=:email AND AccessLvl!=1 AND Status!=0");
			$stmt->bindParam(':email',$_POST['accountemail']);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row['SQA1'] == $_POST['q1'] && $row['SQA2'] == $_POST['q2'])
			{
				// Create a new password
				$t_hasher = new PasswordHash(8, FALSE);
				$password = genpwd(10); // Generate a random password
				$hash = $t_hasher->HashPassword($password); // Hash the password
				// Send an email with the new password to the user's email address
				$to = $_POST['accountemail'];
				$subject ='Your password has been reset';
				$body =
'Your password has been reset. Here are your new credentials:

------------------------
Email: '.$_POST['accountemail'].'
Password: '.$password.'
------------------------

You can log in via the link below:
'.fetchinline($gpages).'login.php

Please remember to change your password to something more memorable after you have logged in';
				$header = 'From: '.$serverEmail;
				if (mail($to, $subject, $body, $header))
				{
					// Success
					$stmt = $conn->prepare("UPDATE User SET Password=:password WHERE Email=:email");
					$stmt->bindParam(':password',$hash);
					$stmt->bindParam(':email',$_POST['accountemail']);
					$stmt->execute();
					$_SESSION['Form']['Email'] = $_POST['accountemail'];
					header('Location: '.fetchinline($gpages).'password_reset.php'); // Email passed to success page in url
				}
				else
				{
					// Error
					$_SESSION['Form']['SetEmail'] = $_POST['accountemail'];
					$_SESSION['Form']['Error2'] = "The password reset email failed to send";
					header('Location: '.fetchinline($gpages).'forgotten_password.php');
				}
			}
			else
			{
				// Error
				$_SESSION['Form']['SetEmail'] = $_POST['accountemail'];
				$_SESSION['Form']['Error2'] = 'One or both of the answers to the security questions are incorrect';
				header('Location: '.fetchinline($gpages).'forgotten_password.php');
			}
		}
		else
		{
			// Error
			$_SESSION['Form']['SetEmail'] = $_POST['setemail'];
			$_SESSION['Form']['Error2'] = 'The email address is different to the one originally specified. Please update the email address before proceeding';
			header('Location: '.fetchinline($gpages).'forgotten_password.php');
		}
	}
?>
<div id="first_row" class="row">
	<div class="large-12 column"><h2>Forgotten Password</h2></div>		
</div>
<div class="row">
	<div class="large-12 column"><p id="message">To reset your password, you must enter the email address associated with your account and answer the 2 security questions you set up when registering. After successfully completing this form you will receive an email providing a new password, which you will then be able to log in with.</p></div>		
</div>
<div class="row">
	<div class="large-6 column">
		<form action="<?php echo fetchdir($php).$currentFile; ?>" method="post" id="enter_email" name="enter_email" class="nocsrf">
			<label for="accountemail">Email address</label><input type="text" name="accountemail" id="accountemail"<?php fillInput('Email'); ?>/>
			<?php
				if (!is_null($_SESSION['Form']['Error']))
				{
					echo '<p id="fb" style="color:red">'.$_SESSION['Form']['Error'].'</p>';
				}
				if (isset($_SESSION['Form']['SetEmail']))
				{
					echo '<input type="submit" class="radius button small" name="submitemail" id="submitemail" value="Update"/>';
				}
				else
				{
					echo '<input type="submit" class="radius button small" name="submitemail" id="submitemail" value="Continue"/>';
				}
				
				// Second part of form appears through ajax
				if (isset($_SESSION['Form']['SetEmail']))
				{
					dbconnect();
					
					$stmt = $conn->prepare("SET @email=:email");
					$stmt->bindParam(':email',$_SESSION['Form']['SetEmail']);
					$stmt->execute();
					$stmt = $conn->prepare("SELECT sq FROM Security_Question WHERE sqid IN ((SELECT sq1 FROM User WHERE email=@email),(SELECT sq2 FROM User WHERE email=@email))");
					$stmt->execute();
					$i = 0;
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
					{
						$sq[$i] = $row['sq'];
						$i++;
					}
					
					echo '
						<h5>Security Questions</h5>
						<label for="q1">'.$sq[0].'</label><input type="text" name="q1" id="q1"',fillInput('Q1'),'/>
						<label for="q2">'.$sq[1].'</label><input type="text" name="q2" id="q2"',fillInput('Q2'),'/>
						<input type="hidden" name="setemail" id="setemail" value="'.$_SESSION['Form']['SetEmail'].'"/>
					';
					if (isset($_SESSION['Form']['Error2']))
					{
						echo '<p id="fb" style="color:red">'.$_SESSION['Form']['Error2'].'</p>';
					}
					echo '
						<input type="submit" class="radius button small success" name="submit" id="submit" value="Reset Password"/>
					';
				}
			?>
		</form>
	</div>		
</div>
<?php
	if (isset($_POST['ajax']))
	{
		unset($_SESSION['Form']);
	}
?>