<?php
/**
 *      cPrivatemesage.php
 *      
 * 		Icon site: http://www.iconarchive.com/category/folder/legendora-icons-by-raindropmemory.html
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
 *
 *@author Belgarat - Marco Brunet
 *@version 1.0
 */
?>
<?php
require_once(SRV_ROOT . "/lib/lib_math.php");
class cPrivatemessage {
	protected $classname;
	protected $version;
	private $id_sito;
	private $oUt;
	private $aPermission;
    private $default_folder=1;

	/**
	*Validate object
	*@access private
	*/
	private $oValidate;
	/**
	*Id message
	*@var integer
	*@access private
	*/
	private $id_msg;
	/**
	*Object message
	*@var varchar
	*@access private
	*/
	private $obj;
	/**
	*Message body
	*@var varchar
	*@access private
	*/	
	private $body;
	/**
	*Message signature
	*@var varchar
	*@access private
	*/	
	private $sign;
	/**
	*Message sender
	*@var varchar
	*@access private
	*/	
	private $from;
	/**
	*Message recipient
	*@var varchar
	*@access private
	*/
	private $to;
	
	function __construct(){
	 	$this->classname = "cPrivatemessage";
	 	$this->version = "Lux in tenebra <br> 2008-09 www.luxintenebra.net";
		$this->oValidate = new cValidate;
		$this->install_modul();
	}

	/**
	*Install record in the tbl_moduls and tbl_permission
	*/
	private function install_modul(){
		$q = new Query();
		$q->tables = array("tbl_moduls");
		$q->fields = array("modul");
		$q->filters = "(modul='".$this->classname."')";
		if($q->Open()){
			if(!$q->GetNextRecord()) {
				$q->Close();
				$q->tables = array("tbl_moduls");
				$q->fields = array("modul");
				$q->values = array($this->classname);
				echo "ciao";
				$q->DoInsert();
			}
		}
	}

	public function include_js(){
		require_once(SRV_ROOT."/class/".$this->classname."/script.js.php");
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

	/**
	*Read site id
	*/
	public function let_id_sito(){
		return $this->id_sito;
	}	

	/**
	*Set message id
	*/
	public function set_id_msg($id){
		if(is_numeric($id)){
			$this->id_msg=$id;
		}else{
			$this->id_msg=0;
		}
	}

	/**
	*Set id message
	*/
	public function let_id_msg(){
		return $this->id_msg;
	}

	/**
	*Set message object
	*/
	public function set_obj($str){
		if (($str=="") || (is_null($str))) {
			$this->obj = "Object omitted!";
		} else {
			$str = $this->oValidate->_sql($str);
			$this->obj = $this->oValidate->_txt($str);
		}
	}

	/**
	*Let message object
	*/
	public function let_obj(){
		return $this->obj;
	}

	/**
	*Set message body
	*/
	public function set_body($str){
		if (($str=="") || (is_null($str))) {
			$this->body = "Body omitted!";
		} else {
			$str = $this->oValidate->_sql($str);
			$this->body = $this->oValidate->_txt($str);
		}
	}

	/**
	*Let message body
	*/
	public function let_body(){
		return $this->body;
	}

	/**
	*Set message signature
	*/
	public function set_sign($str){
		if (($str=="") || (is_null($str))) {
			$this->sign = "";
		} else {
			$str = $this->oValidate->_sql($str);
			$this->sign = $this->oValidate->_txt($str);
		}
	}

	/**
	*Let message signature
	*/
	public function let_sign(){
		return $this->sign;
	}

	/**
	*Set message sender
	*/
	public function set_sender($id){
		if (is_numeric($id)) {
			$this->sender = 0;
		} else {
			$str = $this->oValidate->_sql($str);
			$this->sender = $this->oValidate->_txt($str);
		}
	}
	
	/**
	*Set message status fields
	* stato_src: 0 non letto, 1 spedito, 2 spedito e cancellato, 3 bozza
	* stato_dst: 0 ricevuto, 1 letto, 2 cancellato, 3 archiviato
	*/
	public function set_msg_status($id,$id_src,$id_dst,$status,$idUt=0){

		if($idUt==0){
			$idUt=$this->oUt->id;
		}

		$q = new Query();
		/**
		 * Se l'id utente è uguale all'id sorgente andrò a variare lo stato di invio
		 **/ 
		if($idUt==$id_src){
			$q->fields = array("stato_src");
		}

		/**
		 * Se l'id utente è uguale all'id destinatario andrò a variare lo stato di ricevuto
		 **/ 		
		if($idUt==$id_dst){
			$q->fields = array("stato_dst");
		}
		
		$q->tables = array("tbl_pri_msg");
		$q->values = array($status);
		$q->filters = "(id=".(int)$id.")";

		$result=$q->DoUpdate();
		return $result;
		
	}

	/**
	*Send message
	*@return: true for success, false for failed
	*/
	public function send($dst,$obj,$body,$sign){
		
		$id_dst=$this->let_id_dst($dst);
		
		if($id_dst!=0){
			$q = new Query();
			$q->tables = array("tbl_pri_msg");
			$q->fields = array("id_sito","id_src","id_dst","obj","body","sign","send_timestamp");
			$q->values = array($this->let_id_sito(), $this->oUt->id, $id_dst, $obj, $body, $sign, time());
			$result=$q->DoInsert();
			if($result!=0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}
	
	/**
	*Delete message
	*@return: true for success, false for failed
	*/
	public function delete($idmsg){
		
		$idmsg=(int)$idmsg;
				
		$oDb = Db::getInstance();
		$result=$oDb->DeleteRecords("tbl_pri_msg","id=".$idmsg);

		if($result==1){
			return true;
		}else{
			return false;
		}		
		
	}	

	/**
	*Passed the username return id
	*@return: id user
	*/
	public function let_id_dst($dst){
		
		$q = new Query();
		$q->tables = array("userdb");
		$q->fields = array("id");
		$q->filters = "(Name='".$dst."')";

		if ($q->Open()){
			$iddst = $q->GetNextRecord();
			if($iddst){
				return $iddst[0];
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}
	
	/**
	*Passed the username return id
	*@return: id user
	*/
	public function let_name_dst($id){
		
		$q = new Query();
		$q->tables = array("userdb");
		$q->fields = array("Name");
		$q->filters = "(ID='".$id."')";

		if ($q->Open()){
			$namedst = $q->GetNextRecord();
			if($namedst){
				return $namedst[0];
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}	


	/**
	*Let message status fields
	* stato_src: 0 bozza, 1 spedito, 2 spedito e cancellato
	* stato_dst: 0 ricevuto, 1 letto, 2 cancellato, 3 archiviato
	*/
	public function let_msg_status(){

		
	}

	/**
	*Let message signature
	*/
	public function let_sender(){
		return $this->sender;
	}

	/**
	*Let folders
	*@return array folders list
	*/
	private function let_folders(){
		
		$q = new Query();
		$q->fields = array("id","name","description");
		$q->tables = array("tbl_pri_msg_folder");
		$q->filters = "((id_sito = " . $this->let_id_sito() . " and (id_utente = 0 or id_utente = " . $this->oUt->id . ")))";
		
		if ($q->Open() ){
			while ($row = $q->GetNextRecord()) {
				$aFolder[]=$row;
			}
		}else{
			return false;
		}
		return $aFolder;
	}

	//FINIRE FUNZIONE
	public function let_messages($id_folder=1,$offset=0,$msgxpag=10){
		
		$q = new Query();
		$q->fields = array("id","obj","id_src","send_timestamp","id_dst","stato_src","stato_dst");
		$q->tables = array("tbl_pri_msg");
		switch($id_folder){
			case 1:
				$q->filters = "(id_sito = " . $this->let_id_sito() . " and id_dst = " . $this->oUt->id . ")";
				break;
			case 2:
				$q->filters = "(id_sito = " . $this->let_id_sito() . " and id_src = " . $this->oUt->id . ")";
				break;
			default:
				$q->filters = "(id_sito = " . $this->let_id_sito() . " and id_folder=" . $id_folder  . " and id_src = " . $this->oUt->id . ")";
				break;
		}
		$q->limit = $offset.",".$msgxpag;
		$q->sortfields = array("id desc");

		if ($q->Open() ){
			while ($row = $q->GetNextRecord()) {
				$aMessages[]=$row;
			}
		}else{
			return false;
		}

		return $aMessages;
	}

	/**
	*Da commentare!!!!
	*/
	public function read_message(){
		
		$q = new Query();
		$q->fields = array("obj","body","sign","id_src","id_dst");
		$q->tables = array("tbl_pri_msg");
		$q->filters = "(id_sito = " . $this->let_id_sito() . " and id=" . $this->let_id_msg() . " and ( id_src=" . $this->oUt->id  . "  or id_dst=" . $this->oUt->id  . " ))";

		if ($q->Open() ){
			while ($row = $q->GetNextRecord()) {
				$aMessages=$row;
			}
		}else{
			return false;
		}

		return $aMessages;
	}
	/*
	 *Legge i permessi dell'oggetto forum e restituisce all'interno di un array, gli array per ogni singolo modulo previsto 
	*/
	public function let_permission($id){
		/*
		 * Genero un arrai contentente gli id dei moduli dei permessi di questa classe
		 */
		$id_modul="";
		$q = new Query();
		$q->fields = array("id,modul");
		$q->tables = array("tbl_moduls");
		$q->filters = "( modul like '" . $this->classname . "%' )";
		$q->sortfields = array("id");
		if ($q->Open()){
			while($row = $q->GetNextRecord()){
				$id_modul[$row[0]]=$row[1];
			}
		}
		$q->Close();

		/*
		 * Genera un array con i permessi relativi alla classe specificata ciclando sugli id
		 */

		foreach($id_modul as $key => $value){
			$permission[$value] = $this->oUt->fArrayPermission($this->let_id_sito(),$value,$id,$this->oUt->id, 'tbl_pri_msg', 'id_sito', 'id', 'id_src');
		}
		return $permission;
		/*$q = new Query();
		$q->fields = array("tbl_permission.id","tbl_permission.id_modul", "tbl_permission.create", "tbl_permission.modify", "tbl_permission.delete", "tbl_permission.show");
		$q->tables = array("tbl_permission");
		$q->filters="";
		$flag=true;
		foreach($id_modul as $key => $value){
			if($flag){
				$q->filters = " (( id_modul = " . $key . " )";
				$flag=false;
			}else{
				$q->filters .= " OR ( id_modul = " . $key . " )";
			}
		}
		$q->filters .= ") AND ( id_sito=" . $this->let_id_sito() . " )";
		$q->sortfields = array("id");
		if ($q->Open()){
			while($row = $q->GetNextRecord(true)){
				echo array_search($row['id_modul'],$id_modul);
				$permission[$id_modul[$row['id_modul']]]=$row;
			}
		}
		$q->Close();
		return $permission;
		unset($permission);
		unset($id_modul);*/
	}


	#Show: metodo che viene lanciato di default quando la pagina viene inclusa dal modulo cContent.php
	public function show($opt=""){

		$control=$this->let_permission($this->let_id_msg());

		if($control['cPrivatemessage']['Show']==1){

			$oUtSrc = new cUtente();

			$message_console = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/template.tpl'));
			//$message_console = eregi_replace("<!-- BACKGROUND -->", SRV_ROOT . SITE_IMG . "/pri_msg_bg.jpg", $message_console);
			//$message_console = eregi_replace("<!-- IMG_FOLDER -->", SRV_ROOT . SITE_IMG . "/ico/pri_msg_folder22x22.png", $message_console);
			
			$aFolders=$this->let_folders();
			$html="";
			if(is_array($aFolders)){
				if(count($aFolders)!=-1){
					foreach($aFolders as $key => $folder){
						$ajax="show_folders('id_msg_list','src/ajax/PrivateMessage_folder.php','get','idfolder=" . $folder[0] . "');";
						$html.="<li><a title=\"" . $folder[2] . "\" onClick=\"javascript: ".$ajax."\" href=\"javascript: void(0);\">" . $folder[1] . "</li></a>\r\n";
					}
				}
			}
			$message_console = eregi_replace("<!-- FOLDER -->", $html, $message_console);
			
			if(!isset($id_folder)){
				$id_folder=$this->default_folder;
			}
			
			$aMessages=$this->let_messages($id_folder);



			$html="";
			if(count($aMessages)!=0){
				foreach($aMessages as $key => $msg){
					$color="";
					if($id_folder==2){
						$oUtSrc->id=(int)$msg[4];
						if($msg[5]==0){
							$color="style='font-weight:bold;'";
						}
					}else{
						$oUtSrc->id=(int)$msg[2];
						if($msg[6]==0){
							$color="style='font-weight:bold;'";
						}					
					}
					$oUtSrc->Leggi();
					######### IMPLEMENTARE FILTRO HTML PER JSON DEL MESSAGGIO RESTITUITO #############
					//$ajax="show_message('id_element','src/ajax/PrivateMessage_read.php','get','idmsg=" . $msg[0] . "');$('idmsg_".$msg[0]."').setStyle('font-weight:normal;');";
					$ajax="var msg_read = new Ajax.Updater('id_msg_show_area','src/ajax/PrivateMessage_read.php', { method: 'get', parameters: 'idmsg=".$msg[0]."'});$('idmsg_".$msg[0]."').setStyle('font-weight:normal;');";
					$html.="<li id=\"idmsg_".$msg[0]."\" ".$color.">". date("d-m-y", $msg[3]) ." - <a title=\"Premi per aprire il messaggio\" href=\"#\" onClick=\"javascript: " . $ajax  . "\">".$oUtSrc->dati["Name"]." - " . $msg[1] . "</li></a>\r\n";
				}
			}
			$message_console = eregi_replace("<!-- LIST -->", $html, $message_console);
			
			//$ajax="delete_message('id_msg_list','src/ajax/PrivateMessage_delete.php','get','idmsg=" . $msg[0] . "');";
			//$message_console = eregi_replace("<!-- AJAX_DELETE -->", $ajax, $message_console);
		
		//$message_console = eregi_replace("<!-- PERCORSO -->", $this->oUt->dati["Name"], $message_console);
		//$message_console = eregi_replace("<!-- ID -->", $iIdC, $message_console);
		
			echo $message_console;
			unset($oUtSrc);
		
		}
	}
	
	/**
	 * Function forte testing new message
	 * @return: true new message, false no message
	 **/
	public function test_new_msg(){
		
		$q = new Query();
		$q->fields = array("count(id)");
		$q->tables = array("tbl_pri_msg");
		$q->filters = "id_dst='".$this->oUt->id."' and stato_dst='0'";
		if ($q->Open()){
			$newmsg=$q->GetNextRecord();
			if($newmsg[0]!=0){
				return $newmsg[0];
			}else{
				return 0;
			}
		}else{
			return false;
		}

	}
}
?>
