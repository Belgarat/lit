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

$idpost = $oForum->set_id_post($_GET["id_post"]);

$readpost = $oForum->read_post($oUt->fArrayPermission($_SERVER["SITO"],"cForum_thread"));

$aPost = array('id'=>$oForum->let_id_post(),'Name'=>$oForum->let_NamePoster(),'Obj'=>$oForum->let_ObjPost(),'Body'=>$oForum->let_BodyPost(),'Sign'=>$oForum->let_SignPost());

echo json_encode($aPost);

//$oForum->show_form_edit($oUt->dati["Name"]);

?>
