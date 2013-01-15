<?php
/*
 * Created on 02/ott/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
//require_once($_SERVER["DOCUMENT_ROOT"] . "/playbyforum/class/cMail.php");
require_once("../class/cMail.php");

$oMail = new cMail;

$oMail->HTML=true;
$oMail->From="master@luxintenebra.net";
$oMail->Add_To("viacart@gmail.com");
$oMail->Add_To("baran@luxintenebra.net");
$oMail->Add_Cc("belgarat@luxintenebra.net");
$oMail->Add_Bcc("viacart@gmail.com");
$oMail->object="test";
$oMail->message="<b>Ciao</b>";
$oMail->Preview();

?>
