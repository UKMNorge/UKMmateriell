<?php
global $wpdb;
$current_user = wp_get_current_user();
$cuid = $current_user->ID;

$bruker = $wpdb->get_row("SELECT `b_id`,`lock_email`, `b_email` FROM `ukm_brukere`
						  WHERE `wp_bid` = '".$cuid."'");	

$open = get_site_option('UKMmateriell_form_start');
$close = get_site_option('UKMmateriell_form_stop');
if( is_string( $open ) ) {
	$start = strtotime( $open );
	$open = $start < time();
}
if( is_string( $close ) ) {
	$stop = strtotime( $close );
	$close = $stop < time();
}

$is_open = ($open == true && $close == false);

$infos = array('user_id' => $bruker->b_id,
		   'user_key'	=> md5($bruker->b_id . UKM_INSTRATO_PEPPER),
		   'site_type' => get_option('site_type'),
		   'season' => get_option('season'),
		   'deadline' => get_site_option('UKMmateriell_form_stop'),
		   'is_open' => $is_open,
		   'start' => $start,
		   'stop' => $stop,
		  );

// Hvis dette er en deltakerbruker
$qry = new SQL("SELECT `p_id`, `username`, `email`
					FROM `ukm_wp_deltakerbrukere`
					WHERE `wp_id` = #cuid", array('cuid' => $cuid));
#echo $qry->debug();
$b = $qry->run('array');
if ( $b ) {
	$bruker_id = str_pad($b['p_id'], 8, "1000000000", STR_PAD_LEFT);
	$infos['user_id'] = $bruker_id;
	$infos['user_key'] = md5($bruker_id.UKM_INSTRATO_PEPPER);
}


// Dropp eksternbrukere for UKM Norge hvis lokalside
if( get_option('site_type') == 'fylke' ) {
	if( $cuid == 1 ) {
		$infos['eksternbruker']['navn'] = 'Funksjonen er kun tilgjengelig når logget inn som fylke';
		$infos['eksternbruker']['pass'] = '';
	} else {
		if($bruker->lock_email == 'true' && strpos($bruker->b_email, '@urg.ukm.no') !== false) {
			$bruker->lock_email = str_replace('@urg.ukm.no','@ukm.no', $bruker->b_email);
		}
		
		if($bruker->lock_email == 'true' && strpos($bruker->b_email, '@ukm.no') !== false) {
			$fylkenavn = str_replace('@ukm.no','', $bruker->b_email);
			$bruker = $wpdb->get_row("SELECT `b_id` 
					FROM `ukm_brukere`
					WHERE `b_email` = '". $fylkenavn ."'
					AND `lock_email` = 'true'");
			
			$eksternbrukere = array('finnmark-ekstern' => 'postbud50',
									'troms-ekstern' => 'alke13',
									'nordland-ekstern' => 'dramatisere60',
									'nord-trondelag-ekstern' => 'tusenarig42',
									'sor-trondelag-ekstern' => 'trengende36',
									'moreogromsdal-ekstern' => 'opsjon72',
									'sognogfjordane-ekstern' => 'bindestrek63',
									'hordaland-ekstern' => 'deviasjon69',
									'rogaland-ekstern' => 'laken15',
									'vest-agder-ekstern' => 'harborste22',
									'aust-agder-ekstern' => 'kjorebane17',
									'telemark-ekstern' => 'blindgate24',
									'vestfold-ekstern' => 'bilradio81',
									'buskerud-ekstern' => 'utstrale62',
									'oslo-ekstern' => 'delfinfisk76',
									'ostfold-ekstern' => 'injurie94',
									'akershus-ekstern' => 'ventilatorhette79',
									'oppland-ekstern' => 'tilfredsstille52',
									'hedmark-ekstern' => 'aksjoner44' );
			$infos['eksternbruker']['navn'] = $fylkenavn.'-ekstern';
			$infos['eksternbruker']['pass'] = $eksternbrukere[strtolower($fylkenavn).'-ekstern'];
		}
	}
}