<?php
session_start();
require_once("../cfg/config.php");
require_once("../cfg/pbf_global.php");
require_once(SRV_ROOT."/class/db/database.php");
require_once(SRV_ROOT."/class/cLog.php");
require_once(SRV_ROOT."/class/cDate.php");
require_once(SRV_ROOT."/class/utente.php");
require_once(SRV_ROOT."/class/cValidate.php");
require_once(SRV_ROOT."/class/cForm.php");
require_once(SRV_ROOT."/class/cMenu.php");

$oLog = new cLog(1);
$oLog->set_log_level(1);
$oLog->display=1;
$oValidate = new cValidate;

$form = new cForm;
$oDb = Db::getInstance();
$oDb->Open();

$oUt = new cUtente();

$oUt->id=intval($_SESSION["ID"]);
$oUt->Leggi();

$oMenu = new cMenu();
$oMenu->set_id_sito($_SERVER["SITO"]);
$oMenu->set_user($_SERVER["SITO"],$oUt);

$oLog->id_sito=$_SERVER["SITO"];
$oLog->user_id=$_SESSION["ID"];

$action = $oValidate->_sql($oValidate->_txt(@$_GET["action"]));
$mnu_id = (int) @$_GET["mnu_id"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<HTML>
	<HEAD>
		<title>Gestione menu - Lux In tenebra Paly by Forum - gdr on line</title>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" >
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/scriptaculous/lib/prototype.js"></script>
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/scriptaculous/src/scriptaculous.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_amb.css">
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_style.css">
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_site.css">
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_multimenuvert.css">
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_site_ie.css">
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_multimenuvert_ie.css">
		<![endif]-->
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/multimenu.js"></script>
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/ajax.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT ?>/css/pbf_content.css">
		<LINK REL="SHORTCUT ICON" HREF="<?php echo HTTP_ROOT ?>/img/pbf/sword.ico" TYPE="image/x-icon">
		<meta name="copyright" content="&amp;copyright 2004-09 Lux In Tenebra Group">
	</HEAD>
	<body id="body">
		<div id="contenitore">
			<div id="head">
			</div>
			<div id="top" style="color:#b95033;">
				<div style="float:left;">
					<a href="gest_menu.php">.: Menu principale :.</a>
				</div>
				<div>
				<?php
				if ($_SESSION["ID"]==""){
					?>
					<a class="LinkBanner" href="./index.php?IdP=20"> - Login - </a>
					<?php
				}else{
					?>
					Utente:
					<?php
					echo " " . $oUt->dati["Name"] . " - ";
					?>
					Ultimo login:
					<?php
					echo " " . date("d F Y - H.i.s",$oUt->dati["DataOraLogin"]);
					?>
					<a class="LinkBanner" href="../src/logout.php"> - Logout - </a>
					<?php
				}
				?>
				</div>
			</div>
			<?php
			switch ($action) 
			{
				case 'showgroup':
					echo "<div id='left'>\r\n";
					echo "&Egrave ora possibile con i tasti funzione presenti a sinistra: 
					cambiare il gruppo di appartenenza, rinominare, spostare e cancellare le varie voci.\r\n";
					echo "</div>";
					echo "<div id='main_no_news'>\r\n";
					$oMenu->ShowListGroupMenu($oMenu->let_id_sito(),$mnu_id);
					echo "</div>\r\n";
					break;
				default:
					echo "<div id='left'>\r\n";
					echo "Selezionare il gruppo di menu da modificare e gestire cliccando sul nome.\r\n";
					echo "</div>";
					echo "<div id='main_no_news'>\r\n";
					$oMenu->ShowGroupsMenu();
					echo "</div>\r\n";
					break;
			}
			?>
		</div>
	</body>
</HTML>
<?php
$oConn->closedb($oConn);
?>
