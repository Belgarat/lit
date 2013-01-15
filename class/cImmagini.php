<?php
/**
 * Oggetto per la gestione delle immagini
 * Created 20/07/07
 * @author Belgarat
 */

class cImage extends cContent{
	/*Proprietà della classe che vengono valorizzate dal metodo read o inserite/aggiornate dai 
	*medoti insert/update
	*/
	
	public $id;
	//public $id_sito;
	/*!Titolo dell'immagine'*/
	public $title; 
	/*!Sottotitolo dell'immagine'*/
	public $subtitle;
	/*!formato dell'immagine*/
	public $format;
	/*!percorso dell'immagine*/
	public $path; 
	/*!dimensione dell'immagine*/
	public $size; 
	/*!id utente*/
	public $iIdUt; 
 	

	/**
	* Legge l'immagine associata ad un id
	* \return <b>true</b> se l'operazione va a buon fine, <b>false</b> altrimenti
	*/
 	function read(){
		if (!$this->id==0){
			unset($this->title);
			unset($this->subtitle);
			unset($this->type);
			unset($this->path);
			unset($this->size);
			unset($this->iIdUt);
			$sSql="select * from tbl_immagini where id=" . $this->id;
			$risultato = mysql_query($sSql);
			if (!$risultato){
				return false . mysql_error();
			}else{
				//while ($row=mysql_fetch_assoc($risultato)){
					$row=mysql_fetch_assoc($risultato);
					$this->title = $row["titolo"];
					$this->subtitle = $row["sottotitolo"];
					$this->type = $row["formato"];
					$this->path = $row["percorso"];
					$this->size = $row["dimensione"];
					$this->iIdUt = $row["IdUt"];
				//}
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
	* Carica le informazioni dell'immagine all'interno del database, le immagini sono salvate su filesystem
	* \return L'<b>id</b> dell'immagine se l'inserimento va a buon fine, <b>0</b> altrimenti
	*/
 	function insert(){
 		if(get_magic_quotes_gpc()!=1){
 			$this->title=addslashes($this->title);
 			$this->subtitle=addslashes($this->subtitle);
 			$this->path=addslashes($this->path);
 		}
 		$sSql="insert into tbl_immagini(id_sito,titolo,sottotitolo,percorso,formato,dimensione,iIdUt) ";
 		$sSql.="values(";
 		$sSql.="'" . $this->id_sito . "', ";
 		$sSql.="'" . $this->title . "', ";
 		$sSql.="'" . $this->subtitle . "', ";
 		$sSql.="'" . $this->path . "', ";
 		$sSql.="'" . $this->type . "', ";
 		$sSql.="'" . $this->size . "', ";
 		$sSql.="'" . $this->iIdUt . "'";
		$sSql.=");";
		mysql_query($sSql);
		if(mysql_error()==0){
			return mysql_insert_id();
		}else{
			return 0;
		}
 	}

	/**
	* Non implemetata
	*/
 	function update(){
 		
 	}

	/**
	* Elimina un'immagine caricata
	*/
 	function delete(){
 		$sSql="delete from tbl_immagini where id_sito='" . $this->id_sito . "' and id='" . $this->id . "'";
 		mysql_query($sSql);
 		$sSql="delete from tbl_contenuto where id_sito='" . $this->id_sito . "' and idtipo='2' and idcont_tab='" . $this->id . "'";
 		mysql_query($sSql);
 	}
 	
 }
?>
