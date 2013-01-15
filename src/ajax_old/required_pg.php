<?php

require_once("../../cfg/pbf_global.php");
require_once("../../cfg/config.php");
//require_once(SRV_ROOT."/class/db/database.php");
//require_once(SRV_ROOT."/class/db/query.php");
//require_once(SRV_ROOT."/class/pg/Opg.php");

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
		default:
			require_once(SRV_ROOT."/class/$classname.php");
	}
}
?>
