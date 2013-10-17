<?php
global $objPHPExcel;
require_once('UKM/inc/excel.inc.php');
$objPHPExcel = new PHPExcel();

exorientation('portrait');

$objPHPExcel->getProperties()->setCreator('UKM Norges arrangørsystem');
$objPHPExcel->getProperties()->setLastModifiedBy('UKM Norges arrangørsystem');
$objPHPExcel->getProperties()->setTitle('UKM Materiellbestilling');
$objPHPExcel->getProperties()->setKeywords('UKM Materiellbestilling');

## Sett standard-stil
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);

####################################################################################
## OPPRETTER ARK
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->getTabColor()->setRGB('A0CF67');

exSheetName('Ark1');

// HEADERS
$row = 1;
exCell('A'.$row, 'orh_kundnr');
exCell('B'.$row, 'orh_navn');
exCell('C'.$row, 'orh_adr');
exCell('D'.$row, 'orh_pnr');
exCell('E'.$row, 'orh_pstd');
exCell('F'.$row, 'orh_att');
exCell('G'.$row, 'orh_meld');
exCell('H'.$row, 'ukm_fylke');
exCell('I'.$row, 'ukm_pakke');
exCell('J'.$row, 'ukm_miljo');
exCell('K'.$row, 'ukm_diplomer');
exCell('L'.$row, 'ukm_forsendelse');
exCell('M'.$row, 'ukm_kommentar');

// DATA
foreach( $infos['fylker'] as $fylke ) {
	$row++;
	
	exCell('A'.$row, $row-1);
	exCell('B'.$row, ucwords($fylke->adressat));
	exCell('C'.$row, ucwords($fylke->adresse));
	exCell('D'.$row, $fylke->postnr);
	exCell('E'.$row, ucwords($fylke->sted));
	exCell('F'.$row, ucwords($fylke->kontakt));
	exCell('G'.$row, $fylke->melding);
	exCell('H'.$row, $fylke->navn);
	exCell('I'.$row, 'fylke');
	exCell('J'.$row, 'nei');
	exCell('K'.$row, $fylke->ekstradiplom);
	exCell('L'.$row, $fylke->forsendelse);
	exCell('M'.$row, $fylke->kommentar);

	$fylke->load_kommuner();
	foreach( $fylke->kommuner as $kommune ) {
		$row++;
		
		$melding = $kommune->melding;		
		if($fylke->hvordansendes == 'direktealle') {
			$adresse = $kommune->adresse;
			$kontakt = $kommune->kontaktperson;
			$postnr  = $kommune->postnummer;
			$poststed= $kommune->sted;
		} else {
			$adresse = $fylke->adresse;
			$melding .= ' LEVERES: '.$fylke->navn;
			$kontakt = $fylke->kontakt;
			$postnr  = $fylke->postnr;
			$poststed= $fylke->sted;
		}		
		exCell('A'.$row, $row-1);
		exCell('B'.$row, ucwords($kommune->adressat));
		exCell('C'.$row, $adresse);
		exCell('D'.$row, $postnr);
		exCell('E'.$row, ucwords($poststed));
		exCell('F'.$row, ucwords($kontakt));
		exCell('G'.$row, $melding);
		exCell('H'.$row, $fylke->navn);
		exCell('I'.$row, $kommune->pakke);
		exCell('J'.$row, $kommune->miljo == 'papermill' ? 'Ja' : 'Nei');
		exCell('K'.$row, $kommune->diplomer);
		exCell('L'.$row, $fylke->forsendelse);
		exCell('M'.$row, '');
	}
}

// WRITE
$excel = new StdClass;
$excel->link = exWrite($objPHPExcel,'UKM_Materiellbestilling_'.date('Y-m-d_His'));
$excel->created = time();

$infos['excel'] = $excel;