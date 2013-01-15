<?php
/**
 * Oggetto per la gestione degli allegati
 * Created 20/07/07
 * @author	Belgarat
 * @todo	Funzione update()
 */
  class cAttach extends cContent{
	/*Proprietà della classe che vengono valorizzate dal metodo read o inserite/aggiornate dai 
	*medoti insert/update
	*/

	public $id;
	//public $id_sito;
	/*!Titolo dell'allegato*/
	public $title; 
	/*!Sottotitolo dell'allegato*/
	public $subtitle;
	/*!formato del file*/
	public $format; 
	/*!dimensione del file allegato*/
	public $size; 
	/*!percorso dell'allegato*/
	public $path; 
	/*!id utente*/
	public $iIdUt; 

	/**
	* legge le proprietà dell'allegato dal database
	* \return <b>true</b> se l'operazione va a buon fine, <b>false</b> altrimenti
	*/
 	function read(){
		if (!$this->id==0){
			unset($this->title);
			unset($this->subtitle);
			unset($this->type);
			unset($this->path);
			unset($this->size); 		
			$sSql="select * from tbl_allegati where id=" . $this->id;
			
			$risultato = mysql_query($sSql);
			if (!$risultato){
				return false;
			}else{
				//while ($row=mysql_fetch_assoc($risultato)){
				$row=mysql_fetch_assoc($risultato);
				$this->title = $row["titolo"];
				$this->subtitle = $row["sottotitolo"];
				$this->type = $row["formato"];
				$this->size = $row["dimensione"];
				$this->path = $row["percorso"];
				return true;
				//non viene mai eseguito 
				mysql_free_result($risultato);
			}
	
		}
		else
		{	
			return false;
		}
 	}

	/**
	* Inserisce le proprietà dell'allegato all'interno del database
	* \return L'<b>id</b> dell'allegato se l'operazione va a buon fine, <b>0</b> altrimenti
	*/
 	function insert(){
 		if(get_magic_quotes_gpc()!=1){
 			$this->title=addslashes($this->title);
 			$this->subtitle=addslashes($this->subtitle);
 			$this->path=addslashes($this->path);
 		}
  		$sSql="insert into tbl_allegati(id_sito,titolo,sottotitolo,percorso,formato,dimensione) ";
 		$sSql.="values(";
 		$sSql.="'" . $this->id_sito . "', ";
 		$sSql.="'" . $this->title . "', ";
 		$sSql.="'" . $this->subtitle . "', ";
 		$sSql.="'" . $this->path . "', ";
 		$sSql.="'" . $this->type . "', ";
 		$sSql.="'" . $this->size . "' ";
		$sSql.=");";
		mysql_query($sSql);
		if(mysql_error()==0){
			return mysql_insert_id();
		}else{
			return 0;
		}
 	}
 	
	/**
	* Aggiorna le proprietà di un allegato
	*/
 	function update(){
 		
 	}
 	
	/**
	* Elimina dal database un allegato
	*/
 	function delete(){
  		$sSql="delete from tbl_allegati where id_sito='" . $this->id_sito . "' and id='" . $this->id . "'";
 		mysql_query($sSql);
 		$sSql="delete from tbl_contenuto where id_sito='" . $this->id_sito . "' and idtipo='3' and idcont_tab='" . $this->id . "'";
 		mysql_query($sSql);
 	}
 	
 }
 
?>
