<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$id_pg=(int)$_POST["id"];

//if($control['cForum']['Show']==1){
	
	$oPg = new cPg();
	$oPg->set_id_pg($id_pg);
	$oPg->set_id_sito($_SERVER["SITO"]);
	$oPg->set_user($_SERVER["SITO"],$oUt);
	$oPg->leggi();
	$oPg->show();

//}else{
    
	//echo "Impossibile procedere. <SPAN style='color: red;'><br \>Autorizzazioni insufficienti.</SPAN><br \><br \>\r\n";

//}
?>
