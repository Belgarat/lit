<?php
function fDivLogin($iLeft,$iTop,$iWidth,$iHeight,$iColore,$sLabel,$sPercorso){
	if ($sLabel<>"") $sLabel = $sLabel . "<br>";	
?>
		<form name="frmLogin" id="Form1" action="<?php echo $sPercorso; ?>"  method=post>
			<?php
				echo $sLabel;
			?>
			<table>
			<tr>
			<td>
			Username: 
			</td>
			<td>
			<input size="13" type="text" name="txtUsername" ID="Text1">
			</td>
			</tr>
			<tr>
			<td>
			Password: 
			</td>
			<td>
			<input size="13" type="Password" name="txtPassword" ID="Password1"><br>
			</td>
			</tr>
			<tr>
			<td colspan="2" align="right">
			<input type="Submit" Name="Submit" Value="Entra" ID="Submit1">
			</td>
			</tr>
			</table>
		</form>
	<?php
	//Call fContatore()
	?>
<?php
}
?>

<?php
function fDivLogout($iLeft,$iTop,$iWidth,$iHeight,$iColore,$sLabel,$sPercorso,$Imm){
	if ($sLabel<>"") $sLabel = $sLabel . "<br>";
?>
	<form name="frmLogin" id="idLogin" action="<?php echo $sPercorso ?>"  method=post>
		<center>
		<?php
			echo $sLabel;
		?>
		<br>
		<?php
		if (substr($Imm,0,1)=="."){
			echo "<img height='70' src='".substr($Imm,1,strlen($Imm)-1)."'>";
		}else{
			echo "<img height='70' src='".$Imm."'>";
		}
		?>
		<BR>
		<input style="{
						BORDER-RIGHT: #682782 thin solid;
						BORDER-TOP: #682782 thin solid;
						FONT-WEIGHT: bold;
						BACKGROUND-IMAGE: url(./forum/SfTasti.jpg);
						BORDER-LEFT: #682782 thin solid;
						CURSOR: hand;
						COLOR: #82649b;
						BORDER-BOTTOM: #682782 thin solid;
						BACKGROUND-COLOR: transparent;
		}" type="Submit" Name="Submit" Value="Logout">
	</form>
	</center>
	<div style="text-align:center;">
	<?php
	//Call fContatore()
	?>
	</div>
<?php
}
function fCopyright(){
	$data=getdate();
	$y=$data['year'];
	?>
	<div style="margin:5px;">	
	<p class="cTestoNorm"><center><font size=1 color=#606060>Copyright @ 2000-<?php echo substr($y,2,2); ?> Lux In Tenebra (LiT) Group All rights reserved.</center></font></p>
	<p class="cTestoNorm"><center><font size=1 color=#606060>Tutte le immagini non prodotte dai nostri grafici appartengono ai legittimi proprietari</center></font><p>
	</div>	
	<?php
}

function fElaboraNews($sN){

	$sEl=$sN;
	
	$sEl=str_replace("[","<",$sEl);
	$sEl=str_replace("]",">",$sEl);
	
	return $sEl;
}


function fNews($id_sito){

        $sSql = "SELECT Autore,date_format(Date,'%Y') as Anno,date_format(Date,'%m') as Mese,date_format(Date,'%d') as Giorno,News FROM tbl_news where id_sito='" . $id_sito . "' and id_tipo=1 order by Date desc,Time desc LIMIT 0,3;";
		$ris = mysql_query($sSql);		
		
		if (!$ris){
			echo "Query fallita!";
			exit;
		}
		
        while ($riga = mysql_fetch_assoc($ris)){
                echo "<br>" . $riga["Autore"];
                echo " - " . date("d F Y",mktime(0,0,0,$riga["Mese"],$riga["Giorno"],$riga["Anno"]));				
                echo "<br>" . fElaboraNews($riga["News"]);
        }
}
?>