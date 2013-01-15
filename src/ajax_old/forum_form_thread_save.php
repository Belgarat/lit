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
$oForum->set_user($oForum->let_id_sito(),$oUt);
$oValidate = new cValidate();

$oForum->set_id_thread(@$_POST["txtEditIdThread"]);

$control=$oForum->let_permission($oForum->let_id_thread());

if ($oForum->let_id_thread()==0) {
    if($control['cForum_thread']['Create']==1){
	$oForum->set_TitleThread(@$_POST["txtTitleThread"]);

	$oForum->set_DescThread(@$_POST["txtDescrizione"]);

	$oForum->set_ModThread(@$_POST["txtModeratori"]);

	$oForum->set_TypeThread(@$_POST["txtTipo"]);
        
        $oForum->set_ArgumentThread(@$_POST["txtArgument"]);

	$oForum->set_ImgThread(@$_POST["txtImmagine"]);

	$oForum->set_OrdThread(@$_POST["txtOrdine"]);

	$oForum->set_StatusThread(@$_POST["txtStatus"]);
	if ($oForum->insert_thread()){
		echo "<p>Inserimento <i>" . @$oForum->let_TitleThread() . "</i> effettuato!</p>\r\n";
	} else {
		echo "Inserimento <i>" . @$oForum->let_TitleThread() . "</i> <SPAN style='color: red'>fallito!</SPAN><br \>Contattare gli admin del sito!<br \><br \>\r\n";
	}
    }else{
        echo "Impossibile procedere. <SPAN style='color: red'></SPAN><br \>Autorizzazioni insufficienti.<br \><br \>\r\n";
    }
} else {
    if($control['cForum_thread']['Modify']==1){
	$oForum->set_TitleThread(@$_POST["txtEditTitleThread"]);

	$oForum->set_DescThread(@$_POST["txtEditDescrizione"]);

	$oForum->set_ModThread(@$_POST["txtEditModeratori"]);

	$oForum->set_TypeThread(@$_POST["txtEditTipo"]);
        
        $oForum->set_ArgumentThread(@$_POST["txtEditArgument"]);

	$oForum->set_ImgThread(@$_POST["txtEditImmagine"]);

	$oForum->set_OrdThread(@$_POST["txtEditOrdine"]);

	$oForum->set_StatusThread(@$_POST["txtEditStatus"]);
	if ($oForum->update_thread()){
		echo "<p>Aggiornamento <i>" . @$oForum->let_TitleThread() . "</i> effettuato!</p>\r\n";
	} else {
		echo "Aggiornamento <i>" . @$oForum->let_TitleThread() . "</i> <SPAN style='color: red'>fallito!</SPAN><br \>Contattare gli admin del sito!<br \><br \>\r\n";
	}
    }else{
        echo "Impossibile procedere. <SPAN style='color: red'></SPAN><br \>Autorizzazioni insufficienti.<br \><br \>\r\n";
    }
}

?>
