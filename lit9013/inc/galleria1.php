<?php
session_start();
$oUt = new cUtente($oConn);
$oUt->id=intval($_SESSION["ID"]);
$oUt->Leggi();

$sSql="select count(ID) from tblgalleria1";


$row=mysql_fetch_row(mysql_query($sSql));
$iTotImm=$row[0];

$sSql="select * from tblgalleria1";

$ris_gal=mysql_query($sSql);

$sSql="select filtro from tblgalfiltro order by filtro asc";

$risultato_filter=mysql_query($sSql);
switch ($_GET["act"]){
	case "del":
		if($oUt->fControlPermission(1,"modGalAdunanze","delete")==1 and $_GET["Risp"]="y"){
			$sSql="select Cartella,Nome from tblgalleria1 where Id=" . $_GET["Imm"];
			$risultato=mysql_query($sSql);
			if($risultato){
				$row_filter=mysql_fetch_assoc($risultato);
				if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/playbyforum/public/" . $row_filter["Cartella"] . "/" . $row_filter["Nome"])==true){
					$delfile=unlink($_SERVER["DOCUMENT_ROOT"] . "/playbyforum/public/" . $row_filter["Cartella"] . "/" . $row_filter["Nome"]);					
				}
				if($delfile){
					$sSql="delete from tblgalleria1 where id=" . $_GET["Imm"];
					mysql_query($sSql);
				}
			}else{
				echo "Immagine inesistente.<br>";
			}
			mysql_free_result($risultato);
			//DA DECIDERE LINK
			echo "<a href='./index.php?action=galleria&Pag=" . $_GET["Pag"] . "'>Indietro</a>";
		}else{
			if($oUt->fControlPermission(1,"modGalAdunanze","delete")==1){
				echo "Sicuro di voler cancellare l'immagine?";
				echo "<a href='./index.asp?" . $_GET . "&Risp=y'>Si</a> - <a href='./index.asp?action=galleria&Pag=" . $_GET["Pag"] . "'>No</a>";
			}
		}
	case "mod":
		if($oUt->fControlPermission(1,"modGalAdunanze","modify")==1){
			require_once($_SERVER["DOCUMENT_ROOT"] . "/playbyforum/inc/galleria/ModGal.php");
		}
	case "ins":
		if($oUt->fControlPermission(1,"modGalAdunanze","create")==1){
			require_once($_SERVER["DOCUMENT_ROOT"] . "/playbyforum/inc/galleria/InsGal.php");
		}
	default:
			?>
			<table border="0" bordercolor="#4E1D69" cellSpacing=2 cellpadding="10" width="100%" border=0 valign="top" ID="Table17">
				<tr>
				<td vAlign=top width="100%">
					<table border=0>
						<tr>
						<td colspan=2>
							<?php
							
							if($oUt->fControlPermission(1,"modGalAdunanze","modify")==1){
							?>
								<a href=javascript:apriupload('uploadImm.php')>Upload immagini</a>
							<?php
							}
							?>
						</td>
						<td align=right colspan=3>
						</td>
						</tr>						
						<tr>
							<?php
							if($oUt->fControlPermission(1,"modGalAdunanze","show")==1){
								ListaImm($iTotImm,$_GET["Imm"],$ris_gal);
							}
							?>
						</tr>
					</table>
				</td>
				</tr>
			</table>
			<?php	
}
							
	?>

<?php
function ListaImm($iTot,$iImm,$risultato){
	$iC=0; //contatore di record
	//iTot=0 //contatore dei record totali
	$iRec=15; //record per pagina
	$iPgs=0; //pagine totali
	$iPg=$_GET["Pag"];
	$iCat="";
	$iK=0;
	$iColonne=5;

	$oUt = new cUtente($oConn);
	$oUt->id=intval($_SESSION["ID"]);
	$oUt->Leggi();
	
	if($iPg==""){
		$iPg=1;
	}

	$iDaRec=($iRec * $iPg)-$iRec;
	
	if($iDaRec==0){
		$iDaRec=0;
	}

	$iPgs=$iTot / $iRec;
	
	if(($iPgs-round($iTot / $iRec))!=0){

		$iPgs=round($iPgs + 1);
		
	}
	
	//in caso di anteprima
	if($iImm!="") {
		echo "<tr><td align='center' colspan='" . $iColonne . "'>";
		echo "<img border='0' src='./public/" . fLinkImm($iImm) . "'>";
		echo "</td></tr>";
		echo "<tr><td align='center' colspan='" . $iColonne . "'>";
		echo "<font size='2'>Link da utilizzare per scheda PG: <font color='red'> http://www.luxintenebra.net/public/" . fLinkImm($iImm) . "</font></font>";
		echo "</td></tr>";
		echo "<tr><td align='center' colspan='" . $iColonne . "'>";
		echo "<a href='./index.php?IdP=" . $_GET["IdP"] . "&Pag=" . $_GET["Pag"] . "&search=" . $_GET["search"] . $_POST["search"] . "'>Chiudi anteprima</a>";
		echo "</td></tr>";
	}
	$iTest=0;	
	while($iC<($iRec*$iPg)){		
		if($iC>=$iDaRec){
			echo "<tr valign='top'>";
		}
		for($iK=1;$iK<=$iColonne;$iK++){
			if(!$row_gal=mysql_fetch_assoc($risultato)){
				$iC=$iRec * $iPg;
			}else{
				if($iC>=$iDaRec){
					?>
						<td bgcolor="#351c4a" width="150">
							<font size="2">
							<p align="center">
							<?php
							echo "<a href='./index.php?IdP=" . $_GET["IdP"] . "&Pag=" . $_GET["Pag"] . "&search=" .  $_GET["search"] . $_POST["search"] . "&Imm=" . $row_gal["Id"] . "'><img class='cImmOver'  border='0' height='75' src='" . "./public/" . $row_gal["Cartella"] . "/" . $row_gal["nome"] . "'></a><br>";
							echo round(($row_gal["dim"] / 1024)) . " Kb<br>";
							if($oUt->fControlPermission(1,"modGalAdunanze","modify")==1){
								echo "<a href='./index.php?action=galleria&Pag=" . $_GET["Pag"] . "&act=mod&Imm=" . $row_gal["id"] . "'>Modifica</a>";
							}
							if($oUt->fControlPermission(1,"modGalAdunanze","delete")==1){
								echo "<a href='./index.php?action=galleria&Pag=" . $_GET["Pag"] . "&act=del&Imm=" . $row_gal["id"] . "'> - Cancella</a>";
							}
							?>
							</p>
							</font>
						</td>
					<?php
				}
			}
			$iC++;
		}
		
		if($iC>=$iDaRec){
			echo "</tr>";
		}
	}
	
	echo "<tr><td align='center' colspan='" . $iColonne . "'>";
	if(!$row_gal=mysql_fetch_assoc($risultato)){
		echo "<a href='./index.php?IdP=" . $_GET["IdP"] . "&search=" . $_GET["search"] . $_POST["search"] . "'>Pagina " . $iPg . " di " . $iPgs . " clicca qui per tornare all'inizio...</a><br><br>";
		$iC=1;
		while($iC<=$iPgs){
			if(round($iPg)==$iC){
				echo " " . $iC;
			}else{
				echo " <a href='./index.php?IdP=" . $_GET["IdP"] . "&Pag=" . $iC . "&search=" .  $_GET["search"] . $_POST["search"] . "'>" . $iC . "</a>";
			}
			$iC++;
		}
	}else{
		echo "<a href='./index.php?IdP=" . $_GET["IdP"] . "&Pag=" . ($iPg + 1) . "&search=" .  $_GET["search"] . $_POST["search"] . "'>Pagina " . $iPg . " di " . $iPgs . " clicca qui per pagina seguente..</a><br><br>";
		$iC=1;
		while($iC<=$iPgs){
			if(round($iPg)==$iC){
				echo " " . $iC;
			}else{
				echo " <a href='./index.php?IdP=" . $_GET["IdP"] . "&Pag=" . $iC . "&search=" .  $_GET["search"] . $_POST["search"] . "'>" . $iC . "</a>";
			}
			$iC=$iC+1;
		}
	}
	echo "</td></tr>";
}

function fLinkImm($iImm){
	
	$sSql="select Cartella,Nome from tblgalleria1 where ID=" . $iImm;
	
	$risultato=mysql_query($sSql);
	
	if($risultato){
	
		$row=mysql_fetch_assoc($risultato);
		return $row["Cartella"] . "/" . $row["Nome"];
	
	}
	
}
?>
