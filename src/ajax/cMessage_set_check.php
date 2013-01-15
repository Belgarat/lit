<?php
/*
*TODO: INSERIRE AGGIORNAMENTO DEL FLAG CHECKMSG UNA VOLTA CHIUSO IL MESSAGGIO
*
*
*/
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$id=(int) $_POST["ID"];

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();
$oValidate = new cValidate();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oMsg = new cMessage();
$oMsg->set_id_sito($_SERVER["SITO"]);
$oMsg->set_user($_SERVER["SITO"], $oUt);
$oMsg->set_id_utente($_SESSION["ID"]);
$oMsg->set_id_message($id);
$oMsg->set_check_message();
?>
