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
		$sql->add('fylke_status',$_POST['fylke_status']);
		$sql->add('fylke_tid', time());
		$sql->run();
	} elseif (isset($_POST['submitSteg2'])) {
		## LOOP ALLE KOMMUNE-ID-FELT
		for($i=0; $i<sizeof($_POST['kommune_id']); $i++) {

			$kommune = $_POST['kommune_id'][$i];
			$aktiv = $_POST['kommune_valg'][$i] == 'skalha';

			$test = new SQL("SELECT `kommune_id`
							 FROM `wp_materiell`
							 WHERE `kommune_id` = '#kommuneid'",
							 array('kommuneid' => $kommune));
			$test = $test->run();
			
			if(SQL::fetch($test)==0)
				$sql = new SQLins('wp_materiell');
			else
				$sql = new SQLins('wp_materiell', array('kommune_id'=>$_POST['kommune_id'][$i])); 
				
			$sql->add('kommune_id', $kommune);
			$sql->add('fylke_id', get_option('fylke'));
			$sql->add('kontaktperson', $_POST['kommune_kontaktperson'][$i]);
			$sql->add('adresse', $_POST['kommune_adresse'][$i]);
			$sql->add('adressat', $_POST['kommune_adressat'][$i]);
			$sql->add('postnummer', $_POST['kommune_postnr'][$i]);
			$sql->add('sted', $_POST['kommune_sted'][$i]);
			$sql->add('pakke', $_POST['kommune_valg_pakke'][$i]);
			$sql->add('miljo', $_POST['kommune_miljo'][$i]);
			$sql->add('diplomer', $_POST['kommune_ekstradiplom'][$i]);
			$sql->add('skalha', $aktiv ? $_POST['kommune_valg'][$i] : 'arrikke');
			$sql->add('aktiv', $aktiv ? 'Ja' : 'Nei');
			
			$sql->run();
		}
	}
	$steg = 2;
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
	$sql = new SQL("SELECT `mat`.*,
						   `kommune`.`name` AS `navn`,
						   `kommune`.`id` AS `kommune_id`
					FROM `smartukm_kommune` AS `kommune`
					LEFT JOIN `wp_materiell` AS `mat` ON (`mat`.`kommune_id` = `kommune`.`id`)
					WHERE `kommune`.`idfylke` = '".get_option('fylke')."'
					ORDER BY `kommune`.`name` ASC");
	$res = $sql->run();
	while ($row = mysql_fetch_assoc($res)) {
		foreach($row as $key => $val) {
			$row[$key] = utf8_encode($val);
		}
		// Deaktiverer deaktiverte (doh)
		if($row['aktiv']=='Nei')
			$row['skalha'] = 'arrikke';
		
		$monstring = new kommune_monstring($row['kommune_id'], get_option('season'));
		$monstring = $monstring->monstring_get();
		$row['delavmonstring'] = $monstring->get('pl_name');
		$kommuner[$row['kommune_id']] = $row;
	}

$infos = array('site_type' => get_option('site_type'),
		   'season' => get_option('season'),
		   'deadline' => get_site_option('UKMmateriell_form_stop'),
		   'fylke' => $fylkedata,
		   'kommuner' => $kommuner,
		   'steg' => $steg,
		  );
