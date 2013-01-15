<?php
session_start();
require_once("../cfg/pbf/global.php");
require_once("../cfg/config.php");
require_once(SRV_ROOT."/class/utente.php");
require_once(SRV_ROOT."/class/db/database.php");
$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();
$oUtA = new cUtente();
$oUtA->id=$_SESSION["ID"];
$oUtA->Leggi();
$oUt->id=$_GET["PG"];
$oUt->Leggi();
?>

<SCRIPT LANGUAGE="javascript">
    function Chiudi()
    {
    window.close(this);
    }   
</SCRIPT>

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
	<DIV id="idMenu">
	<?php if(($_SESSION["ID"]==$oUt->id) or ($oUtA->fControlPermission($_SERVER["SITO"],"cUtente","modify"))){?>
		<b><a id="IdLinkMod" href="./ProfUtMod.php?UT=<?php echo $_GET["PG"];?>">Modifica dati utente</a> - </b>
		<?php if(array_search("admins",$oUtA->groups)){?>
			<b><a id="IdLinkMod" target="_blanck" href="./vedilog.asp?UT=<?php echo $_GET["PG"];?>">LOG</a> - </b>
		<?php } ?>
	<?php } ?>
		<b><a id="IdLinkMod" href="javascript:Chiudi()">Chiudi</a></b>
	</DIV>
	<DIV id="idNome">
		<?php echo $oUt->dati["Name"];?>
	</DIV>
	<DIV id="idAnzianita">
		<?php echo $oUt->DescAnzianita . " <a target='_blank' href='./legendaanzianita.asp'>(Legenda)</a>";?>
	</DIV>
	<DIV id="idImm">
		<img width="200" src="<?php echo $oUt->dati["ImmUt"];?>" border=0>		
	</DIV>
	<DIV id="idMail">
		<?php echo $oUt->dati["Email"];?>
	</DIV>
	<DIV id="idMSN">
		<?php echo $oUt->dati["MSN"];?>
	</DIV>
	<DIV id="idICQ">
		<?php echo $oUt->dati["ICQ"];?>
	</DIV>
	<DIV id="idSky">
		<?php echo $oUt->dati["Skype"];?>
	</DIV>
	<DIV id="idYahoo">
		<?php echo $oUt->dati["Yahoo"];?>
	</DIV>
	<DIV id="idMotto">
		<?php echo $oUt->dati["Motto"];?>
	</DIV>
	<DIV id="idFirma">
		<?php echo $oUt->dati["Signature"];?>
	</DIV>
	<DIV id="idPG">
		<?php echo $oUt->ListaPg();?>
	</DIV>
	<DIV id="idAvvCorso">
		<?php echo $oUt->AvventureInCorso();?>
	</DIV>
	<DIV id="idAvvConcluse">
		<?php echo $oUt->AvventureConcluse();?>
	</DIV>
	<DIV id="idSoprannome">
		<?php echo $oUt->dati["soprannome"];?>
	</DIV>
	<DIV id="idCollaborazione">
		<?php echo $oUt->DescCollaborazione;?>
	</DIV>
	<DIV id="idRuoloGioco">
		<?php echo $oUt->RuoloGioco();?>
	</DIV>
	<DIV id="idOnLine">
		<?php if($oUt->OnLine()==true){
			echo "<font color=green><b>On-line</b></font>";
		}else{
			echo "<font color=red><b>Off-line</b></font>";
		}?>
	</DIV>
	<DIV id="idInattivo">
		<?php echo $oUt->Inattivo();?>
	</DIV>
</BODY>
</HTML>
<?php
$oDb->Close();
?>
