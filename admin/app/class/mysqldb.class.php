<?
class MysqlDB {
	private $connection;
	private $lastQuery;
	private $cfg;
	private $transactional = false;

	public function __construct() {
		/*parent::__construct();*/

		$this->cfg = $this->loadCfgFromIni();

		$conexion = @mysql_connect($this->cfg['server'], $this->cfg['user'], $this->cfg['pass']) or die('Can\'t connect to database');
		mysql_select_db($this->cfg['database'], $conexion);

		$this->connection = $conexion;
		//return $this->connection;
	}

	/*public function __destruct()
	{
		$this->free($this->lastQuery);
		$this->close();
	}*/

	private function loadCfgFromIni() {
		$ini = parse_ini_file('config.ini', true);
		//print_r($ini[$_SERVER['SERVER_NAME']]);
		$domain = str_replace('www.', '', $_SERVER['SERVER_NAME']);
		return $ini[$domain];
	}

	public function prepare($sql, $params = array()) {

		$params = array_map('mysql_real_escape_string', array_map('stripslashes', $params));

		/*foreach($params as $index => $param) {
			$params[$index] = mysql_real_escape_string(($param), $this->connection);
		}*/

		return vsprintf($sql, $params);
	}

	public function query($sql) {

		$q = mysql_query($sql, $this->connection);

		if (!$q){
			if ($this->transactional) {
				throw new Exception("Failed query $sql. Error: ".mysql_error());
			}
			return false;
		}
		$this->lastQuery = $q;
		return $q;
	}
	
	public function begin_transaction()
	{
		$q1 = mysql_query("SET AUTOCOMMIT=0;", $this->connection);
		$q2 = mysql_query("START TRANSACTION;", $this->connection);
		if ($q1 && $q2) {
			$this->transactional = true;
			return true;
		} else throw new Exception("Failed to start transaction");
	}
	
	public function commit()
	{
		if ($this->transactional)
			$q = mysql_query("COMMIT;", $this->connection);
		else return false;
		return true;
	}
	
	public function rollback()
	{
		if ($this->transactional)
			$q = mysql_query("ROLLBACK;", $this->connection);
		else return false;
		return true;
	}

	public function free(&$query)
	{
		return @mysql_free_result($query);
	}

	public function getConnection()
	{
		return $this->connection;
	}

	public function close()
	{
		return mysql_close($this->connection);
	}

}