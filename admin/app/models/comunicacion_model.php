<?
class comunicacionModel extends BaseModel
{

	public function getGrupos()
	{

		
		$q = $this->db->query("SELECT id, grupo FROM grupo_boletin");
		$r=array();
		while ($row = mysql_fetch_assoc($q)) {
			$r[]=$row;
		}
		return $r;		
	}

	public function getGrupo($id,&$msg){
		$sql = "SELECT id, grupo FROM grupo_boletin  WHERE id=%d";
		$sql = $this->db->prepare($sql, array($id));

		$q = $this->db->query($sql);
		if ($q){
			return mysql_fetch_assoc($q);
			
		}else{
			$msg=mysql_error();
			return false;
		}
	}

	public function editGrupo(&$msg) {


		if ((int)$_POST['id_grupo']== 0) {
			$sql = $this->db->prepare("INSERT INTO grupo_boletin (id, grupo)  VALUES (NULL, '%s');", array(
										$_POST['grupo']));
		} else {

			$sql = $this->db->prepare("UPDATE grupo_boletin SET
										grupo = '%s'										
										WHERE id = '%d'", array($_POST['grupo'],$_POST['id_grupo']));
		}


		$q = $this->db->query($sql);
		if ($q === true){
		 return true;
		}else{
			$msg=mysql_error();
			return false;
		}
	}

	public function getContactos($filtro, $tipo, $page, &$paginado)
	{


		if(!empty($filtro))
		$sql_filtro=" AND (nombre2 LIKE '%".$filtro."%' OR  p.nombre LIKE '%".$filtro."%'OR  p.apellido LIKE '%".$filtro."%'  )";
		
		$sql = "SELECT e.id, e.id_psicologo, e.email, e.nombre as nombre2, p.nombre, p.apellido FROM emails e LEFT JOIN  psicologos p ON e.id_psicologo=p.id WHERE 1=1 $sql_filtro";


      	$link = str_replace('%', '%%', Router::getSiteRoot().preg_replace('%/page/[0-9]+%i', '', Router::getPath()));
		$pg = new Paginado($sql, $this->db, $page, 30, $link.'/page/--1', $link);
		$sql = str_replace('{{date_format}}', '%d/%m/%Y ', $pg->limitSql());

		$paginado = $pg->buildPaginado();

		$q = $this->db->query($sql);
		$r = array();
		while ($row = mysql_fetch_assoc($q)) {
			if(is_null($row['id_psicologo'])) $row['nombre']=$row['nombre2'];
			else $row['nombre']=$row['nombre'].', '.$row['apellido'];
			$sql="SELECT g.grupo FROM grupo_boletin g,  rel_email_boletin eb WHERE  g.id=eb.id_grupo AND eb.id_email=%d",array($row['id']));

			$r[] = $row;			
		}

		return $r;
	}


}
