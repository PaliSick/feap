<?php
function connectdb($server, $user, $pass, $db) {
	$connect = mysql_connect($server, $user, $pass);
	mysql_select_db($db, $connect);
	
	return $connect;
}
$conexion2 = connectdb('localhost', 'root', 'pass', 'PSICOT'); 
$sql="SELECT * FROM SCCGRP ORDER BY ID LIMIT 0,10000";
//$sql="SELECT * FROM SCCGRP WHERE ID=2";
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
	$sqlInsert="INSERT INTO rel_seccion (id, id_seccion, id_psicologo, id_miembro, cargo )VALUES (".$row['ID'].", ".$row['IDSCC'].", ".$row['IDPSICOT'].", ".$row['IDMBR'].", '".$row['CARGO']."')";
	//echo $sqlInsert;
	if(mysql_query($sqlInsert, $feap));
	else echo mysql_error().'ID: '.$row['ID'].'<br>';
}
echo '<br>FIN';
?>