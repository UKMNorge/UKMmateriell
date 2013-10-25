<?php
require_once('class.materiell.php');

$fylker = new SQL("SELECT `id` FROM `smartukm_fylke`
				   WHERE `id` < 21
				   ORDER BY `name` ASC");
$fylker = $fylker->run();
$mangler = 0;
$fylkedata = array();

$total['pakker']['mini'] = 0;
$total['pakker']['medium'] = 0;
$total['pakker']['stor'] = 0;
$total['pakker']['total'] = 0;

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
		$fylkedata['levering'] = $fylke->forsendelse;
	
	$fylke->load_kommuner();
	foreach( $fylke->kommuner as $kommune ) {
		if( $kommune->skalha != 'skalha')
			continue;
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

	$total['pakker']['mini']		+= (int) $fylkedata['pakker']['mini'];
	$total['pakker']['medium']		+= (int) $fylkedata['pakker']['medium'];
	$total['pakker']['stor']		+= (int) $fylkedata['pakker']['stor'];
	$total['pakker']['total']		+= (int) $fylkedata['pakker']['total'];
	$total['ekstra']['lokaldiplom'] += (int) $fylkedata['ekstra']['lokaldiplom'];
	$total['ekstra']['fylkediplom'] += (int) $fylkedata['ekstra']['fylkediplom'];
	$fylkemateriell[] = $fylkedata;
}

$infos = array('fylker' => $fylkemateriell);