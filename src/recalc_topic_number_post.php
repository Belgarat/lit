<?php
session_start();
require_once("../cfg/config.php");
require_once("../cfg/pbf_global.php");
require_once(SRV_ROOT."/class/db/database.php");

$oDb = Db::getInstance();
$oDb->Open();

$q = new Query();
$q->tables = array("messagedb");
$q->fields = array("id");
$q->filters = "ReplyID=0";

if($q->Open()){
	while($row = $q->GetNextRecord()) {
		$qu = new Query();
		$qu->tables = array("messagedb");
		$qu->fields = array("count(id)");
		$qu->filters = "ReplyID=".$row[0];
		if($qu->Open()){
			$rowupdate=$qu->GetNextRecord();
			$tot[$row[0]]=$rowupdate[0];
		}
		$qu->Close();
	}
	var_dump($tot);
}


foreach($tot as $key=>$value){
	$qu = new Query();
	$qu->tables = array("messagedb");
	$qu->fields = array("Replies");
	$qu->values = array($value);
	$qu->filters = "id=".$key;
	$qu->DoUpdate();
	$qu->Close();
}

echo "<span style='color:red;'><hr>Fine!</span>"

?>
