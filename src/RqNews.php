<?php
/*
 * Created on 03/nov/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
session_start();
require_once("../cfg/config.php");
require_once("../cfg/pbf/global.php");
require_once(SRV_ROOT."/class/db/database.php");
require_once(SRV_ROOT."/class/cNews.php");
$oDb = Db::getInstance();
$oDb->Open();
$oNews = new cNews();
$oNews->set_id_sito($_GET["st"]);
$oNews->MaxNews=1;
$oNews->id_tipo=$_GET["tp"];
$oNews->id_news=$_GET["IdN"];
$oNews->read();
switch($_GET["Elem"]){
	case "id_news":
		echo $oNews->id_news;
		break;
	case "id_utente":
		echo $oNews->id_utente;
		break;
	case "author":
		echo $oNews->author;
		break;
	case "title":
		echo $oNews->title;
		break;
	case "body":
		echo $oNews->body;
		break;
	case "datetime":
		echo $oNews->datetime;
		break;
}

?>
