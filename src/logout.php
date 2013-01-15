<?php
/*
 * Created on 12/giu/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
session_start();

function redirect($url,$tempo = FALSE ){
 if(!headers_sent() && $tempo == FALSE ){
  header('Location:' . $url);
 }elseif(!headers_sent() && $tempo != FALSE ){
  header('Refresh:' . $tempo . ';' . $url);
 }else{
  if($tempo == FALSE ){
    $tempo = 0;
  }
  echo "<meta http-equiv=\"refresh\" content=\"" . $tempo . ";url=" . $url . "\">";
  }
}


require_once("../cfg/config.php");

$url_section = explode(".",$_SERVER["SERVER_NAME"]);
//require_once("../cfg/" . $url_section[0] . "/global.php");
require_once("." . HTTP_ROOT . "/"  . $_SESSION["url_section"]  . "/cfg/global.php");
require_once("." . HTTP_ROOT . "/"  . $_SESSION["url_section"]  . "_inclusions.php");

$oDb = Db::getInstance();
$oDb->Open();

$oUt = new cUtente();

$oUt->id = @$_SESSION["ID"];
$oUt->Leggi();
$oUt->dati["DataOraLogin"]=time();
$oUt->Aggiorna($oUt->dati);


//ASINELLO - distruggere anche l'oggetto utenti.
$sSql="delete from useronline where IDUt=" . @$_SESSION["ID"];
mysql_query($sSql);

session_unset();
session_destroy();
session_start();

//setcookie("auth", $oUt->id . ";;" . md5($oUt->dati["Name"] . $oUt->dati["Password"]), time()-8200, "/", "luxintenebra.int");

redirect(HTTP_ROOT . "/index.php");
?>
