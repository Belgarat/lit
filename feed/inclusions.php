<?php

require_once("../cfg/config.php");
require_once("../lib/lib_math.php");

function __autoload($classname){
	switch($classname){
		case "Query": //can have problems (case sensitivness)
			require_once("../class/db/$classname.php");
			break;
		case "Db": //better to modify php file name (ugly workaround)
			require_once("../class/db/database.php");
			break;
		default:
			require_once("./$classname.php");
	}
}
?>
