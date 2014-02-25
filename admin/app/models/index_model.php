<?
class indexModel extends BaseModel {

	public function login()
	{
		session_start();
		$sql = $this->db->prepare("SELECT id, pass FROM usser WHERE usser='%s' LIMIT 1", array($_POST['user']));
		$q = $this->db->query($sql);

		if ($q) {
			if(mysql_num_rows($q)==1){
				$row=mysql_fetch_assoc($q);
				$hasher= new PasswordHash(8, FALSE);
				if ($hasher->CheckPassword($_POST['pass'],$row['pass'])) {
					$_SESSION['d_id'] = $row['id'];
			 		return true;
				}


			 }
		}
	 	if((int)$_SESSION['sd_cont_ip']==0)	
		$_SESSION['sd_ip_addres']=$_SERVER['REMOTE_ADDR'];	
			
		if($_SESSION['sd_ip_addres']==$_SERVER['REMOTE_ADDR'])
			$_SESSION['sd_cont_ip']=$_SESSION['sd_cont_ip']+1;

	  	return false;	
	}

}