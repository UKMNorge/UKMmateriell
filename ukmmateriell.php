<?php  
/* 
Plugin Name: UKM Materiell
Plugin URI: http://www.ukm-norge.no
Description: Generer meny-element og infoside for instrato og materiellbestilling
Author: UKM Norge / M Mandal 
Version: 2.0 
Author URI: http://www.ukm-norge.no
*/
require_once('UKM/inc/twig-admin.inc.php');

## HOOK MENU AND SCRIPTS
if(is_admin()) {
	add_action('UKM_admin_menu', 'UKMmateriell_menu',100);
}
add_action('network_admin_menu', 'UKMmateriell_network_menu');

function UKMmateriell_menu() {
	UKM_add_menu_page('resources','Materiell', 'Materiell', 'ukm_materiell', 'UKMmateriell', 'UKMmateriell', '//ico.ukm.no/kolli-menu.png',15);
	UKM_add_scripts_and_styles('UKMmateriell', 'UKMmateriell_bootstrap3');
}
function UKMmateriell() {
	global $blog_id;
	if($blog_id == 1) {
		require_once('ukmnorge.controller.php');
		echo TWIG('ukmnorge.twig.html', $infos , dirname(__FILE__));
	} else {
		require_once('dash.controller.php');
		echo TWIG('dash.twig.html', $infos , dirname(__FILE__));
	}
}

function UKMmateriell_network_menu() {
	$page = add_menu_page('Materiell', 'Materiell', 'superadmin', 'UKMmateriell_network','UKMmateriell_network', '//ico.ukm.no/kolli-menu.png',2100);
	add_action( 'admin_print_styles-' . $page, 'UKMmateriell_bootstrap3' );
}
function UKMmateriell_network() {
	require_once('dash.controller.php');
	echo TWIG('dash.twig.html', $infos , dirname(__FILE__));
}

function UKMmateriell_bootstrap3(){
	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');
}