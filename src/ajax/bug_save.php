<?php
	/*
	*script che salva il bug
	*
	*	20091011 - Start development
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
$cBug->set_user($cBug->let_id_sito, $oUt);

if($_POST['Inserisci']=="Inserisci"){
	$name = $oValidate->_sql($_POST["Nome"]);
	$title = $oValidate->_sql($_POST["Titolo"]);		
	$problem = $oValidate->_sql($_POST["Problema"]);
	print_r($_POST);

	if ((isset($name) || $name) && (isset($title) || $title) && (isset($problem) || $problem)){
		$cBug->insertBug($name, $title, $problem);
		}
}

?>
