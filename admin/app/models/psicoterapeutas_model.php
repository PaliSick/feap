<?
class psicoterapeutasModel extends BaseModel
{
	
	public function getPsicoterapeutas($estado,$filtro, $from, $to,  $page, &$paginado)
	{


		if(!empty($filtro))
		$sql_filtro=" AND (p.id LIKE '%".$filtro."%' OR  c.nombre LIKE '%".$filtro."%' OR  c.apellido LIKE '%".$filtro."%')";
		
		$sql = "SELECT p.id, p.nombre, p.apellido, p.localidad, pr.provincia FROM psicologos p LEFT JOIN provincias pr ON p.id_provincia=pr.id WHERE  1=1 $filtro";

		$pg = new Paginado($sql, $this->db, $page, 15, $link.'/page/--1', $link);
		$sql = $pg->limitSql();
		$paginado = $pg->buildPaginado();

		$q = $this->db->query($sql);
		$r = array();
		while ($row = mysql_fetch_assoc($q)) {
			$row['opciones']=$this->getOpcionesPsico($row['id']);
			$r[] = $row;			
		}

		return $r;
	}

	public function getOpcionesPsico($id)
	{
		$sql = $this->db->prepare("SELECT r.id_opcion, o.opcion FROM `rel_opciones` r LEFT JOIN opciones o ON r.id_opcion=o.id WHERE r.id_psicologo=%d", array($id));
		
		$q = $this->db->query($sql);
		
		while ($row = mysql_fetch_assoc($q)) {
			$r.=$row['opcion'].', ';
		}
		$r=substr($r,0,-2);
		return $r;
	}





}

?>