<?php
	//@Title: Footer
	//@Author: AM + JC

	echo '<footer class="row">';
	echo '<div class="large-12 columns">';
	echo '<hr />';
	echo '<div class="row">';
	echo '<div class="large-6 columns">';
	echo '<p class="footer">&copy; Aperture Car Hire '.date("Y").'</p>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</footer>';
	echo '<script>'.$validation_js_code.'</script>'; // Parses javascript validation @Added by JC
	echo '<!-- Framework Scripts	-->';
	echo '<script src="',fetchdir($jsframework),'vendor/zepto.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.cookie.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.alerts.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.clearing.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.dropdown.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.forms.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.joyride.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.magellan.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.orbit.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.placeholder.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.reveal.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.section.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.tooltips.js"></script>';
	echo '<script src="',fetchdir($jsframework),'foundation/foundation.topbar.js"></script>';
	echo '<!-- End Framework Scripts	-->';
	echo '<!-- Start Foundation Framework -->';
	echo "<script> $(document).foundation(); </script>";
	
	// @Added by JC
	unset($_SESSION['Form']); // Remove error / success message and form inputs from the session after rendering the page
?>