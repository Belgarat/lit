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
$oForum->set_user($_SERVER["SITO"],$oUt);

$oValidate = new cValidate();

$idpost = $oForum->set_id_post($_GET["id_post"]);

$control=$oForum->let_permission($oForum->let_id_post());

if($control['cForum_post']['Modify']==1){

    $readpost = $oForum->read_post();

    $aPost = array('id'=>$oForum->let_id_post(),'Name'=>$oForum->let_NamePoster(),'Obj'=>$oForum->let_ObjPost(),'Body'=>$oForum->let_BodyPost(),'Sign'=>$oForum->let_SignPost());

    echo json_encode($aPost);

}else{
    echo "Impossibile procedere. <SPAN style='color: red'></SPAN><br \>Autorizzazioni insufficienti.<br \><br \>\r\n";
}

//$oForum->show_form_edit($oUt->dati["Name"]);

?>
