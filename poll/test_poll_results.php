<?php
session_start();
error_reporting(E_ALL); // when you finish testing you should change this to E_NONE

include_once ("miniPoll.class.php");
include_once ("config.php");

$connection = mysql_connect ($host, $user, $pass) or die ("Unable to connect");
mysql_select_db ($db) or die ("Unable to select database");

?>

<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Mini Poll Example</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="poll.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<h1>LiT</h1>
	<hr>
<?php

$test = new miniPoll((int)@$_GET['pollid']);
$test->IdUt=@$_SESSION["ID"];
if (isset($_GET['poll']) && is_numeric($_GET['pollid'])) {
	$pollid = $_GET['pollid'];

	if (isset($_GET['voteid']) && is_numeric($_GET['voteid'])) {
		$voteid = $_GET['voteid'];
		$test->processPoll($pollid, $voteid);
	}

}
#if (isset($_GET['pollid'])) {
#	$pollid = $_GET['pollid'];
#	$test->pollResults($pollid);
#}else{
#	$test->oldPolls(0);
#}

@mysql_close($connection);

?>

</body>
</html>
