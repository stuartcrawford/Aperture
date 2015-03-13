<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php setpermissions('admin','staff'); ?>
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

		<!-- Footable Initialize -->
		<script>
      	$(function() {
      		
      		//Bind Footable to Extra Table
      		$('#Optional_Extra, #Optional_Extra-inactive').footable({
				calculateWidthAndHeightOverride:function($table, info) {

					var parentWidth = $table.parent().width();

					if (info.viewportWidth < info.width) info.width = info.viewportWidth;
					if (info.viewportHeight < info.height) info.height = info.viewportHeight;
					if (parentWidth < info.width) info.width = parentWidth;

					return info;
				}
			});

			//Force Optional_Extra Breakpoint based on Parent Width
			$('#resize1').click(function() {
				
				setTimeout(function() {
				
					var w = $(this).data('size');
				
					$('.footable-container').css({width:w});
					$('#Optional_Extra')[0].footable.resize();
					
				}, 2);
			});
			
			//Force Optional_Extra-Inactive Breakpoint based on Parent Width
			$('#resize2').click(function() {
				
				setTimeout(function() {
				
					var w = $(this).data('size');
				
					$('.footable-container').css({width:w});
					$('#Optional_Extra-inactive')[0].footable.resize();
					
				}, 2);
			});
			
			//Reset Ajax Tab Color Change
			$("#section4 a").click(function() {
				$(this).css("background-color", "");
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
    	
    	<!-- Filter by Column function written by @Author: Alex McCormick 05.04.2013 -->
		<script>
		
			// Extend jQuery Expression Engine
			// New Pseudo Class Selectors :containsExact, :containsExactCase, :containsIgnoreCase
			$.extend( $.expr[":"], {
								
				//Case Insensitive Exact match function
 				containsExact: 
  				$.expr.createPseudo(function(text) {
   					return function(elem) {
    					return $.trim(elem.innerHTML.toLowerCase()) === text.toLowerCase();
   					};
  				}),
 
 				//Case Sensitive Exact match function
 				containsExactCase: 
  				$.expr.createPseudo(function(text) {
   					return function(elem) {
    					return $.trim(elem.innerHTML) === text;
   					};
  				}), 

				//Case Insensitive Contains match function
  				containsIgnoreCase:
  				$.expr.createPseudo(function(text) {
    				return function(elem) {
        				return $.trim(elem.innerHTML.toUpperCase()).indexOf(text.toUpperCase()) >= 0;
    				};
				})
  				
			});
			
			//On Focus Filter Searchbox Display Vehicle Table
			//Through Foundation Section Container Tabs Library
			$(function() {    
   	 			$('#filter').click(function() {
   	 				$("#section2").attr('class','section');
   	 				$("#section3").attr('class','section');
   	 				$("#section4").attr('class','section');
   	 				$("#section5").attr('class','section');
   	 				$("#section2").attr('style','padding-top:0px;');
   	 				$("#section3").attr('style','padding-top:0px;');
   	 				$("#section4").attr('style','padding-top:0px;');
   	 				$("#section5").attr('style','padding-top:0px;');
   	 				$("#section1").attr('class','section active');
   	 				$(document).foundation('section','off');
   	 				$(document).foundation('section');
   	 			});
   			});
			
			//Filter by Column written by @Author: Alex McCormick 05.04.2013
			$(function() {    
   	 			$('#filter').change(function() {
   	 				
   	 				$('#Optional_Extra tbody tr').hide();
   	 				$('#Optional_Extra tbody tr').removeClass('footable-detail-show');
   	 				
   	 				if ($(this).val() == '') {
   	 					$('#Optional_Extra tbody tr:not(.footable-row-detail)').show();	
   	 				}
   	 				else { 
   	 				
   	 					var column = $('#customDropdown option:selected').val(); 
   	 				
   	 					if (column == 'All') {
   	 						$("#Optional_Extra tbody tr:containsIgnoreCase('" + $(this).val() + "')").show();
   	 					}
   	 					else if (column == 'AssetNo' || column == 'AssetGroupNo'){
	        				$("#Optional_Extra tbody td."+ column +":containsExact('" + $(this).val() + "')").parent().show();
        				}
        				else {
        					$("#Optional_Extra tbody td."+ column +":containsIgnoreCase('" + $(this).val() + "')").parent().show();
        				}
        			}
    			});
    		});
    		
		</script>
		<script>
			// @Author Alex McCormick 10.04.2013
			function selectrecord(id)
			{	

				$("#resize0").css("background-color", "");
				$("#resize3").css("background-color", "");
			
				var ID = id;
				var action = '';
				var CSRFToken = '<?php echo $_SESSION['CSRFToken']?>'; 
				
				action = 'activebookings';
				
				$.ajax({
					url: "<?php fetchdir($php); ?>manage_extras_tab3.php",
					data: 
					{
						CSRFToken: CSRFToken,  
						action: action,
						record_id: ID
					},
					type: "POST",
					async: false,
					success: function(data) {
						
						//Unbind Footable and Forced Breakpoint
						$('#Booking').unbind();
						$('#resize3').unbind();
						
						//Load Active Bookings
						$("#section3 .content").html(data);
						
						$("#resize3").css("background-color", "#9EC4FF");

				
						//Bind Footable		
						$('#Booking').footable({
							calculateWidthAndHeightOverride:function($table, info) {

								var parentWidth = $table.parent().width();

								if (info.viewportWidth < info.width) info.width = info.viewportWidth;
								if (info.viewportHeight < info.height) info.height = info.viewportHeight;
								if (parentWidth < info.width) info.width = parentWidth;

								return info;
							}
						});
						
						//Force Booking Breakpoint based on Parent Width
						$('#resize3').click(function() {
							
							$(this).css("background-color", "");
							
							setTimeout(function() {
				
								var w = $(this).data('size');
				
								$('.footable-container').css({width:w});
								$('#Booking')[0].footable.resize();
					
							}, 1);
						});
					}
				});
				
				action = 'editextra';
		
				$.ajax({
					url: "<?php fetchdir($php); ?>manage_extras_tab4.php",
					data:  
					{
						CSRFToken: CSRFToken,  
						action: action,
						record_id: ID
					},
					type: "POST",
					async: false,
					success: function(data) {
						$("#section4 .content").html(data);
						$("#resize0").css("background-color", "#9EC4FF");
					}
				}); 
			}
		</script>	
	</head>
	<body>
		<!-- Header -->
		<?php include realpath(dirname(__FILE__)."/../../php/header.php"); ?>
		<!-- End Header -->
			
		<!-- Main Content -->  
  		<div id="first_row" class="row">
  			<div class="large-12 columns">
  				<h2>Optional Extra</h2>
  			</div>
  		</div>
  		<div id="second_row" class="row">
			<div class="large-7 small-12 columns">
				<label>Search</label>
				<input id="filter" type="text">
			</div>
			
			<!-- Column Name Dropdown -->			
			<div class="offset-1 large-5 small-12 columns">
				<form class="custom">
					<label for="customDropdown">Column</label>
					<select id="customDropdown" class="large">
						<option value="All">All</option>
  						<option value="AssetNo">ID</option>
  						<option value="Definition">Description</option>
  						<option value="SerialNumber">Serial Num</option>
  						<option value="HireCost">Hire Cost</option>
  						<option value="AssetGroupNo">Asset Group No.</option>
  						<option value="Location">Location</option>
					</select>
				</form> 
			</div>
			<div class="large-1 columns">
			</div> 															
			<!-- End Column Name Dropdown -->
			
		</div>
		
		<!-- Tab Container -->
		<div id="tab_container" class="row">
			<div class="large-12 columns">
				<div class="section-container tabs" data-section data-options="deep_linking: false">
			
					<!-- Tab 1 -->
					<section id="section1" class="section" style="">
						<h5 class="title" style="left: 198px;"><a id="resize1" data-size="100%" href="#100%">Active</a></h5>
						<div class="content" data-slug="panel1" style="">		
							<!-- Vehicle Table -->
							<?php include realpath(dirname(__FILE__)."/../../php/manage_extras_tab1.php"); ?>
							<!-- End Vehicle Table -->	
						</div>
					</section>
					<!-- End Tab 1 -->
					
					<!-- Tab 2 -->
					<section id="section2" class="section" style="">
						<h5 class="title" style="left: 0px;"><a id="resize2" data-size="100%" href="#100%">Inactive</a></h5>
						<div class="content" data-slug="panel2" style="">
							<?php include realpath(dirname(__FILE__)."/../../php/manage_extras_tab2.php"); ?>	
						</div>
					</section>
					<!-- End Tab 2 -->
					
					<!-- Tab 3 -->
					<section id="section3" class="section" style="">
						<h5 class="title" style="left: 198px;"><a id="resize3" data-size="100%" href="#100%">Booked</a></h5>
						<div class="content" data-slug="panel3" style="">
							<h3>Please Select an Optional Extra</h3>
							<p>You will need to have selected an Optional Extra from the View All tab to see the information in this section</p>
						</div>
					</section>
					<!-- End Tab 3 -->
					
					<!-- Tab 4 -->
					<section id="section4" class="section" style="">
						<h5 class="title" style="left: 0px;"><a id="resize0" href="#panel">Edit</a></h5>
						<div class="content" data-slug="panel4" style="">
							<h3>Please Select an Optional Extra</h3>
							<p>You will need to have selected an Optional Extra from the View All tab to see the information in this section</p>	
						</div>
					</section>
					<!-- End Tab 4 -->
					
					<!-- Tab 5 -->
					<section id="section5" class="section" style="">
						<h5 class="title" style="left: 0px;"><a href="#panel">Add New</a></h5>
						<div class="content" data-slug="panel5" style="">
							<?php include realpath(dirname(__FILE__)."/../../php/manage_extras_tab5.php"); ?>	
						</div>
					</section>
					<!-- End Tab 5 -->
					
				</div>
			</div>
		</div>
		<!-- End Tab Container -->
		
		<!-- End Main Content --> 
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>