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
$oForum->set_id_thread(@$_POST["txtThread"]);

$oValidate = new cValidate();

$oForum->set_NamePoster(@$_POST["txtNome"]);

$oForum->set_ObjPost(@$_POST["txtOggetto"]);

$oForum->set_BodyPost(@$_POST["txtMessaggio"]);

$oForum->set_SignPost(@$_POST["txtFirma"]);

$n_post = $oForum->insert_thread();

if ($n_post){
	echo "<p>Creazione thread <i>" . $oForum->let_ObjPost() . "</i> completata!</p>\r\n";
	echo "<p>Ricarica la pagina per visualizzare la lista aggiornata.</p>\r\n";
} else {
	echo "Creazione thread <i>" . $oForum->let_ObjPost() . "</i> <SPAN style='color: red'>fallita!</SPAN><br \>Contattare gli admin del sito!<br \><br \>\r\n";
}
?>
