<?php
	if ($_GET["dir"] == 'vehicle')
	{
		$basePath = "../img/vehicle/"; // Set this to the image directory, must be relative not absolute!
		$_GET["type"] = "exact";
		$_GET["upscale"] = "true";
	}
	else if ($_GET["dir"] == 'image')
	{
		$basePath = "../img/";
		$_GET["type"] = "scale";
	}
	else
	{
		$basePath = "../img/";
	}
	$sImagePath = $basePath.stripslashes($_GET["file"]); // Prevents IGOR
	$iWidth = (int)$_GET["width"];
	$iHeight = (int)$_GET["height"];
	$sType = $_GET["type"];
	(isset($_GET["upscale"]) == "true" ? $upscale = true : $upscale = false);
	$bgcolour = array(255,255,255); // White
	$quality = 90; // Default 75
	 
	$img = @imagecreatefromstring(file_get_contents($sImagePath)) or die("Cannot create new image");
	
	if ($img) {
	 
		$iOrigWidth = imagesx($img);
		$iOrigHeight = imagesy($img);
	 
		if ($sType == 'scale') {
	 
			// Get scale ratio
			$fScale = min($iWidth/$iOrigWidth,
				  $iHeight/$iOrigHeight);
	 
			if ($fScale < 1 || $upscale == true) {
	 
				$iNewWidth = floor($fScale*$iOrigWidth);
				$iNewHeight = floor($fScale*$iOrigHeight);
				
				$tmpimg = imagecreatetruecolor($iNewWidth,
								   $iNewHeight);
	 
				imagecopyresampled($tmpimg, $img, 0, 0, 0, 0,
				$iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);
	 
				imagedestroy($img);
				$img = $tmpimg;
			}    
	 
		}

		else if ($sType == 'exact') {
	 
			// Get scale ratio
			$fScale = min($iWidth/$iOrigWidth,
				  $iHeight/$iOrigHeight);
	 
			if ($fScale < 1 || $upscale == true) {
				
				$iNewWidth = floor($fScale*$iOrigWidth);
				$iNewHeight = floor($fScale*$iOrigHeight);
				
				$tmpimg = imagecreatetruecolor($iWidth,
								   $iHeight);
	 
				// Set background colour
				$backgroundColor = imagecolorallocate($tmpimg, $bgcolour[0], $bgcolour[1], $bgcolour[2]);
				imagefill($tmpimg, 0, 0, $backgroundColor);
				
				if ($iNewWidth == $iWidth) {
	 
					$yAxis = abs(($iNewHeight/2)-
						($iHeight/2));
					$xAxis = 0;
	 
				} else if ($iNewHeight == $iHeight)  {
	 
					$yAxis = 0;
					$xAxis = abs(($iNewWidth/2)-
						($iWidth/2));
	 
				}
				
				imagecopyresampled($tmpimg, $img, $xAxis, $yAxis, 0, 0,
				$iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);
	 
				imagedestroy($img);
				$img = $tmpimg;
			}    
	 
		}
		
		else if ($sType == "cropped") {
	 
			// Get scale ratio
			$fScale = max($iWidth/$iOrigWidth,
				  $iHeight/$iOrigHeight);
	 
			if ($fScale < 1 || $upscale == true) {
	 
				$iNewWidth = floor($fScale*$iOrigWidth);
				$iNewHeight = floor($fScale*$iOrigHeight);
	 
				$tmpimg = imagecreatetruecolor($iNewWidth,
								$iNewHeight);
				$tmp2img = imagecreatetruecolor($iWidth,
								$iHeight);
	 
				imagecopyresampled($tmpimg, $img, 0, 0, 0, 0,
				$iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);
	 
				if ($iNewWidth == $iWidth) {
	 
					$yAxis = ($iNewHeight/2)-
						($iHeight/2);
					$xAxis = 0;
	 
				} else if ($iNewHeight == $iHeight)  {
	 
					$yAxis = 0;
					$xAxis = ($iNewWidth/2)-
						($iWidth/2);
	 
				}
	 
				imagecopyresampled($tmp2img, $tmpimg, 0, 0,
						   $xAxis, $yAxis,
						   $iWidth,
						   $iHeight,
						   $iWidth,
						   $iHeight);
	 
				imagedestroy($img);
				imagedestroy($tmpimg);
				$img = $tmp2img;
			}   
	 
		}
	 
		header("Content-type: image/jpeg");
		imagejpeg($img,null,$quality);
		//$savePath = "../img/cache/";
		//imagejpeg($img,$savePath.basename($_GET["file"]),$quality);
	}
?>