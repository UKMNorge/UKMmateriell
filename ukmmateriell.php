<?php  
/* 
Plugin Name: UKM Materiell
Plugin URI: http://www.ukm-norge.no
Description: Generer meny-element og infoside for instrato og materiellbestilling
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/

require_once('UKM/inc/twig-admin.inc.php');

## HOOK MENU AND SCRIPTS
if(is_admin()) {
	global $blog_id;
	if($blog_id != 1)
		add_action('admin_menu', 'UKMmateriell_menu',100);
		
	add_action( 'admin_enqueue_scripts', 'UKMmateriell_scriptsandstyles' );
}

function UKMmateriell_menu() {
	$page = add_menu_page('Materiell', 'Materiell', 'editor', 'UKMmateriell', 'UKMmateriell', 'http://ico.ukm.no/kolli-menu.png',125);
	
	if(get_option('site_type') == 'fylke')
		$subpage = add_submenu_page('UKMmateriell', 'Bestill pakke', 'Bestill pakke', 'editor', 'UKMmateriellpakke', 'UKMmateriellpakke');
}

function UKMmateriell() {
	require_once('dash.controller.php');
	echo TWIG('dash.twig.html', $infos , dirname(__FILE__));
}

function UKMmateriellpakke() {
	require_once('materiellpakke.controller.php');
	echo TWIG('materiellpakke.twig.html', $infos , dirname(__FILE__));
}

function UKMmateriell_scriptsandstyles() {
	wp_enqueue_style('ukmmateriell', plugin_dir_url( __FILE__ ) .'/css/ukmmateriell.css');
}