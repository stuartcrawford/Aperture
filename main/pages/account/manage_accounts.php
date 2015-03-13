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
      		
      		//Bind Footable
      		$('#Account, #Account-inactive').footable({
				calculateWidthAndHeightOverride:function($table, info) {

					var parentWidth = $table.parent().width();

					if (info.viewportWidth < info.width) info.width = info.viewportWidth;
					if (info.viewportHeight < info.height) info.height = info.viewportHeight;
					if (parentWidth < info.width) info.width = parentWidth;

					return info;
				}
			});

			//Force Breakpoint based on Parent Width
			$('a.resize1').click(function() {
				
				setTimeout(function() {
				
					var w = $(this).data('size');
				
					$('.footable-container').css({width:w});
					$('#Account')[0].footable.resize();
					
				}, 1);
			});
				
			//Force Breakpoint based on Parent Width
			$('a.resize2').click(function() {
				
				setTimeout(function() {
				
					var w = $(this).data('size');
				
					$('.footable-container').css({width:w});
					$('#Account-inactive')[0].footable.resize();
					
				}, 1);
			});
			
			//Reset Ajax Tab Color Change
			$("#section3 a").click(function() {
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
   	 				
   	 				$('#Account tbody tr').hide();
   	 				$('#Account tbody tr').removeClass('footable-detail-show');
   	 				
   	 				if ($(this).val() == '') {
   	 					$('#Account tbody tr:not(.footable-row-detail)').show();	
   	 				}
   	 				else { 
   	 				
   	 					var column = $('#customDropdown option:selected').val(); 
   	 				
   	 					if (column == 'All') {
   	 						$("#Account tbody tr:containsIgnoreCase('" + $(this).val() + "')").show();
   	 					}
   	 					else if (column == 'UserID'){
	        				$("#Account tbody td."+ column +":containsExact('" + $(this).val() + "')").parent().show();
        				}
        				else {
        					$("#Account tbody td."+ column +":containsIgnoreCase('" + $(this).val() + "')").parent().show();
        				}
        			}
    			});
    		});
    		
		</script>
		<script>
			// @Author Alex McCormick 22.04.2013
			function selectaccount(id)
			{
				
				$("#resize0").css("background-color", "");
				
				var aID = id;	
				var action = 'editaccount';
				var CSRFToken = '<?php echo $_SESSION['CSRFToken']?>'; 
				
				$.ajax({
					url: "<?php echo fetchdir($php)."manage_accounts_tab3.php"; ?>",
					data: 
					{
						CSRFToken: CSRFToken,  
						action: action,
						a_id: aID
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
			
		<!-- @Edit: Removed CSS Styling html block as this page is already linked to manage_accounts.css written by UE
			 @Author: Alex McCormick 
			 @Date: 27.03.2013 			-->
			
		<!-- Main Content -->
		<div id="first_row" class="row">
  			<div class="large-12 columns">
  				<?php if ($_SESSION['User']['AccessLvl'] == 1) { ?><h2>User</h2><?php } else if ($_SESSION['User']['AccessLvl'] == 2) { ?><h2>Customer<a id="start-jr" style="display:none;"><img src="<?php fetchdir($img); ?>portalHelpIcon.gif" /></a></h2><?php } ?>
  			</div>
  		</div>
		
  		<div id="second_row" class="row">
			<div class="large-7 small-12 columns">
				<span id="numero1" class="so-awesome"></span>
				<label>Search</label>
				<input id="filter" type="text">
			</div>
  			
  			<!-- @Edit: Renamed div ID from test name to "no-more-tables" to match CSS styling rules provided by UE
  				 @Author: Alex McCormick 
			     @Date: 27.03.2013 			-->
				 <div class="offset-1 large-5 small-12 columns">
				<form class="custom">
					<label for="customDropdown">Column</label>
					<span id="numero2" class="so-awesome"></span>
					<select id="customDropdown" class="large">
						<option value="All">All</option>
  						<option value="UserID">ID</option>
  						<option value="CreationDate">Creation Date</option>
  						<option value="AccessLvl">Access Level</option>
  						<option value="Email">Email Address</option>
  						<option value="FirstName">First Name</option>
  						<option value="LastName">Last Name</option>
  						<option value="Add1">Address 1</option>
  						<option value="Add2">Address 2</option>
  						<option value="Add3">Address 3</option>
  						<option value="Postcode">Postcode</option>
  						<option value="Phone">Phone</option>
						<option value="LicenseNo">License No</option>
				<!--	<option value="NextLogin">NextLogin</option>
						<option value="LoginAttempts">LoginAttempts</option>
						<option value="SQ1">SQ1</option>
						<option value="SQ2">SQ2</option>
						<option value="SQA1">SQA1</option>
						<option value="SQA2">SQA2</option>
  						<!-- Note: Image-column Available not filterable 
  							 on contains: innerHTML functions above
  							
  							<option value="Available">Available</option>
  							 
  								-->
					</select>
				</form> 
			</div>
			<div class="large-1 columns">
			</div> 															
			<!-- End Column Name Dropdown -->
			
		</div>
		
					
			<div id="tab_container" class="row">
			<div class="large-12 columns">
				<p id="numero3" class="so-awesome"><span id="numero4" class="so-awesome"></span><span id="numero5" class="so-awesome"></span></p>
				<div class="section-container tabs" data-section data-options="deep_linking: false">
				
				<!-- Tab 1 -->
				
				<section id="section1" class="section active">
						<h5 class="title" style="left: 198px;"><a class="resize1" data-size="100%" href="#100%">Active</a></h5>
						<div class="content" data-slug="panel1" style="">				
							<!-- Account Table -->
							<?php include realpath(dirname(__FILE__)."/../../php/manage_accounts_tab1.php"); ?>	
							<!-- End Account Table -->	
						</div>
					</section>
					<!-- End Tab 1 -->
					
					<!-- Tab 2 -->
					
					<section id="section2" class="section">
					<h5 class="title" style="left: 0px;"><a class="resize2" data-size="100%" href="#100%">Inactive</a></h5>
					<div class="content" data-slug="panel2" style="">
						<?php include realpath(dirname(__FILE__)."/../../php/manage_accounts_tab2.php"); ?>	
					</div>
					</section>
					<!-- End Tab 2 -->					
					
					<!-- Tab 3 -->
					<section id="section3" class="section">
					<h5 class="title" style="left: 0px;"><a id="resize0">Edit</a></h5>
					<div class="content" data-slug="panel2" style="">
						<?php if ($_SESSION['User']['AccessLvl'] == 1) { ?><h3>Please Select a User Account</h3><?php } else if ($_SESSION['User']['AccessLvl'] == 2) { ?><h3>Please Select a Customer</h3><?php } ?>
						<?php if ($_SESSION['User']['AccessLvl'] == 1) { ?><p>You will need to have selected a User Account to see the information in this section</p><?php } else if ($_SESSION['User']['AccessLvl'] == 2) { ?><p>You will need to have selected a Customer to see the information in this section</p><?php } ?>
					</div>
					</section>
					<!-- End Tab 3 -->
					
					<?php if($_SESSION['User']['AccessLvl'] == 1) { ?>
					<!-- Tab 4 -->
					<section id="section4" class="section">
						<h5 class="title" style="left: 198px;"><a class="resize1" data-size="100%" href="#100%">Add Staff</a></h5>
						<div class="content" data-slug="panel1" style="">		
							<!-- Account Table -->
							<?php include realpath(dirname(__FILE__)."/../../php/manage_accounts_tab4.php"); ?>	
							<!-- End Account Table -->	
						</div>
					</section>
					<!-- End Tab 4 -->
					
					<!-- Tab 5 -->
					<section id="section5" class="section">
						<h5 class="title" style="left: 198px;"><a class="resize1" data-size="100%" href="#100%">Add Admin</a></h5>
						<div class="content" data-slug="panel1" style="">		
							<!-- Account Table -->
							<?php include realpath(dirname(__FILE__)."/../../php/manage_accounts_tab5.php"); ?>	
							<!-- End Account Table -->	
						</div>
					</section>
					<!-- End Tab 5 -->
					<?php } ?>
					
					</div>
				</div>
			</div>
			<!-- End Column Name Dropdown -->
  			
  		</div>
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?> 	
 		<!-- End Footer -->
 		
 		<!-- Joyride -->
 		<?php if ($_SESSION['User']['AccessLvl'] == 2) {  if ($_SESSION['staffjoyride'] == 'on') { ?>
		<script>
		 	$('#start-jr img').on('click', function() {
				$(document).foundation('joyride','start');
			});
			
			//Fix Transform Glitch for Modals
			$('#start-jr img,*[data-button="Next"]').click(function() {
				$('body').css('transform','');
			});
			
			//Auto Start Joyride
			$(document).ready(function() {
				$('#start-jr img').click();
			});  
		</script>
		<ol class="joyride-list" data-joyride>
			<li data-text="Next">
				<h4>Aperture Staff Portal</h4>
				<p>This tour will guide you through the staff portal. Each page contains tabs or sections which store a mix of data tables and forms...</p>
			</li>
			<li data-id="numero1" data-class="custom so-awesome" data-button="Next">
				<h4>#1 Search</h4>
				<p>Type something and start your search! Full table searches are case-insensitive and can part-match your query rather than you having to exactly-match the data. Hit Enter when you want to search</p>
			</li>
			<li data-id="numero2" data-button="Next">
				<h4>#2 Filter by Column</h4>
				<p>You can search the whole table for your query or you can select a specific column to filter your search by</p>
			</li>
			<li data-text="Next">
				<h4>Tables</h4>
				<p>Every page displays a different subset of data. On this page are records relating to all registered Customers. You can view more details of a row on any table by clicking on a row. Once you've read these details, simply click on the row again to hide. You can read multiple rows more details sections at the same time.<br/>
			</li>
			<li data-id="numero3" data-button="Next">
				<h4>#3 Active Tab</h4>
				<p>All Staff Portal pages display the active records for the page title's data set on the first section.<br/>
				To select a record to edit, click on the select column on the far right</p>
			</li>
			<li data-id="numero4" data-button="Next">
				<h4>#4 Inactive Tab</h4>
				<p>On the next tab we display the inactive records for this data set. <br/>Again you can select a record by clicking in the select column on the row you wish to select.</p>
			</li>
			<li data-text="Next">
				<h4>Forms</h4>
				<p>Forms are contained within a tab and can either are blank (for Add New tabs) or populated by a selected record</p>
			</li>
			<li data-id="numero5" data-button="Next">
				<h4>#5 Edit Tab</h4>
				<p>The Edit tab loads a form filled in with the data from the selected record from the Active OR Inactive tab for updating, activation or deletion</p>
			</li>
			<li data-button="Finish">
				<h4>Aperture Staff Portal</h4>
				<p>Thank you for taking the tour. We hope we helped!</p>
			</li>
		</ol> 
		<?php }  $_SESSION['staffjoyride'] = 'off';	} ?>
 		<!-- End Joyride -->
 		
	</body>
</html>