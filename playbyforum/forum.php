<?php
/*
 * Created on 09/feb/08
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
//session_start();
global $oLog;
//global $db;
$oUt = new cUtente();
$form = new cForm();
$oUt->id = intval($_SESSION["ID"]);
$oUt->Leggi();

$oForum = new cForum();

$oForum->set_id_thread(@$_GET["IdTh"]);

$oForum->set_id_topic(@$_GET["IdPs"]);

switch ($_POST["bSalva"]) {	
	case "Reply":
	
		$oForum->set_id_post($_POST["txtIdPost"]);
		
		$oForum->set_NamePoster($_POST["txtNome"]);
		
		$oForum->set_ObjPost($_POST["txtOggetto"]);

		$oForum->set_BodyPost($_POST["txtMessaggio"]);

		$oForum->set_SignPost($_POST["txtFirma"]);
				
		if ($oForum->insert_post()){
			echo "<p>Reply <i>" . $oForum->let_ObjPost . "</i> completato!</p>\r\n";
		} else {
			echo "Reply <i>" . $oForum->let_ObjPost . "</i> <SPAN style='color: red'>fallito!</SPAN><br \>Contattare gli admin del sito!<br \><br \>\r\n";
		}
	
	case "EditPost":
		
		$oForum->set_id_post($_POST["txtIdPost"]);
		
		$oForum->set_NamePoster($_POST["txtNome"]);
		
		$oForum->set_ObjPost($_POST["txtOggetto"]);

		$oForum->set_BodyPost($_POST["txtMessaggio"]);

		$oForum->set_SignPost($_POST["txtFirma"]);		

		if ($oForum->update_post()){
			echo "<p>Aggiornamento <i>" . $oForum->let_ObjPost . "</i> effettuato!</p>\r\n";
		} else {
			echo "Aggiornamento <i>" . $oForum->let_ObjPost . "</i> <SPAN style='color: red'>fallito!</SPAN><br \>Contattare gli admin del sito!<br \><br \>\r\n";
		}
		
	default:
		//mostra la barra di navigazione forum 
		$oForum->show_bar();
		?>
		<hr>
		<?php
		//if nidificati per la scelta della visualizzazione. 
		if($oForum->let_id_thread()==0){
			$oForum->show_threads($oUt->fArrayPermission($_SERVER["SITO"],"cForum_thread"));
		}else{
			if($oForum->let_id_topic()==0){
				$oForum->show_thread($oUt->fArrayPermission($_SERVER["SITO"],"cForum_thread"));
			}else{
				$oForum->page_bar(@$_GET["Offset"],$_SERVER["SCRIPT_NAME"] . "?IdP=" . @$_GET["IdP"] . "&IdTh=" . @$_GET["IdTh"] . "&IdPs=" . @$_GET["IdPs"]);
				echo "<a name=\"inizio\"></a>";
				$oForum->show_form_reply($oUt->dati["Name"]);
				?>
				<div class="box_reply" id="formEditPost" style="display:none;">
				</div>
				<div class="box_reply" id="formConfirmDelete" style="display:none;">
				</div>
				<?php
				$oForum->show_topic($oUt->fArrayPermission($_SERVER["SITO"],"cForum_thread"));				
			}
		}
}

?>
