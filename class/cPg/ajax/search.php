<?php
session_start();
require_once("../../cfg/config.php");

$url_section = explode(".",$_SERVER["SERVER_NAME"]);
require_once("../../cfg/" . $url_section[0] . "/global.php");
require_once(SRV_ROOT . "/" . $url_section[0] . "_inclusions.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

//if($control['cForum']['Show']==1){
	
	$oPg = new cPg();
	//$oPg->set_id_pg($id_pg);
	$oPg->set_id_sito($_SERVER["SITO"]);
	$oPg->set_user($_SERVER["SITO"],$oUt);

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
		if($oPg->exist_pg_user((int)$pg_data["txt_user_id"],strip_tags($pg_data["txt_pg_name"]))==0){;
			$new_id_pg=$oPg->createPG((int)$pg_data["txt_user_id"], strip_tags($pg_data["txt_pg_name"]), $pg_data["txt_pg_gender"], strip_tags($pg_data["txt_pg_descr"]), (int)$pg_data["txt_pg_age"]);
            if($new_id_pg!=0){
                $oPg->set_master($pg_data["txt_master_id"], $new_id_pg);
                $oPg->set_adventure($pg_data["txt_adventure_id"], $new_id_pg);
    			echo "<p><label>Nome personaggio: </label>".strip_tags($pg_data["txt_pg_name"])."</p>";
	    		echo "<p><label>Sesso: </label>".$pg_data["txt_pg_gender"]."</p>";
		    	echo "<p><label>Eta: </label>".(int)$pg_data["txt_pg_age"]."</p>";
			    echo "<p><label>Descrizione: </label>".strip_tags($pg_data["txt_pg_descr"])."</p>";		
    			echo "<span style='color:green;'>Personaggio creato con successo!</span><br>";
	    		echo "<input type=\"button\" value=\"Close\" onClick=\"javascript: $('id_form_new_pg').reset(); $('id_pg_manager_message').fade({duration: 0.5});\">";
            }else{
     		    echo "<p><span style='color:red;'>Error: problema sconosciuto contattare un amministratore!</span></p>";
	        	echo "<input type=\"button\" value=\"Close\" onClick=\"javascript: $('id_pg_manager_message').fade({duration: 0.5});\">";
           }
		}else{
			echo "<p><span style='color:red;'>Error: nome personaggio gi&agrave presente fra quelli di propriet&agrave dell'utente!</span></p>";
			echo "<input type=\"button\" value=\"Close\" onClick=\"javascript: $('id_pg_manager_message').fade({duration: 0.5});\">";
		}
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
