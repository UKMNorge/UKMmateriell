<?php
$INFOS['season'] = get_site_option('season');

$start = get_site_option('UKMmateriell_form_start');
$stop = get_site_option('UKMmateriell_form_stop');

$startdate = strtotime( $start );
$stopDate = strtotime( $stop );

$startIsOld = date("Y", $startdate ) != $INFOS['season']-1;
$stopIsOld = date("Y", $stopDate ) != $INFOS['season']-1;

if( !$start || !$stop || $dateIsOld || $stopIsOld ) {		
	$MESSAGES[] = array('level' 	=> 'alert-danger',
						'module'	=> 'Materiell',
						'header'	=> 'Bestillingsskjema må konfigureres',
						'body' 		=> 'Rett problemet under innstillinger-siden på materiell',
						'link'		=> 'admin.php?page=UKMMateriellInnstillinger'
						);
}