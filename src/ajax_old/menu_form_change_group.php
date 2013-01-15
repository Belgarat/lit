<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();
$oForm = new cForm();
$oValidate = new cValidate();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oMenu = new cMenu();
$oMenu->set_id_sito($_SERVER["SITO"]);
$oMenu->set_user($_SERVER["SITO"],$oUt);

$action = $oValidate->_sql($oValidate->_txt(@$_POST["action"]));

$mnu_id = (int) @$_POST["mnu_id"];

$mnuitem = $oMenu->read_menu_item($mnu_id);

//Variabili con il comando ajax da lanciare a seconda della necessità
$cmd_apply_ajax="onclick='javascript:var modarea = new Ajax.Updater(\"id_modarea_" . $mnu_id . "\", \"" . HTTP_ROOT . "/src/ajax/menu_form_change_group.php\", ";
$cmd_apply_ajax.="{ method: \"post\", parameters: Form.serialize($(\"id_modarea_" . $mnu_id . "\")), onSuccess: \"\", onFailure: \"\" });'";

switch ($action){
	case 'save':

		if ($oMenu->change_group_menu_item($mnu_id, (int) @$_POST["value"])) {
			echo "<span style=\"color: green;\">Operation complete! New name is: " . (int) @$_POST["value"] . "!<br>Eseguire refresh per aggiornare pagina.</span>\r\n";
		} else {
			echo "<span style=\"color: red;\">Operation failed!</span>";
		}
		break;
	case 'abort':
		break;
	default:
		$oForm->formOpenForm("formMenuEdit");
		$oForm->formInput("hidden","mnu_id",$mnu_id);
		$oForm->formSelect("id_value","value",$oMenu->ListGroupsMenu($mnuitem["padre"]));
		$oForm->formInput("button","action","save","2",$cmd_apply_ajax);
		$oForm->formCloseForm();
		break;
}

?>
