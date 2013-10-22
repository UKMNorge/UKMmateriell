<?php

class produkt {
	public function __construct($produkt_id) {
		$qry = new SQL("SELECT * 
						FROM `wp_materiell_produkt`
						WHERE `produkt_id` = '#produkt_id'",
						array('produkt_id' => $produkt_id));
		$res = $qry->run('array');
		
		foreach($res as $key => $val) {
			$newkey = str_replace('produkt_', '', $key);
			if(is_string($val))
				$this->$newkey = utf8_encode($val);
			else
				$this->$newkey = $val;
		}
	}
	
	public function behov($mini=false,$medium=false,$stor=false,$fylke=false) {
		$this->mp_mini 		= $mini === false 	? new materiellpakke('mini') 	: $mini;
		$this->mp_medium 	= $medium === false ? new materiellpakke('medium')	: $medium;
		$this->mp_stor 		= $stor === false 	? new materiellpakke('stor')	: $stor;
		$this->mp_fylke 	= $fylke === false	? new materiellpakke('fylke')	: $fylke;
		
		$opplag_mini 	= $this->mp_mini->antall();
		$opplag_medium 	= $this->mp_medium->antall();
		$opplag_stor 	= $this->mp_stor->antall();
		$opplag_fylke	= $this->mp_fylke->antall();
		
		$this->behov = ( (int) $opplag_mini * (int) $this->pakke_mini )
					 + ( (int) $opplag_medium * (int) $this->pakke_medium )
					 + ( (int) $opplag_stor * (int) $this->pakke_stor )
					 + ( (int) $opplag_fylke * (int) $this->pakke_fylke )
					 ;
					 
					 
		if($this->id == 25) {
			// ALLE EKSTRA DIPLOMER
			$fylkediplom = new SQL("SELECT SUM(`fylke_ekstradiplom`) AS `antall`
									FROM `wp_materiell_fylke`");
			$fylkediplom = (int) $fylkediplom->run('field','antall');
			$this->behov += $fylkediplom;
		}
		
		if($this->id == 24) {
			$lokaldiplom = new SQL("SELECT SUM(`diplomer`) AS `antall`
									FROM `wp_materiell`");
			$lokaldiplom = (int) $lokaldiplom->run('field','antall');
			$this->behov += $lokaldiplom;
		}
	}
}

class materiellpakke {
	var $antall = false;

	public function __construct( $pakke_type ) {
		$this->type = $pakke_type;
	}
	
	public function antall() {
		if(!$this->antall) {
			if($this->type == 'fylke') {
				$this->antall = 19;
			} else {
				$sql = new SQL("SELECT `pakke`, COUNT(`kommune_id`) AS `count`
								FROM `wp_materiell`
								WHERE `skalha` = 'skalha'
								AND `pakke` = '#pakke'
								GROUP BY `pakke`",
								array('pakke' => $this->type));
				$this->antall = $sql->run('field','count');
			}
		}
		return $this->antall;
	}
}


class materiell {
	
	private function _season() {
		$this->season = get_option('season');
	}

	public function __construct($fylke_id) {
		$this->_season();
		$qry = new SQL("SELECT *
						FROM `wp_materiell_fylke`
						WHERE `fylke_id` = '#fylke_id'",
						array('fylke_id' => $fylke_id));
		$res = $qry->run('array');
		foreach($res as $key => $val) {
			$newkey = str_replace('fylke_','', $key);
			if(is_string($val))
				$this->$newkey = utf8_encode($val);
			else
				$this->$newkey = $val;
		}
		
		// Oppdatert means ready (doh?)
		$this->oppdatert = $this->status == 'alt_er_klart';

		// Introduced in mid-order process 2013, no reason to bug them once again to tick the box
		if(date('Y') == '2013' && $this->status == 'ikke_begynt') {
			$this->oppdatert = $this->tid < mktime( 0, 0, 0, 8, 1, date("Y"));
			$this->status = 'Ukjent';
		}
		$this->status = str_replace('_', ' ', $this->status);
		
		// FORSENDELSE
		if( $this->hvordansendes == 'direktealle' )
			$this->forsendelse = 'Alle pakkene sendes direkte til kommunene';
		elseif( $this->hvordansendes == 'allesendes' )
			$this->forsendelse = 'Alle sendes til fylkesadressen med fylkespakken';
		else
			$this->forsendelse = 'Ukjent';
		
		// CALC EXCEL MESSAGE
		$this->melding = 'Fylkespakke';
		if($this->ekstradiplom > 0)
			$this->melding .= ' (+'. $this->ekstradiplom .' diplomer)';
	}
	
	
	public function load_kommuner() {
		$qry = new SQL("SELECT *
						FROM `wp_materiell`
						WHERE `fylke_id` = '#fylke_id'",
						array('fylke_id' => $this->id));
		$res = $qry->run();
		
		while($r = mysql_fetch_assoc( $res ) ) {
			$kommune = array();
			foreach($r as $key => $val) {
				$newkey = str_replace('kommune_','', $key);
				if(is_string($val))
					$kommune[$newkey] = utf8_encode($val);
				else
					$kommune[$newkey] = $val;
			}
			
			$kommune = (object) $kommune;
			
			$kommune->melding = ucwords($kommune->pakke)
							  . ($kommune->miljo == 'papermill' ? 'MILJØ' : ' ')
							  . 'pakke';

			if($kommune->diplomer > 0)
				$kommune->melding .= ' (+'. $kommune->diplomer .' diplomer)';
			
			if(empty($kommune->adressat))
				$kommune->adressat = $kommune->navn;
			
			
			$this->kommuner[] = $kommune;
		}
	}
}