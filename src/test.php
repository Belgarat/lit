<?php
session_start();
require_once("../cfg/config.php");
require_once("../cfg/pbf_global.php");
require_once(SRV_ROOT."/class/db/database.php");

require_once(SRV_ROOT."/class/utente.php");
require_once(SRV_ROOT."/class/cForm.php");
require_once(SRV_ROOT."/class/cForum.php");
require_once(SRV_ROOT."/class/cValidate.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();
$oForum = new cForum();
$oValidate = new cValidate();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oForum->set_id_post(8809);
$oForum->read_post($oUt->fArrayPermission($_SERVER["SITO"],"cForum_thread"));
$oForum->show_form_edit($oUt->dati["Name"]);


echo $oUt->dati["Name"];

?>
