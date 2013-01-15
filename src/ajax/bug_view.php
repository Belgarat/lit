<?php

/*

il sorgente ha percorsi per essere piazzato direttamente nella cartella /src.
Non è ancora inserito nel menu per essere richiamato ed al momento può visualizzare soltanto 15 voci nei div a comparsa.
La versione definitiva potrà mostrare più pagine da 15 elementi.
D.k

 klown - 20090731 - The page shows only one bug with full details.
					inclusion.php file used
 klown - 20091007 - Table view of bugs

*/
//require_once("../cfg/config.php");
//require_once("../cfg/pbf_global.php");
//require_once(SRV_ROOT."/class/db/database.php");

session_start();
require_once("inclusions.php");

$oDb = Db::getInstance();
$oDb->Open();

$oUt = new cUtente();
$oUt->id=(int)$_SESSION["ID"];
$oUt->Leggi();

$oBug = new cBug();
$oBug->set_id_sito($_SERVER["SITO"]);
$oBug->set_user ($oBug->let_id_sito, $oUt);
    				
$oValidate = new cValidate();

$bugId = $oValidate->_sql($_POST['id']);

$bug = $oBug->readBug($bugId);

//read from the noticeboard
?>

<table style = "width:60%; text-align:center" border = 1>
	<tr>
		<td><?php echo $bug[Id];?></td><?php echo $bug[Titolo];?></td><td><?php echo $bug[Nome];?></td>
	</tr>
	<tr>
		<td colspan=3 ><?php echo $bug[Problema];?></td>
	</tr>
	<tr>
		<td><?php echo date('d/m/Y', $bug[Data]);?></td><td><?php echo$bug[Stato];?></td><td><?php  echo $bug[Incaricato] ? $bug[Incaricato] :  "Ancora da prendere in carico";?></td>
	</tr>
</table>

<?php echo $oBug->getFather($bug[Id])?>
