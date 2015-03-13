<?php
		
		//include formValidator class		
		include ('php/formvalidator.php');
		//create a new formValidator instance and pass the id (also the name) of the html form to the constructor
		$form[0] = new formValidator('user_registration','submit');
		$form[1] = new formValidator('auser_registration','asubmit');

		//Available functions and example usage (you must use not empty validation for all mandatory fields, all validation of the same field should be in sequence):
		$form -> validateNumber('name','Number must be a number');
		$form -> validateDecimal('name','0','Number must be a whole number');
		$form -> validateRange('name','0',null,'Number must be a positive number',null); //Php cannot distinguish the difference between null and 0 as an input, so if 0 is used it must be quoted. For simplicity quote all numbers. Note that null should be unquoted or '' should be used
		$form -> validatePounds('name','Price is invalid');
		$form -> validateEmail('name','Email is invalid');
		$form -> minLength('name','3','Username must be at least 3 characters long');
		//$form -> maxLength('name','100','Username is too long long');
		//$form -> limitSpaces('name','1','Name can only contain 1 space');
		//$form -> notEmpty('name','Name has not been entered');
		//$form -> notEmptyGroup('name','You must enter a username or nickname');
		//$form -> excludesSpecial('name','Username must include only letters, numbers or underscores');
		//$form -> fieldMatch('pass','pass2','Passwords do not match'); //Must be last in the que and have not empty validation of field 2 directly before it (with null errormessage) if the matching fields are mandatory to allow highlighting
		//$form -> notMatch('sq1','sq2','Security questions cannot be the same'); //Must be last in the que and have not empty validation of field 2 directly before it (with null errormessage) if the matching fields are mandatory to allow highlighting
		//$form -> validateTelephone('telephone','Telephone number must have exactly 11 digits and must contain only numbers and spaces'); //Must be last in the que and have not empty validation of field 2 directly before it (with null errormessage) if the matching fields are mandatory to allow highlighting
		//$form -> selected('title','You must select a title');
		//$form -> checked('agree','You must agree to the Terms & Conditions');
		//$form -> callFunction('createUser()',true); //Must be the very last field check

		//add fields to check
		$form[0] -> notEmpty('user','You forgot to enter a username');
		$form[0] -> minLength('user','3','Username must have at least 3 characters');
		$form[0] -> notEmpty('name','You forgot to enter your name');
		$form[0] -> notEmpty('surname','You forgot to enter your surname');
		$form[0] -> notEmpty('email','You forgot to enter your email');
		$form[0] -> validateEmail('email','You must enter a valid email');
		$form[0] -> notEmpty('pass','You forgot to enter a password');
		$form[0] -> minLength('pass','6','Your password must have at least 6 characters');
		$form[0] -> notEmpty('pass2',null);
		$form[0] -> fieldMatch('pass','pass2','Passwords don\'t match');

		$form[1] -> notEmpty('user','You forgot to enter a username');
		$form[1] -> minLength('user','3','Username must have at least 3 characters');
		$form[1] -> notEmpty('name','You forgot to enter your name');
		$form[1] -> notEmpty('surname','You forgot to enter your surname');
		$form[1] -> notEmpty('email','You forgot to enter your email');
		$form[1] -> validateEmail('email','You must enter a valid email');
		$form[1] -> notEmpty('pass','You forgot to enter a password');
		$form[1] -> minLength('pass','6','Your password must have at least 6 characters');
		$form[1] -> notEmpty('pass2',null);
		$form[1] -> fieldMatch('pass','pass2','Passwords don\'t match');
		$form[1] -> callFunction('createUser()',true);
		
		//set background color of the input if there is a validation error
		//$form[0] -> setColor('#FFFFBC');
		//$form[1] -> setColor('#FFFFBC');
		
		//get javascript code
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

		//Redirect in this part to avoid reposting data
		//check if the fields validate, this is usefull if the browser does not support javascript or it's disabled		
		if ( $form[0] -> valid() === true && isset($_POST['submit']))
		{
			//create your user here
			echo '<p>User created!</p>';
			//header("location: ".$_SERVER['PHP_SELF']);
		}
		else if ( $form[0] -> valid() && isset($_POST['submit']))
		{
			$error_message = '<p style="color:red; font-weight:bold;">'.$form[0] -> valid().'</p>';
			//if there is an error $form -> valid() returns the error message;
		}
		//check if the fields validate, this is usefull if the browser does not support javascript or it's disabled		
		if ( $form[1] -> valid() === true && isset($_POST['asubmit']))
		{
			//create your user here
			echo '<p>User created!</p>';
		}
		else if ( $form[1] -> valid() && isset($_POST['asubmit']))
		{
			$error_message = '<p style="color:red; font-weight:bold;">'.$form[1] -> valid().'</p>';
			//if there is an error $form -> valid() returns the error message;
		}
?>
<html>
<head>
<!-- some css styles to make our form pretty -->
<style>
.row
{
	clear:left;
	display:block;
	margin:5px 0pt 0pt;
	padding:1px 3px;
	width:400px;
}


a:hover
{
	color:#fff;
}

#user_registration, #auser_registration
{
	clear:both;
	display:block;
}


#user_registration label, #auser_registration label
{
	display: block;  /* block float the labels to left column, set a width */
	float: left; 
	width: 70px;
	margin: 0px 10px 0px 5px; 
	text-align: right; 
	line-height:1em;
	font-weight:bold;
}
#user_registration input, #auser_registration input
{
	width:250px;
}
</style>
<script src="js/formvalidator.js" type="text/javascript"></script>
<script src="js/framework/vendor/jquery.js" type="text/javascript"></script>
<script>
	function createUser()
	{
		alert("User Created!");
	}
</script>
</head>
<body>
<?php 
//if the validation falls back to php, then print the validation error
if (isset($error_message)) echo $error_message;
?>
	<form method="post" action="" id="user_registration" name="user_registration">
		<p>	
		<label for="user">Username</label><input type="text" name="user" id="user" value="<?php if (isset($_POST['user']) && isset($_POST['submit'])) echo $_POST['user'];?>"/>
		</p>
		<p>	
		<label for="name">Name</label><input type="text" name="name" id="name" value="<?php if (isset($_POST['name']) && isset($_POST['submit'])) echo $_POST['name'];?>"/>
		</p>
		<p>	
		<label for="surname">Surname</label><input type="text" name="surname" id="surname" value="<?php if (isset($_POST['surname']) && isset($_POST['submit'])) echo $_POST['surname'];?>"/>
		</p>
		<p>	
		<label for="email">E-mail</label><input type="text" name="email" id="email" value="<?php if (isset($_POST['email']) && isset($_POST['submit'])) echo $_POST['email'];?>"/>
		</p>
		<p>	
		<!--To change what the password is remembered for in the message "Remember password for x on y" when submitting a password, add a hidden field above it with the value being what you want x to be-->
		<label for="pass">Password</label><input type="password" name="pass" id="pass"/>
		</p>
		<p>	
		<label for="pass2">Confirm Password</label><input type="password" name="pass2" id="pass2"/>
		</p>
		<p>	
		<input type="submit" name="submit" id="submit" value="Register">
		</p>
	</form>
	<form method="post" action="" id="auser_registration" name="auser_registration">
		<p>	
		<label for="user">Username</label><input type="text" name="user" id="user" value="<?php if (isset($_POST['user']) && isset($_POST['asubmit'])) echo $_POST['user'];?>"/>
		</p>
		<p>	
		<label for="name">Name</label><input type="text" name="name" id="name" value="<?php if (isset($_POST['name']) && isset($_POST['asubmit'])) echo $_POST['name'];?>"/>
		</p>
		<p>	
		<label for="surname">Surname</label><input type="text" name="surname" id="surname" value="<?php if (isset($_POST['surname']) && isset($_POST['asubmit'])) echo $_POST['surname'];?>"/>
		</p>
		<p>	
		<label for="email">E-mail</label><input type="text" name="email" id="email" value="<?php if (isset($_POST['email']) && isset($_POST['asubmit'])) echo $_POST['email'];?>"/>
		</p>
		<p>	
		<!--To change what the password is remembered for in the message "Remember password for x on y" when submitting a password, add a hidden field above it with the value being what you want x to be-->
		<label for="pass">Password</label><input type="password" name="pass" id="pass"/>
		</p>
		<p>	
		<label for="pass2">Confirm Password</label><input type="password" name="pass2" id="pass2"/>
		</p>
		<p>	
		<input type="submit" name="asubmit" id="submit" value="Register">
		</p>
	</form>
<p>Original code from codeassembly.com</p>
<script>
<?php echo $validation_js_code;?>
</script>
</body>
</html>