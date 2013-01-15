<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();

$oUt = new cUtente();

$oUt->id=(int)$_SESSION["ID"];

$oUt->Leggi();

$oNoticeboard = new cNoticeboard();
$oNoticeboard->set_id_sito($_SERVER["SITO"]);
$oNoticeboard->set_user($_SERVER["SITO"], $oUt);
$oNoticeboard->show();
?>
