<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();
$oValidate = new cValidate();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oMsg = new cMessage();
$oMsg->set_id_sito($_SERVER["SITO"]);
$oMsg->set_user($_SERVER["SITO"], $oUt);
$oMsg->set_id_utente($_SESSION["ID"]);
$oMsg->read_last_messages_user();

$dati=$oMsg->let_messages_list();

if(count($dati)!=0){
	foreach($dati as $value){
		if($value!="-1"){
			?>
			<a title="Chiudi messaggio di avviso!" alt="Chiudi messaggio di avviso" href="javascript:void(0);" onclick="javascript: ffade('head_message'); var check = new Ajax.Request('<?php echo HTTP_AJAX . '/cMessage_set_check.php'; ?>',{method: 'post', parameters: 'ID=<?php echo key($dati); ?>'});"><img id="head_message_button" src="<?php echo SITE_IMG . "/" . "close_swords.png";?>"></a>
			<?php
			echo "<br>" . $value[1];
		}else{
			?>
			<!--<img src="<?php echo HTTP_IMG . "/" . "blankpoint.gif";  ?>" onload="javascript: $('head_message').hide()">-->
			<?php
		}
	}
}
?>
