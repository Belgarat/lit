<?php
session_start();
//require_once("verify_auth.php");
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

$idthread = $oForum->set_id_thread($_GET["id_thread"]);

$readthread = $oForum->read_thread($oUt->fArrayPermission($_SERVER["SITO"],"cForum_thread"));

$aThread = array('id'=>$oForum->let_id_thread(),'Title'=>$oForum->let_TitleThread(),'Desc'=>$oForum->let_DescThread(),'Mod'=>$oForum->let_ModThread(),'Type'=>$oForum->let_TypeThread(),'Argument'=>$oForum->let_ArgumentThread(), 'Img'=>$oForum->let_ImgThread(), 'Ord'=>$oForum->let_OrdThread(), 'Status'=>$oForum->let_StatusThread());

echo json_encode($aThread);

//$oForum->show_form_edit($oUt->dati["Name"]);

?>
