<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oPage = new cPage();
$oPage->set_id_sito($_SERVER["SITO"]);
$oPage->set_opt(@$_GET["opt"]);
$oPage->set_user($oPage->let_id_sito(),$oUt);

echo $oPage->show_online();

unset($oUt);
unset($oPage);

?>
