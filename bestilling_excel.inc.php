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





exSheetName('LOKALKONTAKTER');

$row = 1;

exCell('A'.$row, 'Navn','bold');
exCell('B'.$row, 'Mønstring','bold');
exCell('C'.$row, 'E-post','bold');
exCell('D'.$row, 'Mobil','bold');
exCell('E'.$row, 'Tittel','bold');
foreach( $all_contacts as $place => $contacts ) {
	foreach($contacts as $mail => $conarr) {
		$conarr = array_unique($conarr);
		foreach($conarr as $con) {
			$row++;
			exCell('A'.$row, $con['name']);
			exCell('B'.$row, $con['place']);
			exCell('C'.$row, $con['mail']);
			exCell('D'.$row, $con['phone']);
			exCell('E'.$row, $con['title']);
		}
	}
}



$excel = new StdClass;
$excel->link = exWrite($objPHPExcel,'UKM_Materiellbestilling_'.date('Y-m-d_His'));
$excel->created = time();