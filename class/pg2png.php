<?php

require_once("../cfg/config.php");

$selectConnection = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME, $selectConnection);

echo "Aggionta colonna discriminante";
$query = "ALTER TABLE `pgdb` ADD `IdAutore` INT( 6 ) NOT NULL AFTER `IDUt`;";

mysql_query($query, $selectConnection) or die "non riesco ad aggiornare la tabella";

echo "Aggionrnamento nomi colonne\n";

echo 'Equipaggiamento->PngDove \n';
$query = "ALTER TABLE `pgdb` CHANGE `Equipaggiamento` `Png_dove` INT( 7 ) UNSIGNED NOT NULL;";
mysql_query($query, $selectConnection) or die "non riesco ad aggiornare la tabella";

echo 'Elimino Abilita';
$query = " ALTER TABLE `pgdb` DROP COLUMN `Abilita`;";
mysql_query($query, $selectConnection) or die "non riesco ad aggiornare la tabella";

echo 'Incantesimi->Fama'
"ALTER TABLE `pgdb` CHANGE `Incantesimi` `Png_fama` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
mysql_query($query, $selectConnection) or die "non riesco ad aggiornare la tabella";


$query = 'SELECT Id, Fama, Immagine, Dove, Nome, Ideatore, Storia, Descfisica, NoteMasterIdeatore FROM tblpng;';

$result=mysql_query($query, $selectConnection);

$insertConnection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
mysql_select_db(DB_NAME, $insertConnection);

while ($row=mysql_fetch_assoc($result)){
	$queryInsert = "INSERT INTO `pgdb` (`Png_fama`, `Photo`, `Png_dove`, `Username`, `IdAutore`, `bg`, `Descrizione`) values (".$row["Fama"].", "$row["Immagine"]", "$row["Dove"]", "$row["Nome"]", ".$row["Ideatore"].", ".$row["Storia"].", ".$row["descfisica"].") ";

	$mysql_query($queryInsert, $insertConnection);
	
	$idLastInsert = mysql_insert_id($insertConnection);
	
	$queryInsert = "select * from tblpngnote where idPng=".$row["Id"];
	
	$result = mysql_query($queryInsert, $insertConnection);
	($result) ? $note = mysql_fetch_row($result) : $note=NULL;
	
	$queryInsert = "insert into tblnotepg (NoteMaster, NoteGiocatore, IdPg) values (".$row["NoteMasterId"].", ".$note.", ".$row["Id"].")";

?> 
