<?php
/*
 * Created on 12/ago/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
session_start();

require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente($oConn);
$oAtt = new cAttach();
$oAtt->set_id_sito($_SERVER["SITO"]);
$oUt->id=intval($_SESSION["ID"]);
$oUt->Leggi();

$Permission=$oUt->fArrayPermission($_SERVER["SITO"],"cContent");

$oPage = new cPage();
$oPage->set_id_sito($_SERVER["SITO"]);
$oPage->set_user($oPage->let_id_sito(),$oUt);
$oCont = new cContent();
$oCont->set_id_sito($_SERVER["SITO"]);
$oCont->set_user($oCont->let_id_sito(),$oUt);

$iIdC = $_GET["iIdC"];
$iIdP = $_GET["iIdP"];
$oAtt->id=$_GET["IdAtt"];

if($Permission["Modify"]!=0){
	$oAtt->delete();
}

$sSql="";
/*incrocia le tabella tbl_rel_pag_cont e entrambe le tabelle tbl_contenuto e tbl_tipocontenuto per ottenere la pagina completa*/
$sSql="select idC,tbl_cont.id_sito,tbl_cont.idtipo,tbl_cont.idcont_tab,tbl_cont.nome_tabella from tbl_rel_pag_cont, ";
$sSql.="(select tbl_contenuto.id, tbl_contenuto.id_sito, tbl_contenuto.idtipo,tbl_contenuto.idcont_tab, tbl_tipocontenuto.descrizione,nome_tabella from tbl_contenuto, tbl_tipocontenuto where tbl_contenuto.idtipo=tbl_tipocontenuto.id) as tbl_cont "; 
$sSql.="where tbl_cont.id_sito='" . $_SERVER["SITO"] . "' and tbl_cont.id=" . $iIdC . " and idC=" . $iIdC . " order by tbl_cont.idtipo, tbl_cont.idcont_tab;";

$risultato = mysql_query($sSql);
if (!$risultato){
	echo $sSql;
	echo "Nessun contenuto";
}else{
	$iC=0;
	while($row=mysql_fetch_assoc($risultato)){
		switch($row["idtipo"]){
			case 3:
				$oAtt->id=$row["idcont_tab"];
				$immagini=$oAtt->read();
				$aryAtt[$oAtt->id]=array($oAtt->id,$oAtt->path,$oAtt->title);
				break;
			default:
				break;						
		}
	}
	$form = new cForm();
	$form->formBr();
	if($Permission["Modify"]==0){
		echo "Permission deny!";
	}
	
	if ($aryAtt!="") {
		echo "<table>";
		echo "<tr><td><b>Id</b></td><td><b>Percorso</b></td><td><b>Titolo</b></td></tr>";
		foreach($aryAtt as $key=>$val){
			echo "<tr>";
			foreach($aryAtt[$key] as $k=>$value){
				echo "<td>" . $value . "</td>";
			}
			echo "<td>";
			$form->formInput("button","bCancella","Cancella","","OnClick=\"javascript:Open_url('lDivAllegati','./inc/amb/del_att_cont.php?IdAtt=".$key."&iIdP=".$iIdP."&iIdC=".$iIdC."')\" style='font-size:10px;color:white;background-color:transparent;border:0px'");
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	mysql_free_result($risultato);
}

 
?>
