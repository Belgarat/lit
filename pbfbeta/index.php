<?php

$html_head="";
$tpl="";

//istanzio l'oggetto pg
$oPg = new cPg;
$oPg->set_id_sito($_SERVER["SITO"]);
$oPg->set_user($_SERVER["SITO"],$oUt);
/*
 * Apertura pagina
 */
echo DOCTYPE;
echo "<html>";

/*
 * Includo il tag HEAD e il tag di apertura body
 */
require_once('header.phtml');

/*
 * Includo i div id_Plugin,contenitore,head e top
 */
require_once('top.phtml');
//$tpl = implode("", file(SRV_ROOT . "/" . HOME .'/top.phtml'));
//$tpl = preg_replace("#<!-- NUM_USERS_ONLINE -->#", $oUt->num_users_online(), $tpl);
//echo $tpl;

// Inserisco il div nascosto dei messaggi privati
$oPriMsg->show();

/*
 * Includo i div main_no_news, blocco centrale della pagina
 */
require_once('body.phtml');

/*
 * Includo google javascript
 */
require_once('google.php');

/*
 * includo il pie di pagina che comprende la chiusura del div contenitore e del body
 */
require_once('footer.phtml');

/*
 * Chiusura pagina
 */
echo "</HTML>";
?>

