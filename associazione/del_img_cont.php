<?php
/*
 * Created on 10/ago/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/global.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/oConn.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/cPage.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/forum/Class/cForm.php");
$oConn = new connessione("/inc/config1.php");
$oConn->connect();
$img1 = new cImage;
$form = new cForm;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
	<HEAD>
		<title>Lux In tenebra - Messaggio interno</title>		
	 	<script language="javascript" type="text/javascript">
	 	function Chiudi(){
	 		close(this);	 		
	 	}
	 	</script>
		<script type="text/javascript" src="forum/js/ajax.js"></script>
		<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
		<link rel="stylesheet" type="text/css" href="./css/amb.css">
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="site.css">
		<link rel="stylesheet" type="text/css" href="./css/content.css">
		<LINK REL="SHORTCUT ICON" HREF="./images/sword.ico" type="image/x-icon">
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
 	<BODY>
	 	<?php
	 	$img1->id=$_GET["IdImm"];
	 	$imd1->id_sito=$_SERVER["SITO"];
	 	if($img1->read()){
			$form->formOpenDiv("","","","style=\"position:absolute;top:30%;margin:20px;\"");
			print $img1->title;
	 		$form->formBr();
	 		$form->formInput("button","Close","Chiudi","","OnClick=\"javascrit:Chiudi()\"");
	 		$form->formCloseDiv();
	 	}else{
			$form->formOpenDiv("","","","style=\"position:absolute;top:30%;margin:20px;\"");
	 		print(htmlentities("Immagine cancellata in precedenza o inesistente."));
	 		$form->formBr();
	 		$form->formInput("button","Close","Chiudi","","OnClick=\"javascrit:Chiudi()\"");
	 		$form->formCloseDiv();
	 	}
	 	?>
	</BODY>
</HTML>
<?php
$oConn->closedb();
?>