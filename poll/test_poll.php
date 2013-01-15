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
<?php
// this is all you need :)
if($_SESSION["ID"]!=0){
$id_poll=@$_GET["id"];


$test = new miniPoll($id_poll);
$test->IdUt=@$_SESSION["ID"];


$test->pollForm($iduser);

@mysql_close($connection);
}else{
	echo "Prima devi fare Login nel portale e quindi votare!";
}

?>

</body>
</html>
