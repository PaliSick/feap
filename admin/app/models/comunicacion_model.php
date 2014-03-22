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



}
