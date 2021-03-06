<?php
session_start();
require_once("verify_auth.php");
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

$title = $oValidate->_sql(@$_POST["txtTitle"]);
$msg = $oValidate->_sql(@$_POST["txtComment"]);
$sign = $oValidate->_sql(@$_POST["txtSign"]);
$id_parent = (int)@$_POST["txtParent"];

$oPage = new cPage();
$oPage->set_id_sito($_SERVER["SITO"]);
$oPage->set_user($oPage->let_id_sito(),$oUt);
$oCont = new cContent();
$oCont->set_id_sito($_SERVER["SITO"]);
$oCont->set_user($oCont->let_id_sito(),$oUt);

$oOgg = new cText();
$oOgg->set_id_sito($_SERVER["SITO"]);

#$oOgg->id_sito=$this->id_sito;
$oOgg->title = $title;
$oOgg->sign = $sign;
$oOgg->body = $msg;
$oOgg->bComment = $id_parent;

#echo "Testo da inserire: " . $oOgg->title . ", " . $oOgg->body . ", " . $oOgg->sign . ", " . $oOgg->bComment;

if($oOgg->insert_comment()){
    echo "Success!";
}else{
    echo "Error!";
};

?>
