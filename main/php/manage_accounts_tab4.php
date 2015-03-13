<?php

	//add_new_accounts
	//@Author: UE + JC
	//@Date: 30.04.2013
	/**
	// Validation
	$form[0] = new formValidator('add_staff','staff');
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
	}**/
	
	//Admin & Staff View
	if ($_SESSION['User']['AccessLvl'] == 1) {
?>
<script>
	// Ajax registration
	$(function(){
		var formname = '#add_staff';
		var submitname = 'staff';
		$('[name="'+submitname+'"]').click(function (e)
		{
			e.preventDefault();
			var data = submitname+'=&ajax=&'+$(formname).serialize();
			$.ajax({
				url: "<?php fetchdir($php); ?>manage_accounts_insert.php",
				data: data,
				type: "POST",
				async: false,
				success: function(response) {
					if (response == 'Success')
					{
						window.location = '<?php fetchdir($apages); ?>manage_accounts.php';
					}
					else if (response == 'Redirect')
					{
						window.location = '<?php fetchdir($gpages); ?>authentication_error.php';
					}
					else
					{
						$(formname+' [name="RegPassword"]').val('');
						$(formname+' [name="RegPassword2"]').val('');
						$(formname+' .fb').html('<p style="color:red">'+response+'</p>');
					}
				}
			});
		});
		$('h5.title').click(function()
		{
			$('.fb').html('');
		});
	});
</script>
<div class="row">
  			<div class="large-6 column">
				<form name="add_staff" id="add_staff" method="post">
					<label for="title">Title *</label>
					<select name="title" id="title" class="small">
						<option<?php fillSelect('Title','','',true); ?> DISABLED value="">Select title</option>
						<option <?php fillSelect('Title','Mr'); ?> value="Mr">Mr</option>
						<option <?php fillSelect('Title','Mrs'); ?> value="Mrs">Mrs</option>
						<option <?php fillSelect('Title','Miss'); ?> value="Miss">Miss</option>
						<option <?php fillSelect('Title','Ms'); ?> value="Ms">Ms</option>
						<option <?php fillSelect('Title','Dr'); ?> value="Dr">Dr</option>
						<option <?php fillSelect('Title','Prof'); ?> value="Prof">Prof</option>
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
					<label for="SQ1">Security question 1 *</label>
					<select name="SQ1" id="SQ1">
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
					<input type="text" name="SQA1" id="SQA1"<?php fillInput('SQA1'); ?>/>
					<label for="SQ2">Security question 2 *</label>
					<select name="SQ2" id="SQ2">
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
					<input type="text" name="SQA2" id="SQA2"<?php fillInput('SQA2','SQA2'); ?>/>
					<input type="text" style="display:none;"/>
					<input type="password" style="display:none;"/>
					<label for="RegEmail">Email address *</label><input type="text" name="RegEmail" id="RegEmail"<?php fillInput('Email'); ?>/>
					<label for="RegPassword">Password *</label><input type="password" name="RegPassword" id="RegPassword"/>
					<label for="RegPassword2">Confirm password *</label><input type="password" name="RegPassword2" id="RegPassword2"/>
					<div class="fb"></div>
					<input type="submit" class="radius button small" name="staff" id="staff" value="Add"/>
				</form>
			</div>		
			</div>
<?php } ?>