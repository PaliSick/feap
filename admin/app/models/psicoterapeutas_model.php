<?
class psicoterapeutasModel extends BaseModel
{
	
	public function getPsicoterapeutas($estado, $filtro, $miembro, $prov, $seccion,  $page, &$paginado, $excel)
	{

		$filter=false;
		if(!empty($filtro)){
			$sql_filtro=" AND (p.nombre LIKE '%".$filtro."%' OR  p.apellido LIKE '%".$filtro."%')";
			$filter=true;
		}
		
		if($estado>0){
			if($estado==2) $estado=0;
			$sql_estado=" AND p.estado=".$estado;
			$filter=true;
		}
		if($miembro>0){
			$sql_miembro=" AND rm.id_psicologo=p.id AND rm.id_miembro=".$miembro;
			$sql_fromM=", rel_miembros rm";
			$filter=true;
		}
		if($seccion>0){
			$sql_seccion=" AND rs.id_psicologo=p.id AND rs.id_seccion=".$seccion;
			$sql_fromS=", rel_seccion rs";
			$filter=true;
		}
		if($prov>0){
			$sql_prov=" AND p.id_provincia=".$prov;
			$filter=true;
		}
		if($filter){
			$sql = "SELECT p.id, p.nombre, p.apellido, p.localidad, p.id_provincia FROM psicologos p ".$sql_fromS.$sql_fromM."  WHERE  1=1 $sql_filtro $sql_estado $sql_miembro $sql_seccion $sql_prov";
		}else{
			$sql = "SELECT p.id, p.nombre, p.apellido, p.localidad, pr.provincia FROM psicologos p LEFT JOIN provincias pr ON p.id_provincia=pr.id";
		}
		if($excel==0){
			$link = str_replace('%', '%%', Router::getSiteRoot().preg_replace('%/page/[0-9]+%i', '', Router::getPath()));
			$pg = new Paginado($sql, $this->db, $page, 15, $link.'/page/--1', $link);
			$sql = $pg->limitSql();
			$paginado = $pg->buildPaginado();
		}
		$q = $this->db->query($sql);
		$r = array();
		while ($row = mysql_fetch_assoc($q)) {
			$row['opciones']=$this->getOpcionesPsico($row['id']);
			if($filter){
				$row['provincia']=$this->getProvicia($row['id_provincia']);
			}
			$r[] = $row;			
		}

		return $r;
	}

	public function getProvicia($id)
	{
		$sql = $this->db->prepare("SELECT provincia FROM `provincias` WHERE id=%d", array($id));
		$q = $this->db->query($sql);
		$row = mysql_fetch_assoc($q);
		return $row['provincia'];
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

	public function getMiembros()
	{
		$sql ="SELECT id, nombre FROM miembros";
		$q = $this->db->query($sql);
		$r = array();
		while ($row = mysql_fetch_assoc($q)) {		
				$r[] = $row;
		}

		return $r;			
	}

	public function getSecciones()
	{
		$sql ="SELECT id, seccion FROM secciones";
		$q = $this->db->query($sql);
		$r = array();
		while ($row = mysql_fetch_assoc($q)) {		
				$r[] = $row;
		}

		return $r;		
	}
	public function getOpciones()
	{
		$sql ="SELECT id, opcion FROM opciones WHERE deleted=0";
		$q = $this->db->query($sql);
		$r = array();
		while ($row = mysql_fetch_assoc($q)) {		
				$r[] = $row;
		}

		return $r;		
	}
	public function getProvincias()
	{
		$sql ="SELECT id, provincia FROM provincias";
		$q = $this->db->query($sql);
		$r = array();
		while ($row = mysql_fetch_assoc($q)) {		
				$r[] = $row;
		}

		return $r;			
	}

}

?>