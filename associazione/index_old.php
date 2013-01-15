<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/associazione/inc/global.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/cfg/config.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/db/database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cLog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cDate.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/oConn.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/utente.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cValidate.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cForm.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cPage.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cMenu.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cNews.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cForum.php");


$oLog = new cLog(0);
$oLog->set_log_level(1);
$oLog->display=1;
//$oConn = new connessione("/playbyforum/inc/config1.php");
$oValidate = new cValidate;
$oMenu = new cMenu;
//$oConn->connect();
$oDb = new Db;
$oDb->Open();

$oUt = new cUtente($oConn);
$oNews = new cNews($oConn);
$oNews->id_sito = $_SERVER["SITO"];
cPage::$id_sito=$_SERVER["SITO"];

if($_GET["h"]!=""){
	$ActUt=$oUt->active_user($_GET["h"]);
	if($ActUt==true){
		$oUt->id=intval($ActUt);
		$oUt->add_user_group($_SERVER["SITO"]);
		echo "Il tuo utente è stato attivato! Ora puoi eseguire la login.";
		//$oUt->Leggi();
	}
}else{
	$oUt->id=intval($_SESSION["ID"]);
	$oUt->Leggi();
}

$oLog->id_sito=$_SERVER["SITO"];
$oLog->user_id=$_SESSION["ID"];
if(($_GET["IdP"]=="") || (!isset($_GET["IdP"]))){
	$IdP=22;
}else{
	$IdP=$_GET["IdP"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<HTML>
	<HEAD>
		<title>Lux In tenebra - Associazione culturale ludico letteraria</title>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" >
		<script type="text/javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="/js/prototype.js"></script>
		<script src="/js/scriptaculous/scriptaculous.js" type="text/javascript"></script>
		<script src="js/multimenu.js" type="text/javascript"></script>
		<script language="javascript" type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/amb.css">
		<link rel="stylesheet" type="text/css" href="./style.css">
		<link rel="stylesheet" type="text/css" href="./site.css">
		<link rel="stylesheet" type="text/css" href="./css/multimenuvert.css">
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="./site_ie.css">
			<link rel="stylesheet" type="text/css" href="./css/multimenuvert_ie.css">
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="./css/content.css">
		<?php
			$oNews->include_css();
		?>		
		<LINK REL="SHORTCUT ICON" HREF="./images/sword.ico" TYPE="image/x-icon">
		<meta name="description" content="un sito dedicato al gioco di ruolo e alla possibilità di giocare con altri appassionati di d&amp;d o ad&amp;d intorno su un forum creato appositamente">
		<meta name="keywords" content="Fantasy, fantasy, Fantasy-online, fantasy on line, libri fantasy, gdr on line, gdr on-line, giochi di ruolo fantasy, giochi di ruolo on line, giochi di ruolo on-line, d&amp;d, ad&amp;d, D&amp;D, AD&amp;D, forum di gioco, forum d&amp;d, Forum D&amp;D, forum d&amp;d on line, forum ad&amp;d, forum AD&amp;D, elfi, fate, mezzelfi, umani, razze d&amp;d, giocate, ambientazione ad&amp;d, ambientazione D&amp;D, ambientazione AD&amp;D, ambientazione d&amp;d, iscriviti forum d&amp;d, giocatori, master, Master, Master D&amp;D, Master d&amp;d, master d&amp;d, master D&amp;D, citt� virtuale fantasy, giocare on line, giocare on-line, draghi, storie">
		<meta name="copyright" content="&amp;copyright 2004 Lux In Tenebra Group">
		<SCRIPT type="text/javascript">
				var MenuAttivo = null;
				var precedente;
		        function apriupload(lForm)
		        {
		        open("./"+lForm, "", "width="+500+",height="+400+",scrollbars=yes, left="+((screen.width-500)/2)+",top="+((screen.height-600)/2)+"");		        
		        }
		        function Open_url(IdDiv,lForm)
		        {
		        	new Ajax.Updater(IdDiv, lForm);
		        }
				tinyMCE.init({
				    theme : "advanced",
				    mode: "exact",
				    elements : "news",
				    theme_advanced_toolbar_location : "top",
				    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,"
				    + "justifyleft,justifycenter,justifyright,justifyfull,formatselect,"
				    + "bullist,numlist,outdent,indent,separator,link,unlink,anchor,image,undo,redo,cleanup,code,separator,charmap",
				    theme_advanced_buttons2 : "",
				    theme_advanced_buttons3 : "",
				    height:"350px",
				    width:"600px",
				    //file_browser_callback : 'myFileBrowser'
				});
		</SCRIPT>

	</HEAD>
	<body id="body">
		<div id="contenitore">
			<div id="head">
			</div>
			<div id="top" style="color:#b95033;">
				<div style="float:left;">
					<a class="LinkBanner" href="
					<?php
					echo $_SERVER["DOMAIN"];
					?>
					">Homepage</a>
				</div>
				<div>
				<?php
				if ($_SESSION["ID"]==""){
					?>
					<a class="LinkBanner" href="./index.php?IdP=23"> - Login - </a>
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
			<div id="left">
				<div id="IdPrincipale">					
					<?php
						//<ul class='menu3'>
						$oMenu->ShowPreview($_SERVER["SITO"],1);						
						//</ul>
					?>					
				</div>
				<div class="BarraMenu">
					Notizie
				</div>
				<div id="right">
					<?php
					$aAllow["Show"]=$oUt->fControlPermission($_SERVER["SITO"],"cNews","show");
					$aAllow["Create"]=$oUt->fControlPermission($_SERVER["SITO"],"cNews","create");
					$aAllow["Modify"]=$oUt->fControlPermission($_SERVER["SITO"],"cNews","modify");
					$aAllow["Delete"]=$oUt->fControlPermission($_SERVER["SITO"],"cNews","delete");						
					$oNews->id_tipo=1;
					$oNews->show($aAllow);
					//fNews($_SERVER["SITO"]);
					?>
				</div>
			</div>
			<?php
			if($IdP==1000){
			?>
				<div id="main">
			<?php
			}else{
			?>
				<div id="main_no_news">
			<?php
			}
				switch($_GET["action"]){
					case "edit":
						//edita il contenuto passato
						if($oUt->fControlPermission($_SERVER["SITO"],"cContent","modify",cContent::read_id_content($IdP))==1){						
							cContent::modify_cont($_GET["IdP"]);
						}
						break;
					case "delete":
						//elimina il contenuto
						if($oUt->fControlPermission($_SERVER["SITO"],"cContent","delete",cContent::read_id_content($IdP))==1){						
							cContent::delete_cont($_GET["IdP"]);
						}
						break;
					case "save":
						//edita il contenuto passato
						if($_GET["IdP"]==""){
							if($oUt->fControlPermission($_SERVER["SITO"],"cContent","create",cContent::read_id_content($IdP))==1){
								$IdPgNew=cContent::save_cont("",$_SESSION["ID"]);
								echo "Inserimento avvenuto correttamente.<br>";
								echo "Cliccare <a href='./index.php?IdP=" . $IdPgNew . "'>QUI</a> per completare l'operazione.'";
							}else{
								echo "Non disponi dei permessi necessari.<br>";
								echo "Cliccare <a href='./index.php'>QUI</a> per completare l'operazione.'";								
							}
						}else{
							if($oUt->fControlPermission($_SERVER["SITO"],"cContent","modify",cContent::read_id_content($IdP))==1){
								cContent::save_cont($IdP,$_SESSION["ID"]);
								echo "Contenuto aggiornato correttamente.<br>";
								echo "Cliccare <a href='./index.php?IdP=" . $_GET["IdP"] . "'>QUI</a> per completare l'operazione.'";
							}else{
								echo "Non disponi dei permessi necessari.<br>";
								echo "Cliccare <a href='./index.php'>QUI</a> per completare l'operazione.'";								
							}
						}
						break;
					default:
						?>
						<div style="display:block;float:right;text-align:right;width:30%;border-width:0px 0px 0px 0px;border-color:white;border-style:solid;">
						<?php
						$aAllow="";
						$aAllow["Create"]=$oUt->fControlPermission($_SERVER["SITO"],"cContent","create",cContent::read_id_content($IdP));
						$aAllow["Modify"]=$oUt->fControlPermission($_SERVER["SITO"],"cContent","modify",cContent::read_id_content($IdP));
						$aAllow["Delete"]=$oUt->fControlPermission($_SERVER["SITO"],"cContent","delete",cContent::read_id_content($IdP));
						cContent::DesignBar($aAllow,$IdP);
						?>
						</div>
						<br>
						<?php
						//mostra il contenuto della pagina passando l'ID della stessa.
						if($oUt->fControlPermission($_SERVER["SITO"],"cContent","show",$IdP)==1){
							cContent::show($IdP);
						}
				}	
				?>
			</div>
		</div>
		<!--<div id="bottom">
		<?php
			fCopyright();
		?>		
		</div>-->
	</body>
</HTML>
<?php
$oConn->closedb($oConn);
?>
