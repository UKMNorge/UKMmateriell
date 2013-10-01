<?php

// FYLKE-INFOS
$sql = new SQL("SELECT * FROM wp_materiell_fylke WHERE wp_materiell_fylke.fylke_id = '".get_option('fylke')."'");
$fylkeinfos = $sql->run('array');
foreach($res as $key => $val) {
	$newkey = str_replace('fylke_','', $key);
	$fylkedata[$newkey] = utf8_encode($val);
}
$infos = array('site_type' => get_option('site_type'),
		   'season' => get_option('season'),
		   'deadline' => '21.10.2013'
		   'fylke' => $fylkedata;
		  );
