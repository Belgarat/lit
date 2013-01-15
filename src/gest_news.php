<?php
session_start();
require_once("../cfg/pbf/global.php");
require_once("../cfg/config.php");
require_once(SRV_ROOT."/class/db/database.php");
require_once(SRV_ROOT."/class/utente.php");
require_once(SRV_ROOT."/class/cPage.php");
require_once(SRV_ROOT."/class/cValidate.php");
require_once(SRV_ROOT."/class/cForm.php");
require_once(SRV_ROOT."/class/cNews.php");

//assegno le variabili che conterranno l'id del sito e l'id del tipo di news da mostrare e gestire
$id_sito=$_GET["st"];
$id_tipo=$_GET["tp"];


//$oConn = new connessione("/playbyforum/inc/config1.php");
$oDb = Db::getInstance();
$oDb->Open();

$oForm = new cForm;
$oValidate = new cValidate;
//$oConn->connect();
$oUt = new cUtente($oConn);
$oUt->id=intval($_SESSION["ID"]);
$oUt->Leggi();

$oPage = new cPage();
$oPage->set_id_sito($_SERVER["SITO"]);
$oPage->set_opt(@$_GET["opt"]);
$oPage->set_user($oPage->let_id_sito(),$oUt);

$oNews = new cNews($oConn);
$oNews->set_id_sito($id_sito);
$oNews->set_opt(@$_GET["opt"]);
$oNews->set_user($oNews->let_id_sito(),$oUt);
if($oUt->fControlPermission($id_sito,"cNews","modify")==0){
	$oConn->redirect($_SERVER["DOMAIN"] . "index.php");
}

if($_POST["Save"]=="Save"){	
	if(($_POST["txtId_news"]=="") or (is_null($_POST["txtId_news"])==true)){
		$oNews->id_utente=$_SESSION["ID"];
		$oNews->set_id_sito($id_sito);
		$oNews->set_opt(@$_GET["opt"]);
		$oNews->set_user($oNews->let_id_sito(),$oUt);
		$oNews->id_tipo=$id_tipo;
		$oNews->author=$oUt->dati["Name"];
		$oNews->title=$_POST["txtTitle"];
		$oNews->body=$_POST["news"];
		echo $oNews->insert_news($oUt->fArrayPermission($id_sito,"cNews"));
	}else{
		$oNews->id_news=$_POST["txtId_news"];
		$oNews->id_utente=$_SESSION["ID"];
		$oNews->set_id_sito($id_sito);
		$oNews->id_tipo=$id_tipo;
		$oNews->author=$oUt->dati["Name"];
		$oNews->title=$_POST["txtTitle"];
		$oNews->body=$_POST["news"];
		echo $oNews->modify_news($oUt->fArrayPermission($id_sito,"cNews"));
	}	
}

if($_POST["Delete"]=="Cancella news selezionata"){
	$oNews->id_news=$_POST["txtId_news"];
	echo $oNews->delete_news($oUt->fArrayPermission($id_sito,"cNews"));
}

?>
<HTML>
<HEAD>
		<title>Gestione News - Araldo</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/ajax.js"></script>		
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/scriptaculous182/lib/prototype.js"></script>
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/scriptaculous182/src/scriptaculous.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/tiny_mce/tiny_mce.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_amb.css">
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_style.css">
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_site.css">
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_site_ie.css">
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_content.css">
		<?php
			$oNews->include_css();
			$oNews->include_js();
		?>		
		<LINK REL="SHORTCUT ICON" HREF="<?php echo HTTP_ROOT ?>/img/ico/sword.ico" TYPE="image/x-icon">
</HEAD>
<BODY onload="">
	<div>
	<p Class="cTestoNorm">
	<b>Gestione News - Araldo</b>
	<br>
	<table border="0" bordercolor="#4E1D69" cellSpacing=2 cellpadding="5" width="100%" height="480" border=0 valign="top">
		<tr>
			<td vAlign=top width="50%">
				<form method="post" id="formNews" name="dformNews" action="<?php echo $_SERVER["REQUEST_URI"]?>">
					<?php
					$oForm->formInput("button","Nuovo","Nuovo...","","onclick=\"clear_form();\"");
					$oForm->formLabel("lblEtichettaStato","Stato"," - Stato: ");
					$oForm->formLabel("lblStato","Indicatore di stato che può essere di modificao inserimento","Nuovo inserimento","style='color:red;font-weight:bolder;'");
					$oForm->formBr(2);

					//$oForm->formLabel("lblIdNews","Id della News","Id: ");
					$oForm->formOpenDiv("DivNumero","","Numero news. Non editabile.","style=\"border=0px;display:block;width:100%;text-align:right;\"");
					$oForm->formLabel("lblIdNews","Id della news","Numero: ");
					$oForm->formInput("text","txtId_news","",5,"readonly id=\"txtId_news\" style=\"background:#EF9AA1;border:0px;\"");
					$oForm->formCloseDiv();
					//$oForm->formLabel("lblIdUt","Id dell'utente"," Id utente: ");
					$oForm->formInput("hidden","txtId_utente","",5,"readonly id=\"txtId_utente\"");
					$oForm->formLabel("lblAuthor","Username dell'autore"," Autore: ");
					$oForm->formBr();					
					$oForm->formInput("text","txtAuthor","",20,"readonly id=\"txtAuthor\"");
					//$oForm->formLabel("lblDatetime","Data articolo"," Data e ora: ");
					$oForm->formInput("hidden","txtDatetime","",10,"readonly id=\"txtDatetime\"");
					$oForm->formBr();

					$oForm->formLabel("lblTitle","Titolo della news","Titolo: ");
					$oForm->formBr();
					$oForm->formInput("text","txtTitle","",50,"id=\"txtTitle\"");
					$oForm->formBr();
					$oForm->formLabel("lblNews","Corpo della news","News: ");
					$oForm->formBr();
					$oForm->formTextarea("news","","15","60","id='news'");
					$oForm->formBr(2);
					$oForm->formInput("submit","Save","Save");
					$oForm->formInput("submit","Delete","Cancella news selezionata");
					?>
				</form>
			</td>
			<td vAlign=top width="50%">
				<div style="display:block;height:480px;overflow:auto;">
					<?php
					$oNews = new cNews();
					$aAllow["Show"]=$oUt->fControlPermission($id_sito,"cNews","show");
					$aAllow["Create"]=$oUt->fControlPermission($id_sito,"cNews","create");
					$aAllow["Modify"]=$oUt->fControlPermission($id_sito,"cNews","modify");
					$aAllow["Delete"]=$oUt->fControlPermission($id_sito,"cNews","delete");
					$oNews->set_id_sito($id_sito);
					$oNews->set_opt(@$_GET["opt"]);
					$oNews->set_user($oNews->let_id_sito(),$oUt);
					$oNews->id_tipo = $id_tipo;
					$oNews->MaxNews=10;
					$oNews->show_list_news($aAllow,1);
					?>
				</div>
			</td>
		</tr>
	</table>
	</div>
</BODY>
</HTML>
<?php
//$oConn->closedb();
?>
<SCRIPT>
	clear_form();
</SCRIPT>
