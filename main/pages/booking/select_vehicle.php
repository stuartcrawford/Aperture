<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	setpermissions('admin','staff','customer');

	reservation_timeout();

	if ($_SESSION['Booking']['Step'] < 1)
	{
		if(isset($referrer))
		{
			Header ('Location: '.fetchinline($bpages).'page_expired.php');
			exit;
		}
		else
		{
			Header ('Location: '.fetchinline($gpages).'index.php');
			exit;
		}
	}
?>  
<!DOCTYPE html>
<?php include realpath(dirname(__FILE__)."/../../php/ie_js_fix.php"); ?>
        <head>
                <?php include realpath(dirname(__FILE__)."/../../php/head.php"); ?>
				<style>
					.progress {
						position:relative;
						z-index:1;
					}
				</style>
		</head>
        <body>
                <!-- Header -->
                <?php include realpath(dirname(__FILE__)."/../../php/header.php"); ?>
                <!-- Header -->
                
                <div id="first_row" class="row">
                        <div class="large-12 column"><h2>Select Vehicle</h2>
						<label><strong>Progress:</strong></label>
						<div class="progress large-12 success round" style="30px"><span class="meter" style="width: 25%"></span></div></div>                                                                  
   				</div>
                
					<?php
						dbconnect();
								
						//Fetch Values from Create Booking
						$pickup = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Pickup']['Date'].' '.$_SESSION['Booking']['Head']['Pickup']['Time']));
						$dropoff = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Dropoff']['Date'].' '.$_SESSION['Booking']['Head']['Dropoff']['Time']));
						$location = $_SESSION['Booking']['Head']['Pickup']['Location'];
						$userID = $_SESSION['User']['ID'];
						
						//Query to select the details of vehicles available within the specified time range at the specified location
						$stmt = $conn->prepare("SET @pickup=:pickup,@dropoff=:dropoff,@location=:location,@timeout=:timeout,@userid=:userid;");
						$stmt->bindParam(':pickup',$pickup);
						$stmt->bindParam(':dropoff',$dropoff);
						$stmt->bindParam(':location',$location);
						$stmt->bindParam(':timeout',$reservationTimeout);
						$stmt->bindParam(':userid',$userID);
						$stmt->execute();
						$stmt = $conn->prepare("
							SELECT g.*
								FROM Asset aa
								LEFT JOIN Booking_Header bb
								ON aa.AssetNo=bb.AssetNo
								LEFT JOIN Asset_Group g
								ON aa.AssetGroupNo=g.AssetGroupNo
								INNER JOIN Vehicle v
								ON aa.AssetNo=v.AssetNo
								WHERE (
									SELECT a.AssetNo
										FROM Asset a
										INNER JOIN Booking_Header b
										ON (a.AssetNo=b.AssetNo
												AND b.Status=1
												AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup))
											OR (a.AssetNo=b.AssetNo
												AND b.Status=4
												AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup)
												AND TIME_TO_SEC(TIMEDIFF(now(),b.CreationDate))<@timeout)
										WHERE a.Active=1
											AND a.AssetGroupNo=g.AssetGroupNo
											AND a.Location=@location
										GROUP BY a.AssetNo
										HAVING (COUNT(a.AssetNo)=(
											SELECT COUNT(AssetNo)
												FROM Booking_Header
												WHERE (AssetNo=a.AssetNo
														AND Status=1)
													OR (AssetNo=a.AssetNo
														AND Status=4
														AND UserID NOT IN(@userid)
														AND TIME_TO_SEC(TIMEDIFF(now(),CreationDate))<@timeout))
											AND bb.BookingHeadID IS NOT NULL)
										LIMIT 1
									) IS NOT NULL
									OR (
										SELECT a.AssetNo
											FROM Asset a
											LEFT JOIN Booking_Header b
											ON (a.AssetNo=b.AssetNo
													AND b.Status=1
													AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup))
												OR (a.AssetNo=b.AssetNo
													AND b.Status=4
													AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup)
													AND TIME_TO_SEC(TIMEDIFF(now(),b.CreationDate))<@timeout)
											WHERE a.Active=1
												AND a.AssetGroupNo=g.AssetGroupNo
												AND a.Location=@location
											GROUP BY a.AssetNo
											HAVING (
												SELECT COUNT(AssetNo)
													FROM Booking_Header
													WHERE (AssetNo=a.AssetNo
															AND Status=1)
														OR (AssetNo=a.AssetNo
															AND Status=4
															AND UserID NOT IN(@userid)
															AND TIME_TO_SEC(TIMEDIFF(now(),CreationDate))<@timeout))=0
											LIMIT 1
										) IS NOT NULL
								GROUP BY g.Manufacturer,g.Definition,g.EngineCapacity,g.Category,g.Transmission
								ORDER BY g.Manufacturer,g.Definition,g.EngineCapacity,g.Category,g.Transmission;
						");
						$stmt->execute();
						
						$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);
						
						if(!$rslt) {
							$_SESSION['Form']['Error'] = 'Sorry no cars available. Please select another time and/or location';
							Header('Location: '.fetchinline($gpages).'index.php');
							exit;
						}
						
						// Print rows
						foreach(  $rslt as $row )
						{
							if ($row['Transmission'] == 0)
							{
								$row['Transmission'] = 'Automatic';
							}
							else if ($row['Transmission'] == 1)
							{
								$row['Transmission'] = 'Manual';
							}
							
							// Calculate the total cost
							$diffsecs = strtotime($dropoff) - strtotime($pickup);
							$diff = ceil($diffsecs / 60 / 60 / 24);
							if ($diffsecs <= 60 * 60 * 12)
							{
								$diff = $diff / 2;
								$total = ceil($diff*$row['HireCost']);
							}
							else
							{
								$total = $diff*$row['HireCost'];
							}
					?>
					
					<div class="row"><div class="large-12 column"><?php echo (isset($_SESSION['Form']['Error'])) ? "<p style='color:red; position:relative; z-index:1;'>".$_SESSION['Form']['Error']."</p>" : "" ?></div></div>
					
				<form action="<?php fetchdir($php); ?>create_booking.php" method="post">
					<div class="row" class="v_row">
						<div class="large-3 columns">
						
							<p><strong><?php echo $row['Manufacturer'].' '.$row['Definition'].' '.$row['EngineCapacity'].' '.$row['Category'].' ('.$row['Transmission'].')'; ?></strong></p>
							<p>
							<img src="<?php fetchdir($img); ?>ico_manual.jpg" alt="icon_manual" title="icon manual"/></a>
							<img src="<?php fetchdir($img); ?>icon_ac.jpg" alt="icon_ac" title="icon AC"/>  
							<img src="<?php fetchdir($img); ?>icon_23.jpg" alt="icon_23" title="icon 23"/>
							</p>
							<p>
							<em><?php echo $row['Doors'].' doors, '.$row['Seats'].' seats'; ?></em>
							
							</p>
						</div>
						
						<div class=" large-6 columns">
							<p class="v_pic">
								<ul data-clearing style="list-style-type:none">
									<li style="list-style-type:none">
										<a href="<?php echo fetchdir($vimg).$row['ImageFile']; ?>">
											<img src="<?php fetchdir($php); ?>thumbnail.php?file=<?php echo $row['ImageFile']; ?>&amp;width=468&amp;height=309&amp;dir=vehicle" alt="<?php echo $row['Manufacturer'].' '.$row['Definition'].' '.$row['EngineCapacity'].' '.$row['Category']; ?>" title="<?php echo $row['Manufacturer'].' '.$row['Definition'].' '.$row['EngineCapacity'].' '.$row['Category']; ?>" data-caption="<?php echo $row['Manufacturer'].' '.$row['Definition'].' '.$row['EngineCapacity'].' '.$row['Category']; ?>"/>
										</a>
									</li>
								</ul>
							</p>
						</div>
						
						<div class=" large-3 columns">
							<p style="direction:rtl;">
								<span>Total &pound;<?php echo number_format($total, 2, '.', ''); ?></span>
								<br>
								<br>
								&pound;<?php echo $row['HireCost']; ?> Per Day<br>
								<input name="HireCost" type="hidden" value="<?php echo $row['HireCost']; ?>" />
								<input name="VehicleID" type="hidden" value="<?php echo $row['AssetGroupNo']; ?>" />
								<button class="radius button" name="booking2" type="submit">Book Now</button>
								<button class="radius button" name="cancel" type="submit">Cancel</button>
							</p>
						</div>
					</div>
				 </form>
				 
					<?php } ?>				
               
                <?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>   
                <!-- End Footer -->     
        </body>
</html>