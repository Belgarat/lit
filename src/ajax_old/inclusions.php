<?php

require_once("../../cfg/pbf_global.php");
require_once("../../cfg/config.php");

function __autoload($classname){
	switch($classname){
		case "Opg":
			require_once(SRV_ROOT."/class/pg/$classname.php");
			break;
		case "Query": //case sensitiveness workaround
			require_once(SRV_ROOT."/class/db/".strtolower($classname).".php");
			break;
		case "Db": //ugly workaround. Better: modify php file name
			require_once(SRV_ROOT."/class/db/database.php");
			break;
		case "cUtente":
			require_once(SRV_ROOT."/class/utente.php");
			break;
		case "cContent":	
			require_once(SRV_ROOT."/class/cContenuto.php");
			break;
		default:
			require_once(SRV_ROOT."/class/$classname.php");
	}
}
?>
