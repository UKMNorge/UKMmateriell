<?php
global $wpdb;
$current_user = wp_get_current_user();
$cuid = $current_user->ID;

$bruker = $wpdb->get_row("SELECT `b_id`,`lock_email`, `b_email` FROM `ukm_brukere`
						  WHERE `wp_bid` = '".$cuid."'");	

if($bruker->lock_email == 'true' && strpos($bruker->b_email, '@urg.ukm.no') !== false) {
	$sql = "SELECT `b_id` 
			FROM `ukm_brukere`
			WHERE `b_email` = '". str_replace('@urg.ukm','@ukm', $bruker->b_email) ."'
			AND `lock_email` = 'true'";
			echo $sql;
	$bruker = $wpdb->get_row($sql);
}

$infos = array('user_id' => $bruker->b_id,
		   'user_key'	=> md5($bruker->b_id . UKM_INSTRATO_PEPPER),
		   'site_type' => get_option('site_type'),
		   'season' => get_option('season'),
		   'deadline' => '21.10.2013'
		  );
