<?php
	include('global.php');
	dbconnect();
	
	if (isset($_POST['submit']))
	{
		// Copy inputs to session in case of error
		$_SESSION['Form']['Email'] = $_POST['email'];
		
		// Fetch customer details
		$stmt = $conn->prepare("SELECT UserID,Status,AccessLvl,FirstName,Password,NextLogin,LoginAttempts FROM User WHERE Email=:email AND Status!=0");
		$stmt->bindParam(':email',$_POST['email']);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// Check if email exists
		if ($row)
		{
			// Email exists
			// Check if locked out of account from too many failed login attempts
			if (time()>=strtotime($row['NextLogin']))
			{
				// Login permitted
				$t_hasher = new PasswordHash(8, FALSE);
				$check = $t_hasher->CheckPassword($_POST['password'], $row['Password']);
				// Check if password is correct
				if ($check)
				{
					// Password correct
					// Reset Login attempts
					$stmt = $conn->prepare("UPDATE User SET NextLogin=now(),LoginAttempts=0 WHERE Email=:email");
					$stmt->bindParam(':email',$_POST['email']);
					$stmt->execute();
					
					// Authenticate the user
					session_regenerate_id(); // Prevents session fixation
					csrfguard_regenerate_token(); // Get new CSRF token
					$_SESSION['User']['ID'] = $row['UserID'];
					$_SESSION['User']['Forename'] = $row['FirstName'];
					$_SESSION['User']['AccessLvl'] = $row['AccessLvl'];
					
					// Clear booking
					$stmt2 = $conn->prepare("SELECT * FROM Booking_Header WHERE UserID = :UserID AND STATUS = 4;");
					$stmt2->execute(array('UserID' => $_SESSION['User']['ID']));
					$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
					
					if($row2) {
						$stmt2 = $conn->prepare("DELETE FROM Booking_Header WHERE BookingHeadID = :BookingHeadID");	
						$stmt2->execute(array('BookingHeadID' => $row2['BookingHeadID']));
						$stmt2 = $conn->prepare("DELETE FROM Booking_Detail WHERE BookingHeadID = :BookingHeadID");	
						$stmt2->execute(array('BookingHeadID' => $row2['BookingHeadID']));
					}	
					
					// Check if account is activated
					if ($row['Status'] == 1)
					{
						// Activated
						(basename($referrer) == 'login.php' ? $_SESSION['Form']['Referrer'] = fetchinline($gpages).'index.php' : $_SESSION['Form']['Referrer'] = $referrer);
						header('Location: '.fetchinline($apages).'login_success.php');
					}
					else if ($row['Status'] == 2)
					{
						// Not activated
						$_SESSION['User']['Activated'] = 'false';
						header('Location: '.fetchinline($gpages).'account_activation.php');
					}
				}
				else
				{
					// Password incorrect
					$attempt = $row['LoginAttempts'];
					// Reset login attempts if timeout period has passed since last login attempt
					if (strtotime($row['NextLogin']) <= time() - $loginTimeout)
					{
						$attempt = 0;
					}
					// Check if max login attempts reached
					if ($attempt == $loginMax - 1)
					{
						// Maximum reached
						$next = date("Y-m-d H:i:s",time() + $loginTimeout);
						// Update next login timestamp and reset login attempts, disabling login for the timeout period
						$stmt = $conn->prepare("UPDATE User SET NextLogin=:next,LoginAttempts=0 WHERE Email=:email");
						$stmt->bindParam(':email',$_POST['email']);
						$stmt->bindParam(':next',$next);
						$stmt->execute();
						
						$_SESSION['User']['ID'] = $row['UserID']; // Allow access to max out login page
						header('Location: '.fetchinline($gpages).'maxed_out_login.php');
					}
					else
					{
						// Maximum not reached
						$attempt++;
						// Update Login attempts
						$stmt = $conn->prepare("UPDATE User SET NextLogin=:next,LoginAttempts=:attempt WHERE Email=:email");
						$stmt->bindParam(':next',date("Y-m-d H:i:s",time()));
						$stmt->bindParam(':attempt',$attempt);
						$stmt->bindParam(':email',$_POST['email']);
						$stmt->execute();
						
						$_SESSION['User']['ID'] = null; // Revoke access to max out login page
						$_SESSION['Form']['Error'] = 'The email address or password you entered is incorrect';
						header('Location: '.fetchinline($gpages).'login.php');
					}
				}
			}
			else
			{
				// Login restricted
				$_SESSION['User']['ID'] = $row['UserID']; // Allow access to max out login page
				header('Location: '.fetchinline($gpages).'maxed_out_login.php');
			}
		}
		else
		{
			// Email address not found
			$_SESSION['Form']['Error'] = $row['UserID'].'The email address or password you entered is incorrect';
			header('Location: '.fetchinline($gpages).'login.php');
		}
	}
	else if (isset($_POST['dropsubmit']))
	{
		// Fetch customer details
		$stmt = $conn->prepare("SELECT UserID,Status,AccessLvl,FirstName,Password,NextLogin,LoginAttempts FROM User WHERE Email=:email AND Status!=0");
		$stmt->bindParam(':email',$_POST['email']);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// Check if email exists
		if ($row)
		{
			// Email exists
			// Check if locked out of account from too many failed login attempts
			if (time()>=strtotime($row['NextLogin']))
			{
				// Login permitted
				$t_hasher = new PasswordHash(8, FALSE);
				$check = $t_hasher->CheckPassword($_POST['password'], $row['Password']);
				// Check if password is correct
				if ($check)
				{
					// Password correct
					// Reset Login attempts
					$stmt = $conn->prepare("UPDATE User SET NextLogin=now(),LoginAttempts=0 WHERE Email=:email");
					$stmt->bindParam(':email',$_POST['email']);
					$stmt->execute();
					
					// Authenticate the user
					session_regenerate_id(); // Prevents session fixation
					csrfguard_regenerate_token(); // Get new CSRF token
					$_SESSION['User']['ID'] = $row['UserID'];
					$_SESSION['User']['Forename'] = $row['FirstName'];
					$_SESSION['User']['AccessLvl'] = $row['AccessLvl'];
					
					// Check if account is activated
					if ($row['Status'] == 1)
					{
						// Activated
						(basename($referrer) == 'login.php' ? $_SESSION['Form']['Referrer'] = fetchinline($gpages).'index.php' : $_SESSION['Form']['Referrer'] = $referrer);
						echo 'Success';
					}
					else if ($row['Status'] == 2)
					{
						// Not activated
						$_SESSION['User']['Activated'] = 'false';
						echo 'Activation';
					}
				}
				else
				{
					// Password incorrect
					$attempt = $row['LoginAttempts'];
					// Reset login attempts if timeout period has passed since last login attempt
					if (strtotime($row['NextLogin']) <= time() - $loginTimeout)
					{
						$attempt = 0;
					}
					// Check if max login attempts reached
					if ($attempt == $loginMax - 1)
					{
						// Maximum reached
						$next = date("Y-m-d H:i:s",time() + $loginTimeout);
						// Update next login timestamp and reset login attempts, disabling login for the timeout period
						$stmt = $conn->prepare("UPDATE User SET NextLogin=:next,LoginAttempts=0 WHERE Email=:email");
						$stmt->bindParam(':email',$_POST['email']);
						$stmt->bindParam(':next',$next);
						$stmt->execute();
						
						$_SESSION['User']['ID'] = $row['UserID']; // Allow access to max out login page
						echo 'MaxedOut';
					}
					else
					{
						// Maximum not reached
						$attempt++;
						// Update Login attempts
						$stmt = $conn->prepare("UPDATE User SET NextLogin=:next,LoginAttempts=:attempt WHERE Email=:email");
						$stmt->bindParam(':next',date("Y-m-d H:i:s",time()));
						$stmt->bindParam(':attempt',$attempt);
						$stmt->bindParam(':email',$_POST['email']);
						$stmt->execute();
						
						$_SESSION['User']['ID'] = null; // Revoke access to max out login page
						echo 'Error';
					}
				}
			}
			else
			{
				// Login restricted
				$_SESSION['User']['ID'] = $row['UserID']; // Allow access to max out login page
				echo 'MaxedOut';
			}
		}
		else
		{
			// Email address not found
			echo 'Error';
		}
	}
?>