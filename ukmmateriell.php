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
}
add_action('network_admin_menu', 'UKMmateriell_network_menu');
#add_filter('UKMWPNETWDASH_messages', 'UKMmateriell_network_dash_messages',3);

if( get_option('site_type') == 'fylke' ) {
	add_filter('UKMWPDASH_messages', 'UKMmateriell_fylke_dash_messages');
}


function UKMmateriell_network_menu() {
#	$page = add_menu_page('Materiell', 'Materiell', 'superadmin', 'UKMmateriell','UKMmateriell', 'http://ico.ukm.no/kolli-menu.png',2100);
	$page = add_menu_page('Materiell', 'Materiell', 'superadmin', 'UKMNinstrato','UKMNinstrato', 'http://ico.ukm.no/kolli-menu.png',2100);
	add_action( 'admin_print_styles-' . $page, 'UKMmateriell_bootstrap' );
/*
	$subpage1 = add_submenu_page( 'UKMmateriell', 'Pakkeinnhold', 'Pakkeinnhold', 'superadministrator', 'UKMpakkeinnhold', 'UKMpakkeinnhold' );
	$subpage2 = add_submenu_page( 'UKMmateriell', 'Opplag', 'Opplag', 'superadministrator', 'UKMopplag', 'UKMopplag' );
	$subpage3 = add_submenu_page( 'UKMmateriell', 'Fordeling', 'Fordeling', 'superadministrator', 'UKMfordeling', 'UKMfordeling' );
	$subpage4 = add_submenu_page( 'UKMmateriell', 'Instrato', 'Instrato', 'superadministrator', 'UKMNinstrato', 'UKMNinstrato' );
	$subpage5 = add_submenu_page( 'UKMmateriell', 'Innstillinger', 'Innstillinger', 'superadministrator', 'UKMMateriellInnstillinger', 'UKMMateriellInnstillinger' );

	add_action( 'admin_print_styles-' . $page, 'UKMmateriell_bootstrap' );
	add_action( 'admin_print_styles-' . $subpage1, 'UKMmateriell_bootstrap' );
	add_action( 'admin_print_styles-' . $subpage2, 'UKMmateriell_bootstrap' );
	add_action( 'admin_print_styles-' . $subpage3, 'UKMmateriell_bootstrap' );
	add_action( 'admin_print_styles-' . $subpage4, 'UKMmateriell_bootstrap' );
	add_action( 'admin_print_styles-' . $subpage5, 'UKMmateriell_bootstrap' );

	add_action( 'admin_print_styles-' . $subpage2, 'UKMmateriell_js_opplag' );
	add_action( 'admin_print_styles-' . $subpage5, 'UKMmateriell_network_settings_scripts' );
*/
}

function UKMmateriell_network_dash_messages( $MESSAGES ) {
	require_once('network_settings_dashmessages.controller.php');
	return $MESSAGES;
}
function UKMmateriell_fylke_dash_messages( $MESSAGES ) {
	require_once('dashmessages.controller.php');
	return $MESSAGES;
}
function UKMmateriell_menu() {
	global $blog_id;
	
	UKM_add_menu_page('resources','Materiell', 'Materiell', 'superadmin', 'UKMmateriell', 'UKMmateriell', 'http://ico.ukm.no/kolli-menu.png',15);
	UKM_add_scripts_and_styles('UKMmateriell', 'UKMmateriell_bootstrap3');
	

	if(get_option('site_type') == 'fylke')
		UKM_add_submenu_page('UKMmateriell', 'Bestill pakke', 'Bestill pakke', 'superadmin', 'UKMmateriellpakke', 'UKMmateriellpakke');

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

function UKMmateriell_bootstrap(){
	wp_enqueue_script('bootstrap_js');
	wp_enqueue_style('bootstrap_css');
}

function UKMmateriell_bootstrap3(){
	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');
}

function UKMmateriell_network_settings_scripts() {
	wp_enqueue_script( 'UKMmateriell_network_settings_script', plugin_dir_url( __FILE__ ) .'ukmmateriell.js');
#	wp_enqueue_script('WPbootstrap3_js');
#	wp_enqueue_style('WPbootstrap3_css');
}


function UKMpakkeinnhold() {
	require_once('opplag.controller.php');
	echo TWIG('pakkeinnhold.twig.html', $infos , dirname(__FILE__));
}

function UKMMateriellInnstillinger() {
	$INFOS = array();
	require_once('network_settings.controller.php');
	echo TWIG('network_settings.twig.html', $INFOS, dirname(__FILE__));
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