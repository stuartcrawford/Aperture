<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php setpermissions('admin','staff','customer'); ?>
<!DOCTYPE html>
<?php include realpath(dirname(__FILE__)."/../../php/ie_js_fix.php"); ?>
		<head>
		<?php include realpath(dirname(__FILE__)."/../../php/head.php"); ?>
		
		<!-- Footable jQuery Plugin -->
		<!-- Custom Breakpoints Added by @Author: Alex McCorick 03.04.2013 -> Mobile: 480, Tablet: 1024, Desktop: 1900 -->
		<script src="<?php fetchdir($jsfootable); ?>footable-0.1.js"></script>
		
		<!-- Footable jQuery Plugin Extensions -->
  		<script src="<?php fetchdir($jsfootable); ?>footable.sortable.js"></script>
  		<!-- Removed by @Author: Alex McCormick 06.04.2013 and replaced with own filtering solution
		<script src="<?php fetchdir($jsfootable); ?>footable.filter.js"></script> -->

			<!--
			<script>
			// @Author Alex McCormick 10.04.2013
			function cancelrecord(id)
			{	

				$("#resize0").css("background-color", "");
				$("#resize3").css("background-color", "");
			
				var ID = id;
				var action = '';
				var CSRFToken = '<?php echo $_SESSION['CSRFToken']?>'; 
				
				action = 'deletebooking';
		
				$.ajax({
					url: "<?php fetchdir($php); ?>booking_history_booking_delete.php",
					data:  
					{
						CSRFToken: CSRFToken,  
						action: action,
						record_id: ID
					},
					type: "POST",
					async: false,
					success: function(data) {
						$('#successmsg').html(data);
					}
				});
				
				action = 'refreshbooking';
				
				
				setTimeout(function(){
				
				$.ajax({
					url: "<?php fetchdir($php); ?>booking_history_tab1.php",
					data:  
					{
						CSRFToken: CSRFToken,  
						action: action
					},
					type: "POST",
					async: false,
					success: function(data) {
						$('table').unbind();
						
						$('#section1 .content').html(data);
						
						      	$(function() {
      		
      		//Bind Footable
      		$('table').footable({
				calculateWidthAndHeightOverride:function($table, info) {

					var parentWidth = $table.parent().width();

					if (info.viewportWidth < info.width) info.width = info.viewportWidth;
					if (info.viewportHeight < info.height) info.height = info.viewportHeight;
					if (parentWidth < info.width) info.width = parentWidth;

					return info;
				}
			});

			//Force Breakpoint based on Parent Width
			$('.content').change(function() {
				
				setTimeout(function() {
				
					var w = $(this).data('size');
				
					$('.footable-container').css({width:w});
					$('table')[0].footable.resize();
					
				}, 1);
			});
      	});
					}
				});  
				
				},1500);
			}
		</script>
		-->
		<script>
			function cancelrecord(id)
			{
				var r=confirm("Are you sure you want to cancel this booking?");
				if (r === true)
				{
					var data = 'action=&ajax=&CSRFToken=<?php echo $_SESSION['CSRFToken']; ?>&record_id='+id;
					$.ajax({
						url: "<?php fetchdir($php); ?>booking_history_booking_delete.php",
						data: data,
						type: "POST",
						async: false,
						success: function(response) {
							if (response == 'Redirect')
							{
								window.location = '<?php fetchdir($gpages); ?>authentication_error.php';
							}
							else
							{
								window.location = '<?php fetchdir($apages); ?>booking_history.php';
							}
						}
					});
				}
			}
		</script>
		
		<!-- Footable Initialize -->
		<script>
      	$(function() {
      		
      		//Bind Footable
      		$('table').footable({
				calculateWidthAndHeightOverride:function($table, info) {

					var parentWidth = $table.parent().width();

					if (info.viewportWidth < info.width) info.width = info.viewportWidth;
					if (info.viewportHeight < info.height) info.height = info.viewportHeight;
					if (parentWidth < info.width) info.width = parentWidth;

					return info;
				}
			});

			//Force Breakpoint based on Parent Width
			$('.content').change(function() {
				
				setTimeout(function() {
				
					var w = $(this).data('size');
				
					$('.footable-container').css({width:w});
					$('table')[0].footable.resize();
					
				}, 1);
			});
      	});
    	</script>

    	<!-- Footable Plugin Linked Images -->
    	<style>
    		.footable > thead > tr > th > span.footable-sort-indicator {
    			background: url('<?php fetchdir($imgfootable); ?>sorting_sprite.png') no-repeat top left;
    		}
    		
    		.footable.breakpoint > tbody > tr > td.expand {
    			background: url('<?php fetchdir($imgfootable); ?>plus.png') no-repeat 5px center;
    		}
    		
    		.footable.breakpoint > tbody > tr.footable-detail-show > td.expand {
    			background: url('<?php fetchdir($imgfootable); ?>minus.png') no-repeat 5px center;
    		}
    	</style>
	</head>
	<body>
		<!-- Header -->
		<?php include realpath(dirname(__FILE__)."/../../php/header.php"); ?>
		
		<!-- End Header -->
			
		<!-- @Edit: Removed CSS Styling html block as this page is already linked to manage_accounts.css written by UE
			 @Author: Alex McCormick 
			 @Date: 27.03.2013 			-->
			
		<!-- Main Content -->
		<div id="first_row" class="row">
  			<div class="large-12 columns">
  				<h2>Booking History</h2>
  			</div>
  		</div>
				
			<div id="tab_container" class="row">
			<div class="large-12 columns">
				<div class="section-container tabs" data-section data-options="deep_linking: false">
				
				<!-- Tab 1 -->
				<section id="section1" class="section active" style="">
						<div class="content" data-slug="panel1" style="">
							<div id="successmsg"></div>		
							<!-- Account Table -->
							<?php include realpath(dirname(__FILE__)."/../../php/booking_history_tab1.php"); ?>	
							<!-- End Account Table -->	
						</div>
					</section>
					<!-- End Tab 1 -->
					
					<!-- Tab 2 -->
					
					<!-- End Tab 2 -->
					
					</div>
				</div>
			</div>
			<!-- End Column Name Dropdown -->
  			
  		</div>
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>