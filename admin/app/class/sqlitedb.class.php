<?
class sqliteDB {
	
	private $debug = true;

	public function __construct($dbFileName) {	
		$this->db = @sqlite_open( $dbFileName, 0666, $this->error );
		
		//if ($this->debug) echo $this->error."<br>";
		
		if 	(!$this->db) die('Imposible abrir la DB');
	}

	public function prepare($sql, $params = array()) {

		$output = array();

		foreach($params as $key => $param) {
			$output[$key] = sqlite_escape_string(str_replace('#{', '{', $param));
		}

		return vsprintf($sql, $output);
	}

	public function query($sql) {
		
		$q = sqlite_query($this->db, $sql, SQLITE_ASSOC, $this->error);
		
		if (!$q){		
			if ($this->debug) {
				echo '<pre>'.$sql.'</pre>';
				echo '<pre>'.$this->error.'</pre>';
			}
		}

		return $q;
	}
	
	public function array_query($sql) {
		$r = sqlite_array_query($this->db, $sql, SQLITE_ASSOC);
		return $r;
	}

	public function humanify($timestamp)
	{
		$timestamp = strtotime($timestamp);
	    $difference = time() - $timestamp;
	    $periods = array("seg", "min", "hora", "dia", "semana", "mes", "año", "decada");
	    $lengths = array(60, 60, 24, 7, 4.35, 12, 10);
	
	    if ($difference > 0) { // this was in the past
	        $ending = "hace";
	    } else { // this was in the future
	        $difference = -$difference;
	        $ending = "faltan";
	    }       
	    for($j = 0; $difference >= $lengths[$j]; $j++) {if ($lengths[$j] == 0) break; $difference /= $lengths[$j];}
	    $difference = round($difference);
	    if($difference != 1) $periods[$j].= ($j == 5) ? "es" : "s";
	    $text = "$ending $difference $periods[$j]";
	    return $text; 

	}

	public function friendly_seo_string($string, $separator = '-')
	{
		$string = trim($string);

		$string = strtolower($string); // convert to lowercase text

		// Recommendation URL: http://www.webcheatsheet.com/php/regular_expressions.php

		// Only space, letters, numbers and underscore are allowed

		$string = trim(preg_replace('/[^ A-Za-z0-9_]/', ' ', $string));
		/*
		"t" (ASCII 9 (0x09)), a tab.
		"n" (ASCII 10 (0x0A)), a new line (line feed).
		"r" (ASCII 13 (0x0D)), a carriage return. 
		*/

		//$string = ereg_replace("[ tnr]+", "-", $string);

		$string = str_replace(" ", $separator, $string);

		$string = preg_replace('/[ -]+/', '-', $string);

		return $string;
	}

	public function err() {
		echo $this->error;
	}


}