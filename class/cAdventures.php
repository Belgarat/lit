<?php
/**
 *      cAdventures.php
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

class cAdventures {
	protected $classname;
	protected $version;
	private $id_sito;
	private $oUt;
	private $aPermission;
	public $ary_adventures;
	private $ary_groups = array(1=>'Standard', 7=>'Ingresso', 8=>'Invito');
	
	function __construct(){
	 	$this->classname = "cAdventures";
	 	$this->version = "Lux in tenebra <br> 2010 www.luxintenebra.net";
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

	public function let_id_sito(){
		return $this->id_sito;
	}

	/**
	* Funzione che legge la lista delle avventure presenti sul portale
	* che non siano nascoste
	*
	* \return la lista delle avventure o false
	*/
	public function leggi(){
		$query = new Query();

		$query->tables = array("avventure");
		$query->fields = array("ID", "Master", "Titolo", "NumPG", "Iscr", "Storia", "IDMaster", "IDAvv", "Gruppo");
		$query->filters = "hide = 0 and id_sito=".$this->let_id_sito();
		$query->sortfields = array("gruppo","Iscr","ID desc");

		if ($query->Open()){
			while ($row = $query->GetNextRecord(true)){
				$this->ary_adventures[]=$row;
			}
		}
	}

	/**
	* Funzione che recupera i dati del PG (Username e ID) collegati a ciascuna avventura
	*
	* \param $adventure L'avventura per cui si richiede l'elenco (ID)
	* \return array con i PG o false
	*/

	public function getPgAssociated($adventure){
		$query = new Query();
		
		$query->tables = array("pgdb");
		$query->fields = array("Username", "ID");
		$query->filters = "IdAvv = ".$adventure;

		$ary_pg_adv=array();

		if ($query->Open()){
			while ($row= $query->GetNextRecord(true)){
				$ary_pg_adv[$row["ID"]] = $row["Username"];
			}
		}else{
			$ary_pg_adv=false;
		}
		return $ary_pg_adv;
	}

	
	/**
	* Funzione che calcola il numero di PG associati a una avventura
	*
	* \param $titolo Titolo dell'avventura
	* \
	* \return il numero di PG associati o false
	*/

	private function getPgNumber($id){
		//inizializzazione variabili
		$numero = false;
		$query = new Query();
		
		$query->tables = array("pgdb");
		$query->fields = array("count(*)");
		$query->filters = "idAvv in (select IdAvv from avventure where ID=".$id.")";

		if ($query->Open()){
			$row = $query->GetNextRecord();
			$numero = intval($row[0]);
		}
		
		return $numero;
	}
	
	/**
	* Creazione di una nuova avventura
	* 
	* \param $gruppo Gruppo di appartenenza dell'avventura (Ingresso, Standard, Invito)
	* \param $titolo Titolo dell'avventura
	* \param $master Il master titolare dell'avventura, TESTO (Nome)
	* \param $iscr Iscrizioni all'avventura
	* \param $storia Breve narrazione dell'avventura
	* \param $numPG Numero massimo dei PG che partecipano all'avventura
	*
	* \return true se l'inserimento è andato a buon fine, false altrimenti
	*
	* \note NumPG è 0 alla creazione, viene calcolato ad ogni update
	*/

	public function createAventure($gruppo, $master, $titolo, $iscr, $storia, $numPG){
		//recupero i dati mancanti dal DB
		$idMaster = getIdMaster($master);
		
		//Inserimento
		$query = new Query();
		$query->tables = array("avventure");
		$query->fields = array("Master", "Titolo", "NumPG", "Iscr", "Storia", "IDMaster", "Hide", "Gruppo");
		$query->values = array($master, $titolo, $numPG, $iscr, $storia, $idMaster, 0, $gruppo);

		$query->DoInsert();
	}

	/**
	* Funzione di appoggio per la creazione dell'avventura
	* Recupera l'ID del master
	* 
	* \param $masterName Nome del Master del quale recuperare l'ID
	* \return Id del master o false
	*/
	private function getIdMaster($masterName){

		$idMaster = false;
		
		$query = new Query();
		$query->tables = array("userdb");
		$query->fields = array("ID");
		$query->filters = "Name = ".$masterName;

		if ($query->Open()){
			while ($row = $query->GetNextRecord()){
				$idMaster = intval($row[0]);
			}
		}
		return $idMaster;
	}


	/**
	* Funzione per la modifica delle avventure
	* 
	* \param $gruppo Gruppo di appartenenza dell'avventura (Ingresso, Standard, Invito)
	* \param $titolo Titolo dell'avventura
	* \param $master Il master titolare dell'avventura, TESTO (Nome)
	* \param $iscr Iscrizioni all'avventura
	* \param $storia Breve narrazione dell'avventura
	*
	* \return true se l'update è stato fatto, false altrimenti 
	*/

	public function updateAdventure($id, $gruppo, $master, $titolo, $iscr, $storia){
		//inizializzo variabili
		$query = new Query();

		//Numero di PG iscritti
		$numPG = getPgNumber($id);
		$idMaster = getIdMaster($master);

		//update del record
		$query->tables = array("avventure");
		$query->fields = array("Gruppo", "Master", "IdMaster", "Titolo", "Storia", "Iscr", "NumPG");
		$query->values = array($gruppo, $master, $idMaster, $titolo, $storia, $iscr, $numPG);
		$query->filters = "id=".$id;

		$query->DoUpdate();
	}

	#Show: metodo che viene lanciato di default quando la pagina viene inclusa dal modulo cContent.php
	public function show($opt=""){
		$starttime = explode(' ', microtime());
		$starttime =  $starttime[1] + $starttime[0];
		
		//carico il template da utilizzare per la visualizzazione
		$tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/cAdventures.tpl.php'));

		if($this->aPermission["Show"]==1){
			
			$this->leggi();
			$gruppo="";
			$bTable=false;
			$html="";
			foreach ($this->ary_adventures as $adventure){
				//Se ho già messo l'intestazione inserisco le avventure in sequenza				
				if ($gruppo==$adventure["Gruppo"]){
					$html .= '<tr><td width="300"> '.$adventure["Titolo"].'</td><td>'.$adventure["Master"].'</td><td>'.$adventure["NumPG"].'</td><td>'.$adventure["Iscr"].'</td></tr>';
					$bTable=true;
					//in ajax possibile leggere i pg a seconda dell'avv selezionata? (solo a richiesta meno letture DB :))

				}else{
					if($bTable){
						$html .= "</table>\r\n";
						$bTable=false;
					}
					//inserico l'intestazione per ogni gruppo di avventure
					$html .= '<br><table style="border: 3px double #3B1251;" class="avventura" id="id_avventura_'.$adventure["Gruppo"].'">';
					$html .= '<tr><th style="padding-bottom:10px; color: #B95033;text-align: center;font-size: 1.5em;" colspan="4">'.$this->ary_groups[$adventure["Gruppo"]].'</th></td>';
					$html .= '<tr><th>Titolo</td><th>Master</th><th>Num. PG</th><th>Stato avventura</th></tr>';
					$html .= '<tr><td> '.$adventure["Titolo"].'</td><td>'.$adventure["Master"].'</td><td>'.$adventure["NumPG"].'</td><td>'.$adventure["Iscr"].'</td></tr>';
				}
				$gruppo=$adventure["Gruppo"];
			}
			

			//elimino l'ultimo segnaposto
			$tpl = preg_replace("#<!--ADV-->#", $html, $tpl);

			echo $tpl;

			$mtime = explode(' ', microtime());
			$totaltime = $mtime[0] +  $mtime[1] - $starttime;
		}
	}
}
?>
