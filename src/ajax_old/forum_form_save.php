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

$oForum->set_id_post(@$_POST["txtEditIdPost"]);

$oForum->set_NamePoster(@$_POST["txtEditNome"]);

$oForum->set_ObjPost(@$_POST["txtEditOggetto"]);

$oForum->set_BodyPost(@$_POST["txtEditMessaggio"]);

$oForum->set_SignPost(@$_POST["txtEditFirma"]);		

$control=$oForum->let_permission($oForum->let_id_post());

if($control['cForum_post']['Modify']==1){

    if ($oForum->update_post()){
            echo "<p>Aggiornamento <i>" . @$oForum->let_ObjPost() . "</i> effettuato!</p>\r\n";
            if(strstr($_SERVER["HTTP_USER_AGENT"],'MSIE 6.0')){
                    echo "<p>Nel caso in cui, il post non sia aggiornato, fare un refresh della pagina!</p>\r\n";
            }
    } else {
            echo "Aggiornamento <i>" . @$oForum->let_ObjPost() . "</i> <SPAN style='color: red'>fallito!</SPAN><br \>Contattare gli admin del sito!<br \><br \>\r\n";
    }
}else{
    echo "Impossibile continuare con la procedura. <SPAN style='color: red'></SPAN><br \>Autorizzazioni insufficienti.<br \><br \>\r\n";
}

?>
