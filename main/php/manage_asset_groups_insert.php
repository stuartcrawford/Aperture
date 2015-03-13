<?php

	include("global.php");
	dbconnect();

	if(isset($_POST['add_vehicle_group']))
	{
		$input = 'file'; // Name of the file upload input
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
		
		// CONTINUE REST OF ADD VEHICLE ASSET GROUP SCRIPT HERE
		$Manufacturer = $_POST['Manufacturer'];
		$Model = $_POST['Definition'];
		$Litre = $_POST['EngineCapacity'];
		$Category = $_POST['Category'];
		$HireCost = $_POST['HireCost'];
		$Imagefilename = $filename;
		$Transmission = $_POST['Transmission'];
		$Doors = $_POST['Doors'];
		$Seats = $_POST['Seats'];
		
		$stmt = $conn->prepare("INSERT INTO Asset_Group(Active, Manufacturer, Definition, EngineCapacity, Category, HireCost, ImageFile, Transmission, Doors,
								Seats) VALUES (1,:Manufacturer,:Model,:Litre,:Category,:HireCost,:Imagefilename,:Transmission,:Doors,:Seats)");
		$stmt->execute(array(	'Manufacturer' => $Manufacturer,'Model' => $Model,'Litre' => $Litre,'Category' => $Category,
								'HireCost' => $HireCost,'Imagefilename' => $Imagefilename,'Transmission' => $Transmission,'Doors' => $Doors,
								'Seats' => $Seats));
								
		Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
	}
	
	else if(isset($_POST['add_extra_group'])) {
		
		$Description = $_POST['Definition'];
		$HireCost = $_POST['HireCost'];
		
		$stmt = $conn->prepare("INSERT INTO Asset_Group(Active, Definition, HireCost) VALUES(1, :Description, :HireCost)");
		$stmt->execute(array('Description' => $Description, 'HireCost' => $HireCost));
		
		Header('Location: '.fetchinline($apages).'manage_asset_groups.php');
	}
	
?>