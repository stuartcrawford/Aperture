<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	setpermissions('admin','staff','customer');
	if (!is_null($_SESSION['Form']['Success']))
	{
		// Clear form inputs stored in the session to avoid autofilling on success (because the success page is the same page)
		$success = $_SESSION['Form']['Success'];
		unset($_SESSION['Form']);
		$_SESSION['Form']['Success'] = $success;
	}
	
	dbconnect();
	
	$stmt = $conn->prepare("SELECT * FROM User WHERE UserID=:id");
	$stmt->bindParam(':id',$_SESSION['User']['ID']);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
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
  			<div class="large-12 column"><h2>My Account</h2></div>		
  		</div>
		
		<div class="row">
  			<div class="large-6 column">
				<form method="post" id="name" name="name" class="custom" action="<?php fetchdir($php); ?>my_account.php">
					<?php
						if (!is_null($_SESSION['Form']['Success']))
						{
							echo '<p id="fb" style="color:green">'.$_SESSION['Form']['Success'].'</p>';
						}
						else if (!is_null($_SESSION['Form']['Error']))
						{
							echo '<p id="fb" style="color:red">'.$_SESSION['Form']['Error'].'</p>';
						}
					?>
					<p>* Required fields</p>
					<h5>Name</h5>
					<label for="title">Title *</label>
					<select name="title" id="title" class="small">
						<option<?php fillSelect('Title','','Title',true); ?> DISABLED value="">Select title</option>
						<option<?php fillSelect('Title','Mr','Title'); ?> value="Mr">Mr</option>
						<option<?php fillSelect('Title','Mrs','Title'); ?> value="Mrs">Mrs</option>
						<option<?php fillSelect('Title','Miss','Title'); ?> value="Miss">Miss</option>
						<option<?php fillSelect('Title','Ms','Title'); ?> value="Ms">Ms</option>
						<option<?php fillSelect('Title','Dr','Title'); ?> value="Dr">Dr</option>
						<option<?php fillSelect('Title','Prof','Title'); ?> value="Prof">Prof</option>
					</select>
					<label for="fname">First name *</label><input type="text" name="fname" id="fname"<?php fillInput('FirstName','FirstName'); ?>/>
					<label for="lname">Last name *</label><input type="text" name="lname" id="lname"<?php fillInput('LastName','LastName'); ?>/>
					<input type="submit" class="radius button small" name="submitname" value="Update"/>
					<hr>
				</form>
				<form method="post" id="address" name="adresss" class="custom" action="<?php fetchdir($php); ?>my_account.php">
					<h5>Address</h5>
					<label for="Add1">Address line 1 *</label><input type="text" name="Add1" id="Add1"<?php fillInput('Add1','Add1'); ?>/>
					<label for="Add2">Address line 2</label><input type="text" name="Add2" id="Add2"<?php fillInput('Add2','Add2'); ?>/>
					<label for="Add3">Address line 3</label><input type="text" name="Add3" id="Add3"<?php fillInput('Add3','Add3'); ?>/>
					<label for="Postcode">Postcode *</label><input type="text" name="Postcode" id="Postcode"<?php fillInput('Postcode','Postcode'); ?>/>
					<input type="submit" class="radius button small" name="submitaddress" value="Update"/>
					<hr>
				</form>
				<form method="post" id="contact" name="contact" class="custom" action="<?php fetchdir($php); ?>my_account.php">
					<h5>Contact Details</h5>
					<label for="Telephone">Telephone number</label><input type="text" name="Telephone" id="Telephone" class="large-6"<?php fillInput('Telephone','Telephone'); ?>/>
					<label for="Mobile">Mobile number</label><input type="text" name="Mobile" id="Mobile" class="large-6"<?php fillInput('Mobile','Mobile'); ?>/>
					<input type="submit" class="radius button small" name="submitcontact" value="Update"/>
					<hr>
				</form>
				<form method="post" id="license" name="license" class="custom" action="<?php fetchdir($php); ?>my_account.php">
					<h5>License Number</h5>
					<label for="LicenseNo">Driving license number</label><input type="text" name="LicenseNo" id="LicenseNo" class="large-6"<?php fillInput('LicenseNo','LicenseNo'); ?>/>
					<input type="submit" class="radius button small" name="submitlicense" value="Update"/>
					<hr>
				</form>
				
				<?php
					if ($row['DOB'] != '0000-00-00')
					{
				?>
				<h5>Date of Birth</h5>
				<label for="DOB">D.O.B</label><input type="text" name="DOB" id="DOB"<?php fillInputDate('DOB','DOB'); ?> readonly/>
				<br>
				<hr>
				<?php }		?>
				
				<?php if ($_SESSION['User']['AccessLvl'] != 1) {	?>
				<form method="post" id="questions" name="questions" class="custom" action="<?php fetchdir($php); ?>my_account.php">
					<h5>Security Questions</h5>
					<label for="q1">Security question 1 *</label>
					<select name="q1" id="q1">
						<?php
							$stmt2 = $conn->prepare("SELECT sqid,sq FROM Security_Question");
							$stmt2->execute();
							$i = 0;
							foreach( $stmt2->fetchAll(PDO::FETCH_ASSOC) as $row2 )
							{
								if ($i == 0)
								{
									echo '<option',fillSelect('SQ1',$row2['sqid'],'SQ1',true),' value="'.$row2['sqid'].'">'.$row2['sq'].'</option>';
								}
								else
								{
									echo '<option',fillSelect('SQ1',$row2['sqid'],'SQ1'),' value="'.$row2['sqid'].'">'.$row2['sq'].'</option>';
								}
								$i++;
							}
						?>
					</select>
					<input type="text" name="a1" id="a1"<?php fillInput('SQA1','SQA1'); ?>/>
					<label for="q2">Security question 2 *</label>
					<select name="q2" id="q2">
						<?php
							$stmt2 = $conn->prepare("SELECT sqid,sq FROM Security_Question");
							$stmt2->execute();
							$i = 0;
							foreach( $stmt2->fetchAll(PDO::FETCH_ASSOC) as $row2 )
							{
								if ($i == 1)
								{
									echo '<option',fillSelect('SQ2',$row2['sqid'],'SQ2',true),' value="'.$row2['sqid'].'">'.$row2['sq'].'</option>';
								}
								else
								{
									echo '<option',fillSelect('SQ2',$row2['sqid'],'SQ2'),' value="'.$row2['sqid'].'">'.$row2['sq'].'</option>';
								}
								$i++;
							}
						?>
					</select>
					<input type="text" name="a2" id="a2"<?php fillInput('SQA2','SQA2'); ?>/>
					<input type="submit" class="radius button small" name="submitquestions" value="Update"/>
					<hr>
					<?php }		?>
				</form>
				<form method="post" id="email" name="email" class="custom" action="<?php fetchdir($php); ?>my_account.php">
					<h5>Change Email</h5>
					<input type="text" style="display:none;"/>
					<input type="password" style="display:none;"/>
					<label for="Email">Email address</label><input type="text" name="Email" id="Email"<?php fillInput('Email'); ?>/>
					<label for="Email2">Confirm Email</label><input type="text" name="Email2" id="Email2">
					<input type="submit" class="radius button small" name="submitemail" value="Update"/>
					<hr>
				</form>
				<form method="post" id="password" name="password" class="custom" action="<?php fetchdir($php); ?>my_account.php">
					<h5>Change Password</h5>
					<input type="text" style="display:none;"/>
					<input type="password" style="display:none;"/>
					<label for="OldPassword">Old password</label><input type="password" name="OldPassword" id="OldPassword"/>
					<label for="Password">New password</label><input type="password" name="Password" id="Password"/>
					<label for="Password2">Confirm new password</label><input type="password" name="Password2" id="Password2"/>
					<input type="submit" class="radius button small" name="submitpassword" value="Update"/>
				</form>
			</div>	
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>