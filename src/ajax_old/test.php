<?php
session_start();
require_once("../../cfg/config.php");
require_once("../../cfg/pbf_global.php");
require_once(SRV_ROOT."/class/db/database.php");

require_once(SRV_ROOT."/class/utente.php");
require_once(SRV_ROOT."/class/cPg.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$id_pg=(int)$_POST["id"];

//if($control['cForum']['Show']==1){
	
	$oPg = new cPg();
	$oPg->set_id_pg($id_pg);
	$oPg->set_id_sito($_SERVER["SITO"]);
	$oPg->set_user($_SERVER["SITO"],$oUt);
	
	$oPg->leggi();

	$pg_data=$_POST;

	$err_empty="";
	foreach($pg_data as $key => $value){
		if($value==""){
            if($key!="_"){
    			$err_empty[]=$key;
            }
		}
	}
	
	if($err_empty==""){
		echo "<FORM id='form_edit_".$id_pg."'>";
		if($pg_data["edit"]!=""){
			echo "<INPUT type='hidden' name='id' value='".$id_pg."' readonly='readonly'>";
			echo "<INPUT type='hidden' name='field' value='".$pg_data["edit"]."' readonly='readonly'>";
			echo "<INPUT type='text' size='".strlen($oPg->ary_descpg[$pg_data["edit"]])."' name='field_".$pg_data["edit"]."' value='".$oPg->ary_descpg[$pg_data["edit"]]."'>";
			?>
			<INPUT type='button' onClick='javascript: var uli = new Ajax.Updater("NAME_<?php echo $id_pg?>","src/ajax/test.php",{method: "post",parameters: Form.serialize($("form_edit_<?php echo $id_pg ?>"))})' value='Ok'>
			<?php
		}else{
			$result=$oPg->updateField($id_pg, $pg_data["field"], $pg_data["field_".$pg_data["field"]],"pg_view");

			if($result){;
				echo $pg_data["field_".$pg_data["field"]];
			}else{
				echo "Errore nel salvataggio.";
			}
		}
		echo "</FORM>";
	}else{
		var_dump($err_empty);
		var_dump($pg_data);
		echo "<p><span style='color:red;'>Error: comilare tutti i campi!</span></p>";
		echo "<input type=\"button\" value=\"Close\" onClick=\"javascript: $('id_pg_manager_message').fade({duration: 0.5});\">";
	}


//}else{
    
	//echo "Impossibile procedere. <SPAN style='color: red;'><br \>Autorizzazioni insufficienti.</SPAN><br \><br \>\r\n";

//}
?>
