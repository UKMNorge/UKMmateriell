<?php

$INFOS['season'] = get_site_option('season');
	
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	update_site_option('UKMmateriell_form_start', $_POST['start_datepicker'].' 00:00:00');
	update_site_option('UKMmateriell_form_stop', $_POST['stop_datepicker'].' 23:59:59');
}

if( isset( $_GET['action'] ) && $_GET['action'] = 'reset' ) {
	$sql = new SQLins('wp_materiell_fylke');
	$sql->update = true;
	$sql->wheres = " WHERE `fylke_status` != 'ikke_begynt'";
	$sql->add('fylke_status', 'ikke_begynt');
	
	$sql->run();
	$INFOS['reset'] = true;
}

$INFOS['opens'] = get_site_option('UKMmateriell_form_start');
$INFOS['closes'] = get_site_option('UKMmateriell_form_stop');
