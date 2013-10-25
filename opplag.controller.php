<?php
require_once('class.materiell.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	if( $_POST['save_what'] == 'opplag') {
		foreach($_POST as $key => $val) {
			if( strpos($key, 'produkt_') !== false ) {
				$infos = explode('_', $key);
				$produkt = $infos[1];
				$field = $infos[2];
				
				$sql = new SQLins('wp_materiell_produkt', array('produkt_id' => $produkt));
				$sql->add($field, $val);
				$sql->run();
			}
		}
	}
	
	if( $_POST['save_what'] == 'pakkeinnhold') {
		foreach($_POST['produkt'] as $id => $info) {
			$sql = new SQLins('wp_materiell_produkt', array('produkt_id' => $id));
			$sql->add('pakke_mini', $info['pakke_mini']);
			$sql->add('pakke_medium', $info['pakke_medium']);
			$sql->add('pakke_stor', $info['pakke_stor']);
			$sql->add('pakke_fylke', $info['pakke_fylke']);
			$sql->run();
		}
	}
}


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


// ALLE EKSTRA DIPLOMER
$fylkediplom = new SQL("SELECT SUM(`fylke_ekstradiplom`) AS `antall`
						FROM `wp_materiell_fylke`
						AND `fylke_id` < '21'");
$fylkediplom = (int) $fylkediplom->run('field','antall');

$lokaldiplom = new SQL("SELECT SUM(`diplomer`) AS `antall`
						FROM `wp_materiell`
						WHERE `skalha` = 'skalha'
						AND `fylke_id` < '21'");
$lokaldiplom = (int) $lokaldiplom->run('field','antall');


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

$infos = array('produkter' => $produkter,
			   'pakker' => $pakker,
			   'ekstra_diplomer' => array( 'lokal' => $lokaldiplom, 'fylke' => $fylkediplom )
			  );