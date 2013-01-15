<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

if($control['cSoci']['Modify']==1){
	
	$oSoci = new cSoci();
	$oSoci->set_id_sito($_SERVER["SITO"]);
	$oSoci->set_user($oPriMsg->let_id_sito(),$oUt);
	$oSoci->set_id_socio($_GET["idsocio"]);

	$control=$oSoci->let_permission($oSoci->let_id_socio());

	$oSoci->show();

}else{
    
	echo "Impossibile procedere. <SPAN style='color: red;'><br \>Autorizzazioni insufficienti.</SPAN><br \><br \>\r\n";

}
?>
