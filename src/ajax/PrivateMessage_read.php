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
$oPriMsg->set_id_msg($_GET["idmsg"]);

$control=$oPriMsg->let_permission($oPriMsg->let_id_msg());

if($control['cPrivatemessage']['Show']==1){

    $readmessage = $oPriMsg->read_message();

    //$aMsg = array('Id'=>$oPriMsg->let_id_msg(),'Destinatario'=>$oPriMsg->let_name_dst($readmessage[3]),'Obj'=>$readmessage[0],'Body'=>$readmessage[1],'Sign'=>$readmessage[2]);
   
var_dump($oPriMsg->let_id_msg(),$readmessage[3],$readmessage[4],1);

    $oPriMsg->set_msg_status($oPriMsg->let_id_msg(),$readmessage[3],$readmessage[4],1);
    
	$tpl = implode("", file(SRV_ROOT . '/class/cPrivatemessage/read_msg.tbl.php'));
	
	$tpl = preg_replace("#<!-- IDMSG -->#", $oPriMsg->let_id_msg(), $tpl);
	$tpl = preg_replace("#<!-- DSTNAME -->#", $oPriMsg->let_name_dst($readmessage[3]), $tpl);
	$tpl = preg_replace("#<!-- OBJ -->#", $readmessage[0], $tpl);
	$tpl = preg_replace("#<!-- BODY -->#", $readmessage[1], $tpl);
	$tpl = preg_replace("#<!-- SIGN -->#", $readmessage[2], $tpl);
		
	echo $tpl;

    //echo json_encode($aMsg);

}else{
    echo "Impossibile procedere. <SPAN style='color: red;'><br \>Autorizzazioni insufficienti.</SPAN><br \><br \>\r\n";
}


?>
