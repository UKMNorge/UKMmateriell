<?php
require_once('class.materiell.php');

$fylker = new SQL("SELECT `id` FROM `smartukm_fylke`
				   WHERE `id` < 21
				   ORDER BY `name` ASC");
$fylker = $fylker->run();
$mangler = 0;

while( $r = mysql_fetch_assoc( $fylker ) ) {
	$fylke = new materiell( $r['id'] );
	
	if(!$fylke->oppdatert)
		$mangler++;
	
	$fylkemateriell[] = $fylke;
}


$infos = array('fylker' => $fylkemateriell, 'ant_ikke_oppdatert' => $mangler);