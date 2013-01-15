<?php
	require_once(SRV_ROOT."/class/utente.php");
	
	$oUt = new cUtente();
	$oUt->id=intval($_SESSION["ID"]);
	$oUt->Leggi();

	$oNews = new cNews();
	$aAllow["Show"]=$oUt->fControlPermission($_SERVER["SITO"],"cNews","show");
	$aAllow["Create"]=$oUt->fControlPermission($_SERVER["SITO"],"cNews","create");
	$aAllow["Modify"]=$oUt->fControlPermission($_SERVER["SITO"],"cNews","modify");
	$aAllow["Delete"]=$oUt->fControlPermission($_SERVER["SITO"],"cNews","delete");
	$oNews->set_id_sito($_SERVER["SITO"]);
	$oNews->set_id_page((int)$_GET["IdP"]);
	$oNews->set_title_page((int)$_GET["IdP"]);
	$oNews->set_opt(@$_GET["opt"]);
	$oNews->set_user($oNews->let_id_sito(),$oUt);	
	$oNews->id_tipo=2;
	$oNews->MaxNews=5;
	
?>
<?php
if((!$_GET["IdN"]=="")){
	?>
	<table border="0" bordercolor="#4E1D69" cellSpacing=2 cellpadding="10" width="100%" border=0 valign="top">
	<tr>
	<td vAlign=top width="100%">
	<?php
	$oNews->id_news = (int) $_GET["IdN"];
	$oNews->show_single_news($aAllow,0);
}else{	
	?>
	</br>
	<table border="0" bordercolor="#4E1D69" cellSpacing=2 cellpadding="10" width="100%" border=0 valign="top">
	<tr>
	<td vAlign=top width="100%">
	<?php
	$oNews->show_list_news($aAllow,0);
	$oNews->show($aAllow,0);
}
?>
</td>
</tr>
</table>
