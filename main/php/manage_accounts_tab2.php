<?php
	
	//View_Account_Details
	//@Author: Alex McCormick
	//@Amendments by: Unyime Ekerette
	dbconnect();
	
	//Admin View
	if ($_SESSION['User']['AccessLvl'] == 1) {
	
		//Select Query
		$stmt = $conn->prepare("SELECT UserID, Status, CreationDate, AccessLvl, Title, Email, DOB, FirstName, 
        LastName, Add1, Add2, Add3, Postcode, Telephone, Mobile, LicenseNo, NextLogin, LoginAttempts, 
        SQ1, SQ2, SQA1, SQA2 FROM User WHERE Status = 0");
		$stmt->execute();
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		
		if ($rslt)
		{
			
		//Start Table, Start Head
		
		/* @Edit: Added <thead> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table = '<table id="Account-inactive" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
		
		//New Row
    	$html_table .= '<tr>' . "\n";
	
		//Header
		$html_table .= '<th data-class="expand" data-sort-initial="true" data-type="numeric">ID</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric">CreationDate</th>';
		$html_table .= '<th data-hide="phone">AccessLvl</th>';
		$html_table .= '<th data-hide="phone">Title</th>';
		$html_table .= '<th data-hide="phone,tablet">Email</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">DOB</th>';
		$html_table .= '<th data-hide="phone">FirstName</th>';
		$html_table .= '<th data-hide="phone">LastName</th>';
		$html_table .= '<th data-hide="phone,tablet">Add1</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">Add2</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">Add3</th>';
		$html_table .= '<th data-hide="phone,tablet">Postcode</th>';
		$html_table .= '<th data-hide="phone,tablet">Telephone</th>';
		$html_table .= '<th data-hide="phone,tablet">Mobile</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">LicenseNo</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">NextLogin</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">LoginAttempts</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">SQ1</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">SQ2</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">SQA1</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">SQA2</th>';	
		$html_table .= '<th data-sort-ignore="true">Select</th>';
	
		//End Head
		
		/* @Edit: Added </thead> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
    	$html_table .= '</tr>' . "\n" . '</thead>';
	
		//Start Body
		
		/* @Edit: Added <tbody> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table .= '<tbody>';
		
		//Fetch Data
		foreach( $rslt as $row ) {
			
			if($row['AccessLvl'] == 1) { $AccessLevel = 'Admin'; } else if($row['AccessLvl'] == 2) { $AccessLevel = 'Staff'; } else if($row['AccessLvl'] == 3) { $AccessLevel = 'Customer'; }	
	
			//...by Column Name	
			$html_table .= '<tr>';
			$html_table .='<td class="UserID" data-class="expand" data-sort-initial="true" data-type="numeric">'.$row['UserID'].'</td>';
			$html_table .= '<td class="CreationDate" data-hide="phone,tablet">'.$row['CreationDate'].'</td>';
			$html_table .= '<td class="AccessLvl" data-hide="phone">'.$AccessLevel.'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone">'.$row['Title'].'</td>';
			$html_table .= '<td class="Email" data-hide="phone,tablet">'.$row['Email'].'</td>';
			$html_table .= '<td class="DOB" data-hide="phone,tablet">'.$row['DOB'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone">'.$row['FirstName'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone">'.$row['LastName'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone,tablet">'.$row['Add1'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['Add2'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['Add3'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone,tablet">'.$row['Postcode'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone,tablet">'.$row['Telephone'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone,tablet">'.$row['Mobile'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['LicenseNo'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['NextLogin'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['LoginAttempts'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['SQ1'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['SQ2'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['SQA1'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['SQA2'].'</td>';
			$html_table .= '<td data-sort-ignore="true"><div id="a_id'.$row['UserID'].'" class="select_account"><a href="javascript:selectaccount(\''.$row['UserID'].'\');">Select</a></div></td>';
			$html_table .= '</tr>';

		
		}
	
		//End Body, End Table
		
		/* @Edit: Added </tbody> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table .= '</tbody>' . '</table>';
	
		//Display Table
		echo $html_table;
		
		}
		else {
			echo '<h3>No Results</h3>';
			echo '<p>There are no inactive Accounts at this time</p>';
		}
	
	}

	// Staff View
	else if ($_SESSION['User']['AccessLvl'] == 2) {
				//Select Query
		$stmt = $conn->prepare("SELECT UserID, Status, CreationDate, AccessLvl, Title, Email, DOB, FirstName, 
        LastName, Add1, Add2, Add3, Postcode, Telephone, Mobile, LicenseNo, NextLogin, LoginAttempts, 
        SQ1, SQ2, SQA1, SQA2 FROM User WHERE Status = 0 AND AccessLvl = 3");
		$stmt->execute();
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		
		if ($rslt)
		{
			
		//Start Table, Start Head
		
		/* @Edit: Added <thead> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table = '<table id="Account-inactive" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
		
		//New Row
    	$html_table .= '<tr>' . "\n";
	
		//Header
		$html_table .= '<th data-class="expand" data-sort-initial="true" data-type="numeric">UserID</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric">CreationDate</th>';
		$html_table .= '<th data-hide="phone">Title</th>';
		$html_table .= '<th data-hide="phone,tablet">Email</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">DOB</th>';
		$html_table .= '<th data-hide="phone">FirstName</th>';
		$html_table .= '<th data-hide="phone">LastName</th>';
		$html_table .= '<th data-hide="phone,tablet">Add1</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">Add2</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">Add3</th>';
		$html_table .= '<th data-hide="phone,tablet">Postcode</th>';
		$html_table .= '<th data-hide="phone,tablet">Telephone</th>';
		$html_table .= '<th data-hide="phone,tablet">Mobile</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">LicenseNo</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">NextLogin</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">LoginAttempts</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">SQ1</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">SQ2</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">SQA1</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">SQA2</th>';	
		$html_table .= '<th data-sort-ignore="true">Select</th>';
	
		//End Head
		
		/* @Edit: Added </thead> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
    	$html_table .= '</tr>' . "\n" . '</thead>';
	
		//Start Body
		
		/* @Edit: Added <tbody> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table .= '<tbody>';
		
		//Fetch Data
		foreach( $rslt as $row ) {	
	
			//...by Column Name	
			$html_table .= '<tr>';
			$html_table .='<td class="UserID" data-class="expand" data-sort-initial="true" data-type="numeric">'.$row['UserID'].'</td>';
			$html_table .= '<td class="CreationDate" data-hide="phone,tablet">'.$row['CreationDate'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone">'.$row['Title'].'</td>';
			$html_table .= '<td class="Email" data-hide="phone,tablet">'.$row['Email'].'</td>';
			$html_table .= '<td class="DOB" data-hide="phone,tablet">'.$row['DOB'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone">'.$row['FirstName'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone">'.$row['LastName'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone,tablet">'.$row['Add1'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['Add2'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['Add3'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone,tablet">'.$row['Postcode'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone,tablet">'.$row['Telephone'].'</td>';
			$html_table .= '<td class="RegistrationNo" data-hide="phone,tablet">'.$row['Mobile'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['LicenseNo'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['NextLogin'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['LoginAttempts'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['SQ1'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['SQ2'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['SQA1'].'</td>';
			$html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['SQA2'].'</td>';
			$html_table .= '<td data-sort-ignore="true"><div id="a_id'.$row['UserID'].'" class="select_account"><a href="javascript:selectaccount(\''.$row['UserID'].'\');">Select</a></div></td>';
			$html_table .= '</tr>';

		
		}
	
		//End Body, End Table
		
		/* @Edit: Added </tbody> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table .= '</tbody>' . '</table>';
	
		//Display Table
		echo $html_table;
		
		}
		else {
			echo '<h3>No Results</h3>';
			echo '<p>There are no inactive Customers at this time</p>';
		}
	}
?>