<?php
session_start();

require_once("./variabilenondefinita_inclusions.php");

$oDb = Db::getInstance();
$oDb->Open();

$oUt = new cUtente();

$oUt->id=(int)$_SESSION["ID"];

$oUt->Leggi();

$oLog = new cLog(1);
$oLog->set_log_level(1);
$oLog->display=1;
$oValidate = new cValidate;
$form = new cForm;

if($_GET["h"]!=""){
	$ActUt=$oUt->active_user($_GET["h"]);
	if($ActUt==true){
		$oUt->id=intval($ActUt);
		//$oUt->add_user_group($_SERVER["SITO"]);
		echo "Il tuo utente è stato attivato! Ora puoi eseguire la login.";
		//$oUt->Leggi();
	}
}else
{
	$oUt->id=intval($_SESSION["ID"]);
	$oUt->Leggi();
}

$oPage = new cPage();
$oPage->set_id_sito($_SERVER["SITO"]);
$oPage->set_opt(@$_GET["opt"]);
$oPage->set_user($oPage->let_id_sito(),$oUt);
if(($_GET["IdP"]=="") || (!isset($_GET["IdP"]))){
    if((int)$_GET["IdN"]!=""){
        $IdP=114;
        $oPage->set_id_page($IdP);
    }else{
        $IdP=$oPage->DefaultPage($oUt);
        $oPage->set_id_page($IdP);
    }        
}else{
	$IdP=$_GET["IdP"];
	$oPage->set_id_page($IdP);
}

$oCont = new cContent();
$oCont->set_id_sito($_SERVER["SITO"]);
$oCont->set_opt(@$_GET["opt"]);
$oCont->set_user($oCont->let_id_sito(),$oUt);


$oNews = new cNews();
$oNews->set_id_sito($_SERVER["SITO"]);
$oNews->set_opt(@$_GET["opt"]);
$oNews->set_user($oNews->let_id_sito(),$oUt);

$oMenu = new cMenu();
$oMenu->set_user($_SERVER["SITO"],$oUt);

$oPriMsg = new cPrivatemessage();
$oPriMsg->set_id_sito($_SERVER["SITO"]);
$oPriMsg->set_user($oPriMsg->let_id_sito(),$oUt);

$oLog->id_sito=$_SERVER["SITO"];
$oLog->user_id=$_SESSION["ID"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<HTML>
	<HEAD>
		<title>Lux In tenebra Forum gdr on line - <?php echo $oPage->page_title; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/scriptaculous/lib/prototype.js"></script>
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/scriptaculous/src/scriptaculous.js"></script>
		<link rel="stylesheet" type="text/css" href="css/variabilenondefinita_amb.css">
		<link rel="stylesheet" type="text/css" href="css/variabilenondefinita_style.css">
		<link rel="stylesheet" type="text/css" href="css/variabilenondefinita_site.css">
		<link rel="stylesheet" type="text/css" href="css/variabilenondefinita_multimenuvert.css">
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="css/variabilenondefinita_site_ie.css">
			<link rel="stylesheet" type="text/css" href="css/variabilenondefinita_multimenuvert_ie.css">
		<![endif]-->
		<script type="text/javascript" src="js/multimenu.js"></script>
		<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript" src="js/ajax.js"></script>
		<link rel="stylesheet" type="text/css" href="css/varibilenondefinita_content.css">
		<?php
			//Istazio oggetto PG
			$oPriMsg->include_js();
		?>
		<LINK REL="SHORTCUT ICON" HREF="<?php echo SITE_IMG ?>/sword.ico" TYPE="image/x-icon">
		<meta name="description" content="un sito dedicato al gioco di ruolo e alla possibilità di giocare con altri appassionati di d&amp;d o ad&amp;d intorno su un forum creato appositamente">
		<meta name="keywords" content="Fantasy, fantasy, Fantasy-online, fantasy on line, libri fantasy, gdr on line, gdr on-line, giochi di ruolo fantasy, giochi di ruolo on line, giochi di ruolo on-line, d&amp;d, ad&amp;d, D&amp;D, AD&amp;D, forum di gioco, forum d&amp;d, Forum D&amp;D, forum d&amp;d on line, forum ad&amp;d, forum AD&amp;D, elfi, fate, mezzelfi, umani, razze d&amp;d, giocate, ambientazione ad&amp;d, ambientazione D&amp;D, ambientazione AD&amp;D, ambientazione d&amp;d, iscriviti forum d&amp;d, giocatori, master, Master, Master D&amp;D, Master d&amp;d, master d&amp;d, master D&amp;D, citt� virtuale fantasy, giocare on line, giocare on-line, draghi, storie">
		<meta name="copyright" content="&amp;copyright 2004 Lux In Tenebra Group">
		<script type="text/javascript">
				var MenuAttivo = null;
				var precedente;
				
				Event.observe(window.document,'dom:loaded',init);				
				
		        function apriupload(lForm)
		        {
		        open("./"+lForm, "", "width="+500+",height="+400+",scrollbars=yes, left="+((screen.width-500)/2)+",top="+((screen.height-600)/2)+"");
		        }
		        function Open_url(IdDiv,lForm)
		        {
		        	new Ajax.Updater(IdDiv, lForm);
		        }
			new Ajax.PeriodicalUpdater('id_num_online','src/ajax/utente_users_online.php',{frequency: 12});
		</script>
	</HEAD>
	<body id="body">
		<div id="contenitore">
			<div id="id_right">
				<?php
				$oMenu->ShowPreview($_SERVER["SITO"],1);
				?>
				
			</div>		
			<div id="head">
			</div>
			<div id="top" style="color:#b95033;">
				<div style="float:left;">
					<a href="
					<?php					
					echo $_SERVER["MAIN"];
					?>
					">.: Homepage :.</a>
				</div>
				<div>
				<?php
				if ($_SESSION["ID"]==""){
					?>
					<a class="LinkBanner" href="./index.php?IdP=109">.: Login :. </a>
					<?php
				}else{
					echo " " . $oUt->dati["Name"] . " > ";
					?>
					<a id="id_msg_link" onClick="javascript: $('Privatemessage').toggle();" href="javascript: void(0);">.: Messaggi :.</a>
					<a class="LinkBanner" href="<?php echo $_SERVER["DOMAIN"]; ?>src/logout.php">.: Logout :.</a>
					<?php
				}
				?>
				</div>
				<div id="id_PluginBoard" style="display:none;">

				</div>
			</div>			
			<?php
			$oPriMsg->show();
			?>
			<div id="main_no_news">	
			<?php
				$aPermission = $oUt->fArrayPermission($_SERVER["SITO"],"cContent",$oCont->read_id_content($IdP));
				$act=$_GET["action"];
				if($_GET==null){
					$act="blog";
				}
				switch($act){
					case "edit":
						//edita il contenuto passato
						if($aPermission["Modify"]==1){
							$oCont->modify_cont($_GET["IdP"]);
						}
						break;
					case "delete":
						//elimina il contenuto
						if($aPermission["Delete"]==1){
							$oCont->delete_cont($_GET["IdP"]);
						}
						break;
					case "save":
						//edita il contenuto passato
						if($_GET["IdP"]==""){
							if($aPermission["Create"]==1){
								$IdPgNew=$oCont->save_cont("",$_SESSION["ID"]);
								echo "Inserimento avvenuto correttamente.<br>";
								echo "Cliccare <a href='./index.php?IdP=" . $IdPgNew . "'>QUI</a> per completare l'operazione.'";
							}else{
								echo "Non disponi dei permessi necessari.<br>";
								echo "Cliccare <a href='./index.php'>QUI</a> per completare l'operazione.'";
							}
						}else{
							if($aPermission["Modify"]==1){
								$oCont->save_cont($IdP,$_SESSION["ID"]);
								echo "Contenuto aggiornato correttamente.<br>";
								echo "Cliccare <a href='./index.php?IdP=" . $_GET["IdP"] . "'>QUI</a> per completare l'operazione.'";
							}else{
								echo "Non disponi dei permessi necessari.<br>";
								echo "Cliccare <a href='./index.php'>QUI</a> per completare l'operazione.'";
							}
						}
						break;
					case "blog":
						echo "<br>";
						
						$oMsg = new cMessage();
						$oMsg->show();

						//mostra il contenuto della pagina passando l'ID della stessa.
						if($aPermission["Show"]==1){
							$oCont->viewmode="blog";
							$oCont->blog_parent_menu=110;
							$oCont->enable_comments();
							$oCont->show($IdP);							
						}
						break;					
					default:						
						?>						
						<div style="display:block;float:right;text-align:right;width:30%;border-width:0px 0px 0px 0px;border-color:white;border-style:solid;">
						<?php
						$oCont->DesignBar($aPermission,$IdP);						
						?>
						</div>
						<br>
						<?php
						
						$oMsg = new cMessage();
						$oMsg->show();

						//mostra il contenuto della pagina passando l'ID della stessa.
						if($aPermission["Show"]==1){
							$oCont->show($IdP);							
						}
						
				}						
				?>
			</div>
		</div>
		<?php

			//include('google.php');
		?>
	</body>
</HTML>
