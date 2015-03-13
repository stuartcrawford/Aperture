<?php

	//Manage_Accounts_Edit_Account
	//@Author: Alex McCormick
	//@Date: 14.04.2013
	
	include('global.php');
	dbconnect();

?>
<script>
	// Ajax registration
	$(function(){
		var formname = '#account_update_or_delete';
		$('[name="update_accounts"]').click(function (e)
		{
			e.preventDefault();
			var data = 'update_accounts=&ajax=&'+$(formname).serialize();
			$.ajax({
				url: "<?php fetchdir($php); ?>manage_accounts_account_update_or_delete.php",
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
						$(formname+' .fb').html('<p style="color:red">'+response+'</p>');
					}
				}
			});
		});
		$('[name="delete_account"]').click(function (e)
		{
			e.preventDefault();
			var data = 'delete_account=&ajax=&'+$(formname).serialize();
			$.ajax({
				url: "<?php fetchdir($php); ?>manage_accounts_account_update_or_delete.php",
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
<?php		
	$account = $_POST['a_id'];
	
	if ($_POST['action'] == 'editaccount')
	{
		//Select Query
		$stmt = $conn->prepare("SELECT UserID, Status, CreationDate, AccessLvl, Title, Email, DOB, FirstName, 
        LastName, Add1, Add2, Add3, Postcode, Telephone, Mobile, LicenseNo, NextLogin, LoginAttempts, 
        SQ1, SQ2, SQA1, SQA2 FROM User WHERE UserID = :id");
		$stmt->execute();
		$stmt->execute(array('id' => $account));
								
		//Fetch Data
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
?>		
							
							
							<form id="account_update_or_delete" method="post" action="<?php fetchdir($php); ?>manage_accounts_account_update_or_delete.php">
								<input type="hidden" name="Account" value="<?php echo $account; ?>" />
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">CreationDate</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="CreationDate" type="text" value="<?php echo $row['CreationDate']; ?>" placeholder="" readonly />
									</div>
								</div>
								<?php if ($_SESSION['User']['AccessLvl'] == 1) { ?>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">AccessLvl</label>
									</div>
									<div class="large-10 columns">
										<?php if($row['AccessLvl'] == 1) { $AccessLevel = 'Admin'; } else if($row['AccessLvl'] == 2) { $AccessLevel = 'Staff'; } else if($row['AccessLvl'] == 3) { $AccessLevel = 'Customer'; } ?>
										<input name="AccessLvl" id="" type="text" value="<?php echo $AccessLevel; ?>" placeholder="" readonly/>
									</div>
								</div>
								<?php } ?>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Email</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="Email" type="text" value="<?php echo $row['Email']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Title</label>
									</div>
									<div class="large-10 columns">
										<select name="Title" id="title" class="small">
											<option<?php fillSelect('Title','','Title',true); ?> DISABLED value="">Select title</option>
											<option<?php fillSelect('Title','Mr','Title'); ?> value="Mr">Mr</option>
											<option<?php fillSelect('Title','Mrs','Title'); ?> value="Mrs">Mrs</option>
											<option<?php fillSelect('Title','Miss','Title'); ?> value="Miss">Miss</option>
											<option<?php fillSelect('Title','Ms','Title'); ?> value="Ms">Ms</option>
											<option<?php fillSelect('Title','Dr','Title'); ?> value="Dr">Dr</option>
											<option<?php fillSelect('Title','Prof','Title'); ?> value="Prof">Prof</option>
										</select>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">First Name</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="FirstName" type="text" value="<?php echo $row['FirstName']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Last Name</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="LastName" type="text" value="<?php echo $row['LastName']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Add1</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="Add1" type="text" value="<?php echo $row['Add1']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Add2</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="Add2" type="text" value="<?php echo $row['Add2']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Add3</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="Add3" type="text" value="<?php echo $row['Add3']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">PostCode</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="Postcode" type="text" value="<?php echo $row['Postcode']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Telephone</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="Telephone" type="text" value="<?php echo $row['Telephone']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Mobile</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="Mobile" type="text" value="<?php echo $row['Mobile']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">LicenseNo</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="LicenseNo" type="text" value="<?php echo $row['LicenseNo']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">DOB</label>
									</div>
									<div class="large-10 columns">
										<input id="" name="DOB" type="text" value="<?php if ($row['DOB'] != '0000-00-00') echo $row['DOB']; ?>" placeholder="" />
									</div>
								</div>
								<div style="<?php if($row['AccessLvl'] == 1) { echo 'display:none'; } ?>">
									<div class="row collapse">
										<div class="large-2 columns">
											<label class="inline">SQ1</label>
										</div>
										<div class="large-10 columns">
											<select id="" name="SQ1">
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
										</div>
									</div>
									<div class="row collapse">
										<div class="large-2 columns">
											<label class="inline">SQA1</label>
										</div>
										<div class="large-10 columns">
											<input id="" name="SQA1" type="text" value="<?php echo $row['SQA1']; ?>" placeholder="" />
										</div>
									</div>
									<div class="row collapse">
										<div class="large-2 columns">
											<label class="inline">SQ2</label>
										</div>
										<div class="large-10 columns">
											<select id="" name="SQ2">
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
										</div>
									</div>
									<div class="row collapse">
										<div class="large-2 columns">
											<label class="inline">SQA2</label>
										</div>
										<div class="large-10 columns">
											<input id="" name="SQA2" type="text" value="<?php echo $row['SQA2']; ?>" placeholder="" />
										</div>
									</div> 
								</div>
								<input type="text" style="display:none;"/>
								<input type="password" style="display:none;"/>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">New password</label>
									</div>
									<div class="large-10 columns">
										<input type="password" name="Newpassword" id="Newpassword"/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Confirm new password</label>
									</div>
									<div class="large-10 columns">
										<input type="password" name="Newpassword2" id="Newpassword2"/>
									</div>
								</div>
								<div class="fb"></div>
								<button class="radius button" name="update_accounts" type="submit">Update</button>
								<?php 
									//Check Enabled or Disabled Status
									$stmt = $conn->prepare("SELECT Status FROM User WHERE UserID = :id");
									$stmt->execute(array('id' => $account));
		
									foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
									{ 
										if ($row['Status'] == 0) { ?><button class="radius button" name="enable_account" type="submit" style="background-color:#589E1F; border-color:#589E1F;">Enable</button><?php } 
	 									else if ($row['Status'] == 1) { if ($account != $_SESSION['User']['ID']) { ?><button class="radius button" name="delete_account" type="submit" style="background-color:#CC1417; border-color:#CC1417;">Disable</button><?php } }
	 								} 
	 							?>
							</form>
<?php	}	}	 ?>				