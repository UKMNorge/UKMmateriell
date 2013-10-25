<?php
require_once('class.materiell.php');

$fylker = new SQL("SELECT `id` FROM `smartukm_fylke`
				   WHERE `id` < 21
				   ORDER BY `name` ASC");
$fylker = $fylker->run();
$mangler = 0;
$fylkedata = array();

while( $r = mysql_fetch_assoc( $fylker ) ) {
	$fylke = new materiell( $r['id'] );
	
	$fylkedata = array();
	$fylkedata['ekstra']['fylkediplom'] += (int) $fylke->ekstradiplom;
	$fylkedata['navn'] = $fylke->navn;
	
	$fylke->load_kommuner();
	foreach( $fylke->kommuner as $kommune ) {
		$pakke = strtolower( str_replace( array('-',' '),
										  '',
										  $kommune->pakke
										 )
							);
		$fylkedata['pakker'][$pakke] += 1;
		$fylkedata['ekstra']['lokaldiplom'] += (int) $kommune->diplomer;

		if( $kommune->hvordansendes == 'direktealle' )
			$fylkedata['levering'] = $kommune->forsendelse;
	}	
	$fylkemateriell[] = $fylkedata;
}

var_dump( $fylkemateriell );

$infos = array('fylker' => $fylkemateriell);