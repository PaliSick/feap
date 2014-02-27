<?php
function connectdb($server, $user, $pass, $db) {
    $connect = mysql_connect($server, $user, $pass);
    mysql_select_db($db, $connect);
    
    return $connect;
}
$conexion2 = connectdb('localhost', 'root', 'pass', 'PSICOT'); 
$sql="SELECT * FROM JNTGRP ORDER BY ID LIMIT 0,10000";
//$sql="SELECT * FROM JNTGRP WHERE ID=2";
$q = mysql_query($sql, $conexion2);
$row2=array();
while ($r = mysql_fetch_assoc($q)) {
    $row2[]=$r;
}
$feap = connectdb('localhost', 'root', 'pass', 'feap'); 
foreach ($row2 as $row) {
    if($row['IDSCC']<1) $row['IDSCC']='NULL';
    if($row['IDPSICOT']<1) $row['IDPSICOT']='NULL';

    $ano=substr($row['FECHAELECCIONCARGO'],0,4);
    $mes=substr($row['FECHAELECCIONCARGO'],4,2);
    $dia=substr($row['FECHAELECCIONCARGO'],6,2);
    $fecha_eleccion=$ano.'-'.$mes.'-'.$dia; 
    $anob=substr($row['FECHABAJA'],0,4);
    $mesb=substr($row['FECHABAJA'],4,2);
    $diab=substr($row['FECHABAJA'],6,2);
    $fecha_renovacion=$anob.'-'.$mesb.'-'.$diab;

    $sqlInsert="INSERT INTO rel_junta (id, id_junta, id_psicologo, cargo, fecha_eleccion, fecha_renovacion, observaciones )VALUES  (".$row['ID'].", ".$row['IDJNT'].", ".$row['IDPSICOT'].", '".utf8_encode(mysql_real_escape_string($row['CARGO']))."', '".$fecha_eleccion."', '".$fecha_renovacion."', '".utf8_encode(mysql_real_escape_string($row['OBSERVACIONES']))."')";
    //echo $sqlInsert;
    if(mysql_query($sqlInsert, $feap));
    else echo mysql_error().'ID: '.$row['ID'].'<br>';
}
echo '<br>FIN';
?>
