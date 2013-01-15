<?php
session_start();
require_once("../../cfg/config.php");

$url_section = explode(".",$_SERVER["SERVER_NAME"]);
require_once("../../cfg/" . $url_section[0] . "/global.php");
require_once(SRV_ROOT . "/" . $url_section[0] . "_inclusions.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUtSrc = new cUtente();
$oUt = new cUtente();

$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oValidate = new cValidate();

$oPriMsg = new cPrivatemessage();
$oPriMsg->set_id_sito($_SERVER["SITO"]);
$oPriMsg->set_user($oPriMsg->let_id_sito(),$oUt);

$idfolder = (int)$_GET["idfolder"];

$control=$oPriMsg->let_permission($oPriMsg->let_id_msg());

if($control['cPrivatemessage']['Show']==1){

    $aMessages=$oPriMsg->let_messages($idfolder);

    ?>
    <ul class="msg_list">
    <li class="title">Messaggi</li>
    <?php

	$html="";
	if(!isset($idfolder)){
		$idfolder=$oPriMsg->default_folder;
	}
	
	$aMessages=$oPriMsg->let_messages($idfolder);

	if(count($aMessages)!=0){
		foreach($aMessages as $key => $msg){
			$color="";
			if($idfolder==2){
				$oUtSrc->id=(int)$msg[4];
				if($msg[5]==0){
					$color="style='font-weight:bold;'";
				}
			}else{
				$oUtSrc->id=(int)$msg[2];
				if($msg[6]==0){
					$color="style='font-weight:bold;'";
				}					
			}
			$oUtSrc->Leggi();
			######### IMPLEMENTARE FILTRO HTML PER JSON DEL MESSAGGIO RESTITUITO #############
			$ajax="show_message('id_msg_show_area','src/ajax/PrivateMessage_read.php','get','idmsg=" . $msg[0] . "');$('idmsg_".$msg[0]."').setStyle('font-weight:normal;');";
			$html.="<li id=\"idmsg_".$msg[0]."\" ".$color.">". date("d-m-y", $msg[3]) ." - ".$oUtSrc->dati["Name"]." - <a title=\"Premi per aprire il messaggio\" href=\"#\" onclick=\"javascript: " . $ajax  . "\">" . utf8_encode($msg[1]) . "</li></a>\r\n";
		}
	}

	echo $html;

}else{
    echo "Impossibile procedere. <SPAN style='color: red;'><br \>Autorizzazioni insufficienti.</SPAN><br \><br \>\r\n";
}


?>
