<?php
/*
 * Created on 12/ago/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once($_SERVER["DOCUMENT_ROOT"]."/playbyforum/inc/global.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/oConn.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/class/cPage.php");
//require_once($_SERVER["DOCUMENT_ROOT"] . "class/cContenuto.php");
//require_once($_SERVER["DOCUMENT_ROOT"] . "class/cImmagini.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/class/cForm.php");
$oConn = new connessione("/playbyforum/inc/config1.php");
cPage::$id_sito=$_SERVER["SITO"];
$oConn->connect();

$img = new cImage;

$iIdC = $_GET["iIdC"];
$iIdP = $_GET["iIdP"];
$img->id=$_GET["IdAtt"];
$img->delete();

/*incrocia le tabella tbl_rel_pag_cont e entrambe le tabelle tbl_contenuto e tbl_tipocontenuto per ottenere la pagina completa*/
$sSql="select idC,tbl_cont.id_sito,tbl_cont.idtipo,tbl_cont.idcont_tab,tbl_cont.nome_tabella from tbl_rel_pag_cont, ";
$sSql.="(select tbl_contenuto.id, tbl_contenuto.id_sito, tbl_contenuto.idtipo,tbl_contenuto.idcont_tab, tbl_tipocontenuto.descrizione,nome_tabella from tbl_contenuto, tbl_tipocontenuto where tbl_contenuto.idtipo=tbl_tipocontenuto.id) as tbl_cont "; 
$sSql.="where tbl_cont.id_sito='" . cPage::$id_sito . "' and tbl_cont.id=" . $iIdC . " and idC=" . $iIdC . " order by tbl_cont.idtipo, tbl_cont.idcont_tab;";

$risultato = mysql_query($sSql);
if (!$risultato){
	echo "Nessun contenuto";
}else{
	$iC=0;
	while($row=mysql_fetch_assoc($risultato)){
		switch($row["idtipo"]){
			case 2:
				$img->id=$row["idcont_tab"];
				$immagini=$img->read();
				$aryImg[$img->id]=array($img->id,$img->path,$img->title);
				break;
			default:
				break;						
		}
	}
	$form = new cForm();
	$form->formBr();
	echo "<table>";
	echo "<tr><td><b>Id</b></td><td><b>Percorso</b></td><td><b>Titolo</b></td></tr>";
	foreach($aryImg as $key=>$val){
		echo "<tr>";
		foreach($aryImg[$key] as $k=>$value){
			echo "<td>" . $value . date() . "</td>";
		}
		echo "<td>";
		$form->formInput("button","bCancella","Cancella","","OnClick=\"javascript:Open_url('lDivImmagini','./inc/amb/del_img_cont.php?IdImm=".$key."&iIdP=".$iIdP."&iIdC=".$iIdC."')\" style='font-size:10px;color:white;background-color:transparent;border:0px'");
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
	mysql_free_result($risultato);
}

 
?>
