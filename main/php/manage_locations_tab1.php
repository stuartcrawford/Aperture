<?php
	
	//View_Manage_Locations
	//@Author: Stuart Crawford
	//@Date: 26.04.2013
	
	dbconnect();
	
	//Admin View
	if ($_SESSION['User']['AccessLvl'] == 1) {
	
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Location WHERE Active = 1");
		$stmt->execute();
		
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		
		if ($rslt)
		{
		
		//Start Table, Start Head		
		$html_table = '<table id="Location" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
	
		//New Row	
    	$html_table .= '<tr>' . "\n";
	
		//Header	
		$html_table .= '<th data-class="expand" data-type="numeric" data-sort-initial="true">ID</th>';
		$html_table .= '<th>Add1</th>';
		$html_table .= '<th data-hide="phone">Add2</th>';
		$html_table .= '<th data-hide="phone">Add3</th>';
		$html_table .= '<th data-hide="phone">Postcode</th>';	
		$html_table .= '<th data-sort-ignore="true">Select</th>';

		//End Head	
    	$html_table .= '</tr>' . "\n" . '</thead>';
	
		//Start Body		
		$html_table .= '<tbody>';
		
		//Fetch Data
		foreach( $rslt as $row ) {	

			$html_table .= '<tr>';
			$html_table .= '<td class="LocationID"  	data-class="expand" data-hide="phone">'.$row['LocationID'].'</td>';
			$html_table .= '<td class="Add1">'.$row['Add1'].'</td>';
			$html_table .= '<td class="Add2" 			data-hide="phone">'.$row['Add2'].'</td>';
			$html_table .= '<td class="Add3" 			data-hide="phone">'.$row['Add3'].'</td>';	
			$html_table .= '<td class="Postcode" 		data-hide="phone">'.$row['Postcode'].'</td>';		
				
			$html_table .= '<td data-sort-ignore="true"><div id="select_record'.$row['LocationID'].'" class="select_record"><a href="javascript:selectrecord(\''.$row['LocationID'].'\');">Select</a></div></td>';
			$html_table .= '</tr>';	
		
		}
	
		//End Body, End Table
		$html_table .= '</tbody>' . '</table>';
	
		//Display Table
		echo $html_table;
		}
		else {
			echo '<h3>No Results</h3>';
			echo '<p>There are no active Locations at this time</p>';
		}	
	
	}
?>