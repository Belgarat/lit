<p Class="cTestoNorm">
<b>Faq di LiT - ( a cura dei moderatori )</b>
<br>
<table border="0" bordercolor="#4E1D69" cellSpacing=2 cellpadding="10" width="100%" border=0 valign="top">
<tr>
<td vAlign=top width="100%">
<?php
$sSql = "select * from tbl_faq order by ordinale,id asc";
$risultato=mysql_query($sSql);
if (!$risultato){
	echo "Problemi riscontrati nella generazione della pagina. Riprovare più tardi.";	
}else{
	if (mysql_num_rows($risultato)>0){
		$iC=1;
		while ($row=mysql_fetch_assoc($risultato)){
			?>
			<DIV class="cTestoNorm" style="color: #835084;"><?php echo $iC . " - <b>Domanda:</b> " . $row["domanda"]?><br><br></DIV>
			<DIV class="cTestoNorm"><?php echo "<b>Risposta:</b> " . $row["risposta"]?></div>
			<BR><br>
			<?php
			$iC+=1;
		}
	}else{
		echo "<DIV class='cTestoNorm'>Nessuna FAQ presente.</div>";
	}
}
?>
		<BR>
    	<BR>
	    </td>
    </tr>
</table>
