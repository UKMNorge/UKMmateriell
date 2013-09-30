<?php
global $wpdb;
$current_user = wp_get_current_user();
$cuid = $current_user->ID;

$bruker = $wpdb->get_row("SELECT `b_id` FROM `ukm_brukere`
						  WHERE `wp_bid` = '".$cuid."'");	
$infos = array('user_id' => $bruker->b_id,
		   'site_type' => get_option('site_type'),
		   'season' => get_option('season'),
		   'deadline' => '21.10.2013'
		  );
