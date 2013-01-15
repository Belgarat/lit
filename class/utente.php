<?php
/*
 * TODO: modificare sistema di permessi per la gestione di più moduli per una stessa classe
 */
//require_once("../cfg/pbf_global.php");
//require_once("../cfg/config.php");
require_once(SRV_ROOT."/class/cValidate.php");
Class cUtente
{

public $classname;

public $DescCollaborazione; //contiene la conversione del valore numerico di collaborazione
public $DescAnzianita; //contiene la conversione del valore numerico di anzianità
public $version;

public $id; //parametro che indica l'id dell'utente
//public $id_sito;
public $dati; //array con i dati letti che riguardano l'utente
public $ary_agg;
public $groups; //array bidimensionale con nome gruppo e flag di amministratore
public $grp=array(); //array unica con id gruppo e nome gruppo

public function __construct()
{ 
 	$classname = "cUtente";
 	$version = "Lix in tenebra <br> 2004 www.luxintenebra.net";
 	$this->id=0;
}

//funzione per estrarre i dati di un singolo utente una volta che la prorietà $id è stata valorizzata
public function Leggi()
{
	if ((int)$this->id > (int)0){
		$sSql="select * from userdb where ID=" . $this->id;
		
		$risultato = mysql_query($sSql);
		if (!$risultato){
			echo "Utente inesistente";
			//exit;
		}else{
			$this->dati = mysql_fetch_assoc($risultato);
			$this->fReadGroups();
			mysql_free_result($risultato);
		}
		$this->fCollaborazione($this->dati["Collaborazione"]);
		$this->fAnzianita($this->dati["Anzianita"]);
	}
	else
	{
		$this->dati = array("ID"=>"-1","Name"=>"Anonimo");
		$this->fReadGroups();
	}
}

public function fReadGroups(){
	$this->groups=array();
	if($this->dati["ID"]=="-1"){
		$sSql="select * from tbl_groups where id_group='-1';";
	}else{
		$sSql="select * from (select id_group from tbl_users_groups where id_sito=" . $_SERVER["SITO"] . " and id_user='" . $this->dati["ID"] . "') as t1 ";
		$sSql.="left join tbl_groups on t1.id_group=tbl_groups.id_group;";
	}
	$risultato=mysql_query($sSql);
	if(!$risultato){
		$this->groups[0]=array("id"=>"0","group"=>"sconosciuto","admin"=>0);
	}else{
		while($row=mysql_fetch_assoc($risultato)){
			$this->groups[]=array("id"=>$row["id_group"],"group"=>$row["group"],"admin"=>$row["admin"]);
		}
		mysql_free_result($risultato);		
	}
}

public function let_groups(){
	$sSql = "select id_user,tbl_users_groups.id_group,tbl_groups.group from tbl_users_groups left join tbl_groups on tbl_users_groups.id_group=tbl_groups.id where id_sito=1 and id_user=".$this->id.";";
	$risultato=mysql_query($sSql);
	if(!$risultato){
		$row[0]="Sconosciuto";
		return $row;
	}else{
		while($row=mysql_fetch_assoc($risultato)){
			$grp[$row["id_group"]]=$row["group"];
		}
		mysql_free_result($risultato);
		return $grp;
	}	
}

public function let_users_from_group($id_group){
    $id_group=(int)$id_group;
	$sSql = "select tbl_users_groups.id_user,userdb.Name from tbl_users_groups left join userdb on tbl_users_groups.id_user=userdb.id where tbl_users_groups.id_sito=1 and tbl_users_groups.id_group=".$id_group.";";
	$risultato=mysql_query($sSql);
	if(!$risultato){
		$row[0]="Nessun utente";
		return $row;
	}else{
		while($row=mysql_fetch_assoc($risultato)){
			$masters[$row["id_user"]]=$row["Name"];
		}
		mysql_free_result($risultato);
		return $masters;
	}	
}

public function is_group($grp_name,$IdUt=0){
	$grp = $this->let_groups();	
	$id_grp=array_search($grp_name,$grp);
	return $id_grp;
}

//27-10-2007
//verifica se l'utente è proprietario di un determinato elemento
private function CheckOwner($IdCont,$id_sito,$IdUt,$module,$table='tbl_contenuto',$field_id_sito='id_sito',$field_id='id',$field_owner='id_owner'){
	$sSql="select " . $field_owner  . " from " . $table  .  " where " . $field_id_sito  . "='" . $id_sito . "' and " . $field_id  . "='" . $IdCont . "' and " . $field_owner  . "='" . $IdUt . "'";
        
	$result=mysql_query($sSql);
	
	if(mysql_num_rows($result)<>0){
		return true;
	}else{
		return false;
	}
}

public function fArrayPermission($id_sito,$sClass,$IdCont=-1,$IdUt=0,$table='tbl_contenuto',$field_id_sito='id_sito',$field_id='id',$field_owner='id_owner'){

	$aAllow["Show"]=$this->fControlPermission($id_sito,$sClass,"show",$IdCont,$IdUt, $table,$field_id_sito,$field_id,$field_owner);
	$aAllow["Create"]=$this->fControlPermission($id_sito,$sClass,"create",$IdCont,$IdUt, $table,$field_id_sito,$field_id,$field_owner);
	$aAllow["Modify"]=$this->fControlPermission($id_sito,$sClass,"modify",$IdCont,$IdUt, $table,$field_id_sito,$field_id,$field_owner);
	$aAllow["Delete"]=$this->fControlPermission($id_sito,$sClass,"delete",$IdCont,$IdUt, $table,$field_id_sito,$field_id,$field_owner);

	return $aAllow;
	
}


//27-10-2007
//controlla il permesso specificato nella variabile $sAllow e restituisce 0 se negato e 1 se consentito
//$sAllow attualmente ha questi valori: create,modify,delete,show.
public function fControlPermission($id_sito,$sClass,$sAllow,$IdCont=-1,$IdUt=0,$table='tbl_contenuto',$field_id_sito='id_sito',$field_id='id',$field_owner='id_owner'){
	if($IdUt==0){
		$IdUt=$this->id;
	}
	if($this->groups[0]["admin"]=="1"){
		return 1;
	}else{
		$fResult=0;
		//prima di tutto verifica se esistono permessi speciali per quel gruppo e per quel contenuto
		foreach($this->groups as $ary1){
			$sSql="SELECT tbl_permission.create,tbl_permission.modify,tbl_permission.show,tbl_permission.delete ";
			$sSql.="FROM tbl_permission left join tbl_moduls ";
			$sSql.="on tbl_permission.id_modul=tbl_moduls.id ";
			$sSql.="where tbl_permission.id_sito='" . $id_sito . "' and tbl_moduls.modul='" . $sClass . "' and tbl_permission.id_group='" . $ary1["id"] . "' and tbl_permission.id_content='" . $IdCont . "';";

			$risultato=mysql_query($sSql);
			if($risultato){
				$row=mysql_fetch_assoc($risultato);
				if($fResult<>"1"){
					$fResult=$row[$sAllow];
				}
				mysql_free_result($risultato);
			}
		}
		//se non esistono permessi specifici per quel contenuto e quel gruppo vedo se il modulo dei contenuti
		//ha il permesso indicato per quel gruppo.
		if($fResult==0){
			foreach($this->groups as $ary1){
				$sSql="SELECT tbl_permission.create,tbl_permission.modify,tbl_permission.show,tbl_permission.delete ";
				$sSql.="FROM tbl_permission left join tbl_moduls ";
				$sSql.="on tbl_permission.id_modul=tbl_moduls.id ";
				$sSql.="where id_sito='" . $id_sito . "' and tbl_moduls.modul='" . $sClass . "' and tbl_permission.id_group='" . $ary1["id"] . "';";

				$risultato=mysql_query($sSql);
				if($risultato){
					$row=mysql_fetch_assoc($risultato);
					if($fResult<>"1"){
						$fResult=$row[$sAllow];
					}
					mysql_free_result($risultato);
				}
			}
			if(($fResult==0) and ($this->CheckOwner($IdCont,$id_sito,$IdUt,$sClass,$table,$field_id_sito,$field_id,$field_owner))){
				//di default l'owner del contenuto ha tutti permessi, meno quelli specificati nel seguente
				//case. Ad esempio non ha la possibilità di cancellare il contenuto da lui generato.
				switch($sAllow){
					case "delete":
						return 1;
						break;
					case "create":
						return 0;
						break;
					default:
						return 1;
						break;
				}				
			}else{
				return $fResult;
			}
		}else{
			return $fResult;
		}
	}
}

function fCollaborazione($iColl){

	if ($iColl=="") $iColl=1;
	
	$sSql="SELECT * FROM tblcollaborazione where ID=" . $iColl;
	
	$risultato = mysql_query($sSql);
	if (!$risultato){
		//gestione errore
	}else{
		if(mysql_num_rows($risultato)>1){
			$riga = mysql_fetch_assoc($risultato);
			$this->DescCollaborazione=$riga["Nome"];
		}else{
			$this->DescCollaborazione="Nessun genere";
		}
		mysql_free_result($risultato);
	}
}

function fAnzianita($iAnz){
	
	if ($iAnz=="") $iAnz=0;

	$sSql="SELECT * FROM tblanzianita where Punti<=" . $iAnz . " order by ID desc limit 0,1";

	$risultato = mysql_query($sSql);
	if (!$risultato){
		$this->DescAnzianita="Ospite";		
	}else{
		$riga = mysql_fetch_assoc($risultato);
		$this->DescAnzianita=$riga["Nome"];
		mysql_free_result($risultato);
	}
}



public function TrovaNome($sNome)
{
	if (!$sNome==""){
		$sSql="select * from userdb where Name=" . $sNome;
		$risultato = mysql_query($sSql);
		if (!$risultato){
			echo "Utente inesistente";
			exit;
		}else{
			$this->dati = mysql_fetch_assoc($risultato);
			mysql_free_result($risultato);
		}
		$this->fCollaborazione($this->dati["Collaborazione"]);
		$this->fAnzianita($this->dati["Anzianita"]);
	}
	else
	{	
		$this->dati = array("Name"=>"Anonimo");
	}
}
Public function Aggiorna($ary){
	
	$q = new Query();
	$oControl = new cValidate;
	$oControl->SqlVal($ary,"userdb");
	//general la query di update.
	$iC=0;
	foreach ($ary as $key => $value){
		$this->verify_property($value);
		if ($iC==0){
			
			$aFields[]=$key;
			$aValues[]=$value;
			//$sId = @$ary["id"];
			//$sSql = "update userdb set " . $key . "='" . $value . "'";
			//$iC = 1;
		}
		else{
			$aFields[].=$key;
			$aValues[].=$value;
			//$sSql .= "," . $key . "='" . $value . "'";
		}
	}
	//$sSql .= " where id=" . $ary["ID"] . ";";

	$q->tables= array("userdb");
	$q->filters = "id=" . $ary["ID"];
	$q->fields = $aFields;
	$q->values = $aValues;
	
	$result=$q->DoUpdate();
	//$result=mysql_query($sSql);

	//restituisce 1 se è andato tutto bene.
	return $result;
	//mysql_free_result($result);
}
//estrae dalla tabella userdb i campi con il tipo corrispondente per la
//la creazione del form di registrazione
Public function EstraiCampi(){
	$oVal = new cValidate;
	$sSql="select * from userdb limit 1,1;";
	$risultato = mysql_query($sSql);
	if (!$risultato){
		return "Tabella vuota!";
		exit;
	}else{
		//ciclo per creare l'array che identifica il tipo campo.
		$aTipo=$oVal->TipoCampi($risultato);
		mysql_free_result($risultato);
	}	
	return $aTipo;
}

private function verify_property(&$valore){
	if(get_magic_quotes_gpc()==0){
        $valore=addslashes($valore);
	}
}


Public function Inserisci($ary){
	
	$oControl = new cValidate;
	$oControl->SqlVal($ary,"userdb");
	
	$iC=0;
	foreach ($ary as $key => $value){
		$this->verify_property($value);
		if ($iC==0){
			$sId=$key;
			$sSql = "insert into userdb set " . $key . "='" . $value . "'";
			$iC = 1;
		}
		else{
			$sSql .= "," . $key . "='" . $value . "'";
		}
	}
	$sSql .= ";";
	$result=mysql_query($sSql);
	$this->id=mysql_insert_id();
    #######################################ASINELLO
###########################################TODO: aggiungere tutti i siti in automatico
    $this->add_user_group(1);
    $this->add_user_group(2);
    
    return $this->id;
}

Public function Inserisci_personali($ary){
	
	$oControl = new cValidate;
	$oControl->SqlVal($ary,"tbl_dati_personali");
	
	$iC=0;
	foreach ($ary as $key => $value){
		$this->verify_property($value);
		if ($iC==0){
			$sId=$key;
			$sSql = "insert into tbl_dati_personali set " . $key . "='" . $value . "'";
			$iC = 1;
		}
		else{
			$sSql .= "," . $key . "='" . $value . "'";
		}
	}
	$sSql .= ";";
	$result=mysql_query($sSql);
	return mysql_insert_id();
	mysql_free_result($result);
}

public function new_hash($Id){
	$h = hash("md5",time());
	$sSql="insert into tbl_hash set id='" . $Id . "',cod_hash='" . $h . "';";
	mysql_query($sSql);
	return $h;	
}

public function active_user($hash){
	if(get_magic_quotes_gpc==1){
		$hash=addslashes($hash);
	}
	$sSql="select id from tbl_hash where cod_hash='" . $hash . "'";
	$result=mysql_query($sSql);
	if($result){
		$row=mysql_fetch_row($result);
		$this->id=$row[0];
		$this->Leggi();
		$this->dati["Abilitato"]=0;
		$this->Aggiorna($this->dati);		
		mysql_free_result($result);
		$sSql="delete from tbl_hash where cod_hash='" . $hash . "'";
		mysql_query($sSql);
		return $this->dati["ID"];
	}else{
		return false;
	}
}
//aggiunge un utente ad un gruppo
public function add_user_group($id_sito=1,$id_group=3){
	$sSql="insert into tbl_users_groups set id_sito='" . $id_sito . "', id_user='" . $this->id . "',id_group='" . $id_group . "'";
	mysql_query($sSql);
}

Public function Login($sUtente,$sPwd){

	//query insert table immobili
 	if(get_magic_quotes_gpc()!=1){
		$sSql="select * from userdb where Abilitato=0 and Name='" . mysql_escape_string($sUtente) . "' and password='" . $this->Critta(mysql_escape_string($sPwd)) . "'";
 	}else{
 		$sSql="select * from userdb where Abilitato=0 and Name='" . $sUtente . "' and password='" . $this->Critta($sPwd) . "'";
 	} 
	
	//execute the query and test the result
	//echo $sSql;
	$Result=mysql_query($sSql);
	if (!$Result)
	{
	    //return "Non è possibile effettuare il login, problemi con il database!" . mysql_error();
	    return -1;
	    exit();	
	}
	elseif (mysql_num_rows($Result)>=1)
	{
		$row = mysql_fetch_assoc($Result);
		return $row["ID"];
		mysql_free_result($Result);
	}
	else
	{
		//return "Accesso non autorizzato! Utente " . $_POST["txtUtente"] . " inesistente o password errata.";
		return -1;
		exit;
	}	
}

public function verify_skel($id_sito){
	$bCheck=false;
	//foreach($this->groups as $value){
	if($this->fControlPermission($id_sito,"filesystem","Create")==1){
                mkdir(SRV_ROOT . "/home/" . $this->dati["Name"],0775);
                chmod(SRV_ROOT . "/home/" . $this->dati["Name"],0775);
		$sSql="select * from tbl_skel";
		$ris=mysql_query($sSql);
		$path=SRV_ROOT . "/home/" . $this->dati["Name"];
		if($ris){
			while($row=mysql_fetch_assoc($ris)){					
				if(!file_exists($path . "/" . $row["folder"])){
					mkdir($path . "/" . $row["folder"],0775);
					chmod($path . "/" . $row["folder"],0775);
				}
			}
		}
		mysql_free_result($ris);			
	}
	//}
}

public function AggOnline($ID,$logout=false){	
	if ($ID<>-1){
		$this->id=$ID;
		$this->Leggi();
		$deadtime=(int)$this->Inattivo($ID);
		//finire la procedura con aggiornamento on-line e logout con cancellazione relativa
		if ($logout==false){
			if ($this->OnLine()==false){
				$sSql="insert into useronline set";
				$sSql.=" User='" . $this->dati["Name"] . "',";
				$sSql.=" Master='" . $this->dati["Master"] . "',";
				$sSql.=" IDUt='" . $this->dati["ID"] . "',";
				$sSql.=" DtISO='" . date("Ymd") . "',";
				$sSql.=" DtTimer=TIME_To_SEC(Now()),";
				$sSql.=" DataOra=unix_timestamp(),";
				$sSql.=" opz='0';";
				mysql_query($sSql);
			}else{
				if(($deadtime >= (int)0) and ($deadtime < (int)2)){
					$sSql="update useronline set";
					$sSql.=" DtISO='" . date("Ymd") . "',";
					$sSql.=" DtTimer=TIME_To_SEC(Now()),";
					$sSql.=" DataOra=unix_timestamp(),";
					$sSql.=" opz='0' where IDUt=" . $this->dati["ID"] . ";";
					mysql_query($sSql);
				}else{
					$this->delete_user_online($ID);
				}
			}
		}else{
		}
	}
}

public function delete_user_online($id){

	$id=(int)$id;
	
	$sSql="delete from useronline where IDUt=".$id;

	return mysql_query($sSql);

}

public function num_users_online(){

	$q = new Query();
	$q->tables = array("useronline");
	$q->fields = array("count(IDUt)");
	
	if($q->Open()){
		$row=$q->GetNextRecord();
		return $row[0];
	}else{
		return 0;
	}

}

private function fMasc($sPass){

    $sMasc = "";
    $sMascDef = "";
    
    $i=1;
    $Seme = round(64/strlen($sPass));
    
    while ($i <= $Seme){	    		    	
        $sMasc = $sMasc . $sPass;
        $i++;
    }
    
    $i=1;
    if (strlen($sMasc) < 64) {
        while (!(64-strlen($sMasc))==0){
            $sMasc = $sMasc . substr($sMasc,0,1);	            
        }
    }
    
    $i=0;
    while (!($i > strlen($sMasc))){	    	
    	$sMascDef = $sMascDef . chr((ord(substr($sMasc, $i, 1)) + strlen($sPass)));
        $i++;
    }
	
    return $sMascDef;
    
}

public function Critta($sPassword){
    
    if ($sPassword<>"") {
		$sMaschera = $this->fMasc($sPassword);
		$sPasswordCrittata = "";
		$sPasswordCrittataSalvata = "";
	        
		//per ogni carattere della password da crittare
		for ($i=0;$i<strlen($sPassword);$i++){				
			$iNuovoChar = (ord(substr($sPassword, $i, 1)) ^ ord(substr($sMaschera, $i, 1)));
			$sPasswordCrittata .= chr($iNuovoChar);								
		}			
		//per ogni carattere della password crittata, ne genera due della password
		//crittata in formato stampabile/salvabile
		for ($i=0;$i<strlen($sPasswordCrittata);$i++){
			//primo carattere aggiunto: 4 bit meno significativi
			$iCar=ord(substr($sPasswordCrittata, $i, 1));
			$iNuovoChar = ($iCar & hexdec("HF"));
			$sPasswordCrittataSalvata = $sPasswordCrittataSalvata . chr($iNuovoChar + ord("a"));
			//primo carattere aggiunto: 4 bit + significativi
			$iCar=ord(substr($sPasswordCrittata, $i, 1));
			$iNuovoChar = ($iCar & hexdec("HF0"));
			$iNuovoChar = $iNuovoChar/16;
			$sPasswordCrittataSalvata = $sPasswordCrittataSalvata . chr($iNuovoChar + ord("A"));				
		}
	    
		return $sPasswordCrittataSalvata;
    }
	else {
		return "";
	}
}

/*
 * METHOD DEPRECATED -> NEW METOD: user_pg_list()
 */
function ListaPG(){
    $sSql="SELECT Username FROM pgdb where IDUt=" . $this->dati["ID"];
    $risultato=mysql_query($sSql);
    if ($risultato){
    	$Flag=true;
		while ($row=mysql_fetch_array($risultato)){
			if ($Flag==true){
				$LstPG.=$row["Username"];
				$Flag=false;
			}else{
				$LstPG .= ", " . $row["Username"];
			}
		}
		return $LstPG;
    }else
    {
		return "Nessun pg";
    }
    mysql_free_result($risultato);
}

/*
* funzioni che ritornano i vari aspetti del pg (per la creazione)
* 
* Author: Giacomo "KloWn" Bella
* Date:	  Various
* Comments: 20090306 Remember to comment!
* 			20090306 Finished Sex, Class, Alignment, Race
* 			20090309 revisited queries
* 			20090321 selectSex removed (useless)
*/	
public function show_list_pg(){	
	
	$q = new Query();
	$q->fields = array("Username, Photo, ID");
	$q->tables = array("pgdb");
	$q->filters = "( IDUt='" . $this->dati["ID"] . "' and id_sito=".$_SERVER["SITO"].")";
	$q->sortfields = array("Username");
	if ($q->Open()){
		echo "<ul id=\"id_list_pg\" style=\"display:none;\">";
		while($row = $q->GetNextRecord()){
			echo "<li><img width=\"20\" src=\"".$row[1]."\"><a onclick='javascript:var udiv = new Ajax.Updater(\"id_PluginBoard\", \"" . HTTP_AJAX . "/cPg_pg_show.php\",{method: \"post\",parameters: \"url=" . $_SERVER["REQUEST_URI"] . "&id=".$row[2]."\"});Effect.Appear(\"id_PluginBoard\", {duration: 1.0}); Effect.Fade(\"id_list_pg\");' href=\"javascript: void(0);\">".$row[0]."</a></li>\r\n";
		}
		echo "<li style=\"text-align:right;\"><a onclick=\"javascript: Effect.Fade('id_list_pg');\" href=\"javascript: void(0);\">Close</a></li>";
		echo "</ul>";
	}
	$q->Close();
	
}

/*
 * Restituisce un ARRAY con la lista dei PG assegnati ad un utente
 */
function user_pg_list(){
    $sSql="SELECT ID,Username FROM pgdb where IDUt=" . $this->id;
    $risultato=mysql_query($sSql);
    if ($risultato){
        while ($row=mysql_fetch_array($risultato)){
            $LstPG[$row["ID"]]=$row["Username"];
        }
        return $LstPG;
    }else
    {
        return "Nessun pg";
    }
    unset($LstPG);
    mysql_free_result($risultato);
}

function VerificaAnzianita($iAnz){
	if ($iAnz==""){
		$iAnz=0;
	}
	
    $sSql="SELECT * FROM tblanzianita where Punti<=" . $iAnz . " order by ID desc limit 0,1";
    $risultato=mysql_query($sSql);
    if (!$risultato){
		$Anz = "Ospite";		
    }else
    {
    	$row=mysql_fetch_array($risultato);
		$Anz=$row["Nome"];
    }
    mysql_free_result($risultato);
    
    $sSql="SELECT * FROM tblanzianita where Punti<=" . $this->dati["Anzianita"] . " order by ID desc limit 0,1";
    $risultato=mysql_query($sSql);
    if (!$risultato){
		$OldAnz = "Ospite";		
    }else
    {
    	$row=mysql_fetch_array($risultato);
		$OldAnz=$row["Nome"];
		mysql_free_result($risultato);
    }	
	if ($Anz==$OldAnz){
		return false;
	}else{
		return true;
	}
}

function AvventureInCorso(){
    $sSql = "SELECT count(ID) FROM pgdb where idavv<>0 and IDUt=" . $this->dati["ID"];
    $risultato=mysql_query($sSql);
    if ($risultato){
    	$row=mysql_fetch_row($risultato);
		return $row[0];
		mysql_free_result($risultato);
    }else{
		return 0;
    }
}


function AvventureConcluse(){
    $sSql="SELECT count(ID) FROM tblconcluse where IDUt=" . $this->dati["ID"];
    $risultato=mysql_query($sSql);
    if ($risultato){
    	$row=mysql_fetch_row($risultato);
    	mysql_free_result($risultato);
		return $row[0];
	}else{
		return 0;
	}
}


function OnLine(){
    $sSql = "select IDUt from useronline where IDUt='" . $this->dati["ID"] . "'";
    $risultato=mysql_query($sSql);
    if ($risultato){
    	if(mysql_num_rows($risultato)>=1){	    	
			return true;
    	}else{
    		return false;
    	}
    	mysql_free_result($risultato);
	}else{
		return false;
	}
}

function list_user_online(){
	$q = new Query();
	$q->tables = array("useronline");
	$q->fields = array("User","IDUt","DtISO","DtTimer","DataOra","opz");
	$q->sortfields = array("User","DataOra");

    if ($q->Open()){
		while ($row = $q->GetNextRecord(true)){
			$result[] = $row;
		}
		return $result;
	}else{
		return false;
	}
}

function Inattivo($IdUt=0){
    if($IdUt==0){
        $sSql = "SELECT time_format(timediff(date_format(from_unixtime(unix_timestamp()),'%H:%i'),date_format(from_unixtime(DataOra),'%H:%i')),'%H:%i') as inattivo FROM useronline where IDUt=" . $this->dati["ID"];
    }else{
        $sSql = "SELECT time_format(timediff(date_format(from_unixtime(unix_timestamp()),'%H:%i'),date_format(from_unixtime(DataOra),'%H:%i')),'%H:%i') as inattivo FROM useronline where IDUt=" . $IdUt;
    }
    $risultato=mysql_query($sSql);
    if ($risultato){    	
    	$row=mysql_fetch_assoc($risultato);
		return $row["inattivo"];
		mysql_free_result($risultato);
    }else{
		return 0;
    }
}


function RuoloGioco(){
    $sSql = "SELECT * FROM level where Level<=" . $this->dati["Master"] . " order by level desc limit 0,1";
    $risultato=mysql_query($sSql);
    if (mysql_num_rows($risultato)>=1){
    	$row=mysql_fetch_assoc($risultato);
		return $row["_Desc"];
		mysql_free_result($risultato);
    }else{
		return "Giocatore";
    }
}

function TotalePost(){
    $sSql = "SELECT count(*) FROM messagedb where IdUt=" . $this->dati["ID"];
    $risultato=mysql_query($sSql);
    if (mysql_num_rows($risultato)>=1){
    	$row=mysql_fetch_row($risultato);
		return $row[0];
		mysql_free_result($risultato);
    }else{
		return 0;
    }
}

function GiorniIscrizione(){
    $sSql = "SELECT DtISOCreazione,TimerCreazione,DataOraLogin as DtLogin FROM userdb where ID=" . $this->dati["ID"];
    $risultato=mysql_query($sSql);
    $row=mysql_fetch_assoc($risultato);
    if (($row["DtISOCreazione"]==0) || ($row["DtISOCreazione"]=="") || (is_null($row["DtISOCreazione"])==true)){
		$ris=0;
    }else{
		$DtCreazione=strtotime(substr($row["DtISOCreazione"],4,2) . "/" . substr($row["DtISOCreazione"],6,2) . "/" . substr($row["DtISOCreazione"],0,4));
		if ($row["DtLogin"]==0){
			$DtLogin=strtotime($DtCreazione);
		}else{
			$DtLogin=strtotime(date("m/d/y",$row["DtLogin"]));			
		}
		$ris = ((mktime(0,0,0,date("n",$DtLogin),date("j",$DtLogin),date("y",$DtLogin)) - mktime(0,0,0,date("n",$DtCreazione),date("j",$DtCreazione),date("y",$DtCreazione)))/86400);		
    }
    return $ris;
}

public function Numbers_users(){
	$sSql="select count(id) from userdb where abilitato=0;";
	$risultato=mysql_query($sSql);
	if($risultato){
		if(mysql_num_rows($risultato)>0){
			$row=mysql_fetch_row($risultato);
			return $row[0];
			mysql_free_result($risultato);
		}else{
			return 0;
		}
	}else{
		return 0;
	}
} 

public function Last_user(){
	$sSql="select Name from userdb where abilitato=0 order by id desc limit 0,1;";
	$risultato=mysql_query($sSql);
	if($risultato){
		if(mysql_num_rows($risultato)>0){
			$row=mysql_fetch_assoc($risultato);
			return $row["Name"];
			mysql_free_result($risultato);
		}else{
			return "Nessun utente registrato.";
		}
	}
} 
//DEPRECATED
public function list_user($where){
	$sSql="select id,Name from userdb where " . $where . " and abilitato=0 order by Name;";
	$risultato=mysql_query($sSql);
	if($risultato){
		if(mysql_num_rows($risultato)>0){
			while ($row=mysql_fetch_assoc($risultato)){
				$list .= $row["id"] . "," . $row["Name"] . ";";
			}
			return substr($list,0,-1);
			mysql_free_result($risultato);
		}else{
			return "Nessun utente registrato.";
		}
	}
} 

public function users_list($where=""){
	
	$q = new Query();
	$q->tables = array("userdb");
	$q->fields = array("ID","Name");
	if($where==""){
		$q->filters = "abilitato = 0";
	}else{
		$q->filters = $where." and abilitato = 0";
	}
	$q->sortfields = array("Name");
	
	if($q->Open()){
		while ($row=$q->GetNextRecord(true)){
			$list[] = $row;
		}		
		return $list;
	}else{
		return 0;
	}
} 

public function url_img($id){
	$id=(int)$id;
	$q = new Query();
	$q->tables = array("userdb");
	$q->fields = array("ImmUt");
	$q->filters = "ID=".$id;
	
	if($q->Open()){
		$row=$q->GetNextRecord(true);
		return $row["ImmUt"];
	}else{
		return false;
	}

}

}



?>
