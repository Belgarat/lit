<?php
$url_section = explode(".",$_SERVER["SERVER_NAME"]);
if($url_section[0]=="pbfbeta") {
	$inc_dir="../../pbfbeta/";
	require_once($inc_dir . "cfg/global.php");
	require_once($inc_dir . "inclusions.php");
}else{
	$inc_dir="../../cfg/".$url_section[0];
	require_once($inc_dir . "/global.php");
	require_once(SRV_ROOT . "/" . $url_section[0] . "_inclusions.php");
}
?>
