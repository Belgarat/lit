<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oPriMsg = new cPrivatemessage();
$oPriMsg->set_id_sito($_SERVER["SITO"]);
$oPriMsg->set_user($oPriMsg->let_id_sito(),$oUt);

$newmsg=$oPriMsg->test_new_msg();
if($newmsg!=0){
	echo "<span style='color:red;font-weight:bold;'>.: Messaggi(".$newmsg.") :.</span>";
}else{
	echo ".: Messaggi :.";
}
?>
