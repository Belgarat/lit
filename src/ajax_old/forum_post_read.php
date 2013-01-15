<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oForum = new cForum();
$oForum->set_id_sito($_SERVER["SITO"]);
$oValidate = new cValidate();

$oForum->set_id_post(@$_POST["txtIdPost"]);

//$oForum->read_post($oUt->fArrayPermission($_SERVER["SITO"],"cForum_thread"));
$oForum->show_single_post($oUt->fArrayPermission($_SERVER["SITO"],"cForum_thread"));



?>
