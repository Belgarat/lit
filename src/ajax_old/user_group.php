<?php
session_start();
require_once("../../cfg/config.php");

$url_section = explode(".",$_SERVER["SERVER_NAME"]);
require_once("../../cfg/" . $url_section[0] . "/global.php");
require_once(SRV_ROOT . "/" . $url_section[0] . "_inclusions.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();
$oForm = new cForm();
$oValidate = new cValidate();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$action = @$_GET["action"];
$user_id = (int) @$_POST["id_user"];

$oUtEdit = new cUtente();
$oUtEdit->id=$user_id;
$oUtEdit->Leggi();
$groups = $oUtEdit->groups;
/*
//Variabili con il comando ajax da lanciare a seconda della necessità
$cmd_apply_rename="onclick='javascript:var modarea = new Ajax.Updater(\"id_modarea_" . $mnu_id . "\", \"" . HTTP_ROOT . "/src/ajax/menu_form_rename.php\", ";
$cmd_apply_rename.="{ method: \"post\", parameters: Form.serialize($(\"id_modarea_" . $mnu_id . "\")), onSuccess: \"\", onFailure: \"\" });'";
*/

$onclickgroup="onclick='javascript:var modarea = new Ajax.Updater(\"list_group_area\", \"" . HTTP_ROOT . "/src/ajax/user_group.php\", ";
$onclickgroup.="{ method: \"post\", parameters: Form.serialize($(\"id_user_manager\")), onSuccess: function () { $(\"list_group_area\").style.display=inline; }});'";
switch ($action){
	case "list_user_group":
		?>
		<select id="id_list_user_group" name="list_user_group" size="28">
		<?php
		foreach ($groups as $grp){
			?>
			<option value="<?php echo $grp["id"]; ?>"><?php echo $grp["group"]; ?></option>
			<?php
		}
		?>
		</select>
		<?php
		break;
}
?>
