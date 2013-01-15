<p Class="cTestoNorm">
Gli amici delle Terre del Pentacolo
<br/>
<DIV class="cTestoNorm">
<?php
	fAlfabetoLink($_GET["Ord"]);
?>
</div>
<table border="0" bordercolor="#4E1D69" cellSpacing=2 cellpadding="10" width="100%" border=0 valign="top">
<tr>
<td vAlign=top width="100%">
<br><br>
<?php
if ((!isset($_GET["Ord"])) or ($_GET["Ord"]=="")){
	$sSql="select * from tbllink where Esito=1 order by Sito asc";
}else{
	$sSql="select * from tbllink where Esito=1 and left(Sito,1)='" . $_GET["Ord"] . "' order by Sito asc";
}
$risultato=mysql_query($sSql);
if (!$risultato){
	echo "Problemi riscontrati nella generazione della pagina. Riprovare più tardi.";
}else{
	if (mysql_num_rows($risultato)<>0){		
		while ($row=mysql_fetch_assoc($risultato)){
			?>
			<DIV class='cTestoNorm'><A target='_blank' href='<?php echo $row["URL"]?>'><?php echo $row["Sito"]?></A></DIV>
			<DIV class='cTestoNorm'><?php echo $row["Descrizione"]?></div>
			<DIV class='cTestoNorm'><A target='_blank' href='<?php echo $row["URL"]?>'><?php echo $row["URL"]?></A></DIV><BR><BR>
			<?php
		}
	}else{		
		echo "<DIV class='cTestoNorm'>Nessun link presente.</div>";		
	}
	mysql_free_result($risultato);
}
?>

			<BR>
    		<BR>    
	    </td>
    </tr>
</table>
<?php
function fAlfabetoLink($let=""){
	echo "<p align='center'>";
	echo "<a href='./index.php?action=link'>Tutti - </a>";

	for ($i=ord("A");$i<=ord("Z");$i++){
		if (chr($i)==$let){
			echo "<u>" . $let . "</u> ";
		}else{
			echo "<a href='./index.php?action=link&Ord=" . chr($i) . "'>" . chr($i) . " </a>";
		}	
	}
	echo "</p>";
	return;	
}
?>