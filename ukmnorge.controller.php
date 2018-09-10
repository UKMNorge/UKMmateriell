<?php
require_once('class.materiell.php');

$fylker = new SQL("SELECT `id` FROM `smartukm_fylke`
				   WHERE `id` < 21
				   ORDER BY `name` ASC");
$fylker = $fylker->run();
$mangler = 0;

while( $r = SQL::fetch( $fylker ) ) {
	$fylke = new materiell( $r['id'] );
	
	if(!$fylke->oppdatert)
		$mangler++;
	
	$fylkemateriell[] = $fylke;
}

$infos = array('fylker' => $fylkemateriell, 'ant_ikke_oppdatert' => $mangler);

if(UKM_HOSTNAME != 'ukm.dev') {
	require_once('bestilling_excel.inc.php');
}