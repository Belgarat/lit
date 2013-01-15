<?php
	/**
	* classe che gestisce i bug\n
	* parte del codice di D.k. \n\n

		20090716 - Klown - 1st note: leggi and show works, insert never tested\n
		20090718 - Klown - Insert Bug almost ok\n
		20090719 - Klown - 1st page ok (show method)\n
		20091001 - Klown - Bugs can be inserted, bugs can also be listed (last 5)\n
		20091005 - Klown - Template and comment code\n
		20091013 - Klown - Better implementation for pages\n
		20091019 - Klown - More object-oriented implementation\n
		20091104 - Klown - Read & Filter methods improved\n
		20091119 - Klown - readBug() reimplemented\n
		20091216 - Klown - Total restyling\n

	* @author	Giacomo "Klown" Bella <foliestere@gmail.com>
	*/
	
	require_once("cValidate.php");
	require_once("./lib/lib_ary.php");

	class cBug{

		private $id_sito;
		public $classname;
		public $version;
		protected $oUt;

		//validazione
		private $validator;
		private $priorityCond;
		private $stateCond;

		//paginazione
		private $maxPerPage;
		private $limitLow;
		private $totalBugNumber;

		//inserimento e modifica
		private $title;
		private $user;
		private $problem;
		private $assigned;
		private $date;
		private $state;
		private $priority;
		private $id_bug;
		
		private $stateFilter;
		private $priorityFilter;


		/**
		* Costruttore della classe
		*/
		public function __construct(){
			$this->classname="cBug";
			$this->version="Lux in Tenebra <br /> 2009 www.luxintenebra.net";
			$this->validator = new cValidate();
			$this->priorityFilter = array();
			$this->stateFilter = array();
			$this->stateCond = $this->getFilterValues('stato');
			$this->priorityCond = $this->getFilterValues('priorita');
		}
		
		/**
		* Inizializza il sito di riferimento (per "multisito")
		* \param $id_sito Id del sito per il supporto multisito
		*/
		public function set_id_sito($id_sito){
			if (is_numeric($id_sito)){
				$this->id_sito=$id_sito;
				return true;
			}else{
				return false;
			}
		}
		
		/**
		* Ritorna l'id del sito su cui si sta lavorando
		*/
		public function let_id_sito(){
			return $this->id_sito;
		}
		
		/**
		* Inizializza l'utente che sta navigando nel sito
		* \param $id_sito Id del sito per il supporto multisito
		* \param $obj Oggetto utente che sta navigando nel sito
		*/
		public function set_user($id_sito, $obj){
			if(is_object($obj)){
				$this->oUt=$obj;
				$this->aPermission=$this->oUt->fArrayPermission($id_sito, $this->classname);
				return true;
			}else{
				return false;
			}

		}

		/**
		* Inizializza il valore di partenza per la pagina
		* \param $limitLow Numero che indica da che segnalazione iniziare la visualizzazione
		* \return true se l'operazione va a buon fine, false altrimenti
		*/
		public function setLimitLow($limitLow){
			if (is_numeric($limitLow)){
				$this->limitLow=(int)$limitLow;
				return true;
			}
			return false;
		}

		/**
		* Ritorna il numero di partenza per la pagina
		*/
		public function letLimitLow(){
			return $this->limitLow;
		}
		
		/**
		* Inizializza il numero di riferimenti per pagina
		* \param $max Numero massimo di segnalazioni per pagina
		* \return true se l'operazione va a buon fine, false altrimenti
		*/
		public function setMaxPerPage($max){
			if (is_numeric($max)){
				$this->maxPerPage=(int)$max;
				return true;
			}
			return false;
		}
		
		/**
		* Ritorna il numero di riferimenti per pagina
		*/
		public function letMaxPerPage(){
			return $this-> maxPerPage;
		}
		
		/**
		* Calcola il numero totale di bug, tiene conto dei filtri impostati
		* @todo Recuperare in maniera più felice il valore del count
		*/
		public function setTotalBugNumber(){
			$this->totalBugNumber=false;
			$query = new Query();
			$query->tables=array("tbl_bugs");
			$query->fields=array("count(id)");
			$query->filters= $this->generateFilters();

			if ($query->Open()){
				while ($row=$query->GetNextRecord()){
					$this->totalBugNumber= $row[0];
				}
			}
		}
		
		/**
		* Ritorna il numero totale di bug
		*/
		public function letTotalBugNumber(){
			return $this->totalBugNumber;
		}
		
		/**
		* Inizializza l'id del bug richiesto
		* \param $id Id del bug che si vuole prendere in considerazione, se esiste
		*/
		public function setIdBug($id){
			if (is_numeric($id) && ($id)){
				$this->id_bug = $id;
				return true;
			}else{
				return false;
			}
		}
		
		/**
		* Ritorna l'id del bug selezionato
		*/
		public function letIdBug(){
			return $this->$id_bug;
		}

		/**
		* inizializza l'autore del bug
		* \param $author string l'autore se NULL usa il nome dell'utente corrente
		* \return true se il nome esiste e viene assegnato false altrimenti
		*/
		public function setAuthor($author = NULL){
			if (is_null($author)){
				$this->oUt->Leggi();
				if ($this->oUt->dati['Id'] > 0){
					$this->author = $this->oUt->dati['name'];
				}
			}else{
				if is_string($author){
					$query->new Query();
					$query->tables(array('userdb'));
					$query->fields(array('id'));
					$query->filters = "name = $author";
					if ($query->Open()){
						$this->author = $author;
					}else{
						return false;
					}
				}
			}
			return true;
		}
		
		/**
		* Inizializza il titolo del bug
		* \param $title Titolo della segnalazione
		* \return true se l'operazione è andata a buon fine, false in caso contrario
		*/
		public function setTitle($title){
			if (($title != "") && ($title)){
				$this->title = $this->validator->_sql($title);
				return true;
			}
			return false;
		}
		
		/**
		* Ritorna il titolo del bug
		*/
		public function letTitle(){
			return $this->title;
		}
		
		/**
		* Inizializza la descrizione del bug
		* \param $problem Il problema di cui tratta la segnalazione
		* \return <b>true</b> se l'operazione è andata a buon fine, <b>false</b> in caso contrario
		*/
		public function setProblem($problem){
			if (($problem != "" )&&($problem)){
				$this->problem = $this->validator->_sql($problem);
				return true;
			}
			return false;
		}
		
		/**
		* Ritorna la descrizione del bug
		*/
		public function letProblem(){
			return $this->problem;
		}
		
		/**
		* Inizializza la data a quella attuale -Per inserimento e modifica
		*/
		public function setDate(){
			$this->date = date();
		}
		
		/**
		* Ritorna la data in timestamp unix o in formato "GG/MM/AAAA"
		* \param $timestamp Boolean: true ritorna il timestamp, false il formato "italiano" \a Opzionale
		*/
		public function letDate($timestamp=false){
			if ($timestamp){
				return $this->date;
			}else{
				return date("d/m/Y", $this->date);
			}
		}
		
		/**
		* Memorizza lo stato della segnalazione
		* \param $state Stato attuale della segnalazione predefinito Opened \a Opzionale
		* \return <b>true</b> se l'operazione va a buon fine, <b>false</b> altrimenti
		*/
		public function setState($state = 'Opened'){
			$state = $this->validator->_sql($state);
			if (in_array($state, $this->stateCond)){
				$this->state = $state;
				return true;
			}
			return false;
		}
		
		/**
		* Ritorna lo stato della segnalazione
		*/
		public function letState(){
			return $this->state;
		}
		
		/**
		* Memorizza la priorità della segnalazione
		* \param $priority Priorità attuale della segnalazione predefinito Normal \a Opzionale
		* \return <b>true</b> se l'operazione va a buon fine, <b>false</b> altrimenti
		*/
		public function setPriority($priority = 'Normal'){
			$priority = $this->validator->_sql($priority);
			if (in_array($priority, $this->priorityCond)){
				$this->priority=$priority;
				return true;
			}else{
				return false;
			}
		}
		
		/**
		* Ritorna lo stato della segnalazione
		*/
		public function letPriority(){
			return $this->priority;
		}
		
		/**
		* Ritorna l'array dei valori che può assumere un determinato campo\n
		* Legge da database i valori dei campi Enum.
		* \param $field Campo enum della tabella tbl_bugs da cui estrarre i valori possibili
		* \return Un array coi valori - false in caso di insuccesso
		*/
		public function getFilterValues($field){
			$query=new Query();
			$query->tables=array('tbl_bugs');
			if (trim($field) && is_string($field)){
				$query->fields=array(trim($field));
			}
			if ($result=$query->GetEnumValue()){
				return $result;
			}
			return false;
		}
		
		/**
		* Inizializza i filtri sulla priorità: vari controlli
		* \param $filter Array - valori in base a cui filtrare le segnalazioni per priorità
		*/
		public function setPriorityFilter($filter){
			//elimino i duplicati e iserisco i valori in un array
			foreach($filter as $condition){
				$condition=stringCapitalized($condition);
				if (in_array($condition, $this->priorityCond) && (trim($condition)!="")){
					$this->priorityFilter[] = $condition;
				}
			}
			$this->priorityFilter=array_unique($this->priorityFilter);
		}
		
		/**
		* Inizializza i filtri sullo stato
		* \param $filter Array - valori in base a cui filtrare le segnalazioni per stato
		*/
		public function setStateFilter($filter){
			foreach ($filter as $condition){
				$condition = stringCapitalized($condition);
				if(in_array($condition, $this->stateCond) && (trim($condition)!="")){
					$this->stateFilter[] = $condition;
				}
			}
			$this->stateFilter = array_unique($this->stateFilter);
		}

		/**
		* Crea un filtro che possa essere dato in pasto al database
		*/
		public function generateFilters(){
			$filter=""; 
			if(($this->stateFilter) && (isset($this->stateFilter))){
				$limit = count($this->stateFilter)-1;
				foreach ($this->stateFilter as $key=>$cond){
					if (trim($cond)){
						$filter.="stato = '$cond'";
					}
					if ($key < $limit){
						$filter.=" and ";
					}
				}
			}
			if (($this->priorityFilter)&&(isset($this->priorityFilter))){
				$limit = count($this->priorityFilter)-1;
				if ($filter){
					$filter .= " AND ";
				}
				foreach ($this->priorityFilter as $key=>$cond){
				if (trim($cond)){
					$filter .= "priorita = '$cond'";
				}
				if ($key < $limit){
						$filter .= " and ";
					}
				}
			}
			if ($filter){
				return $filter;
			}
			return false;
		}
		
		/**
		* Ritorna l'elenco di coloro che appartengono al gruppo maintainers:\n
		* - maintainers sono gli unici a poter modificare stato e priorità della segnalazione\n
		* - solo ai maintainers può essere assegnato un bug e solo loro possono smistarli
		*/
		public function getMaintainers(){
			$query = new Query();
			$maintainers = array();
			
			$query->tables = array ('tbl_users_groups', 'userdb');
			$query->fields = array ('userdb.name');
			$query->filters = 'userdb.Id = tbl_users_groups.id_user and tbl_users_groups.id_group = 2 and id_sito ='.$this->let_id_sito();

			if ($query->Open()){
				while ($row = $query->GetNextRecord()){
					$maintainers[] = $row[0];
				}
				$query->Close();
				return $maintainers;
			}
			return false;
		}

		/**
		* Assegna il bug a un utente, solo se fa parte del gruppo maintainers
		*/
		public function assignBug($name){
			$name = $this->validator->_sql($name);
			if (in_array($name, $this->getMaintainers())){
				$this->assigned = $name;
				return true;
			}
			return false;
		}

		/**
		* Metodo che visualizza la Bug Page
		* @todo Layout, Visualizzazione dei commenti
		*/
		public function show(){
			$this->setTotalBugNumber();
			$bugNumber = $this->totalBugNumber;
			$html = "<div id='bug'>\n";
			$bugReport = $this->readBug();
			$pages = ceil($bugNumber/$this->maxPerPage);
			$html .= $this->out();
			$html .= '</div>';
		}

		/**
		* crea l'html a partire dai parametri inseriti
		* \param $bugs - il bug o la lista di bug da visualizzare
		* \param $mode - la modalità di visualizzazione \n - details: visualizza i dettagli della segnalazione \n - summary: visualizza solo i dati importanti
		* \return la stringa html da stampare per la visualizzazione
		*/
		public function out($bugs, $mode){
			switch ($mode){
				case "summary":
					//tests if $bugs is the buglist or a single bug
					if(is_array($bugs[0])){
						$html = '<table>';
						$html .= '<tr><th>Id</th><th>Title</th><th>Date</th></tr>';
						foreach ($bugs as $bug){
							$html .= '<tr><td>'.$bug["id"]'</td><td>'.$bug["titolo"].'</td><td>'.$bug["data"].'</td></tr>';
						}
						$html .= '</table>';
					}else{
						$html = '<table border="1">';
						$html .= '<tr><th>Id</th><th>Title</th><th>Date</th></tr>';
						$html .= '<tr><td>'.$bugs["id"].'</td><td>'.$bugs["titolo"].'</td><td>'.$bugs["data"].'</td></tr>';
						$html .= '<tr><td rowspan = "3">'.$bugs["problema"].'</td></tr>';
						$html .= '</table>';
					}
					return $html;
				case "details":
					if (is_array($bug[0])){
						$html = '<table>';
						$html .= '<tr><th>Id</th><th>Author</th><th>Title</th><th>Date</th><th>State</th></td>';
						foreach ($bugs as $bug){
							$html .= '<tr><td>'.$bug["Id"].'</td><td>'.$bug["Autore"].'</td><td>'.$bug["titolo"].'</td><td>'.$bug["data"].'</td><td>'.$bug["stato"].'</td></tr>';
						}
						$html .= '</table>';
					}else{
						$html = '<table>';
						$html .= '<tr><th>Id</th><th>Title</th><th>Date</th></tr>';
						$html .= '<tr><td>'.$bug["Id"].'</td><td>'.$bug["titolo"].'</td><td>'.$bug["date"].'</td><tr>';
						$html .= '<tr><td rowspan = "3">'.$bug["problema"].'</td></tr>';
						$html .= '<tr><td rowspan = "2">'.$bug["autore"].'</td><td>.'$bug["stato"]'.</td></tr>';
						$html .= '</table>';
					}
					return $html;
				}
			}

		/*
		public function retComments($id_father){
			ret=
		}

		public function retFather($id){
			$bug=$this->readBug($id);
			if ($bug[Id_father]>0){
				retFather($bug[Id_father]);
			}else{
				retComments($id);
			}
		}
		*/

		/**
		* Inserisce il bug
		* @todo Gestione di stati e priorità in base all'utente
		*/
		public function insertBug(){
			$query=new Query();
			$query->tables=array('tbl_bugs');
			$query->fields=array('Data', 'Nome','Titolo', 'Problema');
			
			//inserisco la data
			$query->values=array(time());
			
		}
		
		/**	
		* Legge da database i bug:
		* - Se è stato specificato un bug attraverso l'id viene letto quel solo bug
		* - Se non è stato specificato un bug viene ritornata una lista di bug che contiene i bug da limitLow a maxPerPage
		*/
		public function readBug(){
			$query = new Query();
			$query->tables=array('tbl_bugs');
			$query->fields=array('*');
			if (is_numeric($this->id_bug) && ($this->id_bug)){
				$query->filters="id=".$this->id_bug;
				$query->limit="0, 1";
				if ($query->Open()) {
					$bugDesc=$query->GetNextRecord(true);
					$query->Close();
					$this->setTitle($bugDesc['Titolo']);
					$this->setAuthor
					return true;
				}
			}else{
				$query->filters = $this->generateFilters();
				$query->limit="".$this->limitLow.", ".$this->maxPerPage; 
				$query->sortfields=array('Data desc');
				if($query->Open()){
					while($row = $query->GetNextRecord(true)){
						$bugList[]=$row;
					}
				}
				$query->Close();
				return $bugList;
			}
			$query->Close();
			return false;
		}


		/**
		* Update del bug
		* @todo Implementation
		*/
		public function updateBug(){
			
		}
	}
?>
