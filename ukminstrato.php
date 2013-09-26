<?php  
/* 
Plugin Name: UKM Instrato
Plugin URI: http://www.ukm-norge.no
Description: Generer meny-element og infoside for instrato
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/

## HOOK MENU AND SCRIPTS
if(is_admin()) {
	global $blog_id;
	if($blog_id != 1)
		add_action('admin_menu', 'UKMinstrato_menu',100);
}

function UKMinstrato_menu() {
	$page = add_menu_page('Designgenerator', 'Designgenerator', 'editor', 'UKMinstrato', 'UKMinstrato', 'http://ico.ukm.no/cloud-menu.png',198);
}

function UKMinstrato() {
	global $wpdb;
 	$current_user = wp_get_current_user();
 	$cuid = $current_user->ID;

	$bruker = $wpdb->get_row("SELECT `b_id` FROM `ukm_brukere`
								  WHERE `wp_bid` = '".$cuid."'");	

	echo TWIG('instrato.twig.html', array('user_id' => $bruker->b_id) , dirname(__FILE__));
}