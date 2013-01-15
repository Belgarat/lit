<?php 
/*
-Miodifiche apportate da Marco 06/11/2007
-aggiunto variabili globali includendo il file global.php
-Messo percorso assoluto per il CSS
-Inserito in modo automatico il nome utente nel campo Nome
-Per standard già utilizzato nel resto di LiT ho cambiato la data e l'ho fatta inserire dalla query con il comando time e settando il campo nel DB come intero.

	20090718 - Klown - __autoload() implementation
*/

session_start();
require_once('inclusions.php');

$oValidate = new cValidate;

$oDb = Db::GetInstance();
$oDb->Open();

$cBug = new cBug();

$oUt = new cUtente();
$oUt->id=intval($_SESSION["ID"]);
$oUt->Leggi();

$cBug->set_id_sito($_SERVER["SITO"]);


?>
				<div id="contenitore_bug">
					<form action="<?php echo HTTP_AJAX.'/bug_save.php' ?>" method="post">
						<br><br><br>Inserisci il tuo nome<br><br><br>
  						<input type="text" name="Nome" value="<?php echo $oUt->dati["Name"]; ?>" maxlength="20"/>
  						<br><br><br>Inserisci un argomento<br><br><br>
  						<input type="text" name="Titolo" maxlength="100" />
  						<br><br><br>Scrivi il problema<br><br><br>
  						<textarea name="Problema" rows="10" cols="50" wrap="on"></textarea>
   						<br><br><br>
  						<input type="submit" name="Inserisci" value="Inserisci" /><br><br><br>
   					</form>
  				</div>
