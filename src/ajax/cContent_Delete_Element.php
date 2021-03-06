<?php
session_start();
require_once("../../cfg/config.php");

$url_section = explode(".",$_SERVER["SERVER_NAME"]);
if($url_section[0]=="pbfbeta") {
	$inc_dir="../../pbfbeta/";
	require_once($inc_dir . "cfg/global.php");
	require_once($inc_dir . "inclusions.php");
}else{
	$inc_dir="../../cfg/".$url_section[0];
	require_once($inc_dir . "/global.php");
	require_once(SRV_ROOT . "/" . $url_section[0] . "_inclusions.php");
}

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();
$oValidate = new cValidate();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$id_element = (int) $_GET["id"];
$id_type = (int) $_GET["id_type"];

$oPage = new cPage();
$oPage->set_id_sito($_SERVER["SITO"]);
$oPage->set_user($oPage->let_id_sito(),$oUt);
$oCont = new cContent();
$oCont->set_id_sito($_SERVER["SITO"]);
$oCont->set_user($oCont->let_id_sito(),$oUt);

switch ($id_type) {
	case 2:
		$oOgg = new cImage();
		$oOgg->set_id_sito($_SERVER["SITO"]);
		break;
	case 3:
		$oOgg = new cAttach();
		$oOgg->set_id_sito($_SERVER["SITO"]);
		break;
}

$oOgg->id=$id_element;
$oOgg->delete();

?>
