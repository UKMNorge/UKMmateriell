<?php

$steg = 1;

if($_SERVER['REQUEST_METHOD']==='POST') {
	// LAGRE STEG 1
	if(isset($_POST['fylke_kontaktperson'])) {
		$tid = time();
		$sql = new SQLins('wp_materiell_fylke', array('fylke_id'=>get_option('fylke')));
		$sql->add('fylke_kontakt',$_POST['fylke_kontaktperson']);
		$sql->add('fylke_epost',$_POST['fylke_kontakt_epost']);
		$sql->add('fylke_mobil',$_POST['fylke_kontakt_mobil']);
		$sql->add('fylke_adressat',$_POST['fylke_adressat']);
		$sql->add('fylke_adresse',$_POST['fylke_adresse']);
		$sql->add('fylke_postnr',$_POST['fylke_postnr']);
		$sql->add('fylke_sted',$_POST['fylke_sted']);
		$sql->add('fylke_ekstradiplom',$_POST['fylke_diplomer']);
		$sql->add('fylke_hvordansendes',$_POST['fylke_sendes']);
		$sql->add('fylke_sendesdirekte',$_POST['fylke_sendesdirekte']);
		$sql->add('fylke_kommentarer',$_POST['fylke_kommentarer']);
		$sql->add('fylke_tid', time());
		$sql->run();
		
		$steg = 2;
	} elseif (isset($_POST['submitSteg2'])) {
		echo '<pre>';
		var_dump($_POST);
		echo '</pre>';
	}
}

// HENT DATA
	// FYLKE-SKJEMA (STEG 1)
	$sql = new SQL("SELECT * 
					FROM `wp_materiell_fylke`
					WHERE `wp_materiell_fylke`.`fylke_id` = '".get_option('fylke')."'");
	$fylkeinfos = $sql->run('array');
	foreach($fylkeinfos as $key => $val) {
		$newkey = str_replace('fylke_','', $key);
		$fylkedata[$newkey] = utf8_encode($val);
	}
	// KOMMUNE-SKJEMA (STEG 2)
	$kommuner = array();
	$sql = new SQL("SELECT *, `kommune_navn` AS `navn`
					FROM `wp_materiell`
					WHERE `fylke_id` = '".get_option('fylke')."'
					ORDER BY `navn` ASC");
	$res = $sql->run();
	while ($row = mysql_fetch_assoc($res)) {
		foreach($row as $key => $val) {
			$row[$key] = utf8_encode($val);
		}
		$kommuner[$row['kommune_id']] = $row;
	}

$infos = array('site_type' => get_option('site_type'),
		   'season' => get_option('season'),
		   'deadline' => '21.10.2013',
		   'fylke' => $fylkedata,
		   'kommuner' => $kommuner,
		   'steg' => $steg,
		  );
