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
require_once('UKM/monstring.class.php');

## HOOK MENU AND SCRIPTS
if(is_admin()) {
	global $blog_id;
	add_action('admin_menu', 'UKMmateriell_menu',100);
	
	add_action( 'admin_enqueue_scripts', 'UKMmateriell_scriptsandstyles' );
}

function UKMmateriell_menu() {
	global $blog_id;
	
	$page = add_menu_page('Materiell', 'Materiell', 'editor', 'UKMmateriell', 'UKMmateriell', 'http://ico.ukm.no/kolli-menu.png',125);

	if(get_option('site_type') == 'fylke')
		$subpage = add_submenu_page('UKMmateriell', 'Bestill pakke', 'Bestill pakke', 'editor', 'UKMmateriellpakke', 'UKMmateriellpakke');
		
	if($blog_id == 1) {
		$subpage = add_submenu_page('UKMmateriell', 'Pakkeinnhold', 'Pakkeinnhold', 'editor', 'UKMpakkeinnhold', 'UKMpakkeinnhold');

		add_action( 'admin_print_styles-' . $page, 'UKMmateriell_bootstrap' );
		add_action( 'admin_print_styles-' . $subpage, 'UKMmateriell_bootstrap' );	
	}
}

function UKMmateriell_bootstrap(){
	wp_enqueue_script('bootstrap_js');
	wp_enqueue_style('bootstrap_css');
}

function UKMpakkeinnhold() {
	require_once('pakkeinnhold.controller.php');
	echo TWIG('pakkeinnhold.twig.html', $infos , dirname(__FILE__));
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

function UKMmateriellpakke() {
	require_once('materiellpakke.controller.php');
	echo TWIG('materiellpakke.twig.html', $infos , dirname(__FILE__));
}

function UKMmateriell_scriptsandstyles() {
	wp_enqueue_style('ukmmateriell', plugin_dir_url( __FILE__ ) .'css/ukmmateriell.css');
}