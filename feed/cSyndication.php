<?php
	/**
	* Classe per creare i file rss/atom per i feed\n
	* Classe astratta.
	*
	* @author: Giacomo "klown" Bella <foleistere@gmail.com>
	* @note: Abstract class
	* @todo: Creare classi figlie per i vari feed \n
			 Creare template da utilizzare per i feed
	*/

	abstract class cSyndication {
		
		//! Directory in cui si trova il file
		protected $baseDir;

		//! puntatore al file .rss
		protected $file;
		
		//! Titolo del Channel [obbligatorio]
		protected $channelTitle;
		
		//! URL corrispondente al canale [obbligatorio]
		protected $channelLink;
		
		//! Descrizione del Channel [obbligatorio]
		protected $channelDescription;
		
		//! Lingua in cui è scritto il Channel
		protected $channelLang;

		//! Ultima volta che è stato generato il file
		protected $channelLBDate;

		//! Documentazione dello standard usato
		protected $channelDocs;
		
		//! Numero di elementi che compongono il feed
		protected $itemNumber;

		/**
		* Costruttore della classe, inizializza gli attributi obbligatori
		* \param $channelTitle titolo del canale
		* \param $channelLink URL del sito cui fa riferimento il canale
		* \param $channelDescription Descrizione del canale
		*
		*/
		public function __construct($channelTitle, $channelLink, $channelDescription){
			//validazione delle stringhe in input
			if (is_string($channelTitle) && is_string($channelLink) && is_string($channelDescription)){
				if ($channelTitle != ""){ $this->channelTitle = $channelTitle;}
				if ($channelDescription!= ""){ $this->channelDescription = $channelDescription;}
				//validating Link to be a valid Lux in Tenebra link with preg_match
				$pattern = '#^http://[a-z]+\.luxintenebra\.net#';
				if (preg_match($pattern, $channelLink)){
					$this->channelLink = $channelLink;
				}
			}
		}

		/**
		* Funzione che inizializza il file che ospiterà il feed
		* \param $fileName Nome del file 
		*/
		public function setFile($fileName){
			if (is_string($fileName)&& ($fileName!="")){
				if (is_file($fileName)){
					$this->file = $fileName;
				}
			}
		}

		/**
		* Funzione che setta il numero di Item nel feed
		* \param $itemNumber Numero di voci da inserire nel feed
		*/
		public function setItemNumber($itemNumber){
			if (is_numeric($itemNumber) && $itemNumber){
				$this->itemNumber = $itemNumber;
			}
		}

		/**
		* Funzione che recupera i dati dal database
		* \note abstract
		*/
		protected abstract function retrieveData();

		/**
		* Funzione che scrive materialmente il feed
		* \param $file File in cui scrivere il feed definitivo
		* \note abstract
		*/
		public abstract function writeFeed($file);
	}
?>
