<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oValidate = new cValidate();

$oPriMsg = new cPrivatemessage();
$oPriMsg->set_id_sito($_SERVER["SITO"]);
$oPriMsg->set_user($oPriMsg->let_id_sito(),$oUt);

$control=$oPriMsg->let_permission(0);

if($control['cPrivatemessage']['Show']==1){
	
	$dst=$oValidate->_sql($_POST["msg_new_dst"]);
	$obj=$oValidate->_sql($_POST["msg_new_obj"]);
	$body=$oValidate->_sql($_POST["msg_new_body"]);
	$sign=$oValidate->_sql($_POST["msg_new_sign"]);
        
    $result=$oPriMsg->send($dst,$obj,$body,$sign);
    
    if($result){
    	echo "<SPAN style='color: green;'><br \>Operation: success!</SPAN><br \><br \>\r\n";
    	?>
    	<input type="button" value="Close" onClick="javascript: $('id_msg_write').reset(); $('id_msg_new_box').fade({duration: 0.5});$('id_msg_write').fade({duration: 0.5});">
    	<?php
	}else{
		echo "<SPAN style='color: red;'><br \>Operation: failed!</SPAN><br \><br \>\r\n";
    	?>
    	<input type="button" value="Close" onClick="javascript: $('id_msg_new_box').fade({duration: 0.5});">
    	<?php		
	}

}else{
	
    echo "Impossibile procedere. <SPAN style='color: red;'><br \>Autorizzazioni insufficienti.</SPAN><br \><br \>\r\n";
	?>
	<input type="button" value="Close" onClick="javascript: $('id_msg_new_box').fade({duration: 0.5});">
	<?php    
    
}


?>
