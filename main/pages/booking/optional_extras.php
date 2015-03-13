<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	setpermissions('admin','staff','customer');

	reservation_timeout();

	if ($_SESSION['Booking']['Step'] < 2)
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
	</head>
	<body>
		<!-- Header -->
		<?php include realpath(dirname(__FILE__)."/../../php/header.php"); ?>
		<!-- End Header -->
			
		<!-- Main Content --> 
		
			<div id="first_row" class="row">
  				<h2>Optional Extras</h2>
				<label><strong>Progress:</strong></label>
						<div class="progress large-5 success round" style="30px"><span class="meter" style="width: 50%"></span></div>
  				<?php echo (isset($_SESSION['Form']['Error'])) ? "<p style='color:red;'>".$_SESSION['Form']['Error']."</p>" : "" ?>
			</div>
				<form action="<?php fetchdir($php); ?>create_booking.php" method="post">
				<div class="row collapse">
				<div class="large-5 columns small-12 colums">
			<table>
				<tbody>
				
				<tr>
					<th>Products and Services</th>
					<th >Cost per Day</th>    
					<th >Amount</th>
				</tr>
				
				<?php
					//Select Query
					$stmt = $conn->prepare("SELECT * FROM Asset_Group, Asset, Optional_Extra WHERE Asset.AssetGroupNo = Asset_Group.AssetGroupNo AND  Asset.AssetNo = Optional_Extra.AssetNo GROUP BY Definition ORDER BY Optional_Extra.AssetNo ASC");
					$stmt->execute();
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row ) { 
				
				?>
				
				<tr>
				<td><?php echo $row['Definition']; ?></td>
				<?php if($row['Definition'] == 'GPS') { ?><td>&pound;<?php echo $row['HireCost']; ?><input type="hidden" name="GPSCost" value="<?php echo $row['HireCost']; ?>"></td><?php } ?>
				<?php if($row['Definition'] == 'Child Seat') { ?><td>&pound;<?php echo $row['HireCost']; ?><input type="hidden" name="ChildSeatCost" value="<?php echo $row['HireCost']; ?>"></td><?php } ?>
				<?php if($row['Definition'] == 'Infant Seat') { ?><td>&pound;<?php echo $row['HireCost']; ?><input type="hidden" name="InfantSeatCost" value="<?php echo $row['HireCost']; ?>"></td><?php } ?>
				<?php if($row['Definition'] == 'Booster Seat') { ?><td>&pound;<?php echo $row['HireCost']; ?><input type="hidden" name="BoosterSeatCost" value="<?php echo $row['HireCost']; ?>"></td><?php } ?>
				<?php if($row['Definition'] == 'Snow Tires') { ?><td>&pound;<?php echo $row['HireCost']; ?><input type="hidden" name="SnowTiresCost" value="<?php echo $row['HireCost']; ?>"></td><?php } ?>
				<td>
					<?php if($row['Definition'] == 'GPS') { if($_SESSION['Booking']['Detail']['GPS']['Qty'] == 1){ ?><input type="checkbox" name="GPS" checked><?php } else { ?><input type="checkbox" name="GPS"><?php } } ?>
					
					<?php if($row['Definition'] == 'Child Seat') { ?>
						<select name="ChildSeat">
							<?php if($_SESSION['Booking']['Detail']['ChildSeat']['Qty'] == 0){ ?><option value="0" selected>0</option><?php } else { ?><option value="0">0</option><?php } ?>
							<?php if($_SESSION['Booking']['Detail']['ChildSeat']['Qty'] == 1){ ?><option value="1" selected>1</option><?php } else { ?><option value="1">1</option><?php } ?>
							<?php if($_SESSION['Booking']['Detail']['ChildSeat']['Qty'] == 2){ ?><option value="2" selected>2</option><?php } else { ?><option value="2">2</option><?php } ?>
							<?php if($_SESSION['Booking']['Detail']['ChildSeat']['Qty'] == 3){ ?><option value="3" selected>3</option><?php } else { ?><option value="3">3</option><?php } ?>
						</select>	
					<?php } ?>
					
					<?php if($row['Definition'] == 'Infant Seat') { ?>
						<select name="InfantSeat">
							<?php if($_SESSION['Booking']['Detail']['InfantSeat']['Qty'] == 0){ ?><option value="0" selected>0</option><?php } else { ?><option value="0">0</option><?php } ?>
							<?php if($_SESSION['Booking']['Detail']['InfantSeat']['Qty'] == 1){ ?><option value="1" selected>1</option><?php } else { ?><option value="1">1</option><?php } ?>
							<?php if($_SESSION['Booking']['Detail']['InfantSeat']['Qty'] == 2){ ?><option value="2" selected>2</option><?php } else { ?><option value="2">2</option><?php } ?>
							<?php if($_SESSION['Booking']['Detail']['InfantSeat']['Qty'] == 3){ ?><option value="3" selected>3</option><?php } else { ?><option value="3">3</option><?php } ?>
						</select>
					<?php } ?>
					
					<?php if($row['Definition'] == 'Booster Seat') { ?>
						<select name="BoosterSeat">
							<?php if($_SESSION['Booking']['Detail']['BoosterSeat']['Qty'] == 0){ ?><option value="0" selected>0</option><?php } else { ?><option value="0">0</option><?php } ?>
							<?php if($_SESSION['Booking']['Detail']['BoosterSeat']['Qty'] == 1){ ?><option value="1" selected>1</option><?php } else { ?><option value="1">1</option><?php } ?>
							<?php if($_SESSION['Booking']['Detail']['BoosterSeat']['Qty'] == 2){ ?><option value="2" selected>2</option><?php } else { ?><option value="2">2</option><?php } ?>
							<?php if($_SESSION['Booking']['Detail']['BoosterSeat']['Qty'] == 3){ ?><option value="3" selected>3</option><?php } else { ?><option value="3">3</option><?php } ?>
						</select>
					<?php } ?>
					
					<?php if($row['Definition'] == 'Snow Tires') { if($_SESSION['Booking']['Detail']['SnowTires']['Qty'] == 1){ ?><input type="checkbox" name="SnowTires" checked><?php } else { ?><input type="checkbox" name="SnowTires"><?php }  } ?>
				</td>
				</tr>
			
			<?php } ?>	
			
			<tr>
				<td colspan="2" align="right">
						<div align="right" class="small-6 columns">
  							<button class="radius button" name="booking3" type="submit">Continue</button>
  							
  						</div>
  						<div align="right" class="small-6 columns">
  							<button class="radius button" name="cancel" type="submit">Cancel</button>
  						</div>
				</td>
			</tr>
			</tbody>
			</table>		
			</div>
			</div>
			</form>
			
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>