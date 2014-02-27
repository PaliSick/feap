<?php

function connectdb($server, $user, $pass, $db) {
	$connect = mysql_connect($server, $user, $pass);
	mysql_select_db($db, $connect);
	
	return $connect;
}
$conexion2 = connectdb('localhost', 'root', 'pass', 'PSICOT'); 
$sql="SELECT * FROM PSICOTSTD ORDER BY ID LIMIT 0,10000";
//$sql="SELECT * FROM PSICOTSTD WHERE ID=1299";
$q = mysql_query($sql, $conexion2);
$row2=array();
while ($r = mysql_fetch_assoc($q)) {
	$row2[]=$r;
}
$feap = connectdb('localhost', 'root', 'pass', 'feap'); 
foreach ($row2 as $row) {
	if($row['IDSCC']<1) $row['IDSCC']='NULL';
	if($row['IDPSICOT']<1) $row['IDPSICOT']='NULL';
	if($row['IDMBR']<1) $row['IDMBR']='NULL';
		
	$ano=substr($row['FECHAACREDITACION'],0,4);
	$mes=substr($row['FECHAACREDITACION'],4,2);
	$dia=substr($row['FECHAACREDITACION'],6,2);
	$fecha_alta=$ano.'-'.$mes.'-'.$dia;	
	$anob=substr($row['FECHABAJA'],0,4);
	$mesb=substr($row['FECHABAJA'],4,2);
	$diab=substr($row['FECHABAJA'],6,2);
	$fecha_baja=$anob.'-'.$mesb.'-'.$diab;	
	$sqlInsert="INSERT INTO rel_miembros (id, id_psicologo, id_miembro, deleted, fecha_alta, fecha_baja, cargo, observaciones )VALUES (".$row['ID'].", ".$row['IDPSICOT'].", ".$row['IDMBR'].", 0, '".$fecha_alta."', '".$fecha_baja."', '".utf8_encode(mysql_real_escape_string($row['CARGO']))."', '".utf8_encode(mysql_real_escape_string($row['OBSERVACIONES']))."')";
	//echo $sqlInsert;
	if(mysql_query($sqlInsert, $feap));
	else echo mysql_error().'ID: '.$row['ID'].'<br>';
}
echo '<br>FIN';
?>