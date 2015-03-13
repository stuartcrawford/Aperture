<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	if (!is_null($_SESSION['User']['AccessLvl']))
	{
		header('Location: '.fetchinline($gpages).'index.php');
	}
	
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
	
	// Create javascript code
	$validation_js_code = null;
	$count = count($form);
	for ($i = 0; $i < $count; $i++)
	{
		$validation_js_code .= $form[$i] -> parse();
		$validation_js_code .= "$(function(){
									$('[name=\"{$form[$i] -> formButton}\"]').click(function(e){
										{$form[$i] -> formName}init(e);
									});
								});"; // Remove this for custom event handler
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
  			<div class="large-12 column"><h2>Registration</h2></div>		
  		</div>
		
		<div class="row">
  			<div class="large-6 column">
				<form method="post" id="registration" name="registration" class="custom" action="<?php fetchdir($php); ?>registration.php">
					<?php
						if (!is_null($_SESSION['Form']['Error']))
						{
							echo '<p id="fb" style="color:red">'.$_SESSION['Form']['Error'].'</p>';
						}
					?>
					<p>* Required fields</p>
					<label for="title">Title *</label>
					<select name="title" id="title" class="small">
						<option<?php fillSelect('Title','','',true); ?> DISABLED value="">Select title</option>
						<option<?php fillSelect('Title','Mr'); ?> value="Mr">Mr</option>
						<option<?php fillSelect('Title','Mrs'); ?> value="Mrs">Mrs</option>
						<option<?php fillSelect('Title','Miss'); ?> value="Miss">Miss</option>
						<option<?php fillSelect('Title','Ms'); ?> value="Ms">Ms</option>
						<option<?php fillSelect('Title','Dr'); ?> value="Dr">Dr</option>
						<option<?php fillSelect('Title','Prof'); ?> value="Prof">Prof</option>
					</select>
					<label for="fname">First name *</label><input type="text" name="fname" id="fname"<?php fillInput('FirstName'); ?>/>
					<label for="lname">Last name *</label><input type="text" name="lname" id="lname"<?php fillInput('LastName'); ?>/>
					<label for="Add1">Address line 1 *</label><input type="text" name="Add1" id="Add1"<?php fillInput('Add1'); ?>/>
					<label for="Add2">Address line 2</label><input type="text" name="Add2" id="Add2"<?php fillInput('Add2'); ?>/>
					<label for="Add3">Address line 3</label><input type="text" name="Add3" id="Add3"<?php fillInput('Add3'); ?>/>
					<label for="Postcode">Postcode *</label><input type="text" name="Postcode" id="Postcode"<?php fillInput('Postcode'); ?>/>
					<label for="Telephone">Telephone number</label><input type="text" name="Telephone" id="Telephone" class="large-6"<?php fillInput('Telephone'); ?>/>
					<label for="Mobile">Mobile number</label><input type="text" name="Mobile" id="Mobile" class="large-6"<?php fillInput('Mobile'); ?>/>
					<label for="LicenseNo">Driving license number</label><input type="text" name="LicenseNo" id="LicenseNo" class="large-6"<?php fillInput('LicenseNo'); ?>/>
					<label for="age">Age</label>
					<select name="age" id="age" class="small">
						<option<?php fillSelect('Age','','',true); ?> DISABLED value="">Select age</option>
						<?php
							for ($i=17; $i<=65; $i++)
							{
								echo "<option",fillSelect('Age',$i)," value=\"$i\">$i</option>";
							}
						?>
					</select>
					<label for="q1">Security question 1 *</label>
					<select name="q1" id="q1">
						<?php
							dbconnect();
							
							$stmt = $conn->prepare("SELECT sqid,sq FROM Security_Question");
							$stmt->execute();
							$i = 0;
							foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
							{
								if ($i == 0)
								{
									echo '<option',fillSelect('SQ1',$row['sqid'],'',true),' value="'.$row['sqid'].'">'.$row['sq'].'</option>';
								}
								else
								{
									echo '<option',fillSelect('SQ1',$row['sqid']),' value="'.$row['sqid'].'">'.$row['sq'].'</option>';
								}
								$i++;
							}
						?>
					</select>
					<input type="text" name="a1" id="a1"<?php fillInput('SQA1'); ?>/>
					<label for="q2">Security question 2 *</label>
					<select name="q2" id="q2">
						<?php
							$stmt = $conn->prepare("SELECT sqid,sq FROM Security_Question");
							$stmt->execute();
							$i = 0;
							foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
							{
								if ($i == 1)
								{
									echo '<option',fillSelect('SQ2',$row['sqid'],'',true),' value="'.$row['sqid'].'">'.$row['sq'].'</option>';
								}
								else
								{
									echo '<option',fillSelect('SQ2',$row['sqid']),' value="'.$row['sqid'].'">'.$row['sq'].'</option>';
								}
								$i++;
							}
						?>
					</select>
					<input type="text" name="a2" id="a2"<?php fillInput('SQA2'); ?>/>
					<input type="text" style="display:none;"/>
					<input type="password" style="display:none;"/>
					<label for="Email">Email address *</label><input type="text" name="Email" id="Email"<?php fillInput('Email'); ?>/>
					<label for="Password">Password *</label><input type="password" name="Password" id="Password"/>
					<label for="Password2">Confirm password *</label><input type="password" name="Password2" id="Password2"/>
					<input type="submit" class="radius button" name="submit" value="Register"/>
				</form>
			</div>		
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>