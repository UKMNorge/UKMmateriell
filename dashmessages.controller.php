<?php

$open = get_site_option('UKMmateriell_form_start');
$close = get_site_option('UKMmateriell_form_stop');
if( is_string( $open ) ) {
	$start = strtotime( $open );
	$open = $start < time();
}
if( is_string( $close ) ) {
	$stop = strtotime( $close );
	$close = $stop < time();
}

$is_open = ($open == true && $close == false);

if( $is_open ) {
	$MESSAGES[] = array('level' 	=> 'danger',
						'header'	=> 'Husk å bestille materiellpakker!',
						'body'		=> '<a href="admin.php?page=UKMmateriellpakke">Skjema for materiellpakker</a> er åpent frem til '. date('d.m.Y', $stop)
						);

}