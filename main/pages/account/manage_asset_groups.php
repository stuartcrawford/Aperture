<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php setpermissions('admin'); ?>
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
      	
      		var vehiclegroup = 'hide';
			var extragroup = 'hide';
			
			//Reset Ajax Tab Color Change
			$("#section3 a").click(function() {
				$(this).css("background-color", "");
			});
			
			//Sub Nav Filter Menu
			$('a[href="#no_filter"]').click(function() {
				$('a[href="#extras_filter"]').parent().attr('class','');
				$('a[href="#vehicle_filter"]').parent().attr('class','');
				$(this).parent().attr('class','active');
				vehiclegroup = 'hide';
				extragroup = 'hide';							
			});
			
			$('a[href="#vehicle_filter"]').click(function() {
				$('a[href="#no_filter"]').parent().attr('class','');
				$('a[href="#extras_filter"]').parent().attr('class','');
				$(this).parent().attr('class','active');
				vehiclegroup = 'show';
				extragroup = 'hide';
			});
			
			$('a[href="#extras_filter"]').click(function() {
				$('a[href="#no_filter"]').parent().attr('class','');
				$('a[href="#vehicle_filter"]').parent().attr('class','');
				$(this).parent().attr('class','active');
				vehiclegroup = 'hide';
				extragroup = 'show';
			}); 
			
			//Asset Group Type Filters
			//$('#x, #x1, #y, #y1').click(function() {
			$('a[href="#no_filter"],a[href="#vehicle_filter"],a[href="#extras_filter"]').click(function() {
				
				var CSRFToken = '<?php echo $_SESSION['CSRFToken']?>';
				
				
				/*$("input[id='x1']").click(function() {
					$("input[id='y']").click();
				});
				
				$("input[id='y1']").click(function() {
					$("input[id='x']").click();
				});
				
				if ( $("input[id='x']:checked").val() == 'on' ) {
					vehiclegroup = 'hide';
				}
				else if ( $("input[id='x1']:checked").val() == 'on' ) {
					vehiclegroup = 'show';
				}
				
				if ( $("input[id='y']:checked").val() == 'on' ) {
					extragroup = 'hide';
				}
				else if ( $("input[id='y1']:checked").val() == 'on' ) {
					extragroup = 'show';
					$("input[id='x']").click();
				}*/
				
				$.ajax({
					url: "<?php fetchdir($php); ?>manage_asset_groups_filter.php",
					data:  
					{
						CSRFToken: CSRFToken,  
						extragroup: extragroup,
						vehiclegroup: vehiclegroup
					},
					type: "POST",
					async: false,
					success: function(data) {
						$("#filter_container").html(data);
						$("#error_row").html("");
					}
				});
				
				$.ajax({
					url: "<?php fetchdir($php); ?>manage_asset_groups_tab1.php",
					data:  
					{
						CSRFToken: CSRFToken,  
						extragroup: extragroup,
						vehiclegroup: vehiclegroup
					},
					type: "POST",
					async: false,
					success: function(data) {
						$("#section1 .content").html(data);
						
						//Bind Footable to Asset_Group Table
      					$('#Asset_Group').footable({
							
							calculateWidthAndHeightOverride:function($table, info) {

								var parentWidth = $table.parent().width();

								if (info.viewportWidth < info.width) info.width = info.viewportWidth;
								if (info.viewportHeight < info.height) info.height = info.viewportHeight;
								if (parentWidth < info.width) info.width = parentWidth;

								return info;
							
							}
						});
	
						//Force Asset_Group Breakpoint based on Parent Width
						$('#resize1').click(function() {
				
							setTimeout(function() {
				
								var w = $(this).data('size');
				
								$('.footable-container').css({width:w});
								$('#Asset_Group')[0].footable.resize();
					
							}, 1);
						});
					}
				});	
				
				$.ajax({
					url: "<?php fetchdir($php); ?>manage_asset_groups_tab2.php",
					data:  
					{
						CSRFToken: CSRFToken,  
						extragroup: extragroup,
						vehiclegroup: vehiclegroup
					},
					type: "POST",
					async: false,
					success: function(data) {
						if (vehiclegroup == 'hide' && extragroup == 'hide') {
							$("#section2 .content").html('<h3>Please Filter on an Asset Group Type</h3><p>You will need to have filtered on an Asset Group Type from the top of the page to see data in this section</p>');
						}
						else if (extragroup == 'show') { //@Added by JC
							$('#section2 .content').html('<h3>This section is not applicable for Extras</h3><p>You will need to have filtered on the Vehicle Asset Group Type from the top of the page to see data in this section</p>');
						}
						else {
							
						$("#section2 .content").html(data);
						
						//Bind Footable to Asset_Group Table
      					$('#Asset_Group-inactive').footable({
							
							calculateWidthAndHeightOverride:function($table, info) {

								var parentWidth = $table.parent().width();

								if (info.viewportWidth < info.width) info.width = info.viewportWidth;
								if (info.viewportHeight < info.height) info.height = info.viewportHeight;
								if (parentWidth < info.width) info.width = parentWidth;

								return info;
							
							}
						});
			
						//Force Asset_Group-Inactive Breakpoint based on Parent Width
						$('#resize2').click(function() {
				
							setTimeout(function() {
				
								var w = $(this).data('size');
						
								$('.footable-container').css({width:w});
								$('#Asset_Group-inactive')[0].footable.resize();
					
							}, 1);
						});
						
						}
					}
				});
				
				$.ajax({
					url: "<?php fetchdir($php); ?>manage_asset_groups_tab3.php",
					data:  
					{
						CSRFToken: CSRFToken,  
						extragroup: extragroup,
						vehiclegroup: vehiclegroup
					},
					type: "POST",
					async: false,
					success: function(data) {
						if (vehiclegroup == "hide" && extragroup == "hide") {
							$("#section3 .content").html("<h3>Please Filter on an Asset Group Type</h3><p>You will need to have filtered on an Asset Group Type from the top of the page to see data in this section</p>");
						}
						else {
						$("#section3 .content").html("<h3>Please Select an Asset Group</h3><p>You will need to have selected an asset group from the View All tab to see the information in this section</p>");
						}
					}
				});
				
				$.ajax({
					url: "<?php fetchdir($php); ?>manage_asset_groups_tab4.php",
					data:  
					{
						CSRFToken: CSRFToken,  
						extragroup: extragroup,
						vehiclegroup: vehiclegroup
					},
					type: "POST",
					async: false,
					success: function(data) {
						if (extragroup == 'show') { //@Added by JC
							$('#section4 .content').html('<h3>This section is not applicable for Extras</h3><p>You will need to have filtered on the Vehicle Asset Group Type from the top of the page to see data in this section</p>');
						}
						else
						{
							$("#section4 .content").html(data);
						}
					}
				});
				
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
   	 			$('#filter').on('change',function() {
   	 				
   	 				$('#Asset_Group tbody tr').hide();
   	 				$('#Asset_Group tbody tr').removeClass('footable-detail-show');
   	 				
   	 				if ($(this).val() == '') {
   	 					$('#Asset_Group tbody tr:not(.footable-row-detail)').show();	
   	 				}
   	 				else { 
   	 				
   	 					var column = $('#customDropdown option:selected').val(); 
   	 				
   	 					if (column == 'All') {
   	 						$("#Asset_Group tbody tr:containsIgnoreCase('" + $(this).val() + "')").show();
   	 					}
   	 					else if (column == 'AssetNo' || column == 'AssetGroupNo'){
	        				$("#Asset_Group tbody td."+ column +":containsExact('" + $(this).val() + "')").parent().show();
        				}
        				else {
        					$("#Asset_Group tbody td."+ column +":containsIgnoreCase('" + $(this).val() + "')").parent().show();
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
			
				var ID = id;
				var action = '';
				var CSRFToken = '<?php echo $_SESSION['CSRFToken']?>'; 
				
				action = 'editassetgroup';
		
				$.ajax({
					url: "<?php fetchdir($php); ?>manage_asset_groups_tab3.php",
					data:  
					{
						CSRFToken: CSRFToken,  
						action: action,
						record_id: ID
					},
					type: "POST",
					async: false,
					success: function(data) {
						$("#section3 .content").html(data);
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
  				<h2>Asset Group</h2>
  			</div>
  		</div>
  		
  		<!-- Filter Area -->
  		<div id="second_row" class="row">
			<div class="large-7 small-12 columns">
				<label>Search</label>
				<input id="filter" type="text">
			</div>
  			<div id="filter_container">
  				<div class="offset-1 large-5 medium-12 small-12 columns">
					<form class="custom">
						<label for="customDropdown">Column</label>
						<select id="customDropdown">
							<option value="All">All</option>
						</select>
					</form>
				</div> 
			</div>
			<div class="large-1 columns">
			</div> 
		</div>

		<!-- Sub Nav -->
		<div id="third_row" class="row">
			<div class="large-12 columns">
				<dl class="sub-nav">
	  				<dt>Filter:</dt>
	  				<dd class="active"><a href="#no_filter">None</a></dd>
  					<dd><a href="#vehicle_filter">Vehicles</a></dd>
  					<dd><a href="#extras_filter">Extras</a></dd>
				</dl>
			</div>
		</div>
		<div id="error_row" class="row">
			<div class="large-12 columns">
				<?php
					if (!is_null($_SESSION['Form']['Error']))
					{
						echo '<p id="fb" style="color:red">'.$_SESSION['Form']['Error'].'</p>';
					}
				?>
			</div>
		</div>
		<!-- End Sub Nav -->	

		<!-- Default Switch 
		<div id="fourth_row" class="row collapse">
			<div class="large-6 columns">
				<div style="padding:0.9em"> 
				<label style="margin-top:0.35em;">Vehicles</label> 
				<div class="switch">
  					<input id="x" name="switch-x" type="radio" checked>
  					<label for="x" onclick="">Off</label>

  					<input id="x1" name="switch-x" type="radio">
  					<label for="x1" onclick="">On</label>

  					<span></span>
				</div>
				</div>
			</div>
			<div class="large-6 columns">
				<div style="padding:0.9em">  
				<label style="margin-top:0.35em;">Extra</label> 
				<div class="switch">
  					<input id="y" name="switch-y" type="radio" checked>
  					<label for="y" onclick="">Off</label>

  					<input id="y1" name="switch-y" type="radio">
  					<label for="y1" onclick="">On</label>

  					<span></span>
				</div>
			</div>
			</div>
			<div class="large-6 medium-3 small-6 columns">
			</div>
		</div>
		<!-- End Switch -->

		<!-- Tab Container -->
		<div id="tab_container" class="row">
			<div class="large-12 columns">
				<div class="section-container tabs" data-section data-options="deep_linking: false">
			
					<!-- Tab 1 -->
					<section id="section1" class="section" style="">
						<h5 class="title" style="left: 198px;"><a id="resize1" data-size="100%" href="#100%">Active</a></h5>
						<div class="content" data-slug="panel1" style="">		
							<h3>Please Filter on an Asset Group Type</h3>
							<p>You will need to have filtered on an Asset Group Type from the top of the page to see data in this section</p>
						</div>
					</section>
					<!-- End Tab 1 -->
					
					<!-- Tab 2 -->
					<section id="section2" class="section" style="">
						<h5 class="title" style="left: 0px;"><a id="resize2" data-size="100%" href="#100%">Inactive</a></h5>
						<div class="content" data-slug="panel2" style="">
							<h3>Please Filter on an Asset Group Type</h3>
							<p>You will need to have filtered on an Asset Group Type from the top of the page to see data in this section</p>	
						</div>
					</section>
					<!-- End Tab 2 -->
					
					<!-- Tab 3 -->
					<section id="section3" class="section" style="">
						<h5 class="title" style="left: 0px;"><a id="resize0" href="#panel">Edit</a></h5>
						<div class="content" data-slug="panel3" style="">
							<h3>Please Filter on an Asset Group Type</h3>
							<p>You will need to have filtered on an Asset Group Type from the top of the page to see data in this section</p>
						</div>
					</section>
					<!-- End Tab 3 -->
					
					<!-- Tab 4 -->
					<section id="section4" class="section" style="">
						<h5 class="title" style="left: 0px;"><a href="#panel">Add New</a></h5>
						<div class="content" data-slug="panel4" style="">
							<h3>Please Filter on an Asset Group Type</h3>
							<p>You will need to have filtered on an Asset Group Type from the top of the page to see data in this section</p>
						</div>
					</section>
					<!-- End Tab 4 -->
					
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