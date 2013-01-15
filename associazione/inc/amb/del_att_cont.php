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

$att = new cAttach;

$iIdC = $_GET["iIdC"];
$iIdP = $_GET["iIdP"];
$att->id=$_GET["IdAtt"];
$att->delete();

$sSql="";
/*incrocia le tabella tbl_rel_pag_cont e entrambe le tabelle tbl_contenuto e tbl_tipocontenuto per ottenere la pagina completa*/
$sSql="select idC,tbl_cont.id_sito,tbl_cont.idtipo,tbl_cont.idcont_tab,tbl_cont.nome_tabella from tbl_rel_pag_cont, ";
$sSql.="(select tbl_contenuto.id, tbl_contenuto.id_sito, tbl_contenuto.idtipo,tbl_contenuto.idcont_tab, tbl_tipocontenuto.descrizione,nome_tabella from tbl_contenuto, tbl_tipocontenuto where tbl_contenuto.idtipo=tbl_tipocontenuto.id) as tbl_cont "; 
$sSql.="where tbl_cont.id_sito='" . cPage::$id_sito . "' and tbl_cont.id=" . $iIdC . " and idC=" . $iIdC . " order by tbl_cont.idtipo, tbl_cont.idcont_tab;";

$risultato = mysql_query($sSql);
if (!$risultato){
	echo $sSql;
	echo "Nessun contenuto";
}else{
	$iC=0;
	while($row=mysql_fetch_assoc($risultato)){
		switch($row["idtipo"]){
			case 3:
				$att->id=$row["idcont_tab"];
				$immagini=$att->read();
				$aryAtt[$att->id]=array($att->id,$att->path,$att->title);
				break;
			default:
				break;						
		}
	}
	$form = new cForm();
	$form->formBr();
	echo "<table>";
	echo "<tr><td><b>Id</b></td><td><b>Percorso</b></td><td><b>Titolo</b></td></tr>";
	foreach($aryAtt as $key=>$val){
		echo "<tr>";
		foreach($aryAtt[$key] as $k=>$value){
			echo "<td>" . $value . date() . "</td>";
		}
		echo "<td>";
		$form->formInput("button","bCancella","Cancella","","OnClick=\"javascript:Open_url('lDivAllegati','./inc/amb/del_att_cont.php?IdAtt=".$key."&iIdP=".$iIdP."&iIdC=".$iIdC."')\" style='font-size:10px;color:white;background-color:transparent;border:0px'");
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
	mysql_free_result($risultato);
}

 
?>
