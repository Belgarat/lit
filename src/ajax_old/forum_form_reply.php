<?php
session_start();
require_once("../../cfg/config.php");
require_once("auto_include.php");

$oDb = Db::getInstance();
$oDb->Open();
$oUt = new cUtente();
$oUt->id = $_SESSION["ID"];
$oUt->Leggi();

$oForum = new cForum();
$oForum->set_id_sito($_SERVER["SITO"]);
$oForum->set_user($_SERVER["SITO"],$oUt);
$oForum->set_id_thread(@$_POST["txtThread"]);
$oForum->set_id_topic(@$_POST["txtTopic"]);

$oValidate = new cValidate();

$url=$_SERVER["HTTP_REFERER"];
$aUrl=explode("&",$url);
$url=$aUrl[0] ."&" . $aUrl[1] . "&" . $aUrl[2];

if(isset($_POST["txtIdNome"])){
	$oForum->set_id_pg($_POST["txtIdNome"]);
	$oForum->set_NamePoster($_POST["txtNome"]);
}else{	
	$oForum->set_NamePoster(@$_POST["txtNome"]);
}

$oForum->set_id_post(@$_POST["txtIdPost"]);

$oForum->set_ObjPost(@$_POST["txtOggetto"]);

$oForum->set_BodyPost(@$_POST["txtMessaggio"]);

$oForum->set_SignPost(@$_POST["txtFirma"]);

$control=$oForum->let_permission($oForum->let_id_topic());

if($control['cForum_post']['Create']==1){
    if ($oForum->let_id_topic()!=0) {
            $n_post = $oForum->insert_post();
            if ($n_post){
					$oForum->update_last_poster_topic($oForum->let_id_topic(),$oForum->let_NamePoster());
					$oForum->update_last_date_topic($oForum->let_id_topic());
                    $totrec=(int)$oForum->tot_rec_topic($aUrl[2]);
                    $recpag=(int)$oForum->RecPag;
                    $offset=(((int)($totrec/$recpag))*$recpag);
                    echo "<p>Reply <i>" . $oForum->let_ObjPost() . "</i> completato!</p>\r\n";
                    echo "<p>Clickare <a href=\"" . $url . "&Offset=" . $offset . "&rand=" . rand(1000, 10000) . "#" . $n_post . "\">-QUI-</a> per vedere il proprio post.</p>\r\n";
            } else {
                    echo "Reply <i>" . $oForum->let_ObjPost() . "</i> <SPAN style='color: red'>fallito!</SPAN><br \>Contattare gli admin del sito!<br \><br \>\r\n";
            }
    } else {
            $n_post = $oForum->insert_topic();
            if ($n_post){
                    echo "<p>Creato nuovo topic <i>" . $oForum->let_ObjPost() . "</i>!</p>\r\n";
                    echo "<p>Ricaricare la pagina per visualizzarlo!</p>\r\n";
            } else {
                    echo "Creazione nuovo topic <i>" . $oForum->let_ObjPost() . "</i> <SPAN style='color: red'>fallita!</SPAN><br \>Contattare gli admin del sito!<br \><br \>\r\n";
            }
    }
}else{
    echo "Creazione nuovo topic <i>" . $oForum->let_ObjPost() . "</i> <SPAN style='color: red'>fallita! </SPAN><br \>Autorizzazioni insufficienti.<br \><br \>\r\n";
}


?>
