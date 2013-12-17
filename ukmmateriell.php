<?php  
/* 
Plugin Name: UKM Materiell
Plugin URI: http://www.ukm-norge.no
Description: Generer meny-element og infoside for instrato og materiellbestilling
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/

date_default_timezone_set('Europe/Oslo');

require_once('UKM/inc/twig-admin.inc.php');
require_once('UKM/monstring.class.php');

## HOOK MENU AND SCRIPTS
if(is_admin()) {
	global $blog_id;
	add_action('UKM_admin_menu', 'UKMmateriell_menu',100);
	
//	add_action( 'admin_enqueue_scripts', 'UKMmateriell_scriptsandstyles' );
}

function UKMmateriell_menu() {
	global $blog_id;
	
	UKM_add_menu_page('resources','Materiell', 'Materiell', 'editor', 'UKMmateriell', 'UKMmateriell', 'http://ico.ukm.no/kolli-menu.png',15);


	if(get_option('site_type') == 'fylke')
		UKM_add_submenu_page('UKMmateriell', 'Bestill pakke', 'Bestill pakke', 'editor', 'UKMmateriellpakke', 'UKMmateriellpakke');
		

		
	if($blog_id == 1) {
		UKM_add_submenu_page('UKMmateriell', 'Pakkeinnhold', 'Pakkeinnhold', 'editor', 'UKMpakkeinnhold', 'UKMpakkeinnhold');
		UKM_add_submenu_page('UKMmateriell', 'Opplag', 'Opplag', 'editor', 'UKMopplag', 'UKMopplag');
		UKM_add_submenu_page('UKMmateriell', 'Fordeling', 'Fordeling', 'editor', 'UKMfordeling', 'UKMfordeling');
		UKM_add_submenu_page('UKMmateriell', 'Instrato', 'Instrato', 'editor', 'UKMNinstrato', 'UKMNinstrato');

		UKM_add_scripts_and_styles('UKMpakkeinnhold', 'UKMmateriell_bootstrap');
		UKM_add_scripts_and_styles('UKMopplag', 'UKMmateriell_bootstrap');
		UKM_add_scripts_and_styles('UKMopplag', 'UKMmateriell_js_opplag');
		UKM_add_scripts_and_styles('UKMfordeling', 'UKMmateriell_bootstrap');
/*
//		add_action( 'admin_print_styles-' . $page, 'UKMmateriell_bootstrap' );
		add_action( 'admin_print_styles-' . $subpage1, 'UKMmateriell_bootstrap' );	

		add_action( 'admin_print_styles-' . $subpage2, 'UKMmateriell_bootstrap' );	
		add_action( 'admin_print_styles-' . $subpage2, 'UKMmateriell_js_opplag' );	

		add_action( 'admin_print_styles-' . $subpage3, 'UKMmateriell_bootstrap' );	
*/
	}
}

function UKMmateriell_bootstrap(){
	wp_enqueue_script('bootstrap_js');
	wp_enqueue_style('bootstrap_css');
}

function UKMNinstrato() {
	require_once('dash.controller.php');
	echo TWIG('dash.twig.html', $infos , dirname(__FILE__));
}

function UKMopplag() {
	require_once('opplag.controller.php');
	echo TWIG('opplag.twig.html', $infos , dirname(__FILE__));
}

function UKMfordeling() {
	require_once('fordeling.controller.php');
	echo TWIG('fordeling.twig.html', $infos , dirname(__FILE__));
}

function UKMmateriell_js_opplag() {
	wp_enqueue_script('UKMmateriell_opplag', plugin_dir_url( __FILE__ ) . 'js/opplag.materiellpakke.js');
}


function UKMpakkeinnhold() {
	require_once('opplag.controller.php');
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