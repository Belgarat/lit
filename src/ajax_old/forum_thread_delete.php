<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$id_thread = @$_GET["id_thread"];

switch (@$_GET["answer"]) {
	case "confirm":
		echo "Confermi operazione?\r\n<br>";
		echo "<a href='javascript:void(0);' onclick='javascript:var udiv = new Ajax.Updater(\"" . $id_thread . "\", \"" . HTTP_ROOT . "/src/ajax/forum_thread_delete.php\",{method: \"get\",parameters: \"url=" . $_SERVER["REQUEST_URI"] . "&answer=Y&id_thread=" . $id_thread . "\"});'>SI</a>\r\n<br>";
		echo "<a href='javascript:void(0);' onclick='javascript:var udiv = new Ajax.Updater(\"" . $id_thread . "\", \"" . HTTP_ROOT . "/src/ajax/forum_thread_delete.php\",{method: \"get\",parameters: \"url=" . $_SERVER["REQUEST_URI"] . "&answer=N&id_thread=" . $id_thread . "\", onSuccess: function () { $(\"" . $id_thread . "\").innerHTML = \"Topic eliminato.\"; }});'>NO</a>\r\n<br>";
		break;
		
	case "N":
		echo "Operazione annullata!\r\n";
		break;
		
	case "Y":
		$oDb = Db::getInstance();
		$oDb->Open();
		$oUt = new cUtente();
		$oUt->id = $_SESSION["ID"];
		$oUt->Leggi();

                $oForum = new cForum();
                $oForum->set_id_sito($_SERVER["SITO"]);
                $oForum->set_user($_SERVER["SITO"],$oUt);
		$oValidate = new cValidate();

                $oForum->set_id_thread($id_thread);

                $control=$oForum->let_permission($oForum->let_id_thread());

                if(($control['cForum_thread']['Delete']==1)){

                    $rst = $oForum->delete_thread();

                    if ($rst[0]){
                            echo "Cancellazione effettuata!<br>\r\n";
                            echo "Ricaricare la pagina per aggiornare la lista.<br>\r\n";
                    } else {
                            echo "Cencellazione fallita! Errore: " . $rst[1];
                    }
                    break;
                }else{
                    echo "Impossibile procedere. <SPAN style='color: red'></SPAN><br \>Autorizzazioni insufficienti.<br \><br \>\r\n";
                }
		
}


?>
