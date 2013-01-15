<?php
/*
 *      cMessage.php
 *      
 *      Copyright 2009  <belgarat@luxintenebra.net>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
?>

<?php

#require_once("pbf_inclusions.php");

class cMessage {
	protected $classname;
	protected $version;
	private $id_sito;
	private $oUt;
	private $aPermission;
	
	private $message;
	private $id_utente;
	private $id_message;
	private $dataora;
	private $aMessages;
	
	function __construct(){
	 	$this->classname = "cMessage";
	 	$this->version = "Lux in tenebra <br> 2008-09 www.luxintenebra.net";
	}

	public function set_user($id_sito,$obj){
		if (is_object($obj)) {
			$this->oUt = $obj;
		 	$this->aPermission = $this->oUt->fArrayPermission($id_sito,$this->classname);
		 } else {
		 	echo "Non e' un oggetto.";
		 	return false;
		 }
	}

	public function set_id_sito($id_sito){
		if (is_numeric($id_sito)) {
			$this->id_sito = $id_sito;
		 } else {
		 	echo "Id sito non valido. (non numerico!)";
		 	return false;
		 }
	}

	public function set_message($str){
		$oValidate = new cValidate;
		if (($str=="") || (is_null($str))) {
			$this->message = "";
		} else {
			$str = $oValidate->_sql($str);
			$this->message = $oValidate->_txt($str);
		}
	}

	public function set_id_utente($id){
		if (is_numeric($id)) {
			$this->id_utente = $id;
		 } else {
		 	//echo "Id sito non valido. (non numerico!)";
		 	$this->id_utente = $this->oUt->id;
		 	return false;
		 }
	}

	public function set_id_message($id){
		if (is_numeric($id)) {
			$this->id_message = $id;
		 } else {
		 	//echo "Id sito non valido. (non numerico!)";
		 	$this->id_message = 0;
		 	return false;
		 }
	}

	public function set_dataora($data){
		//if (checkdate(date("m",$data),date("d",$data),date("Y",$data)) {
			$this->dataora = $data;
		 //} else {
		 	//echo "Id sito non valido. (non numerico!)";
		 	//$this->dataora = time();
		 	//return false;
		 //}
		 return true;
	}

	
	public function let_message(){
		return $this->message;
	}

	public function let_messages_list(){
		return $this->aMessages;
	}

	public function let_id_sito(){
		return $this->id_sito;
	}	

	public function let_id_utente(){
		return $this->id_utente;
	}	

	public function let_id_message(){
		return $this->id_message;
	}	

	public function let_dataora(){
		return $this->dataora;
	}	

	public function show($opt=""){
		?>
		<SCRIPT>
	    var msg = new Ajax.PeriodicalUpdater('head_message', '../src/ajax/cMessage_show.php', {method: 'post', frequency:5, decay:2});
		</SCRIPT>
		<?php
	}

	public function show_board($id_sito=1, $MaxRecord=5, $iTipo=1){
		
	}
	
	//inserisce un nuovo messaggio nella tabella tblavvisi
	public function insert_message(){
		$q = new Query();
		$q->tables = array("tblavvisi");
		$q->fields = array('IdUt','Avviso','DataOra');
		$q->values = array($this->let_id_utente(),$this->let_message(),time());
		
		if ($q->DoInsert()){
			return true;
		} else {
			echo mysql_error();
			return false;
		}

	}
	
	//set_check_message: imposta a '1' il campo checkmsg rendendolo così segnato come già visualizzato.
	public function set_check_message($value=1){
		$q = new Query();
		$q->tables = array("tblavvisi");
		$q->fields = array('checkmsg');
		$q->values = array($value);
		$q->filters = '(id=' . $this->let_id_message() . ')';
		
		if ($q->DoUpdate()){
			return true;
		} else {
			echo mysql_error();
			return false;
		}

	}
	
	//inserisce un nuovo messaggio nella tabella tblavvisi a tutti gli utenti online
	public function insert_message_user_online(){
        $check=false;
		$qa = new Query();
		$qa->tables = array("tblavvisi");
		$qa->tables = array("tblavvisi");
		$qa->fields = array('IdUt','Avviso','DataOra');
		$q = new Query();
		$q->tables = array("useronline");
		$q->fields = array('IdUt');
		if($q->Open()){
			while( $row = $q->GetNextRecord() ){
				$qa->values = array($row[0],$this->let_message(),time());
	
				if ($qa->DoInsert()){
					$check=true;
				} else {
					#echo mysql_error();
					$check=false;
				}
			}
		}
        return $check;
	}
	
	//legge il messaggio dato un id e popola le proprietà della classe
	public function read_message_by_id(){
		$q = new Query();
		$q->fields = array("Id",
								"IdUt",
								"Avviso",
								"DataOra");
		$q->tables = array("tblavvisi");
		$q->filters = "(id=" . $this->let_id_message() . ")";
		if ($q->Open()){
			$row = $q->GetNextRecord();
			$this->set_id_message($row[0]);
			$this->set_id_utente($row[1]);
			$this->set_message($row[2]);
			$this->set_dataora($row[3]);
		}else{
			return "Messaggio inesistente.";
		}		
	}
	
	//legge tutti i messaggi di un utente popolando l'array aMessages con delle array contentente i campi del messaggio
	public function read_messages_by_user(){
		$q = new Query();
		$q->fields = array("Id",
								"IdUt",
								"Avviso",
								"DataOra");
		$q->tables = array("tblavvisi");
		$q->filters = "(IdUt=" . $this->let_id_utente() . ")";
		$q->sortfields = array("Id desc");
		if ($q->Open()){
			while($row = $q->GetNextRecord()){
				$this->set_id_message($row[0]);
				$this->set_message($row[2]);
				$this->set_dataora($row[3]);
				$this->aMessages[$this->let_id_message()]=array($this->let_id_utente(), $this->let_message(), $this->let_dataora());
			}
		}else{
			$this->aMessages[0]="Nessun messaggio trovato.";
		}		
	}
	
	//restituisce l'ultimo avviso ancora non visto
	public function read_last_messages_user(){
		$q = new Query();
		$q->fields = array("Id",
								"IdUt",
								"Avviso",
								"DataOra");
		$q->tables = array("tblavvisi");
		$q->filters = "(IdUt=" . $this->let_id_utente() . " and checkmsg=0)";
		$q->sortfields = array("Id desc");
		$q->limit = "0,1";
		if ($q->Open()){
			while($row = $q->GetNextRecord()){
				$this->set_id_message($row[0]);
				$this->set_message($row[2]);
				$this->set_dataora($row[3]);
				$this->aMessages[$this->let_id_message()]=array($this->let_id_utente(), $this->let_message(), $this->let_dataora());
			}
		}else{
			$this->aMessages[0]="-1";
		}		
	}
	
	//Cancella un messaggio dato un id
	public function delete_message_by_id(){
		$oDb = Db::getInstance();
		if($this->aPermission["Delete"]==1){
			if ($oDb->DeleteRecords("tblavvisi","id=" . $this->let_id_message() . "")) {
				return true;
			} else {
				//$err[1]=mysql_error();
				return false;
			}
		} else {
			//$err[0] = false;
			//$err[1]="Permesso negato.";
			return false;
		}
	}
	
	//cancella tutti i messaggi di un utente
	public function delete_user_messages(){
		$oDb = Db::getInstance();
		if($this->aPermission["Delete"]==1){
			if ($oDb->DeleteRecords("tblavvisi","IdUt=" . $this->let_id_utente() . "")) {
				return true;
			} else {
				//$err[1]=mysql_error();
				return false;
			}
		} else {
			//$err[0] = false;
			//$err[1]="Permesso negato.";
			return false;
		}
	}

}
?>
