<p Class="cTestoNorm">
<b>Lista utenti - Lux in Tenebra</b>
<br>
<?php
session_start();
$oUt = new cUtente($oConn);
$oUt->id=intval($_SESSION["ID"]);
$oUt->Leggi();
?>
<div style="width:100%;float:none;display:block;">
<?php
	fAlfabetoUt();
?>
</div>
<div style="width:48%;float:left;display:block;">
<?php
	echo "Utenti registrati: " . $oUt->Numbers_users();
?>
</div>
<div style="text-align:right;width:48%;float:right;display:block;clean:both;">
<?php
	echo "Ultimo utente registrato: <b>" . $oUt->Last_user() . "</b>";
?>
</div>
<div style="float:left;display:block;">
<?php
switch($_GET["Ord"]){
	case "":
		$sSql = "SELECT id FROM userdb where Abilitato=0 order by ID desc limit 0,10";
		break;
	case "TUTTI":
		$sSql = "select id from userdb order by Name asc";
		break;
	default:
		$sSql = "SELECT id FROM userdb where Name like '" . $_GET["Ord"] . "%' and Abilitato=0";
		break;
}
$risultato=mysql_query($sSql);
if (!$risultato){
	echo "Problemi riscontrati nella generazione della pagina. Riprovare più tardi.";	
}else{
	if($oUt->fControlPermission($_SERVER["SITO"],"modListaUtenti","show",$IdP)==1){
		if (mysql_num_rows($risultato)>0){
			require_once($_SERVER["DOCUMENT_ROOT"]."/class/utente.php");
			?>
			<table align=center width='600' id='tblForum_postultimi'>			
				<tr class='cTitolo' style="font-size:12px;border-size:1px;border-style:dotted;border-color:red;">
					<td>
						Anzianit&agrave
					</td>
					<?php
					if($_SESSION["ID"]!=-1){
					?>
						<td>
							
						</td>
					<?php
					}
					?>
					<td>
						Utente
					</td>
					<td>
						Collaborazione
					</td>
					<td>
						Ruolo di gioco
					</td>
					<td>
						Ultimo login
					</td>
					<td>
						Stato
					</td>
				</tr>
			<?php
			while ($row=mysql_fetch_assoc($risultato)){
				$oUt = new cUtente($oConn);
				$oUt->id=intval($row["id"]);
				$oUt->Leggi();
				?>
				<tr>
					<td width="100">
						<?php
						echo $oUt->DescAnzianita;
						?>
					</td>				
					<?php
					if($_SESSION["ID"]!=-1){
					?>					
						<td width="10">
							<a alt="Invia messaggio privato a questo utente." href="./default.asp?action=msgnew&Dest=<?php
							echo $oUt->dati["Name"];
							?>"><img border=0 src="./forum/icons/icon1.gif"></a>
						</td>
					<?php
					}
					?>
					<td width="100">
					<b><font size='2'>
					<font size='2'><a style='{font-size:10px;}' href="javascript:apriUtente('<?php echo $oUt->id;?>')">
					<?php
					echo $oUt->dati["Name"];
					?>
					</a></font></b>
					</td>
					<td width="100">
					<b><font size='1'>
					<?php
					echo $oUt->DescCollaborazione;
					?>
					</font></b>
					</td>
					<td width="100">
					<b><font size='1'>
					<?php
					echo $oUt->RuoloGioco();
					?>
					</font></b>
					</td>
					<td width="100">
					<font size='1'>
					<?php
					echo $oUt->dati["LastLogin"];
					?>
					</td>				
					<td align="left" width="50">
					<?
					if($oUt->OnLine()){
						?>
						<font color="green"><b>On-line</b></font>
						<?php
					}else{
						?>
						<font color="red"><b>Off-line</b></font>
						<?php
					}
					?>
					</td>
				</tr>
		</font></p>
			<?php
			}
			?>
			</table>
			<div style="width:100%;text-align:center;"><i>Questi sono gli ultimi dieci utenti registrati.</i></div>
			<?php			
		}else{
			echo "<DIV class='cTestoNorm'>Nessuna utente da visualizzare.</div>";
		}
	}else{
		echo "<DIV class='cTestoNorm'>Non sei autorizzato alla visualizzazione della sezione.</div>";
	}
}

function fAlfabetoUt($let=""){
	echo "<p align='center'>";
	echo "<a href='./index.php?IdP=" . $_GET["IdP"] . "&Ord=TUTTI'>Tutti - </a>";

	for ($i=ord("A");$i<=ord("Z");$i++){
		if (chr($i)==$let){
			echo "<u>" . $let . "</u> ";
		}else{
			echo "<a href='./index.php?IdP=" . $_GET["IdP"] . "&Ord=" . chr($i) . "'>" . chr($i) . " </a>";
		}	
	}
	echo "</p>";
	return;	
}

?>
</div>