<?php

	/**
	* Libreria per il riconoscimento automatico delle lingue
	* @author Giacomo "KloWn" Bella <foliestere@gmail.com>
	* @todo	  Lingue implementate; \n Cambio di lingua;
	*/

	require_once ("inclusions.php");
	define(DEFAULT_LANG, "it");
	
	$oDb = Db::getInstance();
	$oDb->Open();
	
	function setLanguage($userLang=""){
		$query = new Query();
		$query->tables = array('Language');
		$query->fields = array('abbreviation');
		
		//l'utente sceglie la lingua, se è tra quelle didponibili viene
		//cambiata, se no si usa quella di default	
		if ($userLang && is_string($userLang)){
			$query->filters = "'abbreviation' =".$userLang;
				if ($query->Open()){
					$_SESSION["intl"] = $userLang;
				}else{
					$_SESSION["intl"] = DEFAULT_LANG;
				}
				exit;
		}
		
		//se non è indicata esplicitamente si cerca di usare quella passata dal browser
		//se la stringa del browser è vuota si usa la lingua di default 
		if (!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) || !$_SERVER["HTTP_ACCEPT_LANGUAGE"]){
			$_SESSION["intl"] = DEFAULT_LANG;
			exit;
		}
		
		//recupero la lingua dal browser
		$language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
		$language = str_replace(' ', '', $language);
	
		$language=explode(',', $language);

		$supported_langs = array(); //elenco delle lingue supportate da browser
		
		//riconoscimento della lingua. Evita attacchi sulla UserAgent string del browser
		foreach ($language as $lang){
			$lang = explode(';', $lang);//geko browsers workaround
				$query->filters = "'abbreviation'=".$lang[0];
				if ($query->Open()){ // se la query va a buon fine la lingua esiste 
					$supported_langs[$lang[0]] = $lang[0];
				}
			}
		
		$_SESSION['intl'] = $supported_langs;
	}
	
	function getLanguage(){
		if (isset($_SESSION['intl'])){
			return $_SESSION['intl'];
		}else{
			return DEFAULT_LANG;
		}
	}
	
	
?>
