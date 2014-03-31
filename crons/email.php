<?php
 header('Content-type: text/html; charset=iso-8859-1');
 set_time_limit(360);
 include('../functions.php');
 
 $conexion = connectdb($CFG['server'], $CFG['user'], $CFG['pass'], $CFG['db']);
 $envio=BuscarEnvioPend($conexion);
 if (!isset($envio["error"])){
   if (trim($envio["id"])!=""){
    GuardarLog("Comenzando/continuando Envio id ".$envio["id"]." - Boletin: ".$envio["name"]);
    $tipo=BuscarTipoPendxEnvio($conexion, $envio["id"]);
    if (!isset($tipo["error"])){
     if (trim($tipo["idclase"])==""){//no hay pendientes
	  ActualizarEnvioPend($conexion, $envio["id"]);
	  GuardarLog("** Terminado Envio id ".$envio["id"]." - Boletin: ".$envio["name"]." **");
	 }else{
      $retorno=EnviarBoletin($conexion, $envio["id"], $envio["boletin"], $tipo["idclase"]);
	  if (trim($retorno)==""){
	   ActualizarTipoPend($conexion, $envio["id"], $tipo["idclase"]);
	  }else{
	   GuardarLog($retorno);
	  }
	 }
    }else{
	 GuardarLog($tipo["error"]);
	}
   }else{
    GuardarLog("No hay envios pendientes");
   }
 }else{
  GuardarLog($envio["error"]);
 }
 //////////////
 //// Funciones
 function BuscarEnvioPend($conex){
  $comando="SELECT e.id,  l.name FROM envios e, letters l WHERE e.id_letter=l.id AND ISNULL(e.fecha_fin)  LIMIT 1";
  $resultado=mysql_query($comando, $conex);
  $fila=array();
  if ($resultado==false){
   $fila["error"]="Error al buscar Envio Pendiente: ".mysql_errno()." - ".mysql_error();
  }else{
   $fila = mysql_fetch_assoc($resultado);
  }
   
  return $fila;
 }
 
 function BuscarTipoPendxEnvio($conex, $idenvio){
  $comando="Select idclase From relenviosclasses Where idenvio=%d And estado=1 Limit 1";
  $comando = sprintf($comando, $idenvio);
  $resultado=mysql_query($comando, $conex);
  $fila=array();
  if ($resultado==false){
   $fila["error"]="Error al buscar Tipo Pendiente: ".mysql_errno()." - ".mysql_error();
  }else{
   $fila = mysql_fetch_assoc($resultado);
  }
   
  return $fila;
 }
 
 function EnviarBoletin($conex, $idenvio, $idboletin, $idclase){
  $comando="Select email From emails Where tipo=%d";
  $comando = sprintf($comando, $idclase);
  $resultado=mysql_query($comando, $conex);
  $usuarios=array();
  if ($resultado){
   $ind=0;
   while ($fila = mysql_fetch_assoc($resultado)){
    $usuarios[$ind]=$fila;
	$ind++;
   }
   mysql_free_result($resultado);
  }else{
   return "Error al cargar los Usuarios para enviar !!";
  }
  
  $comando="Select subject,body From letters Where id=%d";
  $comando = sprintf($comando, $idboletin);
  $resultado=mysql_query($comando, $conex);
  if ($resultado){
   $boletin = mysql_fetch_assoc($resultado);
   mysql_free_result($resultado);
  }else{
   return "Error al cargar los datos del boletin !!";
  }
  
  require("class.phpmailer.php");
  
  $sfrom='info@inmocuyo.com.ar';// Remitente
  $e_body = stripslashes($boletin['body']);
  $mailer = new PHPMailer();
  $mailer->From     = $sfrom;
  $mailer->FromName = 'InmoCuyo';
  $mailer->ContentType = 'text/html';
  $mailer->Subject  	= $boletin['subject'];
  $mailer->Body 		= $e_body;
  
  $totenviados=0;
  if (count($usuarios)>0){
   foreach ($usuarios as $ind=>$usuario){
    $mailer->AddAddress($usuario['email'], '');
    if(!$mailer->Send()){
	 GuardarLog("Error al enviar Email: ".$usuario['email']);
	}else{
	 $totenviados++;
	}
    $mailer->ClearAddresses();
    $mailer->ClearAttachments();
   }
  }
  
  $comando="Select name From classes Where id=%d";
  $comando = sprintf($comando, $idclase);
  $resultado=mysql_query($comando, $conex);
  if ($resultado){
   $fila = mysql_fetch_array($resultado, MYSQL_ASSOC);
   mysql_free_result($resultado);
  }else{
   $fila["name"]="No disponible";
  }
  GuardarLog(("Resumen Grupo ".$idclase."-".$fila["name"].": Email correctos ".$totenviados));
  
  return "";
 }//fin function
 
 function GuardarLog($texto){
    $dirlog="./logs/";
	$separador=" ; ";
    $nom_archivo = date("dmY").'.log';
    $contenido = date("H:i:s").$separador.$texto.(chr(13).chr(10));
	if (!file_exists($dirlog)){
     $retorno=mkdir($dirlog,0777);
     if (!$retorno) return "NO se pudo crear el directorio ".$dirlog." !!";
    }
    if (!$gestor = fopen($dirlog.$nom_archivo, 'a')) {
         return "No se puede abrir el archivo ($dirlog.$nom_archivo)";
    }
    if (fwrite($gestor, $contenido) === FALSE) {
        fclose($gestor);
        return "No se puede escribir al archivo ($dirlog.$nombre_archivo)";
    }
    fclose($gestor);
  }
  
  function ActualizarTipoPend($conex, $idenvio, $idclase){
   $comando="Update relenviosclasses Set estado=0 Where idenvio=%d And idclase=%d";
   $comando = sprintf($comando, $idenvio, $idclase);
   $resultado=mysql_query($comando, $conex);
  }
  
  function ActualizarEnvioPend($conex, $idenvio){
   $fecha=date("Y-m-d H:i:s");
   $comando="Update envios Set estado=0, fechahorafin='%s' Where id=%d";
   $comando = sprintf($comando, $fecha, $idenvio);
   $resultado=mysql_query($comando, $conex);
  }
?>
