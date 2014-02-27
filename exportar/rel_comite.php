<?php
function connectdb($server, $user, $pass, $db) {
	$connect = mysql_connect($server, $user, $pass);
	mysql_select_db($db, $connect);
	
	return $connect;
}
$conexion2 = connectdb('localhost', 'root', 'pass', 'PSICOT'); 
$sql="SELECT * FROM CMTGRP ORDER BY ID LIMIT 0,10000";
//$sql="SELECT * FROM CMTGRP WHERE ID=2";
$q = mysql_query($sql, $conexion2);
$row2=array();
while ($r = mysql_fetch_assoc($q)) {
	$row2[]=$r;
}
$feap = connectdb('localhost', 'root', 'pass', 'feap'); 
foreach ($row2 as $row) {
	if($row['IDCMT']<1) $row['IDCMT']='NULL';
	if($row['IDPSICOT']<1) $row['IDPSICOT']='NULL';
	if($row['IDSCC']<1) $row['IDSCC']='NULL';
	$email= strstr($row['EMAIL'], '#', true);
	$sqlInsert="INSERT INTO rel_comite (id, id_comite, id_psicologo, id_seccion, cargo, nombre, telefono, telefono2, email, observaciones )	VALUES (".$row['ID'].", ".$row['IDCMT'].", ".$row['IDPSICOT'].", ".$row['IDSCC'].", '".utf8_encode(mysql_real_escape_string($row['CARGO']))."','".utf8_encode(mysql_real_escape_string($row['PERSONA']))."','".utf8_encode(mysql_real_escape_string($row['TEL1']))."','".utf8_encode(mysql_real_escape_string($row['TEL2']))."','".utf8_encode(mysql_real_escape_string($email))."','".utf8_encode(mysql_real_escape_string($row['OBS']))."' )";
	//echo $sqlInsert;
	if(mysql_query($sqlInsert, $feap));
	else echo mysql_error().'ID: '.$row['ID'].'<br>';
}
echo '<br>FIN';
?>