<?php

	include("global.php");
	dbconnect();
	
	//Asset Group ID 
	$id = $_POST['AssetGroup'];

	if(isset($_POST['update_vehicle_group']))
	{
		$input = 'file2'; // Name of the file upload input
		// Check if an image has been specified to upload
		if (filesize($_FILES[$input]['tmp_name']) != 0)
		{
			// Check if an image has been specified to upload
			if (filesize($_FILES[$input]['tmp_name']) == 0)
			{
				$_SESSION['Form']['Error'] = 'You must select an image to upload';
				Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
				exit;
			}
			$filename = $_FILES[$input]['name']; // Get the name of the file (including file extension)
			$ext = '.'.pathinfo($filename, PATHINFO_EXTENSION); // Get the extension from the filename
			// Check if the filetype is allowed
			if (!in_array(strtolower($ext),$allowed_filetypes))
			{
				$_SESSION['Form']['Error'] = 'Image must be a jpg or png file';
				Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
				exit;
			}
			// Check the filesize
			else if (filesize($_FILES[$input]['tmp_name']) > $max_filesize)
			{
				$_SESSION['Form']['Error'] = 'Image cannot be greater than 0.5mb';
				Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
				exit;
			}
			// Check if we can upload to the specified path
			else if (!is_writable(realpath(dirname(__FILE__).'/'.$upload_path)))
			{
				$_SESSION['Form']['Error'] = 'Image could not write to the image directory';
				Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
				exit;
			}
			// Check the length of the filename
			else if (strlen($filename) > $max_filename_length)
			{
				$_SESSION['Form']['Error'] = 'Image cannot have a filename greater than 100 characters';
				Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
				exit;
			}
			// Get filename of old image
			$stmt = $conn->prepare("SELECT ImageFile FROM Asset_Group WHERE AssetGroupNo=:id");
			$stmt->bindParam(':id',$id);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row)
			{
				$oldimage = $upload_path.$row['ImageFile'];
			}
			// Remove the old image
			if (file_exists($oldimage))
			{
				unlink($oldimage);
			}
			// Check if there is already a file with the same name and append a number if there is
			else if (file_exists($upload_path.$filename))
			{
				$noext = str_replace($ext,'',$filename);
				
				$split = explode('_',$noext);
				$count = count($split);
				if ($count >= 2)
				{
					if (preg_match('/^\d+$/', $split[$count - 1]))
					{
						$filename = str_replace($split[$count - 1],'',$noext);
						$continue = true;
						while ($continue == true)
						{
							$split[$count - 1]++;
							if (!file_exists($upload_path.$filename.$split[$count - 1].$ext))
							{
								$continue = false;
							}
						}
						$filename .= $split[$count - 1].$ext;
					}
					else
					{
						$add = 0;
						$continue = true;
						while ($continue == true)
						{
							$add++;
							if (!file_exists($upload_path.$noext.'_'.$add.$ext))
							{
								$continue = false;
							}
							$filename = $noext.'_'.$add.$ext;
						}
					}
				}
				else
				{
					$add = 0;
					$continue = true;
					while ($continue == true)
					{
						$add++;
						if (!file_exists($upload_path.$noext.'_'.$add.$ext))
						{
							$continue = false;
						}
						$filename = $noext.'_'.$add.$ext;
					}
				}
				if (strlen($filename) > $max_filename_length)
				{
					$_SESSION['Form']['Error'] = 'Please specify a different filename for the image to upload';
					Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
					exit;
				}
			}
			
			// Upload the file to the specified path
			if (move_uploaded_file($_FILES[$input]['tmp_name'],$upload_path.$filename)) {}
			else
			{
				$_SESSION['Form']['Error'] = 'Image could not be uploaded. Please try again later';
				Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
				exit;
			}
		}
		
		// Set $id to id of asse group to edit
		// split below section into 2 separate queries, first query, if (filesize($_FILES[$input]['tmp_name']) == 0) DO NOT UPDATE IMAGE ($Imagefilename) ELSE DO
		// CONTINUE REST OF UPDATE VEHICLE ASSET GROUP SCRIPT HERE
		$Manufacturer = $_POST['Manufacturer'];
		$Model = $_POST['Definition'];
		$Litre = $_POST['EngineCapacity'];
		$Category = $_POST['Category'];
		$HireCost = $_POST['HireCost'];
		$Imagefilename = $filename;
		$Transmission = $_POST['Transmission'];
		$Doors = $_POST['Doors'];
		$Seats = $_POST['Seats'];
		
		if (filesize($_FILES[$input]['tmp_name']) == 0){
		
		$stmt = $conn->prepare("UPDATE Asset_Group SET Manufacturer = :Manufacturer, Definition = :Model, EngineCapacity = :Litre, 
								Category = :Category, HireCost = :HireCost, Transmission = :Transmission, Doors = :Doors,
								Seats = :Seats WHERE AssetGroupNo = :id");
		$stmt->execute(array(	'Manufacturer' => $Manufacturer,'Model' => $Model,'Litre' => $Litre,'Category' => $Category,
								'HireCost' => $HireCost,'Transmission' => $Transmission,'Doors' => $Doors,
								'Seats' => $Seats,'id' => $id,));
								
		Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
		}
		else {
		$stmt = $conn->prepare("UPDATE Asset_Group SET Manufacturer = :Manufacturer, Definition = :Model, EngineCapacity = :Litre, 
								Category = :Category, HireCost = :HireCost, ImageFile = :Imagefilename, Transmission = :Transmission, Doors = :Doors,
								Seats = :Seats WHERE AssetGroupNo = :id");
		$stmt->execute(array(	'Manufacturer' => $Manufacturer,'Model' => $Model,'Litre' => $Litre,'Category' => $Category,
								'HireCost' => $HireCost,'Imagefilename' => $Imagefilename,'Transmission' => $Transmission,'Doors' => $Doors,
								'Seats' => $Seats,'id' => $id,));
								
		Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
		}
		}
	else if(isset($_POST['update_extra_group'])) {
			
		$Description = $_POST['Definition'];
		$HireCost = $_POST['HireCost'];
		
		$stmt = $conn->prepare("UPDATE Asset_Group SET Definition = :Description, HireCost = :HireCost WHERE AssetGroupNo = :id");
		$stmt->execute(array(	'Description' => $Description, 'HireCost' => $HireCost,'id' => $id,));
								
		Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
	}
	else if(isset($_POST['delete_asset_group'])) {
		
		$stmt = $conn->prepare("UPDATE Asset_Group SET Active = 0 WHERE AssetGroupNo = :id");	
		$stmt->execute(array('id' => $id));
		
		Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
		
	}
	else if(isset($_POST['enable_asset_group'])) {
		
		$stmt = $conn->prepare("UPDATE Asset_Group SET Active = 1 WHERE AssetGroupNo = :id");	
		$stmt->execute(array('id' => $id));
		
		Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
	}	
?>