<?php
function connectdb($server, $user, $pass, $db) {
	$connect = mysql_connect($server, $user, $pass);
	mysql_select_db($db, $connect);
	
	return $connect;
}


$conexion2 = connectdb('localhost', 'root', 'pass', 'PSICOT'); 
$sql="SELECT * FROM MBR ORDER BY ID LIMIT 0,10000";
//$sql="SELECT * FROM MBR WHERE ID=9";
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
	$web= strstr($row['WEB'], '#', true);
	$ano=substr($row['FECHAALTA'],0,4);
	$mes=substr($row['FECHAALTA'],4,2);
	$dia=substr($row['FECHAALTA'],6,2);
	$fecha_alta=$ano.'-'.$mes.'-'.$dia;
	if($row['IDPRESIDENTE']<1) $row['IDPRESIDENTE']='NULL';
	if($row['IDVICEPRESIDENTE']<1) $row['IDVICEPRESIDENTE']='NULL';
	if($row['IDSECRETARIO']<1) $row['IDSECRETARIO']='NULL';
	if($row['IDTESORERO']<1) $row['IDTESORERO']='NULL';
	if(strlen($fecha_alta)<8) $fecha_alta='0000-00-00';
	if($row['CUOTAANUAL']<=0) $row['CUOTAANUAL']=0;
	if($row['CUOTAPAGADA']<=0) $row['CUOTAPAGADA']=0;
	$sqlInsert="INSERT INTO miembros (id, fecha_alta, cuota, pago, nombre, email, web, id_presidente, id_vicepresidente, id_secretario, id_tesorero, direccion, cp, localidad, id_provincia, telefono, telefono1, telefono2, movil, fax, observaciones) VALUES	(".$row['ID'].", '".$fecha_alta."', ".$row['CUOTAANUAL'].", ".$row['CUOTAPAGADA'].", '".mysql_real_escape_string($row['NOMBRE'])."', '".$email."','".$web."', ".$row['IDPRESIDENTE'].", ".$row['IDVICEPRESIDENTE'].", ".$row['IDSECRETARIO'].", ".$row['IDTESORERO'].", '".mysql_real_escape_string($row['DIR1'])."', '".$row['CP1']."', '".mysql_real_escape_string($row['LOCALIDAD1'])."', ".$provincia.", '".$row['TEL']."', '".$row['TEL2']."', '".$row['TEL3']."', '".$row['MOVIL']."', '".$row['FAX']."', '".mysql_real_escape_string($row['OBSERVACIONES'])."')";
	//echo $sqlInsert;
	if(mysql_query($sqlInsert, $feap));
	else echo mysql_error().'ID: '.$row['ID'].'<br>';
}

echo 'FIN';
?>