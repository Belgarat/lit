<?php
function LeftMenu($iId,$iGrp)
{
	if ($iGrp=="") $iGrp=0;
	
	$sSql="Select * from tblamb_menu where Grp=" . $iGrp . " and Padre=" . $iId . " order by ord";
	$risultato = mysql_query($sSql);
	if (!$risultato){
		echo "Query di selezione menÃ¹ fallita.";
		exit;
	}
	
	if (mysql_num_rows($risultato)==0){
		echo "Nessuna voce di menù trovata";
	}
	else{
		
		while ($riga = mysql_fetch_assoc($risultato)){
			echo "<li>";
			echo "<a class='cLinkMenu' href='" . $riga["link"] . "&grp=" . $riga["Grp"] . "'>" . $riga["Menu"] . "</a>";
		}
		mysql_free_result($risultato);
	}
}
?>
