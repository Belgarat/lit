<?php
	/*
	* progetto per la scheda PG, basato sul nuovo pezzo di database
	* 
	* 
	* Name: 		Giacomo "KloWn" Bella
	* Start Date:	21/11/2008
	* End Date:		n/d
	* 
	* Commenti: 20081121: Restarted developing.
	* 			20081122: Constructor, no dervated class.
	* 			20081201: bugfix in query & leggi() function implemented
	* 			20081204: change query cause to new view created in DB
	* 			20081206: Implemented function letData & defined setData function
	* 			20081212: Implemented function updateData for updating
	* 			20090108: Exception Implementation
	* 			20090309: Created functions to take all data for Pg creation
    * 			20100608: Modify for manage old profile version
    * 			20100608: Add OLDPROFILE costant to determinate the object istance method
	*/

require_once("cAdventures.php");
	class cPg{

		protected $classname;
		protected $version;
		
		public $ary_descpg=array(); //array that contains all desription tag of the PG like name bg & so on
		private $ary_abilities=array();
		private $ary_spells=array();
		private $ary_talents=array();
		private $ary_equipment=array();
		private $ary_lang=array();
		private $ary_notes=array();
		private $aPermission=array();
		private $control=array();
		private $ipg_id; //Id user that owns pg
		private $oUt;
		private $id_sito;
		
		private $table="pgdb"; //imposta quale tabella verrà considerata per il __get e __set delle proprietà dei campi secondo il sistema Overlord delle proprietà
		private $table_index="id"; //imposta quale indice tabella verrà considerata per il __get e __set delle proprietà dei campi secondo il sistema Overlord delle proprietà
                
                private $url_script;
	
		/*
		* Costruttore della classe
		* 
		* development start 22/11/2208
		* developer name Giacomo "KloWn" Bella
		* 
		* commenti: 20081122: start developing.
		* 			20081129: MacroBug fix  O.o
		* 
		*/
		public function __construct(){
			$this->classname = "cPg";
			$this->version = "Lux in tenebra <br> 2004 www.luxintenebra.net";			
			/*
			* TYPE_PROFILE=1 old profile 
			* TYPE_PROFILE=0 new profile 
			*/
			define("TYPE_PROFILE",1);
                        $this->url_script = $_SERVER["SCRIPT_NAME"] . "?IdP=" . @$_GET["IdP"];
		}

		public function include_js(){
			require_once(SRV_ROOT."/class/".$this->classname."/script.js.php");
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
			$permission[$value] = $this->oUt->fArrayPermission($this->let_id_sito(),$value,$id,$this->oUt->id, 'pgdb', 'id_sito', 'id', 'IDUt');
		}
		$this->aPermission=$permission;
		return $permission;
	}

		public function set_table($table,$index='id'){
			$this->table = $table;
			$this->table_index = $index;
		}

		public function let_table(){
			return $this->table;
		}

		public function let_table_index(){
			return $this->table_index;
		}

		public function set_user($id_sito,$obj){
			if (is_object($obj)) {
				$this->oUt = $obj;
				//$this->aPermission = $this->oUt->fArrayPermission($this->let_id_sito(),$this->classname);
				$this->aPermission = $this->oUt->fArrayPermission($this->let_id_sito(),'cPg','',$this->oUt->id, 'pgdb', 'id_sito', 'ID', 'IDUt');
				$this->control = $this->let_permission($this->let_id_pg());
			 } else {
				echo "Non e' un oggetto.";
				return false;
			 }
		}

		/**
		* functions that set id pg
		* 
		* develop start 21/07/2010
		* developer name Marco "Belgarat" Brunet
		* 
		*/
		public function set_id_pg($id=0){
			$id = (int) $id;
			if($id!=0){
				$this->ipg_id=$id;
			}else{
				$this->ipg_id=0;
			}
		}

		/**
		* functions that let id pg
		* 
		* develop start 21/07/2010
		* developer name Marco "Belgarat" Brunet
		* 
		*/
		public function let_id_pg(){
			return (int)$this->ipg_id;
		}

		
		/**
		* functions that reads basilar characteristics(?) of the PG
		* 
		* develop start 22/11/2008
		* developer name Giacomo "KloWn" Bella
		* 
		* commenti: 20081122: defined method firm
		* 			20081129: started developing
		* 			20090108: Exception implementation
        * 			20100608: Implemented read old profile version
		*			20100617: Correct deprecated GetAssoc function		
		*/
		public function leggi(){

			//if (TYPE_PROFILE==1){
    			$query_all = new Query();
	    		$query_all->tables = array("pg_view");
		    	$query_all->fields = array("*");
			    $query_all->filters = "pg_view.id = ".$this->ipg_id;
				
				/*
    			* if (!($this->ipg_id>0)){
	    		* 	throw new Exception ("No Pg to read from");
		    	* }
				*/
				if ($query_all->Open()){
					$this->ary_descpg = $query_all->GetNextRecord(true);
				}

    			$query_all = new Query();
	    		$query_all->tables = array("pg_abilities");
		    	$query_all->fields = array("*");
			    $query_all->filters = "pg_abilities.idpg = ".$this->ipg_id;
				
				/*
    			* if (!($this->ipg_id>0)){
	    		* 	throw new Exception ("No Pg to read from");
		    	* }
				*/
				if ($query_all->Open()){
					while($element = $query_all->GetNextRecord(true)){
						$this->ary_abilities[] = $element;
					}
				}

    			$query_all = new Query();
	    		$query_all->tables = array("pg_talenti");
		    	$query_all->fields = array("*");
			    $query_all->filters = "pg_talenti.PG = ".$this->ipg_id;
				
				/*
    			* if (!($this->ipg_id>0)){
	    		* 	throw new Exception ("No Pg to read from");
		    	* }
				*/
				if ($query_all->Open()){
					while($element = $query_all->GetNextRecord(true)){
						$this->ary_talents[] = $element;
					}
				}
    			$query_all = new Query();
	    		$query_all->tables = array("pg_incantesimi");
		    	$query_all->fields = array("*");
			    $query_all->filters = "pg_incantesimi.PG = ".$this->ipg_id;
			    $query_all->sortfields = array("Classe","LivRichiesto","incantesimo");
				
				/*
    			* if (!($this->ipg_id>0)){
	    		* 	throw new Exception ("No Pg to read from");
		    	* }
				*/
				if ($query_all->Open()){
					while($element = $query_all->GetNextRecord(true)){
						$this->ary_spells[] = $element;
					}
				}

    			$query_all = new Query();
	    		$query_all->tables = array("pg_oggetti");
		    	$query_all->fields = array("*");
			    $query_all->filters = "pg_oggetti.PG = ".$this->ipg_id;
			    $query_all->sortfields = array("Categoria","tipo");
				
				/*
    			* if (!($this->ipg_id>0)){
	    		* 	throw new Exception ("No Pg to read from");
		    	* }
				*/
				if ($query_all->Open()){
					while($element = $query_all->GetNextRecord(true)){
						$this->ary_equipment[] = $element;
					}
				}

    			$query_all = new Query();
	    		$query_all->tables = array("pg_lingua");
		    	$query_all->fields = array("*");
			    $query_all->filters = "pg_lingua.PG = ".$this->ipg_id;
			    $query_all->sortfields = array("Lingua","Zona");
				
				/*
    			* if (!($this->ipg_id>0)){
	    		* 	throw new Exception ("No Pg to read from");
		    	* }
				*/
				if ($query_all->Open()){
					while($element = $query_all->GetNextRecord(true)){
						$this->ary_lang[] = $element;
					}
				}

    			$query_all = new Query();
	    		$query_all->tables = array("tblnotepg");
		    	$query_all->fields = array("*");
			    $query_all->filters = "tblnotepg.IdPg = ".$this->ipg_id;
				
				/*
    			* if (!($this->ipg_id>0)){
	    		* 	throw new Exception ("No Pg to read from");
		    	* }
				*/
				if ($query_all->Open()){
					while($element = $query_all->GetNextRecord(true)){
						$this->ary_notes[] = $element;
					}
				}

            /*}else{
    			$query_all = new Query();
	    		$query_all->tables = array("pgdb");
		    	$query_all->fields = array("*");
			    $query_all->filters = "ID = ".$this->ipg_id;
			
    			if (!($this->ipg_id>0)){
	    			throw new Exception ("No Pg to read from");
		    	}
                if($query_all->Open()){
    			    $this->ary_descpg = $query_all->GetNextRecord(true);
                }
            }*/
		}

		public function __get($property){
    			$query_all = new Query();
	    		$query_all->tables = array($this->table);
		    	$query_all->fields = array($property);
			    $query_all->filters = $this->table_index . " = ".$this->ipg_id;
				//echo $query_all->getSQL();

				if ($query_all->Open()){
					$row=$query_all->GetNextRecord();
					return $row[0];
				} else {
					echo "Errore: propriet&agrave o metodo '$property' inesistente.";
				}
		}

		public function __set($property,$value){
    			$query_all = new Query();
	    		$query_all->tables = array($this->table);
		    	$query_all->fields = array($property);
		    	$query_all->values = array($value);
			    $query_all->filters = $this->table_index . " = ".$this->ipg_id;
				//echo $query_all->getSQL();

				if ($query_all->Update()){
					return true;
				} else {
					echo "Errore: propriet&agrave o metodo '$property' inesistente.";
				}
		}

		/**
		* funzione verifica l'esistenza di un pg con lo stesso nome per uno stesso utente
		*  
		* develop start: 05/08/2010
		* Developer Name: Marco "Belgarat" Brunet
		* \param $id_user ID del proprietario del PG
		* \param $pg_name Il nome del PG di cui verificare l'esistenza
		*
		* \return pg id se true, false altrimenti
		*/

		public function exist_pg_user($id_user, $pg_name){

			$q = new Query();
			$q->tables = array("pg_view");
			$q->fields = array("id");
			$q->filters = "idUt=".$id_user." and pg_view.Username = '".$pg_name."'";
			
			if($q->Open()){
				$row=$q->GetNextRecord();				
				if(is_array($row)){
					return $row[0];
				}else{
					return false;
				}
			}else{
				return false;
			}
			
		}


		/**
		* Funzione che ritorna una lista di personaggi gestiti da quel master
		*  
		* develop start: 05/08/2010
		* Developer Name: Marco "Belgarat" Brunet
		* \param $id_user ID del master		
		*
		* \return array con lista pg gestiti dal master
		*/		
		public function pg_master_list($id_user){

			$q = new Query();
			$q->tables = array("pg_view");
			$q->fields = array("Username","id","photo");
			$q->filters = "pg_view.id_sito=". $this->let_id_sito() ." and pg_view.MasterID = '".$id_user."'";
			
			if($q->Open()){
				$pg_master="";
				while($row=$q->GetNextRecord(true)){
					$pg_master[]=$row;
				}
				if(is_array($pg_master)){
					return $pg_master;
				}else{
					return false;
				}
			}else{
				return false;
			}
			
		}		
			    

		
		/**
		* funzione che reperisce i dati da un array associativo 
		* definita la chiave $data che può essere un array o una stringa.
		* 
		* develop start: 01/12/2008
		* Developer Name: Giacomo "Klown" Bella
		* 
		* commenti: 20081201: start develop
		* 			20081206: add string code and if stuff
		* 			20081207: implemented array-based request management
		*  			20090108: Exception implementation
		*/
		
		public function letData($data){
			
			if(!isset($data)){
				throw new Exception("Sintassi non valida: 1 parametro richiesto");	
			}elseif(is_array($data)){
				//ritorna stringa intera formattata in tabella 
				$result = "<table class=\"pg_response\">\n";
				foreach($data as $request){
					$result .= "\t<tr>\n";
					if (array_key_exists($request, $this->ary_descpg)){ 
						$result .= "\t\t<td>".$request."</td><td>".$this->ary_descpg[$request]."</td>";
					}else{
						throw new Exception ("Accesso al campo ");
					}
				}
				$result .= "</table>";
				return $result;				
			}elseif(is_string($data) and array_key_exists($data, $this->ary_descpg)){
				return $this->ary_descpg[$data];
			}else{
				throw new Exception("Richiesta formulata non correttamente");
			}
		}
		
		/**
		* Funzione per l'aggiornamento delle info nel database dopo le modifiche
		* Non controlla i permessi: li controlla l'oggetto di gestione GestPg.php
		* 
		* develop started: 12/12/2008
		* developer name: Giacomo "klown" Bella
		* 
		* commenti:	20081212: start development
		* 			20081212: modified  function name
		* 			20090108: Exception Implementation
		*/
		
		public function updateData($fields, $values, $table="View_PC_Summary"){
			//prevede 2 array in ingresso :) 
			
			$updateQuery = new Query(); //apro l'oggetto query
			$updateQuery->tables=array($table);
			
			$result = false;
			/*
			* recuperare nomi e tipo dei campi da database
			*/
			if (!$fields || !$values){ // se non sono settati errore
				return false;
			}elseif (is_array($fields) && is_array($values)){
				$dataCounter=0;
				foreach ($fields as $field){
					$updateQuery->fields[]=$field;
					$updateQuery->values[]=$values[$dataCounter];
					$dataCounter++;
				}
				$updateQuery->filters="id=".$this->ipg_id;
				//$result=$updateQuery->getSQL();
				if($updateQuery->DoUpdate()){
					//$result = true;
				}else{
					//var_dump($updateQuery->values);
					$result=mysql_error();
					echo $result;
				}
			}
			//$result=mysql_error();
			$updateQuery->Close();
			return $result;
		}

		/**
		* Funzione per l'aggiornamento dei dati del personaggio
		* data la tabella, campo da aggiornare e id pg
		* 
		* develop started: 05/12/2010
		* developer name: Marco "Belgarat" Brunet
		* 
		* commenti:	20101205: start development
		*/
		
		public function updateField($id_pg, $field, $value, $table="pg_view"){
			//prevede 2 array in ingresso :) 
			
			$updateQuery = new Query(); //apro l'oggetto query
			$updateQuery->tables=array($table);
			
			$updateQuery->fields=array($field);
			
			$updateQuery->values=array($value);
			
			$updateQuery->filters="id=".(int)$id_pg;
			
			if($updateQuery->DoUpdate()){
				$result = true;
			}else{
				$result = false;
			}
			
			$updateQuery->Close();
			
			return $result;
		}
			
		/**
		* Funzione che genera i valori casuali per la scheda pg (caratteristiche)
		* ritorna un array con chiave la caratteristica e valori casuali
		* 
		* Author: Giacomo Bella
		* Start Date: 12/02/2009
		* 
		* Comments:	20090212 - Start developing
		* 
		*/	
		public function randomAbility(){
			define(MIN, 3);
			define(MAX, 18);
			$result=array();
		
			$query=new Query();
			$query->tables=array("Ability");
			$query->fields=array("Ability.name");
			if ($query->Open()){
				while ($element = $query->GetNextRecord()){
					$result["".$element[0]]=mt_rand(MIN, MAX);
				}
			}else{
				$query->Close();
				throw new Exception("Error accessing Database");
			}
			$query->Close();
			return $result;
		}
			
		/**
		* Lista di classi disponibili per la selezione
		*
		* \return array con le classi e descrizione, false
		*/	
		
		public function selectClass(){
			$query = new Query();

			$query->tables = array("classi");
			$query->fields = array("id", "_Desc");
			
			if ($query->Open()){
				while ($row = $query->GetNextRecord(true)){
					$classes[] = $row;
				}
			}else{
				return false;
			}

			return $classes;
		}

		/**
		* Lista degli allineamenti disponibili per la selezione
		*
		* \return array con gli allinementi (id, descrizione, Sigla), false
		*/

		public function selectAlign(){
			$query = new Query();

			$query->tables = array("allineamento");
			$query->fields = array("id", "_Desc", "Sigla");
			
			if ($query->Open()){
				while ($row = $query->GetNextRecord(true)){
					$align[] = $row;
				}
			}else{
				return false;
			}

			return $align;
		}

		/**
		* Lista delle disponibili per la selezione
		*
		* \return array con le razze (id, descrizione, Sigla), false
		*/
		public function selectRace(){
			$query = new Query();

			$query->tables = array("razze");
			$query->fields = array("id", "_Desc", "Sigla");
			
			if ($query->Open()){
				while ($row = $query->GetNextRecord(true)){
					$races[] = $row;
				}
			}else{
				return false;
			}

			return $races;
		}
		

		/**
		* Lista dei talenti disponibili per la selezione
		*
		* \return array con i talenti (id, nome, tipo, descrizione, prerequisito), false
		*/
		public function selectTalents(){
			$query = new Query();

			$query->tables = array("talenti_completa");
			$query->fields = array("id", "Nome", "Tipo", "Descrizione", "prerequisito");
			
			if ($query->Open()){
				while ($row = $query->GetNextRecord(true)){
					$talents[] = $row;
				}
			}else{
				return false;
			}

			return $talents;
		}


		/**
		* Lista delle lingue disponibili per la selezione
		*
		* \retrun array con le lingue (id, lingua, zona), false altrimenti
		*/

		public function selectLanguage(){
			$query = new Query();

			$query->tables = array("tbllingua");
			$query->fields = array("id", "Lingua", "Zona");

			if ($query->Open()){
				while ($row = $query->GetNextRecord(true)){
					$languages[] = $row;
				}
			}else{
				return false;
			}

			return $languages;
		}
			
		/**
		* Creazione del PG (piccolo dubbio sul farlo così, ma ragionando a tabs è una query in meno)
		* 
		* \param $user ID del proprietario del PG
		* \param $name Il nome del PG da creare
		* \param $sesso Il sesso del PG
		* \param $descr La descrizione fisica del PG
		* \param $eta L'età del PG
		*
		* \return Integer l'ID del pg o false se ci sono stati errori
		*/
		public function createPG($user, $name, $sesso, $descr, $eta){
			//inizializzo le query
			$query = new Query();
			
			//creo il pg
			$query->tables = array('pgdb');
			$query->fields = array('IdUt', 'Username', 'Sesso', 'Descrizione', 'Eta', 'id_sito');
			$query->values = array($user, $name, $sesso, $descr, $eta, $this->let_id_sito());

			return $query->DoInsert();
		}

		/**
		* Imposta l'avventura associata a al PG
		* 
		* \param $id_avv ID avventura da impostare
		* \param $id_pg ID PG da impostare, se zero tenta di impostare l'avventura al personaggio corrente
		*
		* \return boolean true = success
		*/
        public function set_adventure($id_avv,$id_pg=0){
            if($id_pg==0){
                $id_pg=$this->ipg_id;
            }else{
                $id_pg=(int)$id_pg;
            }
            $id_avv=(int)$id_avv;

            /*update the pg record setting IdAvv field*/
			$q = new Query();
			
			$q->tables = array('pgdb');
			$q->fields = array('IdAvv');
			$q->values = array($id_avv);
            $q->filters = "(id=".$id_pg.")";

			return $q->DoUpdate();

        }

		/**
        * Imposta l'avventura associata a al PG
		* 
		* \param $id_master ID del master da associare
		* \param $id_pg ID PG da impostare, se zero tenta di impostare l'avventura al personaggio corrente
		*
		* \return boolean true = success
		*/
        public function set_master($id_master,$id_pg){
            if($id_pg==0){
                $id_pg=$this->ipg_id;
            }else{
                $id_pg=(int)$id_pg;
            }
            $id_master=(int)$id_master;

            /*update the pg record setting MasterID field*/
			$q = new Query();
			
			$q->tables = array('pgdb');
			$q->fields = array('MasterID');
			$q->values = array($id_master);
            $q->filters = "(id=".$id_pg.")";

			return $q->DoUpdate();
       }


		/**
		* Inserimento delle caratteristiche del PG
		* 
		* \param $id ID del PG
		* \param $for Forza del PG
		* \param $des Destrezza del PG
		* \param $cos Costituzione del PG
		* \param $int Intelligenza del PG
		* \param $sag Saggezza del PG
		* \param $car Carisma del PG
		* 
		* \return true, false
		*/

		public function setCar($id, $for, $des, $cos, $int, $sag, $car){
			$fieldList = array();
			
			$query = new Query();

			$query->tables('tblcar');
			$query->fields('SiglaCar');
			if ($query->Open()){
				while ($row = $query->GetNextRecord()){
					$fieldList[]=$row;
				}
				//per non avere "effetti memoria" strani
				$query->Close();
				unset($query);

				$query2 = new Query();
				$query2->tables = array ('tblpg_car');

				foreach ($fieldList as $item){
					$query2->fields[]= $item[0];
				}
				$query2->fields[] = 'IdPg';

				$query2->values = array($for, $des, $cos, $int, $sag, $car, $id);

				return ($query2->DoInsert())?true:false;
			}else{
			
				return false;
			}

		}

		/**
		* Inserimento dei talenti del PG
		* 
		* \param $talento Talento da assegnare al PG
		* \param $id Pg a cui assegnare il talento 
		* 
		* \return true, false
		*/

		public function setTalents($id, $talento){
			$query= new Query ();

			$query->tables = array("tblpg_tal");
			$query->fields = array("IdPG", "IdTal");
			$query->values = array($id,$talento);

			return ($query->DoInsert())?true:false;
		}

		/**
		* Inserimento delle abilità del PG
		* 
		* \param $id Pg che riceverà la abilità
		* \param $abilita Id dell'abilità da inserire
		* \param $grado Grado dell'abilità da inserire
		* 
		* \return true, false
		*/

		public function setAbilites($id, $abilita, $grado){
			$query = new Query();

			$query->tables = array("tblpg_ab");
			$query->fields = array("IdPG", "IdAb", "Gradi");
			$query->values = array($id, $abilita, $grado);

			return ($query->DoInsert())?true:false;
		}

		/**
		* Inserimento degli oggetti del pg
		*
		* \param $id Pg che possiede l'oggetto
		* \param $obj Oggetto che viene comprato dal PG
		* \param $objType Tipo di oggetto (default 0)
		*
		* \return true, false
		*/

		public function setEquipment($id, $obj, $objType=0){
			if ($objType === 0){
				$objType = getObjType($obj);
			}
			
			$query = new Query();
			
			$query->tables = array("tblpg_ogg");
			$query->fields = array("IdPG", "IdOgg", "TipoOgg");
			$query->values = array($id, $obj, $objType);

			return ($query->DoInsert())?true:false;
		}
		

		/**
		* Recupera il tipo di un oggetto
		* 
		* \param $obj L'oggetto
		* 
		* \return int - l'id del tipo o 0
		*/

		private function getObjType($obj){
			$query = new Query();

			$query->tables = array("tbloggetti");
			$query->fields = array("TipoOggetto");
			$query->filters = "ID=".$obj;

			if ($query->Open()){
				$row = $query->GetNextRecord();
				return $row[0];
			}

			return 0;
		}

		/**
		* Inserisce la lingua parlata da un PG
		* 
		* \param $id PG che conosce la lingua
		* \param $lang Lingua
		* \param $grado Grado di conoscenza della lingua (default 1)
		*/

		public function setLanguage($id, $lang, $grado=1){
			$query = new Query();

			$query->tables = array("tblpg_lingua");
			$query->fields = array("IdPG", "idLingua", "Gradi");
			$query->values = array($id, $lang, $grado);

			return ($query->DoInsert())?true:false;
		}


		/**
		* Aggiornamento del PG
		*
		* \param $id Id del PG
		* \param $eta Eta del PG
		* \param $sesso Sesso del PG
		* \param $desc Descrizione del PG
		* \param $align integer Allineamento del PG (ID)
		* \param $race integer Razza del PG (ID)
		* 
		* \return true, false
		*/

		public function updatePG ($id, $eta, $sesso, $desc, $align, $race){
			$query=new Query();

			$query->tables = array('pgdb');
			$query->fields = array('Eta','Sesso','Descrizione', 'Allineamento', 'Razza');
			$query->values = array ($eta, $sesso, $desc, $alig, $race);
			$query->filters = "Id=".$id;

			$query->DoUpdate();
		}

		/**
		* Aggiornamento del BG
		* 
		* \param $id Id del PG
		* \param $bg Background del PG
		*
		* \return void
		*/

		public function updateBackground ($id, $bg){
			$query = new Query();

			$query->tables = array('pgdb');
			$query->fields = array('bg');
			$query->values = array($bg);
			$query->filters = "Id=".$id;

			$query->DoUpdate();
		}

		/**
		* Funzione per l'inserimento delle  classi 
		* 
		* \param $id id del PG
		* \param $class1 prima classe ad essere impostata (ID) obbligatoria
		* \param $level1 Livello della prima classe obbligatoria
		* \param $class2 seconda classe (default 14)
		* \param $level2 Livello della seconda classe (defualt 0)
		*
		* \return void
		*/

		public function updateClasses($id, $class1, $level1, $class2=14, $level2=0){
			$biclass = 0;
			//pg multiclasse
			if ($class2 != 14){
				$biclass = -1;
			}

			$query = new Query();
			$query->tables = array('pgdb');
			$query->fields = array('Classe1','Livello1','Classe2','Livello2','Biclasse');
			$query->values = array($class1, $level1, $class2, $level2, $biclass);

			$query->filters = "Id=".$id;

			$query->DoUpdate();

		}
		
		//! esperienza del PG (fare commento fatto bene)
		public function updateExperience($id, $PE1, $PE2=0){
			$query = new Query();

			$query->tables = array('pgdb');
			$query->fields = array('PE', 'PE2');
			$query->values = array($PE1, $PE2);
			$query->filters = "id=".$id;

			$query->DoUpdate();
		}


	
		#Show: metodo che viene lanciato di default quando la pagina viene inclusa dal modulo cContent.php
	public function show($opt=""){
		
                $opt=htmlspecialchars($opt);
            
		$starttime = explode(' ', microtime());
		$starttime =  $starttime[1] + $starttime[0];

                if($_SERVER["HTTP_X_REQUESTED_WITH"] == 'XMLHttpRequest'){
                    $tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/charactersheet_DnD.tbl.php'));
                    $this->show_pg($tpl);
                }else{
                    if($opt=="editpg"){
                        $tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/edit_noajax_pg.tbl.php'));
                        $id_pg=(int)@$_GET["idpg"];
                        $this->set_id_pg($id_pg);
                        $this->set_id_sito($_SERVER["SITO"]);
                        $this->set_user($_SERVER["SITO"],$this->oUt);
                        $this->leggi();
                        $this->show_edit_pg("","",$tpl);
                    }else{
                        $tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/charactersheet_noajax_DnD.tbl.php'));
                        $id_pg=(int)@$_GET["idpg"];
                        $this->set_id_pg($id_pg);
                        $this->set_id_sito($_SERVER["SITO"]);
                        $this->set_user($_SERVER["SITO"],$this->oUt);
                        $this->leggi();
                        $tpl = preg_replace("#<!-- LINKEDIT -->#", "'".$this->url_script."&idpg=".$id_pg."&opt=editpg"."'", $tpl);
                        $this->show_pg($tpl);
                    }
                }

		//printf('Page loaded in %.3f seconds.',  $totaltime);

	}	

        public function show_pg($tpl=""){

            if($this->control["cPg"]["Show"]==1){

                    //$this->leggi();                        

                    $tpl = preg_replace("#<!--LBL_NAME_PG-->#", "Nome pg: ", $tpl);
                    $tpl = preg_replace("#<!--NAME_PG-->#", $this->ary_descpg["Username"], $tpl);
                    $tpl = preg_replace("#<!--LI_ID-->#", $this->ary_descpg["id"], $tpl);
                    $tpl = preg_replace("#<!--ID_PG-->#", $this->ary_descpg["id"], $tpl);

                    $tpl = preg_replace("#<!--LBL_ALIGNMENT-->#", "Allineamento: ", $tpl);
                    $tpl = preg_replace("#<!--ALIGNMENT-->#", $this->ary_descpg["allineamento"], $tpl);
                    $tpl = preg_replace("#<!--LI_ID-->#", $this->ary_descpg["id"], $tpl,2);

                    $tpl = preg_replace("#<!--LBL_GENDER-->#", "Sesso: ", $tpl);
                    $tpl = preg_replace("#<!--GENDER-->#", $this->ary_descpg["sesso"], $tpl);

                    $tpl = preg_replace("#<!--LBL_RACE-->#", "Razza: ", $tpl);
                    $tpl = preg_replace("#<!--RACE-->#", $this->ary_descpg["razza"], $tpl);

                    $tpl = preg_replace("#<!--LBL_CLASS-->#", "Classe pri. : ", $tpl);
                    $tpl = preg_replace("#<!--CLASS-->#", $this->ary_descpg["classe"], $tpl);

                    $tpl = preg_replace("#<!--LBL_CLASS2-->#", "Classe sec. : ", $tpl);
                    $tpl = preg_replace("#<!--CLASS2-->#", $this->ary_descpg["classe2"], $tpl);

                    $tpl = preg_replace("#<!--LBL_LEVEL-->#", "Livello pri. : ", $tpl);
                    $tpl = preg_replace("#<!--LEVEL-->#", $this->ary_descpg["livello1"], $tpl);

                    $tpl = preg_replace("#<!--LBL_LEVEL2-->#", "Livello sec. : ", $tpl);
                    $tpl = preg_replace("#<!--LEVEL-->#", $this->ary_descpg["livello2"], $tpl);

                    $tpl = preg_replace("#<!--LBL_INIZIATIVE-->#", "Iniziativa: ", $tpl);
                    $tpl = preg_replace("#<!--INIZIATIVE-->#", $this->ary_descpg["Ini"], $tpl);

                    $tpl = preg_replace("#<!--LBL_SENSIS-->#", "SENSIS: ", $tpl);
                    $tpl = preg_replace("#<!--SENSIS-->#", $this->ary_descpg["livello1"], $tpl);

                    //id_pg_defence
                    $tpl = preg_replace("#<!--LBL_ARMOR_CLASS-->#", "CA: ", $tpl);
                    $tpl = preg_replace("#<!--ARMOR_CLASS-->#", $this->ary_descpg["CA"], $tpl);

                    $tpl = preg_replace("#<!--LBL_HIT_POINT-->#", "PF: ", $tpl);
                    $tpl = preg_replace("#<!--HIT_POINT-->#", $this->ary_descpg["PF"], $tpl);

                    $tpl = preg_replace("#<!--LBL_FORTITUDE-->#", "Tempra: ", $tpl);
                    $tpl = preg_replace("#<!--FORTITUDE-->#", $this->ary_descpg["Tem"], $tpl);

                    $tpl = preg_replace("#<!--LBL_REFLEX-->#", "Riflessi: ", $tpl);
                    $tpl = preg_replace("#<!--REFLEX-->#", $this->ary_descpg["Rif"], $tpl);

                    $tpl = preg_replace("#<!--LBL_WILL-->#", "Volonta: ", $tpl);
                    $tpl = preg_replace("#<!--WILL-->#", $this->ary_descpg["Vol"], $tpl);

                    $tpl = preg_replace("#<!--LBL_RESISTENCE-->#", "Tempra: ", $tpl);
                    $tpl = preg_replace("#<!--RESISTENCE-->#", $this->ary_descpg["Tem"], $tpl);

                    $tpl = preg_replace("#<!--LBL_SPEED-->#", "Velocit&agrave: ", $tpl);
                    $tpl = preg_replace("#<!--SPEED-->#", "Non gestito", $tpl);

                    $tpl = preg_replace("#<!--LBL_GRAPPLE-->#", "Mischia: ", $tpl);
                    $tpl = preg_replace("#<!--GRAPPLE-->#", $this->ary_descpg["Mis"], $tpl);

                    $tpl = preg_replace("#<!--LBL_DISTANCE-->#", "Distanza: ", $tpl);
                    $tpl = preg_replace("#<!--DISTANCE-->#", $this->ary_descpg["Dis"], $tpl);

                    //id_pg_speel
                    $tpl = preg_replace("#<!--LBL_SPELL-->#", "Incantesimi", $tpl);
                    $tmp="<ul>";
                    foreach ($this->ary_spells as $spell){
                            if($spell["LivRichiesto"]==$actuallevel){
                                    $tmp.="<li>".$spell["incantesimo"]."</li>\r\n";
                            }else{
                                    $tmp.="<li style='font-weight:bold;margin-top:10px;'>Livello: ".$spell["LivRichiesto"]."</li>";
                                    $tmp.="<li>".$spell["incantesimo"]."</li>\r\n";
                            }
                            $actuallevel=$spell["LivRichiesto"];
                    }
                    $tmp.="</ul>";
                    $tpl = preg_replace("#<!--SPELL-->#", $tmp, $tpl);

                    //id_pg_special

                    //id_pg_statistic

                    $tpl = preg_replace("#<!--LBL_STR-->#", "Forza: ", $tpl);
                    $tpl = preg_replace("#<!--STR-->#", $this->ary_descpg["_For"], $tpl);

                    $tpl = preg_replace("#<!--LBL_DEX-->#", "Destrezza: ", $tpl);
                    $tpl = preg_replace("#<!--DEX-->#", $this->ary_descpg["Des"], $tpl);

                    $tpl = preg_replace("#<!--LBL_CON-->#", "Costituzione: ", $tpl);
                    $tpl = preg_replace("#<!--CON-->#", $this->ary_descpg["Cos"], $tpl);

                    $tpl = preg_replace("#<!--LBL_INT-->#", "Intelligenza: ", $tpl);
                    $tpl = preg_replace("#<!--INT-->#", $this->ary_descpg["_Int"], $tpl);

                    $tpl = preg_replace("#<!--LBL_WIS-->#", "Saggezza: ", $tpl);
                    $tpl = preg_replace("#<!--WIS-->#", $this->ary_descpg["Sag"], $tpl);

                    $tpl = preg_replace("#<!--LBL_CHA-->#", "Carisma: ", $tpl);
                    $tpl = preg_replace("#<!--CHA-->#", $this->ary_descpg["Car"], $tpl);

                    $tpl = preg_replace("#<!--LBL_BAB-->#", "BAB: ", $tpl);
                    $tpl = preg_replace("#<!--BAB-->#", $this->ary_descpg["Dis"], $tpl);

                    //skills
                    $tpl = preg_replace("#<!--LBL_SKILLS-->#", "Abilit&agrave", $tpl);
                    $tmp="<dl>";
                    foreach ($this->ary_abilities as $skills){
                            $tmp.="<dt title='".$skills["descrizione"]."'>".$skills["Abilita"]." (Gradi: ".$skills["Gradi"]." - Car: ".$skills["carbase"].")<a href=\"javascript: void(0);\" onMouseOver=\"javascript: $('".$skills["Abilita"]."').style.display='block';\" onMouseOut=\"javascript: $('".$skills["Abilita"]."').style.display='none';\"><img height='12' border='0' src='./img/pbf/pg/add_info.png'></a></dt>\r\n";
                            $tmp.="<dd id='".$skills["Abilita"]."'>".$skills["descrizione"]."</dd>\r\n";
                    }
                    $tmp.="</dl>";
                    $tpl = preg_replace("#<!--SKILLS-->#", $tmp, $tpl);

                    //talents
                    $tpl = preg_replace("#<!--LBL_TALENT-->#", "Talenti", $tpl);
                    $tmp="<dl>";
                    foreach ($this->ary_talents as $talents){
                            $tmp.="<dt title='".$talents["descrizione"]."'>".$talents["nome"]." (Prerequisito: ".$talents["prerequisito"]." - Tipo: ".$talents["tipo"].")<a href=\"javascript: void(0);\" onMouseOver=\"javascript: $('".$talents["nome"]."').style.display='block';\" onMouseOut=\"javascript: $('".$talents["nome"]."').style.display='none';\"><img height='12' border='0' src='./img/pbf/pg/add_info.png'></a></dt>\r\n";
                            $tmp.="<dd id='".$talents["nome"]."'>".$talents["descrizione"]."</dd>\r\n";
                    }
                    $tmp.="</dl>";
                    $tpl = preg_replace("#<!--TALENT-->#", $tmp, $tpl);

                    //language
                    $tpl = preg_replace("#<!--LBL_LANGUAGE-->#", "Lingue", $tpl);
                    $tmp="<dl>";
                    foreach ($this->ary_lang as $lang){
                            $tmp.="<dt>".$lang["Lingua"]."</dt>\r\n";
                            $tmp.="<dd>Zona: ".$lang["Zona"]."</dd>\r\n";
                    }
                    $tmp.="</dl>";
                    $tpl = preg_replace("#<!--LANGUAGE-->#", $tmp, $tpl);

                    //id_pg_property
                    //equipment
                    $tpl = preg_replace("#<!--LBL_EQUIPMENT-->#", "Equipaggiamento", $tpl);
                    $tmp="<dl>";
                    foreach ($this->ary_equipment as $equipments){
                            if($equipments["tipo"]==$actualtype){
                                    $tmp.="<dt title='".$equipments["Descrizione"]."'>".$equipments["Oggetto"]." (";
                                    if($equipments["tipo"]!="Equipaggiamento"){
                                    $tmp.="Danni TP/TM: ".$equiments["DanniTP"]."/".$equipments["DanniTM"]." - Critico: ".$equipments["Critico"]." - Git: ".$equipments["Gittata"]." - Danno: ".$equipments["TipoDanno"]." - Peso: ".$equipments["peso"]." -";
                                    }
                                    $tmp.="Costo:".$equipments["Costo"].")</dt>\r\n";
                                    $tmp.="<dd>".$equipments["Descrizione"]."</dd>\r\n";
                            }else{
                                    $tmp.="<dt style='font-weight:bold;margin-top:10px;'>".$equipments["tipo"]."</dt>";
                                    $tmp.="<dt title='".$equipments["Descrizione"]."'>".$equipments["Oggetto"]." (";
                                    if($equipments["tipo"]!="Equipaggiamento"){
                                    $tmp.="Danni TP/TM: ".$equiments["DanniTP"]."/".$equipments["DanniTM"]." - Critico: ".$equipments["Critico"]." - Git: ".$equipments["Gittata"]." - Danno: ".$equipments["TipoDanno"]." - Peso: ".$equipments["peso"]." -";
                                    }
                                    $tmp.="Costo:".$equipments["Costo"].")</dt>\r\n";
                                    $tmp.="<dd>".$equipments["Descrizione"]."</dd>\r\n";
                            }
                            $actualtype=$equipments["tipo"];
                    }
                    $tmp.="</dl>";
                    $tpl = preg_replace("#<!--EQUIPMENT-->#", $tmp, $tpl);			

                    //id_pg_description

                    $tpl = preg_replace("#<!--LBL_DESCRIPTION-->#", "Descrizione: ", $tpl);
                    $tpl = preg_replace("#<!--DESCRIPTION-->#", $this->ary_descpg["descrizione"], $tpl);

                    $tpl = preg_replace("#<!--LBL_BACKGROUND-->#", "Background: ", $tpl);
                    $tpl = preg_replace("#<!--BACKGROUND-->#", $this->ary_descpg["bg"], $tpl);

                    //id_pg_notes
                    $tpl = preg_replace("#<!--LBL_NOTES-->#", "Note", $tpl);			
                    $tpl = preg_replace("#<!--NOTE_PLAYER-->#", nl2br($this->ary_notes[0]["NoteGiocatore"]), $tpl);
                    $tpl = preg_replace("#<!--NOTE_MASTER-->#", nl2br($this->ary_notes[0]["NoteMaster"]), $tpl);

                    $image=parse_url($this->ary_descpg["photo"]);
                    $tpl = preg_replace("#<!--IMAGE-->#", "<img src='../fun/image.php?url_img=" . $image["path"] . "'>", $tpl);

                    //$tpl = preg_replace("#<!--IMAGE-->#", $this->resize_img(str_replace("http://www.luxintenebra.net/","",$this->ary_descpg["photo"]),100,100), $tpl);

            }
            echo $tpl;

            $mtime = explode(' ', microtime());
            $totaltime = $mtime[0] +  $mtime[1] - $starttime;

            
        }
        
	public function show_manage($opt=""){

            if($_SERVER["HTTP_X_REQUESTED_WITH"] == 'XMLHttpRequest'){

                $oAdv = new cAdventures();
                $oAdv->set_user($this->oUt);
                $oAdv->set_id_sito($_SERVER["SITO"]);
                $tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/manage_PG.tbl.php'));

                        //if($this->aPermission["Modify"]==0){
                $aUsers=$this->oUt->users_list();
                    $aMasters=$this->oUt->let_users_from_group(5);
                    $oAdv->leggi();

                                $html="";
                                foreach($aUsers as $value){
                                        $html.="<option value='".$value["ID"]."'>".$value["Name"]."</option>";
                                }
                                $tpl = preg_replace("#<!-- USERS_LIST -->#", $html, $tpl);

                    $html="";
                    foreach($aMasters as $key => $value){
                        if($key==$this->oUt->id or $this->oUt->is_group("admins")){
                                        $html.="<option value='".$key."'>".$value."</option>";
                        }
                                }
                                $tpl = preg_replace("#<!-- MASTERS_LIST -->#", $html, $tpl);

                    $html="";
                    foreach($oAdv->ary_adventures as $value){
                        if($value["IDMaster"]==$this->oUt->id){
                                        $html.="<option value='".$value["IDAvv"]."'>".$value["Titolo"]."</option>";
                        }
                    }
                                $tpl = preg_replace("#<!-- ADVENTURES_LIST -->#", $html, $tpl);


                                $aPg=$this->pg_master_list($this->oUt->id);
                                $html="<ul>";
                                foreach($aPg as $value){
                                        //$html.="<li><a href=\"javascript: void(0);\">".$value["Username"]."</a></li>";
                                        $html.="<li><img width=\"20\" src=\"".$value["photo"]."\"><a onclick='javascript:var udiv = new Ajax.Updater(\"id_PluginBoard\", \"" . HTTP_AJAX . "/cPg_pg_show.php\",{method: \"post\",parameters: \"url=" . $_SERVER["REQUEST_URI"] . "&id=".$value["id"]."\"});Effect.Appear(\"id_PluginBoard\", {duration: 1.0});' href=\"javascript: void(0);\">".$value["Username"]."</a></li>\r\n";
                                }
                                $html.="</ul>";
                                $tpl = preg_replace("#<!-- PG_MASTER_LIST -->#", $html, $tpl);
                        //}

                        $html="";
                        $html.="";

                        $tpl = preg_replace("#<!-- PG_MASTER_SEARCH -->#", $html, $tpl);

                        echo $tpl;
            }else{
                
                $oAdv = new cAdventures();
                $oAdv->set_user($_SERVER["SITO"],$this->oUt);
                $oAdv->set_id_sito($_SERVER["SITO"]);
                
                $tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/manage_noajax_PG.tbl.php'));

                //if($this->aPermission["Modify"]==0){
                $aUsers=$this->oUt->users_list();
                $aMasters=$this->oUt->let_users_from_group(5);
                $oAdv->leggi();

                $html="";
                foreach($aUsers as $value){
                        $html.="<option value='".$value["ID"]."'>".$value["Name"]."</option>";
                }
                $tpl = preg_replace("#<!-- USERS_LIST -->#", $html, $tpl);

                $html="";
                foreach($aMasters as $key => $value){
                    if($key==$this->oUt->id or $this->oUt->is_group("admins")){
                                    $html.="<option value='".$key."'>".$value."</option>";
                    }
                            }
                            $tpl = preg_replace("#<!-- MASTERS_LIST -->#", $html, $tpl);

                $html="";
                foreach($oAdv->ary_adventures as $value){
                    if($value["IDMaster"]==$this->oUt->id){
                                    $html.="<option value='".$value["IDAvv"]."'>".$value["Titolo"]."</option>";
                    }
                }
                $tpl = preg_replace("#<!-- ADVENTURES_LIST -->#", $html, $tpl);

                //MOSTRA LA LISTA DEI PERSONAGGI GESTITI DAL MASTER
                $aPg=$this->pg_master_list($this->oUt->id);
                $html="<ul>";
                foreach($aPg as $value){
                    $html.="<li><img width=\"20\" src=\"".$value["photo"]."\">
                        <a href=\"".$this->url_script."&idpg=".$value["id"]."\">".$value["Username"]."</a></li>\r\n";
                }
                $html.="</ul>";
                $tpl = preg_replace("#<!-- PG_MASTER_LIST -->#", $html, $tpl);

                $html="";
                $html.="";

                $tpl = preg_replace("#<!-- PG_MASTER_SEARCH -->#", $html, $tpl);

                echo $tpl;                
            }
                
	}

	public function show_edit_pg($opt="",$msg="",$tpl=""){
		
        $oAdv = new cAdventures();
        //$oAdv->set_user($this->oUt);
        $oAdv->set_id_sito($_SERVER["SITO"]);
        if($tpl==""){
            $tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/edit_pg.tbl.php'));
        }
	
	if($this->aPermission['cPg']['Modify']==1){
        
            $tpl = preg_replace("#<!-- LI_ID -->#", $this->ipg_id, $tpl);
			$html=$msg;			
			$tpl = preg_replace("#<!-- messaggio -->#", $html, $tpl);
			$html="<input type='hidden' name='_id' size='".strlen($this->ipg_id)."' value='".$this->ipg_id."'>";
			$tpl = preg_replace("#<!-- id -->#", $html, $tpl);
			$html="<input type='text' name='_username' size='".strlen($this->Username)."' value='".$this->Username."'>";
			$tpl = preg_replace("#<!-- username -->#", $html, $tpl);
			$html="<input type='text' name='_photo' size='".strlen($this->Photo)."' value='".$this->Photo."'>";
			$tpl = preg_replace("#<!-- photo -->#", $html, $tpl);
			$html="<input type='text' name='_eta' size='".strlen($this->Eta)."' value='".$this->Eta."'>";
			$tpl = preg_replace("#<!-- eta -->#", $html, $tpl);
			
			$current_item=$this->Sesso;
			if($current_item=="M"){
				$html="<option value='F'>Femmina</option>";
				$html.="<option selected value='M'>Maschio</option>";
			}else{
				$html="<option value='M'>Maschio</option>";
				$html.="<option selected value='F'>Femmina</option>";
			}
			$tpl = preg_replace("#<!-- sesso -->#", $html, $tpl);			
			
			$html=$this->Descrizione;
			$tpl = preg_replace("#<!-- descrizione -->#", $html, $tpl);

			$html=$this->bg;
			$tpl = preg_replace("#<!-- bg -->#", $html, $tpl);

			//$html=$this->let_note();			
			$tpl = preg_replace("#<!-- note -->#", $html, $tpl);

			$current_item=$this->Razza;
			$list_item=$this->selectRace();
			$html="";
			foreach($list_item as $value){
				if($value["id"]==$current_item){
					$html.="<option selected value='".$value["id"]."'>".$value["_Desc"]."</option>";
				}else{
					$html.="<option value='".$value["id"]."'>".$value["_Desc"]."</option>";
				}
			}
			$tpl = preg_replace("#<!-- race -->#", $html, $tpl);
			
			$current_item=$this->Allineamento;
			$list_item=$this->selectAlign();
			$html="";
			foreach($list_item as $value){
				if($value["id"]==$current_item){
					$html.="<option selected value='".$value["id"]."'>".$value["_Desc"]."</option>";
				}else{
					$html.="<option value='".$value["id"]."'>".$value["_Desc"]."</option>";
				}
			}
			$tpl = preg_replace("#<!-- align -->#", $html, $tpl);

			$current_item=$this->Biclasse;
			if($current_item=="0"){
				$html="<input type='checkbox' name='_Biclasse' value='1'>";
			}else{
				$html="<input type='checkbox' name='_Biclasse' value='0' checked>";
			}
			$tpl = preg_replace("#<!-- biclasse -->#", $html, $tpl);
			
			$current_item=$this->Classe1;
			$list_item=$this->selectClass();
			$html="";
			foreach($list_item as $value){
				if($value["id"]==$current_item){
					$html.="<option selected value='".$value["id"]."'>".$value["_Desc"]."</option>";
				}else{
					$html.="<option value='".$value["id"]."'>".$value["_Desc"]."</option>";
				}
			}
			$tpl = preg_replace("#<!-- class1 -->#", $html, $tpl);

			if($this->Biclasse==1){
				$current_item=$this->Classe2;
				$list_item=$this->selectClass();
				$html="";
				foreach($list_item as $value){
					if($value["id"]==$current_item){
						$html.="<option selected value='".$value["id"]."'>".$value["_Desc"]."</option>";
					}else{
						$html.="<option value='".$value["id"]."'>".$value["_Desc"]."</option>";
					}
				}
				$tpl = preg_replace("#<!-- class2 -->#", $html, $tpl);
			}

			$html="<input type='text' name='_Livello1' size='".strlen($this->Livello1)."' value='".$this->Livello1."'>";			
			$tpl = preg_replace("#<!-- lvl_primario -->#", $html, $tpl);
			$html="<input type='text' name='_Livello2' size='".strlen($this->Livello2)."' value='".$this->Livello2."'>";
			$tpl = preg_replace("#<!-- lvl_secondario -->#", $html, $tpl);

			$html="<input type='text' name='_PE' size='".strlen($this->PE)."' value='".$this->PE."'>";			
			$tpl = preg_replace("#<!-- xp_primario -->#", $html, $tpl);
			$html="<input type='text' name='_PE2' size='".strlen($this->PE2)."' value='".$this->PE2."'>";
			$tpl = preg_replace("#<!-- xp_secondario -->#", $html, $tpl);
			
			$current_item=$this->HideClasse;
			$html="<select name='_HideClasse'>";
			if($current_item=="0"){
				$html.="<option value='0' selected>No</option>";
				$html.="<option value='1'>Si</option>";
			}else{
				$html.="<option value='0'>No</option>";
				$html.="<option value='1' selected>Si</option>";
			}
			$html.="</select>";
			$tpl = preg_replace("#<!-- hide_class -->#", $html, $tpl);

			$current_item=$this->HidePE;
			$html="<select name='_HidePE'>";
			if($current_item=="0"){
				$html.="<option value='0' selected>No</option>";
				$html.="<option value='1'>Si</option>";
			}else{
				$html.="<option value='0'>No</option>";
				$html.="<option value='1' selected>Si</option>";
			}
			$html.="</select>";
			$tpl = preg_replace("#<!-- hide_xp -->#", $html, $tpl);

			$current_item=$this->HideLivello;
			$html="<select name='_HideLivello'>";
			if($current_item=="0"){
				$html.="<option value='0' selected>No</option>";
				$html.="<option value='1'>Si</option>";
			}else{
				$html.="<option value='0'>No</option>";
				$html.="<option value='1' selected>Si</option>";
			}
			$html.="</select>";
			$tpl = preg_replace("#<!-- hide_level -->#", $html, $tpl);

			$current_item=$this->HideBg;
			$html="<select name='_HideBg'>";
			if($current_item=="0"){
				$html.="<option value='0' selected>No</option>";
				$html.="<option value='1'>Si</option>";
			}else{
				$html.="<option value='0'>No</option>";
				$html.="<option value='1' selected>Si</option>";
			}
			$html.="</select>";
			$tpl = preg_replace("#<!-- hide_bg -->#", $html, $tpl);
			
			//TAB MASTER
			if($this->oUt->is_group('master',$this->oUt->id)!=0){
				
				$html="<input type='hidden' name='_id' size='".strlen($this->ipg_id)."' value='".$this->ipg_id."'>";
				$tpl = preg_replace("#<!-- id_pg_master -->#", $html, $tpl);
				
				$oAdv->leggi();
				$html="";
				foreach($oAdv->ary_adventures as $value){
					if($value["IDMaster"]==$this->oUt->id){
						$html.="<option value='".$value["IDAvv"]."'>".$value["Titolo"]."</option>";
					}
				}
				$tpl = preg_replace("#<!-- adventures_list -->#", $html, $tpl);

				$html="<input type='checkbox' name='_MasterID' size='".strlen($this->oUt->id)."' value='".$this->oUt->id."'>";
				$tpl = preg_replace("#<!-- id_attach_master -->#", $html, $tpl);
				
				$html="inline";
				$tpl = preg_replace("#<!-- master_permission -->#", $html, $tpl);
				
			}else{
				$html="none";
				$tpl = preg_replace("#<!-- master_permission -->#", $html, $tpl);
			}

		}
		
		echo $tpl;
	}


		/*
		public function selectClass(){
			
			$query = new Query();
			$query->tables=array("classi");
			$query->fields=array("ID","_Desc");
			$result=array();
			
			if ($query->Open()){
				while ($element=$query->GetNextRecord()){
					$result[]=$element;
				}
				$query->Close();
			}else{
				$query->Close();
				throw new Exception ("Error accessing Database");
			}
			return $result;
		}
		
		public function selectAlign(){
			
			$query = new Query();
			$query->tables=array("Alignment");
			$query->fields=array("idAlignment","name");
			$result=array();
			
			if ($query->Open()){
				while ($element = $query->GetNextRecord()){
					$result[]=$element;
				}
				$query->Close();
			}else{
				$query->Close();
				throw new Exception ("Error accessing Database");
			}
			return $result;
		}
		
		public function selectRace(){
			
			$query = new Query();
			$query->tables=array("Race");
			$query->fields=array("idRace","name");
			$result=array();
			
			if ($query->Open()){
				while ($element = $query->GetNextRecord()){
					$result[]=$element;
				}
				$query->Close();
			}else{
				$query->Close();
				throw new Exception ("Error accessing Database");
			}			
			return $result;
		}
		*/

}
?>
