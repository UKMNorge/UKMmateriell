<?php
global $wpdb;
$current_user = wp_get_current_user();
$cuid = $current_user->ID;

$bruker = $wpdb->get_row("SELECT `b_id`,`lock_email`, `b_email` FROM `ukm_brukere`
						  WHERE `wp_bid` = '".$cuid."'");	

if($bruker->lock_email == 'true' && strpos($bruker->b_email, '@urg.ukm.no') !== false) {
	$fylkenavn = str_replace('@urg.ukm','@ukm', $bruker->b_email);
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
	$infos['eksternbruker']['pass'] = $eksternbrukere[$fylkenavn.'-ekstern'];
}

$infos = array('user_id' => $bruker->b_id,
		   'user_key'	=> md5($bruker->b_id . UKM_INSTRATO_PEPPER),
		   'site_type' => get_option('site_type'),
		   'season' => get_option('season'),
		   'deadline' => '21.10.2013'
		  );
