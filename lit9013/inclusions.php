<?php

require_once("../cfg/pbf_global.php");
require_once("../cfg/config.php");
require_once("../lib/lib_math.php");

function __autoload($classname){
	switch($classname){
		case "Opg":
			require_once(SRV_ROOT."/class/pg/$classname.php");
			break;
		case "Query": //can have problems (case sensitivness)
			require_once(SRV_ROOT."/class/db/$classname.php");
			break;
		case "Db": //better to modify php file name (ugly workaround)
			require_once(SRV_ROOT."/class/db/database.php");
			break;
		case "cUtente": //modifiy file name please :)
			require_once(SRV_ROOT."/class/utente.php");
			break;
		case "cContent":
			require_once(SRV_ROOT."/class/cContenuto.php");
			break;
		case "cLogin":
			require_once(SRV_ROOT."/src/login.php");
			break;
		case "cRegister":
			require_once(SRV_ROOT."/src/register.php");
			break;
		default:
			require_once(SRV_ROOT."/class/$classname.php");
	}
}
?>
