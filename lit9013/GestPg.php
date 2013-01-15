<?php
	//session_start()
	/*
	* Page that manages the PG visualizzation
	* 
	* developper: Giacomo "Klown" Bella
	* start date: 29/11/2008
	* 
	* commenti: 20081129 start developing
	* 			20081201 some html stuff & tests :)
	* 			20081204 debugging & test
	* 
	*/
	
	require_once("../cfg/pbf_global.php");
	require_once("../cfg/config.php");
    require_once(SRV_ROOT."/class/cValidate.php");
    require_once(SRV_ROOT."/class/db/database.php");
    require_once(SRV_ROOT."/class/db/query.php");
	require_once(SRV_ROOT."/class/pg/Opg.php");
	
	$oDb = Db::getInstance();
	$oDb->Open();
	
	$pg= new Opg($_GET["id"]);
	
	if (!isset($pg)){
		echo "errore nell'apertura del PG <br />";
	}else{
		$pg->leggi();
	}
	
	$pageName="GestPg";
	
	print $pageName.": right place<br />";
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>Lux in Tenebra - Gestione del PG</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<link href="../css/" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		<div class="description" >
			<p>Scheda per la gestione del PG <?php echo $pg->letData("name");?> </p>
		</div>
		<div id="pg_main">
			<table class="pg_presentation">
			    <tr>
			        <td>
			        </td>
			    </tr>
			</table>
		</div>
	</body>
</html>
