<?php

	//add_new_accounts
	//@Author: UE + JC
	//@Date: 30.04.2013
	
	//Admin & Staff View
	if ($_SESSION['User']['AccessLvl'] == 1) {
?>
<script>
	// Ajax registration
	$(function(){
		var formname = '#add_admin';
		var submitname = 'admin';
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
				<form name="add_admin" id="add_admin" method="post">
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
					<input type="text" style="display:none;"/>
					<input type="password" style="display:none;"/>
					<label for="RegEmail">Email address *</label><input type="text" name="RegEmail" id="RegEmail"<?php fillInput('Email'); ?>/>
					<label for="RegPassword">Password *</label><input type="password" name="RegPassword" id="RegPassword"/>
					<label for="RegPassword2">Confirm password *</label><input type="password" name="RegPassword2" id="RegPassword2"/>
					<div class="fb"></div>
					<input type="submit" class="radius button small" name="admin" id="admin" value="Add"/>
				</form>
			</div>		
			</div>
<?php } ?>