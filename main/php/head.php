<?php

	//@Title: HTML Head
	//@Author: AM + JC
	
?>
<meta charset="utf-8" />
	
<!-- MOBILE VIEWPORT  -->
<meta name="viewport" content="width=device-width" />
	
<!-- PAGE TAB	-->
<link rel="shortcut icon" href="<?php fetchdir($icon); ?>favicon.ico">
<title>Aperture Car Hire</title>
	
<!-- FRAMEWORK CASCADE STYLING SHEETS	-->
<link rel="stylesheet" href="<?php fetchdir($cssframework); ?>normalize.css">
<link rel="stylesheet" href="<?php fetchdir($cssframework); ?>foundation.css">

<!-- PAGE CASCADE STYLING SHEET	-->
<link rel="stylesheet" href="<?php echo fetchdir($css).$currentName; ?>.css">

<!-- FOUNDATION.ZURB FRAMEWORK	-->
<script src="<?php fetchdir($jsframework); ?>vendor/custom.modernizr.js"></script>
	
<!-- JQUERY LIBRARY	-->
<script src="<?php fetchdir($jsframework); ?>vendor/jquery.js"></script>
	
<!-- ADDITIONAL JAVASCRIPT	-->
<script src="<?php fetchdir($js); ?>formvalidator.js"></script>

<script>
	// jQuery Feedback
	$(function(){
		$(".trigger").click(function(){
			var leftValue = $(".feedbackpanel").css("left");
			if (leftValue == "-350px")
			{
				$(".feedbackpanel").animate({
					left: "0px"
				}, function() {
					$('#submitfeedback .fb').html(''); // @Added by JC
				});
			}
			else
			{
				$(".feedbackpanel").animate({
					left: "-350px"
				}, function() {
					$('#submitfeedback .fb').html(''); // @Added by JC
				});
			}
		});
	});
	
	$(function(){
		var formname = '#submitfeedback';
		var submitname = 'submitfeedback';
		$(formname).submit(function (e)
		{
			e.preventDefault();
			var data = submitname+'=&ajax=&'+$(this).serialize();
			$.ajax({
				url: "<?php fetchdir($php); ?>submit_feedback.php",
				data: data,
				type: "POST",
				async: false,
				success: function(response) {
					if (response == 'Your feedback was sent successfully') // Response of success message
					{
						$(formname)[0].reset();
						$(formname+' .fb').html('<p style="color:green">'+response+'</p>');
					}
					else if (response == 'Redirect')
					{
						window.location = '<?php fetchdir($gpages); ?>authentication_error.php';
					}
					else
					{
						$(formname+' .fb').html('<p style="color:red">'+response+'</p>');
					}
				}
			});
		});
	});
	
	// Ajax Login
	$(function(){
		$('#dropsubmit').submit(function (e)
		{
			e.preventDefault();
			var email = $('#email').val();
			var password = $('#password').val();
			$.ajax({
				url: "<?php echo fetchdir($php) ?>login.php",
				data: "CSRFToken=<?php echo $_SESSION['CSRFToken']; ?>&dropsubmit=&ajax=&email="+email+"&password="+password,
				type: "POST",
				async: false,
				success: function(response) {
					if (response == "Error")
					{
						$('#password').val('');
						$('#errors').show();
					}
					else if (response == "Success")
					{
						window.location = '<?php fetchdir($apages); ?>login_success.php';
					}
					else if (response == "Activation")
					{
						window.location = '<?php fetchdir($gpages); ?>account_activation.php';
					}
					else if (response == "MaxedOut")
					{
						window.location = '<?php fetchdir($gpages); ?>maxed_out_login.php';
					}
					else if (response == 'Redirect')
					{
						window.location = '<?php fetchdir($gpages); ?>authentication_error.php';
					}
				}
			});
		});
	});
	
	// Keep the session alive by pinging the server at a set interval
	$(function() {
			setTimeout("callserver()",<?php echo $pingInterval; ?>);
	});
	function callserver() {
		var remoteURL = '<?php fetchdir($php); ?>ping.php';
		$.get(remoteURL, function(data) {
			setTimeout("callserver()",<?php echo $pingInterval; ?>);
		});
	}
</script>
<style>
	/* Nav bar */
	.a {
		display:inline;
	}
	.top-bar {
		display:inline;
		position:fixed;
		top:0;
		width:100%;
		z-index:9999999999999999999999999999999;
	}
	/* Media queries for setting background colour in dropdowns @Added by JC */
	@media only screen and (max-width: 940px) {
		.hacked {
			background-color:#333333;
		}
		#error1 {
			display:none;
		}
	}
	@media only screen and (min-width: 940px) {
		.hacked {
			background-color:#1E1E1E;
		}
		#error2 {
			display:none;
		}
		.mobileagent {
			display:none;
		}
	}
	
	/* Social Network */
	.social{
		padding-top:2.5px;
		font-size:0;
	}
	
	/* Feedback Panel */
	@media only screen and (max-width: 1040px) {
		.panel_container {
			display:none;
		}
	}
	.panel_container {
		position:fixed;
		overflow:hidden;
		height:relative;
		width:400px;
	}
	.feedbackpanel {
		position:fixed;
		z-index:2000000000;
		top:4em;
		left:0;
		margin:0;
		padding:0;
		height:inherit;
		width:inherit;
		left:-350px;
	}
	.inner {
		padding-left:10px;
		padding-right:10px;
		background-color:#000000;
		opacity:1;
		float:left;
		height:inherit;
		z-index:2000000001;
		width:350px;
	}
	.inner h1 {
		margin: 0 0 0 0;
		padding: 0.8em;
		z-index:2000000002;
		color: #FFFFFF;
	}
	.inner p, .inner h5 {
		margin: 0 0 0 0;
		padding: 0.8em;
		z-index:2000000003;
		color:#FFFFFF;
	}
	.trigger {
		background-color:#000000;
		.rotate {rotate(-90deg)};
		float:left;
		height:100px;
		width:25px;
	}
	.rotate {
		position:relative;
		top:7px;
		-webkit-transform: rotate(90deg);
		-moz-transform: rotate(90deg);
	}
	.rotate p {
		color:#FFFFFF;
	}
	
	/* Javascript disabled notice */
	body.js-enabled #noscript {
		display: none;
	}
	
	/* Clearing lightbox fix */
	.clearing-blackout {
		background: rgba(0, 0, 0, 0.8);
		z-index: 99999999999999999999999999999999;
	}
</style>