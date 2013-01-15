<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

if(array_key_exists("id",$_POST)){
	$id_pg=(int)$_POST["id"];
}else{
	$id_pg=(int)$_POST["_id"];
}

$oPg = new cPg();

$oPg->set_id_pg($id_pg);
$oPg->set_id_sito($_SERVER["SITO"]);
$oPg->set_user($_SERVER["SITO"],$oUt);

$control=$oPg->let_permission($oPg->let_id_pg());
var_dump($_POST);
if($control['cPg']['Modify']==1){
	
	$oPg->set_id_pg($id_pg);
	$oPg->set_table("pgdb","id");
	
	$result="";
	if($_POST["btn_edit_pg_base"]=="Salva"){
		$id_pg=(int)$_POST["_id"];
		$oPg->set_id_pg($id_pg);	
		foreach($_POST as $key => $value){
			if(substr($key,0,1)=="_"){
				$field=substr($key,1,strlen($key)-1);
				if($field!=""){
					$fields[]=$field;
					$values[]=$value;
				}
			}
		}
		$result=$oPg->updateData($fields,$values,"pgdb");
		if($result==""){
			$result="Salvataggio avvenuto!";
		}else{
			$result.=" - Contattare l'amministratore del sito.";
		}
	}

	if($_POST["btn_edit_pg_master"]=="Salva"){
		$id_pg=(int)$_POST["_id"];
		$oPg->set_id_pg($id_pg);	
		foreach($_POST as $key => $value){
			if(substr($key,0,1)=="_"){
				$field=substr($key,1,strlen($key)-1);
				if($field!=""){
					$fields[]=$field;
					$values[]=$value;
				}
			}
		}
		$result=$oPg->updateData($fields,$values,"pgdb");
		if($result==""){
			$result="Salvataggio avvenuto!";
		}else{
			$result.=" - Contattare l'amministratore del sito.";
		}
	}
	
	$oPg->leggi();

	$oPg->show_edit_pg("",$result);
	
}

?>
