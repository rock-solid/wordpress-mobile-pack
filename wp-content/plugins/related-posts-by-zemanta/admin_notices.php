<?php

add_action('zem_rp_admin_notices', 'zem_rp_display_admin_notices');

function zem_rp_add_admin_notice($type = 'updated', $message = '') {
	global $zem_rp_admin_notices;
	
	if (strtolower($type) == 'updated' && $message != '') {
		$zem_rp_admin_notices[] = array('updated', $message);
		return true;
	}
	
	if (strtolower($type) == 'error' && $message != '') {
		$zem_rp_admin_notices[] = array ('error', $message);
		return true;
	}
	
	return false;
}

function zem_rp_display_admin_notices() {
	global $zem_rp_admin_notices;

	foreach ((array) $zem_rp_admin_notices as $notice) {
		echo '<div id="message" class="' . $notice[0] . ' below-h2"><p>' . $notice[1] . '</p></div>';
	}
}

