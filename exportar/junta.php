<?php

function connectdb($server, $user, $pass, $db) {
	$connect = mysql_connect($server, $user, $pass);
	mysql_select_db($db, $connect);
	
	return $connect;
}
$conexion2 = connectdb('localhost', 'root', 'pass', 'PSICOT'); 
$sql="SELECT * FROM JNT ORDER BY ID LIMIT 0,10000";
//$sql="SELECT * FROM CMT WHERE ID=1299";
$q = mysql_query($sql, $conexion2);
$row2=array();
while ($r = mysql_fetch_assoc($q)) {
	$row2[]=$r;
}
$feap = connectdb('localhost', 'root', 'pass', 'feap'); 
foreach ($row2 as $row) {

	$sqlInsert="INSERT INTO junta (id, junta, info )VALUES (".$row['ID'].", '".utf8_encode(mysql_real_escape_string($row['NOMBRE']))."', '".utf8_encode(mysql_real_escape_string($row['INFO']))."')";
	echo $sqlInsert;
	if(mysql_query($sqlInsert, $feap));
	else echo mysql_error().'ID: '.$row['ID'].'<br>';
}
echo '<br>FIN';
?>