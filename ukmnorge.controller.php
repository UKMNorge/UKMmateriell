<?php
require_once('class.materiell.php');

$fylker = new SQL("SELECT `id` FROM `smartukm_fylke`
				   WHERE `id` < 21");
$fylker = $fylker->run();
while( $r = mysql_fetch_assoc( $fylker ) ) {
	$fylkemateriell = new materiell( $r['id'] );
}


$infos = array('fylker' => $fylkemateriell);