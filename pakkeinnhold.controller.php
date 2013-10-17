<?php
require_once('class.materiell.php');

// BEREGN PAKKER 
$pakke_mini = new materiellpakke('mini');
$pakke_medium = new materiellpakke('medium');
$pakke_stor = new materiellpakke('stor');
$pakke_fylke = new materiellpakke('fylke');

$pakker = array('mini' 	=> $pakke_mini,
				'medium'=> $pakke_medium,
				'stor'	=> $pakke_stor,
				'fylke' => $pakke_fylke);
// Beregn antall av hver pakke
foreach($pakker as $index => $pakke)
	$pakker[$index]->antall();



// HENT ALLE PRODUKTER
$sql = new SQL("SELECT *
				FROM `wp_materiell_produkt`
				ORDER BY `produkt_navn` ASC");
$res = $sql->run();

$produkter = array();

while( $r = mysql_fetch_assoc( $res ) ) {
	$produkt = new produkt( $r['produkt_id'] );
	$produkt->behov($pakke_mini, $pakke_medium, $pakke_stor, $pakke_fylke);
	
	$produkter[] = $produkt;
}


$infos = array('produkter' => $produkter, 'pakker' => $pakker);