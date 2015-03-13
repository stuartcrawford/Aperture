<?php
	
	//View_Manage_Optional_Extras
	//@Author: Stuart Crawford
	dbconnect();
	
	//Admin View
	if ($_SESSION['User']['AccessLvl'] == 1) {
	
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Optional_Extra");
		$stmt->execute();
	
		//Start Table, Start Head
		
		/* @Edit: Added <thead> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table = '<table id="OptionalExtra" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
		
		//New Row
    	$html_table .= '<tr>' . "\n";
	
		//Header
		$html_table .= '<th data-class="expand">AssetNo</th>';
		$html_table .= '<th data-hide="phone">SerialNumber</th>';
		$html_table .= '<th data-sort-ignore="true">Edit</th>';
	
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
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row ) {	
	
			//...by Column Name	
			$html_table .= '<tr>';
			$html_table .='<td class="RegistrationNo" data-class="expand">'.$row['AssetNo'].'</td>';
			$html_table .= '<td class="Status" data-class="expand">'.$row['SerialNumber'].'</td>';
			$html_table .= '<td data-sort-ignore="true"><div id="a_id'.$row['AssetNo'].'" class="select_optional_extras"><a href="javascript:selectlocation(\''.$row['AssetNo'].'\');">Edit</a></div></td>';
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
	//Staff View
		
	else if ($_SESSION['User']['AccessLvl'] == 2){
		
	}
?>