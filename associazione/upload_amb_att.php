<?php
/*
 * Created on 08/ago/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/associazione/inc/global.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/cfg/config.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/db/database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/oConn.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/utente.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cForm.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cPage.php");
//$oConn = new connessione("/playbyforum/inc/config1.php");
$oDb = new Db;
$oDb->Open();

$oUt = new cUtente($oConn);
$oAtt = new cAttach();
$oAtt->id_sito=$_SERVER["SITO"];
//$oConn->connect();
$oUt->id=intval($_SESSION["ID"]);
$oUt->Leggi();
$form = new cForm();
cPage::$id_sito=$_SERVER["SITO"];
?>
 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
	<HEAD>
		<title>Lux In tenebra - Upload allegati documentazione</title>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" >
	 	<script language="javascript">
	 	function Chiudi(){
	 		opener.document.location.reload();
	 		close(this);
	 	}
	 	</script>
		<script type="text/javascript" src="forum/js/ajax.js"></script>
		<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
		<link rel="stylesheet" type="text/css" href="./css/amb.css">
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="site.css">
		<link rel="stylesheet" type="text/css" href="./css/content.css">
		<LINK REL="SHORTCUT ICON" HREF="./images/sword.ico" TYPE="image/x-icon">
 	<BODY>
		<?php
		$form->formLabel("lbName","Nome utente",$oUt->dati["Name"]);
		if(isset($_POST["userfile"])){
			$oAtt->title=$_POST["txtTitolo"];
			$oAtt->subtitle=$_POST["sottotitolo"];
			$oAtt->path="home/" . $oUt->dati["Name"] . "/attach/" . $_FILES["userfile"]["name"];
			$oAtt->type=$_FILES["userfile"]["type"];
			$oAtt->size=$_FILES["userfile"]["size"];
			$oAtt->iIdUt=$oUt->dati["ID"];
			$LastId=$oAtt->insert();
			if($LastId<>0){
				cContent::insert_attach_cont($_GET["IdC"],$LastId,3,$_SESSION["ID"]);
			}else{
				echo "Inserimento dati nel database fallito. Errore: " . mysql_error();
			}
		 	$form->formUploadExec($oUt->dati["Name"] . "/attach");
		 	echo "Percorso:" . $_SERVER["DOMAIN"] . "home/" . $oUt->dati["Name"] . "/images/" . $_FILES["userfile"]["name"];
		 	$form->formBr();
		 	echo "Formato:" . $_FILES['userfile']['type'];
		 	$form->formBr();
		 	echo "Dimensione(byte):" . $_FILES['userfile']['size'];
		 	$form->formBr();
		 	$form->formInput("button","Close","Chiudi","","OnClick=\"javascrit:Chiudi()\"");
		}else{
			$form->formBr(2);
			$form->formOpenDiv("","","","style=\"position:absolute;top:30%;margin:20px;\"");
			$form->formUpload("upload_amb_att.php?IdC=" . $_GET["IdC"]);
			$form->formBr();
			$form->formLabel("lbTitolo","Titolo documento","Titolo: ");
			$form->formBr();
			$form->formInput("text","txtTitolo","",58);
			$form->formBr(2);
			$form->formCloseUpload();
			$form->formCloseDiv();
		}
		?>
	</BODY>
</HTML>
<?php
$oConn->closedb();
?>