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
	$fylkedata['pakker'] = array('mini' => 0,
								 'medium' => 0,
								 'stor' => 0,
								 'total' => 0);
	if( $fylke->hvordansendes == 'allesendes' )
		$fylkedata['levering'] = $kommune->forsendelse;
	
	$fylke->load_kommuner();
	foreach( $fylke->kommuner as $kommune ) {
		$pakke = strtolower( str_replace( array('-',' '),
										  '',
										  $kommune->pakke
										 )
							);
		$fylkedata['pakker'][$pakke] += 1;
		$fylkedata['ekstra']['lokaldiplom'] += (int) $kommune->diplomer;

	}
	
	$fylkedata['pakker']['total'] =   (int) $fylkedata['pakker']['mini']
									+ (int) $fylkedata['pakker']['medium']
									+ (int) $fylkedata['pakker']['stor']
									+ 1;
	
	$fylkemateriell[] = $fylkedata;
}

$infos = array('fylker' => $fylkemateriell);