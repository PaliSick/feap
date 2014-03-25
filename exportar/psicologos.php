<?php
function connectdb($server, $user, $pass, $db) {
	$connect = mysql_connect($server, $user, $pass);
	mysql_select_db($db, $connect);
	
	return $connect;
}


$conexion2 = connectdb('localhost', 'root', 'pass', 'PSICOT'); 
$sql="SELECT * FROM PSICOT ORDER BY ID LIMIT 0,10000";
//$sql="SELECT * FROM PSICOT WHERE ID=1";
$q = mysql_query($sql, $conexion2);
$row2=array();
while ($r = mysql_fetch_assoc($q)) {
	$row2[]=$r;
}
$feap = connectdb('localhost', 'root', 'pass', 'feap'); 
foreach ($row2 as $row) {
	$estado=($row['ESTADO']=='ALTA')?1:0;
	$provincia=(int)$row['IDCP1'];
	if($provincia==0) $provincia='NULL';
	if($provincia==69) $provincia=99;
	$provincia2=(int)$row['IDCP2'];
	if($provincia2==0) $provincia2='NULL';
	if($provincia2==69) $provincia2=99;
	$email= strstr($row['EMAIL'], '#', true);
	$sqlInsert="INSERT INTO psicologos (id, estado, di, nombre, apellido, direccion, id_provincia, cp, localidad, telefono, movil,  direccionC, id_provinciaC, cpC, localidadC, telefonoC, telefono1C, telefono2C,  titulo, idioma, especialidades, observaciones) VALUES 	(".$row['ID'].", ".$estado.", '".$row['DI']."', '".utf8_encode(mysql_real_escape_string($row['NOMBRE']))."', '".utf8_encode(mysql_real_escape_string($row['APELLIDOS']))."', '".utf8_encode(mysql_real_escape_string($row['DIR1']))."', ".$provincia.", '".$row['CP1']."', '".utf8_encode(mysql_real_escape_string($row['LOCALIDAD1']))."', '".$row['TEL']."', '".$row['MOVIL']."', '".utf8_encode(mysql_real_escape_string($row['DIR2']))."', '".$provincia2."', '".$row['CP2']."', '".utf8_encode(mysql_real_escape_string($row['LOCALIDAD2']))."', '".$row['TELREG']."', '".$row['TEL2']."', '".$row['TEL3']."', '".$row['TITULACION']."', '".$row['IDIOMAS']."', '".utf8_encode(mysql_real_escape_string($row['ESPECIALIDADES']))."', '".mysql_real_escape_string($row['OBSERVACIONES'])."')";
	//echo $sqlInsert;
	if(mysql_query($sqlInsert, $feap)){
		for ($i=1;$i<=24;$i++){
			if($row['OPC-'.$i]==1){
				$sqlInsert2="INSERT INTO rel_opciones (id_psicologo, id_opcion) VALUES (".$row['ID'].", ".$i.")";
				mysql_query($sqlInsert2, $feap);
			}
		}
		$sqlEmail="INSERT INTO emails (id_psicologo, email) VALUES (".$row['ID'].",'".$email."')";
		mysql_query($sqlEmail, $feap);
	}else echo mysql_error().'ID: '.$row['ID'].'<br>';
}

echo 'FIN';
?>