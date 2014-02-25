<?
class BaseModel {

	protected $db;

	public function __construct($connectDb = true) {
		if ($connectDb) {
			$this->db = new MysqlDB();
		}
	}

	public function seo_string($string, $separator = '-')
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

}