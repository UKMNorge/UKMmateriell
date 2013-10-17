<?php
$fylker = new SQL("SELECT `id` FROM `smartukm_fylke`
				   WHERE `id` < 21");
$fylker = $fylker->run();
while( $r = mysql_fetch_assoc( $fylker ) ) {
	$fylkemateriell = new materiell( $r['id'] );
}


$INFOS = array('fylker' => $fylkemateriell);




class materiell {
	
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
		
		if( strpos($this->tid, ($this->season-1).'-') !== 0) {
			$this->oppdatert = false;
		}
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
				if(is_string($val))
					$kommune[$key] = utf8_encode($val);
				else
					$kommune[$key] = $val;
			}
			
			$this->kommuner[] = $kommune;
		}
	}
}