<?php
function connectdb($server, $user, $pass, $db) {
    $connect = mysql_connect($server, $user, $pass);
    mysql_select_db($db, $connect);
    
    return $connect;
}
$conexion2 = connectdb('localhost', 'root', 'pass', 'PSICOT'); 
$sql="SELECT * FROM DLG ORDER BY ID LIMIT 0,10000";
//$sql="SELECT * FROM DLG WHERE ID=2";
$q = mysql_query($sql, $conexion2);
$row2=array();
while ($r = mysql_fetch_assoc($q)) {
    $row2[]=$r;
}
$feap = connectdb('localhost', 'root', 'pass', 'feap'); 
foreach ($row2 as $row) {
    if($row['IDMBR']<1) $row['IDMBR']='NULL';
    if($row['IDPSICOT']<1) $row['IDPSICOT']='NULL';

    $ano=substr($row['FINI'],0,4);
    $mes=substr($row['FINI'],4,2);
    $dia=substr($row['FINI'],6,2);
    $fecha_ini=$ano.'-'.$mes.'-'.$dia; 
    $anob=substr($row['FFIN'],0,4);
    $mesb=substr($row['FFIN'],4,2);
    $diab=substr($row['FFIN'],6,2);
    $fecha_fin=$anob.'-'.$mesb.'-'.$diab;

    $sqlInsert="INSERT INTO delegados (id, id_psicologo, id_miembro, fecha_ini, fecha_fin,  observaciones )VALUES  (".$row['ID'].", ".$row['IDPSICOT'].",".$row['IDMBR'].", '".$fecha_ini."', '".$fecha_fin."', '".utf8_encode(mysql_real_escape_string($row['OBSERVACIONES']))."')";
    //echo $sqlInsert;
    if(mysql_query($sqlInsert, $feap));
    else echo mysql_error().'ID: '.$row['ID'].'<br>';
}
echo '<br>FIN';
?>
