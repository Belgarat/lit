<?php
/**
* \mainpage LiT - Lux in Tenebra
*
* \section help Voglio aiutare!
	Per scrivere LiT viene utilizzato il linguaggio di programmazione PHP 5, il progetto 
	si appoggia a MySQL come backend, inoltre per la parte grafica utilizziamo HTML e CSS
	
	Se vuoi aiutare nella programmazione, visto che il progetto è ancora in alfa, per un
	primo periodo potresti aiutare scrivendo la documentazione delle classi del progetto.
	So che è un lavoro ingrato e poco esaltante il più delle volte, ma ce n'è estremo bisogno
	visto che siamo passati ad un sistema di documentazione automatica: Doxygen (http://www.doxygen.nl/)
	solo di recente e buona parte del progetto è ancora senza documentazione. Inoltre, 
	in questo modo impari come muoverti tra il codice e magari riesci a capire più rapidamente
	eventuali punti su cui intervenire.

	Se non hai conoscenze di programmazione, o non credi di riuscire a essere utile in quel campo
	puoi sempre aiutare testando a fondo il portale a caccia di bug (nella sezione Sgabuzzino
	del forum c'è un tread dedicato ai beta tester in cui mettere le segnalazioni) oppure
	puoi fare richiesta per giocare come master, oppure unirti a una delle Mailing list che 
	si occupano	di creare l'ambientazione.

* \section source-code Trovare il codice
	Il codice del progetto LiT per creare un CMS adatto al gioco di ruolo su forum 
	può essere reperito, per ora, solo attraverso il repository SVN presente sul sito
	SourceForge: 

	Pagina principale del progetto:
	http://sourceforge.net/projects/luxintenebra/
	
	Indirizzo presso il quale è possibile reperire il codice:
	https://luxintenebra.svn.sourceforge.net/svnroot/luxintenebra
	
	Per scaricare sul proprio PC il codice sorgente di LiT ed aiutarci nello sviluppo
	di questo portale dovete utilizzare Subversion reperibile su:
	http://subversion.tigris.org/
	
	Una volta installato utilizzate il comando
	svn co https://luxintenebra.svn.sourceforge.net/svnroot/luxintenebra luxintenebra

* \section license Licenza
	Lux in Tenebra è un progetto Open Source, rilasciato sotto l'egida della licenze
	GNU General Public License della quale potete trovare una copia all'indirizzo:

	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

*/
session_start();

require_once("./cfg/config.php");
require_once("class/db/database.php");

$oDb = Db::getInstance();
$oDb->Open();

//$q = new Query();
//$q->fields = array("subdomain_name");
//$q->tables = array("tbl_site");
$url_section = explode(".",$_SERVER["SERVER_NAME"]);

if($url_section[0]=="www"){
	require_once("main.php");
}else{
	if($url_section[0]=="pbfbeta") {
            
            $url_section[0]="pbfbeta";
            require_once("." . HTTP_ROOT . "/"  . $url_section[0]  . "/cfg/global.php");
            require_once("." . HTTP_ROOT . "/"  . $url_section[0]  . "/inclusions.php");

            $oUt = new cUtente();
            $oUt->id=(int)$_SESSION["ID"];
            $oUt->Leggi();

            $oValidate = new cValidate;
            $form = new cForm;

            if($_GET["h"]!=""){
                    $ActUt=$oUt->active_user($_GET["h"]);
                    if($ActUt==true){
                            $oUt->id=intval($ActUt);
                            //$oUt->add_user_group($_SERVER["SITO"]);
                            echo "Il tuo utente è stato attivato! Ora puoi eseguire la login.";
                            //$oUt->Leggi();
                    }
            }else
            {
                    $oUt->id=intval($_SESSION["ID"]);
                    $oUt->Leggi();
            }

            $oPage = new cPage();
            $oPage->set_id_sito($_SERVER["SITO"]);
            $oPage->set_opt(@$_GET["opt"]);
            $oPage->set_user($oPage->let_id_sito(),$oUt);
            if(($_GET["IdP"]=="") || (!isset($_GET["IdP"]))){
                if((int)$_GET["IdN"]!=""){
                    $IdP=31;
                    $oPage->set_id_page($IdP);
                }else{
                    $IdP=$oPage->DefaultPage($oUt);
                    $oPage->set_id_page($IdP);
                }        
            }else{
                    $IdP=$_GET["IdP"];
                    $oPage->set_id_page($IdP);
            }

            $oCont = new cContent();
            $oCont->set_id_sito($_SERVER["SITO"]);
            $oCont->set_opt(@$_GET["opt"]);
            $oCont->set_manage(@$_GET["manage"]);
            $oCont->set_user($oCont->let_id_sito(),$oUt);

            $oNews = new cNews();
            $oNews->set_id_sito($_SERVER["SITO"]);
            $oNews->set_opt(@$_GET["opt"]);
            $oNews->set_user($oNews->let_id_sito(),$oUt);

            $oMenu = new cMenu();
            $oMenu->set_id_sito($_SERVER["SITO"]);
            $oMenu->set_user($_SERVER["SITO"],$oUt);

            $oPriMsg = new cPrivatemessage();
            $oPriMsg->set_id_sito($_SERVER["SITO"]);
            $oPriMsg->set_user($oPriMsg->let_id_sito(),$oUt);

            $oLog = new cLog(1);
            $oLog->set_log_level(1);
            $oLog->display=1;
            $oLog->id_sito=$_SERVER["SITO"];
            $oLog->user_id=$_SESSION["ID"];
            
            require_once("." . HTTP_ROOT . "/"  . $url_section[0]  . "/index.php");
            
        }else{
			if(($url_section[0]=="pbf") || ($url_section[0]=="forum")) {
	            $_SESSION["url_section"]="playbyforum";
			}else{
	            $_SESSION["url_section"]=$url_section[0];
			}
            require_once("." . HTTP_ROOT . "/"  . $_SESSION["url_section"]  . "/index.php");
            
        }
}
?>
