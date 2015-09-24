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
	
	$sql = new SQL("SELECT `fylke_status` 
					FROM `wp_materiell_fylke`
					WHERE `wp_materiell_fylke`.`fylke_id` = '".get_option('fylke')."'");
	$fylkeinfos = $sql->run('field', 'fylke_status');
	
	switch( $fylkeinfos ) {
		case 'ikke_begynt':
			$MESSAGES[] = array('level' 	=> 'danger',
								'header'	=> 'Husk å bestille materiellpakker!',
								'body'		=> 'Du må registrere at du har startet å hente inn materiellpakkene! Gå til <a href="admin.php?page=UKMmateriellpakke">Skjema for materiellpakker</a> for å registrere dette', 
								);
			break;
		case 'innhenter_bestilling':
			$MESSAGES[] = array('level' 	=> 'danger',
								'header'	=> 'Husk materiellpakker!',
								'body'		=> '<a href="admin.php?page=UKMmateriellpakke">Skjema for materiellpakker</a> er åpent frem til '. date('d.m.Y', $stop)
								);
			break;
		case 'alt_er_klart':
			$MESSAGES[] = array('level' 	=> 'danger',
								'header'	=> 'Materiellpakker bestilt!',
								'body'		=> 'Vi har registrert din bestilling. Endringer kan gjøres frem til '. date('d.m.Y', $stop) .' i <a href="admin.php?page=UKMmateriellpakke">skjema for materiellpakker</a>', 
								);
			break;
	}
	
}