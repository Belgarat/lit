<?php
/*
 * Created on 25/set/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once($_SERVER["DOCUMENT_ROOT"]."/playbyforum/class/oConn.php");
$oConn = new connessione("/inc/config1.php");
$oConn->connect();

$sSql="select id from userdb where id<>1 and id<>3";
$risultato=mysql_query($sSql);
echo mysql_error();
while($row=mysql_fetch_row($risultato)){
	$sSql="insert into tbl_users_groups set id_user=" . $row[0] . ", id_group=3";
	mysql_query($sSql);
	echo mysql_error();
}
mysql_free_result($risultato);
?>
