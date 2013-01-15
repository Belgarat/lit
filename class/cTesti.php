<?php
/**
 * Oggetto per la gestione dei testi.
 * Created on 20/07/07
 * @author	Belgarat
 * @todo	Gestione paragrafi.\n Integrazione con l'ORM.\n Funzione delete()
 */
  class cText extends cContent{
	/*Proprietà della classe che vengono valorizzate dal metodo read o inserite/aggiornate dai 
	*medoti insert/update
	*/
	
	public $id;
	//public $id_sito;
	/*!Titolo del testo*/
	public $title;	
	/*!Sottotitolo del testo*/
	public $subtitle;
	/*!firma*/
	public $sign; 
	/*!corpo del testo*/
	public $body; 
	 /*!Paragrafo del testo*/
	public $paragraph;
	/*!flag per gestire i commenti diversamente dai testi effettivi*/
	public $bComment; 

	/**
	* legge dal DB i testi richiesti
	* \param $type_read Inidica se il testo da leggere è "html-safe": 0 utilizza le html entities, altrimenti no
	* \return <b>true</b> se la lettura va a buon fine, <b>false</b> altrimenti
	*/
 	function read($type_read=0){
		if (!$this->id==0){
			$sSql="select * from tbl_testi where flag_commento=0 and id=" . $this->id;
			
			$risultato = mysql_query($sSql);
			if (!$risultato){
				return false;
			}else{
				if($type_read==0){
					$row=mysql_fetch_assoc($risultato);
					$this->title = htmlentities($row["titolo"],ENT_COMPAT,'UTF-8');
					$this->subtitle = htmlentities($row["sottotitolo"],ENT_COMPAT,'UTF-8');
					$this->sign = htmlentities($row["firma"],ENT_COMPAT,'UTF-8');
					$this->body = str_replace("&lt;","<",$row["corpo"]);
					$this->body = str_replace("&gt;",">",$this->body);
					$this->paragraph = htmlentities($row["paragrafo"],ENT_COMPAT,'UTF-8');
					$this->bComment = $row["flag_commento"];
				}else{
					$row=mysql_fetch_assoc($risultato);
					$this->title = $row["titolo"];
					$this->subtitle = $row["sottotitolo"];
					$this->sign = $row["firma"];
					$this->body = $row["corpo"];
					$this->paragraph = $row["paragrafo"];
					$this->bComment = $row["flag_commento"];
				}
				return true;
				mysql_free_result($risultato);
			}
	
		}
		else
		{	
			return false;
		}
 	}

	/**
	* legge dal DB i commenti associati al testo presente
	* \return array associativa con i commenti
	*/
 	function read_comments(){
		if (!$this->id==0){
			$sSql="select * from tbl_testi where flag_commento=" . $this->id . " order by ID desc limit 0,5";
			$risultato = mysql_query($sSql);
			if (!$risultato){
				return false;
			}else{
                while($row=mysql_fetch_assoc($risultato)){
                    $comments[]=$row;
                }
				return $comments;
				mysql_free_result($risultato);
			}
	
		}
		else
		{	
			return false;
		}
 	}

	/**
	* Inserisce un commento legandolo al testo specifico inserendo nella tabella dei testi l'id del padre nel campo flag_comment
	* \return L'<b>id</b> del commento inserito, nel caso di errore restituisce 0
	*/
 	function insert_comment(){
 		if(get_magic_quotes_gpc()!=1){
 			$this->title=addslashes($this->title);
 			$this->subtitle=addslashes($this->subtitle);
 			$this->sign=addslashes($this->sign);
			$this->body=addslashes($this->body);
			$this->paragraph=addslashes($this->paragraph); 			
 			$this->path=addslashes($this->path);
 		}
 		$sSql="insert into tbl_testi set ";
 		$sSql.="id_sito='" . $this->id_sito . "', ";
 		$sSql.="titolo='" . $this->title . "', ";
 		$sSql.="sottotitolo='" . $this->subtitle . "', ";
 		$sSql.="firma='" . $this->sign . "', ";
 		$sSql.="corpo='" . $this->body . "', ";
 		$sSql.="paragrafo='" . $this->paragraph . "', ";
 		$sSql.="flag_commento='" . $this->bComment . "';";
 		mysql_query($sSql);
 		echo mysql_error();
 		return mysql_insert_id();
 	}


	/**
	* Inserisce il testo all'interno del database
	* \return L'<b>id</b> del testo inserito
	*/
 	function insert(){
 		if(get_magic_quotes_gpc()!=1){
 			$this->title=addslashes($this->title);
 			$this->subtitle=addslashes($this->subtitle);
 			$this->sign=addslashes($this->sign);
			$this->body=addslashes($this->body);
			$this->paragraph=addslashes($this->paragraph); 			
 			$this->path=addslashes($this->path);
 		}
 		$sSql="insert into tbl_testi set ";
 		$sSql.="id_sito='" . $this->id_sito . "', ";
 		$sSql.="titolo='" . $this->title . "', ";
 		$sSql.="sottotitolo='" . $this->subtitle . "', ";
 		$sSql.="firma='" . $this->sign . "', ";
 		$sSql.="corpo='" . $this->body . "', ";
 		$sSql.="paragrafo='" . $this->paragraph . "', ";
 		$sSql.="flag_commento='" . $this->bComment . "';";
 		mysql_query($sSql);
 		echo mysql_error();
 		return mysql_insert_id();
 	}
 
	/**
	* Aggiorna i testi presenti nel database
	* \return <b>true</b> se l'operazione va a buon fine
	*/
 	function update(){
 		if(get_magic_quotes_gpc()!=1){
 			$this->title=addslashes($this->title);
 			$this->subtitle=addslashes($this->subtitle);
 			$this->sign=addslashes($this->sign);
			$this->body=addslashes($this->body);
			$this->paragraph=addslashes($this->paragraph); 			
 			$this->path=addslashes($this->path);
 		}
 		$sSql="update tbl_testi set ";
 		$sSql.="id_sito='" . $this->id_sito . "', ";
 		$sSql.="titolo='" . $this->title . "', ";
 		$sSql.="sottotitolo='" . $this->subtitle . "', ";
 		$sSql.="firma='" . $this->sign . "', ";
 		$sSql.="corpo='" . $this->body . "', ";
 		$sSql.="paragrafo='" . $this->paragraph . "', ";
 		$sSql.="flag_commento='" . $this->bComment . "' ";
 		$sSql.="where id='" . $this->id . "';";
 		mysql_query($sSql);
 		echo mysql_error();
 		return true; 		
 	}

	/**
	* Cancella un testo
	*/
 	function delete(){
 		
 	}
 	
	/**
	* Interroga il database per sapere che id ha il tipo di contenuto "Testi"
	* \return L'<b>id</b> del tipo "Testi"
	*/
 	public function type_cont(){
 		$sSql="select id from tbl_tipocontenuto where descrizione='Testi';";
 		$risultato=mysql_query($sSql);
		if (!$risultato){			
			return false;
		}else{
			$row=mysql_fetch_assoc($risultato);
			return $row["id"];
			mysql_free_result($risultato);
		}
 	}
}
 
?>
