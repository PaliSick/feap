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

	public function getContactos($filtro='', $email='', $grupo, $page, &$paginado)
	{


		if(!empty($filtro))
		$sql_filtro=" AND (e.nombre LIKE '%".$filtro."%' OR  p.nombre LIKE '%".$filtro."%'OR  p.apellido LIKE '%".$filtro."%'  )";
		
		if(!empty($email))
		$sql_filtro.=" AND (e.email LIKE '%".$email."%'  )";

		if($grupo>0)
		$sql_filtro.=$this->db->prepare(" AND (SELECT id FROM rel_email_boletin WHERE id_email=e.id AND id_grupo=%d)", array($grupo));

		$sql = "SELECT e.id, e.id_psicologo, e.email, e.nombre as nombre2, p.nombre, p.apellido FROM emails e LEFT JOIN  psicologos p ON e.id_psicologo=p.id  WHERE 1=1   $sql_filtro";

      	$link = str_replace('%', '%%', Router::getSiteRoot().preg_replace('%/page/[0-9]+%i', '', Router::getPath()));
		$pg = new Paginado($sql, $this->db, $page, 30, $link.'/page/--1', $link);


		$paginado = $pg->buildPaginado();

		$q = $this->db->query($sql);
		$r = array();
		while ($row = mysql_fetch_assoc($q)) {
			if(is_null($row['id_psicologo'])) $row['nombre']=$row['nombre2'];
			else $row['nombre']=$row['nombre'].', '.$row['apellido'];
			$sql=$this->db->prepare("SELECT g.grupo FROM grupo_boletin g,  rel_email_boletin eb WHERE  g.id=eb.id_grupo AND eb.id_email=%d",array($row['id']));
			$q2 = $this->db->query($sql);
			while ($row2 = mysql_fetch_assoc($q2)) {
				$row['grupo'].=$row2['grupo'].', ';
			}
			$row['grupo']=substr($row['grupo'], 0,-2);
			if($row['id_psicologo']>0)
				$row['url']='href="'.Router::getSiteRoot().'/psicoterapeutas/news/'.$row['id_psicologo'].'#grupos" class="edit"';
			else
				$row['url']='href="'.Router::getSiteRoot().'/comunicacion/edit-contacto/'.$row['id'].'" class="edit editar"';

			$r[] = $row;			
		}

		return $r;
	}
	public function editContacto(&$msg) {

		//Primero me fijo que no exista, si existe mando error (no replace porque puedo borrar )
		if ((int)$_POST['grupo']== 0) {
			$sql=$this->db->prepare("SELECT id FROM emails WHERE email='%s'",array(trim($_POST['email'])));
			$q=$this->db->query($sql);
			if(mysql_num_rows($q)>0){
				$msg="El email ya se encuentra registrado";
				return false;
			}
		}
		if ($this->db->begin_transaction()){
		}else {
			return false;
		}
		if ((int)$_POST['grupo']== 0) {
			$sql = $this->db->prepare("INSERT INTO emails (id, nombre, email)  VALUES (NULL, '%s', '%s');", array(
										$_POST['nombre'],trim($_POST['email']) ));
			
		} else {

			$sql = $this->db->prepare("UPDATE emails SET
										nombre = '%s', email='%s'										
										WHERE id = '%d'", array($_POST['nombre'],$_POST['email'],$_POST['id']));
			$id=$_POST['id'];
		}


		$q = $this->db->query($sql);

		if ((int)$_POST['grupo']== 0) 
			$id=mysql_insert_id() ;
		else			
			$id=$_POST['id'];

		if ($q === true){
			$sql=$this->db->prepare("DELETE FROM rel_email_boletin WHERE id_email=%d",array($_POST['id']));
		 	$this->db->query($sql);
			$sql = $this->db->prepare("INSERT INTO rel_email_boletin (id, id_email, id_grupo)  VALUES (NULL, '%d', '%d');", array(
									$id,$_POST['grupo']));	

			$q = $this->db->query($sql);
			if ($q === true)
				if ($this->db->commit()) {
					return true;
				} else {
					$this->db->rollback();
					$msg="Error insertando las relaciones.";
					return false;
				}								 	
		 		
			else{
				$msg=mysql_error();
				return false;
			}
		}else{
			$msg=mysql_error();
			return false;
		}
	}

	public function getContacto($id,&$msg){
		$sql = "SELECT e.id,  e.email, e.nombre, b.id_grupo  FROM emails e LEFT JOIN rel_email_boletin b ON e.id=b.id_email WHERE e.id=%d  ";
		$sql = $this->db->prepare($sql, array($id));
		$q = $this->db->query($sql);
		if ($q)
			return mysql_fetch_assoc($q);		
		else{
			$msg=mysql_error();
			return false;
		}
	}

	public function insertLetters(&$msg)
	{

		//Start the transaction
		if ($this->db->begin_transaction()){
		}else {
			$msg="Error en la BD, intente nuevamente más tarde";
			return false;
		}
		//Validamos algunos campos
		if (empty($_POST['name']))	{$msg = 'Ingrese el nombre del boletín'; 	return false;}
		if (empty($_POST['subject']))	{$msg = 'Ingrese el asunto'; return false;}
		if (empty($_POST['fecha_envio']))	{$msg = 'Ingrese el asunto'; return false;}
		($_POST['id_grupo']==0)? $id_grupo='NULL':$id_grupo=$_POST['id_grupo'] ;
		if (isset($_POST['id']) && (int)$_POST['id'] != 0) {
			$sql="UPDATE letters SET name='%s', subject='%s', body='%s', fecha=NOW() WHERE id=%d";
			$sql=$this->db->prepare($sql, array($_POST['name'], $_POST['subject'], $_POST['ebody'],  $_POST['id'] ));
			try {
				$this->db->query($sql);
			} catch (Exception $e) {
				$this->db->rollback();
				$msg='Error updateando letters: '.mysql_error();
				return false;
			}
			$sql="UPDATE envios SET id_grupo=%s, fecha_ini='%s' WHERE id_letter=%d";
			$sql=$this->db->prepare($sql, array($id_grupo,$_POST['fecha_envio'],$_POST['id']));
			
			try {
				$this->db->query($sql);
			} catch (Exception $e) {
				$this->db->rollback();
				$msg='Error  updateando envios:'.mysql_error();
				return false;
			}		
		}else{

			$sql="INSERT INTO letters (`id`, `name`, `subject`, `body`, `fecha`, `estado`) VALUES (NULL, '%s', '%s', '%s', NOW(), 1)";
			$sql=$this->db->prepare($sql, array($_POST['name'], $_POST['subject'], $_POST['ebody'] ));
			try {
				$this->db->query($sql);
				$id=mysql_insert_id() ;
			} catch (Exception $e) {
				$this->db->rollback();
				$msg='Error insertando letters'.mysql_error();;
				return false;
			}
			$sql="INSERT INTO envios (`id`, `id_grupo`, `id_letter`, `fecha_ini`) VALUES (NULL, %s, %d, '%s')";
			$sql=$this->db->prepare($sql, array($id_grupo, $id, $_POST['fecha_envio'] ));
			try {
				$this->db->query($sql);
				$id=mysql_insert_id() ;
			} catch (Exception $e) {
				$this->db->rollback();
				$msg='Error insertando envios'.mysql_error();
				return false;
			}
		
		}
		if ($this->db->commit()) {
			$msg="Datos guardados correctamente";
			return true;
		} else {
			$this->db->rollback();
			$msg="Error al final: ".mysql_error();
			return false;
		}

	}

	public function getLetter($id)
	{
		$sql = "SELECT l.*,  e.id_grupo, e.fecha_ini FROM letters l, envios e WHERE e.id=%d AND e.id_letter=e.id ";
		$sql = $this->db->prepare($sql, array($id));
		$q = $this->db->query($sql);
		if ($q)
			return mysql_fetch_assoc($q);		
		else
			return false;
		
	}

	public function getLetters()
	{

		$sql="SELECT l.*,  e.id_grupo,  DATE_FORMAT(e.fecha_ini, '{{date_format}}') as fecha_ini,  DATE_FORMAT(e.fecha_fin, '{{date_format}}') as fecha_fin FROM letters l, envios e WHERE  e.id_letter=e.id ";
		$sql = str_replace('{{date_format}}', '%d/%m/%Y', $sql);
		$q = $this->db->query($sql);
		
		$r=array();
		while ($row = mysql_fetch_assoc($q)) {
			if($row['fecha_fin']==NULL) $row['fecha_fin']='Sin enviar';
			$r[]=$row;
		}
		return $r;		
	}


}
