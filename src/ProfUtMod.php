<?php 
session_start();
require_once("../cfg/pbf/global.php");
require_once("../cfg/config.php");
require_once(SRV_ROOT."/cfg/config.php");
require_once(SRV_ROOT."/class/db/database.php");
require_once(SRV_ROOT."/class/utente.php");
require_once(SRV_ROOT."/class/cValidate.php");
require_once(SRV_ROOT."/class/cForm.php");

$oDb = Db::getInstance();
$oDb->Open();

$oForm = new cForm;
$oUt = new cUtente();
$oUtA = new cUtente();
$oUtA->id=$_SESSION["ID"];
$oUtA->Leggi();

if($oUtA->fControlPermission($_SERVER["SITO"],"cUtente","modify")){
	$oUt->id=$_GET["UT"];
}else{
	$oUt->id=$_SESSION["ID"];
}
$oUt->Leggi();

switch($_POST["cmdSave"]){
	case "Annulla":
		if ($oUtA->fControlPermission($_SERVER["SITO"],"cUtente","modify")) {
			$url=HTTP_ROOT."/src/ProfUt.php?PG=" . $oUt->id;
			header("Location:" . $url);
		}else{
			$url=HRRP_ROOT."/src/ProfUt.php?PG=" . $_SESSION["ID"];
			header("Location:" . $url);
		}
		exit;
		
	case "Salva":
		$Pwd= $_POST["txt$Pwd"];
		$Pwd1= $_POST["txt$Pwd1"];
		
		if(get_magic_quotes_gpc()!=1){
			$oUt->dati["ImmUt"]=addslashes($_POST["txtImm"]);
			$oUt->dati["Email"]=addslashes($_POST["txtemail"]);
			$oUt->dati["Skype"]=addslashes($_POST["txtSkype"]);
			$oUt->dati["MSN"]=addslashes($_POST["txtMSN"]);
			$oUt->dati["ICQ"]=addslashes($_POST["txtICQ"]);
			$oUt->dati["Yahoo"]=addslashes($_POST["txtYahoo"]);
			$oUt->dati["Motto"]=addslashes($_POST["txtMotto"]);
			$oUt->dati["Signature"]=addslashes($_POST["txtSignature"]);
		}else{
			$oUt->dati["ImmUt"]=$_POST["txtImm"];
			$oUt->dati["Email"]=$_POST["txtemail"];
			$oUt->dati["Skype"]=$_POST["txtSkype"];
			$oUt->dati["MSN"]=$_POST["txtMSN"];
			$oUt->dati["ICQ"]=$_POST["txtICQ"];
			$oUt->dati["Yahoo"]=$_POST["txtYahoo"];
			$oUt->dati["Motto"]=$_POST["txtMotto"];
			$oUt->dati["Signature"]=$_POST["txtSignature"];	
		}
		if($oUtA->fControlPermission($_SERVER["SITO"],"cUtente","modify")){
			$oUt->dati["soprannome"]=$_POST["txtSoprannome"];
		}
		if (is_numeric($_POST["txtCollaborazione"])) {
			$oUt->dati["Collaborazione"]=$_POST["txtCollaborazione"];
		}
		//Controllo password
		if($Pwd==$Pwd1){
			if($Pwd!=""){
				$oUt->dati["Password"]=$oUt->Critta($Pwd);
				//Da impostare procedura
				//Call fNuovaPassword($oUt->email,$oUt->Name,$Pwd)
			}
		}else{
			$url=HTTP_ROOT."/src/ProfUtMod.php?Err=$Pwd";
			header("Location:" . $url);
		}
			
		$oUt->Aggiorna($oUt->dati);
		
		if ($oUtA->fControlPermission($_SERVER["SITO"],"cUtente","modify")) {
			$url=HTTP_ROOT."/src/ProfUt.php?PG=" . $oUt->id;
			header("Location:" . $url);
		}else{
			$url=HTTP_ROOT."/src/ProfUt.php?PG=" . $_SESSION["ID"];
			header("Location:" . $url);
		}
		exit;
}

?>
<HTML>
<HEAD>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<TITLE>Scheda Utente - Lux in tenebra</TITLE>
<LINK REL=STYLESHEET TYPE="text/css" HREF="<?php echo HTTP_ROOT ?>/css/pbf_style.css">
<LINK REL=STYLESHEET TYPE="text/css" HREF="<?php echo HTTP_ROOT ?>/css/pbf_site.css">
<LINK REL=STYLESHEET TYPE="text/css" HREF="<?php echo HTTP_ROOT ?>/css/scUt.css">
<STYLE></STYLE>
</HEAD>
<BODY leftMargin="0" topMargin="0" MARGINHEIGHT="0" MARGINWIDTH="0">
	<form name="frmModUt" method="post" action="<?php echo HTTP_ROOT ?>/src/ProfUtMod.php?UT=<?php echo $oUt->dati["ID"];?>">
		<DIV id="idMenu">
		<?php if(($_SESSION["ID"]==$oUt->id) or ($oUtA->fControlPermission($_SERVER["SITO"],"cUtente","modify"))){?>
			<input id="idSalva" name="cmdSave" value="Salva" type="submit">
			<input id="idSalva" name="cmdSave" value="Annulla" type="submit">
		<?php }?>
		</DIV>
		<DIV id="idNome">
			<?php echo $oUt->dati["Name"];?>
		</DIV>
		<DIV id="idAnzianita">
			<?php echo "Non ancora gestito"?>
		</DIV>
		<DIV id="idImm">
			<font size=2><b>Link immagine (http:\\...):</b></font><br>
			<input name="txtImm" size=30 value="<?php echo $oUt->dati["ImmUt"];?>"><br>
			<font size=2><b>(Ottimizzato per 200px larghezza, 250px altezza)</font>
			<br><br>
			<?php
			if($_GET["Err"]="$Pwd"){
				echo "<font size=2 color=red><blink>Password non corrispondenti. Riprovare.<br>Il resto delle modifiche è stato salvato.</blink></font><br>";
			}else{
				echo "Compilare per cambiare la password.";
			}
			?>
			Inserisci password nuova: <input type=password name="txt$Pwd" ID="Password1"><br>
			Rinserisci password nuova:<input type=password name="txt$Pwd1" ID="Password2"><br>
		</DIV>
		<DIV id="idMail">
			<input name="txtemail" value="<?php echo $oUt->dati["Email"];?>">
		</DIV>
		<DIV id="idMSN">
			<input name="txtMSN" value="<?php echo $oUt->dati["MSN"];?>">
		</DIV>
		<DIV id="idICQ">
			<input name="txtICQ" value="<?php echo $oUt->dati["ICQ"];?>">
		</DIV>
		<DIV id="idSky">
			<input name="txtSkype" value="<?php echo $oUt->dati["Skype"];?>">
		</DIV>
		<DIV id="idYahoo">
			<input name="txtYahoo" value="<?php echo $oUt->dati["Yahoo"]?>">
		</DIV>
		<DIV id="idMotto">
			<textarea name="txtMotto" rows=4 cols=18><?php echo $oUt->dati["Motto"];?></textarea>
		</DIV>
		<DIV id="idFirma">
			<textarea name="txtSignature" rows=4 cols=18><?php echo $oUt->dati["Signature"];?></textarea>
		</DIV>
		<DIV id="idPG">
			<?php $oUt->ListaPg();?>
		</DIV>
		<DIV id="idAvvCorso">
			<?php echo $oUt->AvventureInCorso();?>
		</DIV>
		<DIV id="idAvvConcluse">
			<?php echo $oUt->AvventureConcluse();?>
		</DIV>
		<DIV id="idSoprannome">
			<?php
			if($oUtA->fControlPermission($_SERVER["SITO"],"cUtente","modify")){
				$oForm->formInput("text","txtSoprannome",$oUt->dati["soprannome"],strlen($oUt->dati["soprannome"]),"");
			}
			?>
		</DIV>
		<DIV id="idCollaborazione">
			<?php
			if($oUtA->fControlPermission($_SERVER["SITO"],"cUtente","modify")){
				echo "<input type='text' name='txtCollaborazione' value='" . $oUt->dati["Collaborazione"] . "'>";
			}else{
				echo "<input type='text' name='txtCollaborazione' value='" . $oUt->DescCollaborazione . "' readonly>";
			}
			?>
		</DIV>
		<DIV id="idRuoloGioco">
			<?php echo "Non implementato";?>
		</DIV>
		<DIV id="idOnLine">
			<?php if($oUt->OnLine=true){
				echo "<font color=green><b>On-line</b></font>";
			}else{
				echo "<font color=red><b>Off-line</b></font>";
			}?>
		</DIV>
		<DIV id="idInattivo">
			<?php $oUt->Inattivo()?>
		</DIV>
	</form>
</BODY>
</HTML>
<?php
$oDb->Close();
?>
