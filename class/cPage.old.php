<?php
/*
 * Created on 20/lug/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 
 * Classe per la gestione dei contenuti di una pagina web
 */
//require_once("cContenuto.php");
// abstract class cPage{
class cPage
{
 	
 	//abstract function show($IdPg);
 	//abstract function modify_cont($iIdP);
 	protected $id_page;
 	public $page_title;
 	private $classname;
 	
 	protected $id_sito;
	public $testvar;
	
	protected $oUt;
	protected $aPermission;
	protected $opt;
	
 	public function __construct(){
	 	$this->classname = "cPage";
	 	$version = "Lux in tenebra <br> 2004-07 www.luxintenebra.net";
		$this->set_id_page(0);
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

	public function let_subdomain($id_sito=0){
		if ($id_sito==0){
			$id_sito=$this->let_id_sito();
		}
		$q = new Query();
		$q->fields = array("id",
								"id_site",
								"subdomain_name");
		$q->tables = array("tbl_site");
		$q->filters = "(id_site=" . $id_sito . ")";
		if ($q->Open()){
			$row = $q->GetNextRecord();
			return $row[2];
		} else {
			return false;
		}
	}

	public function let_home($id_sito=0){
		if ($id_sito==0){
			$id_sito=$this->let_id_sito();
		}
		$q = new Query();
		$q->fields = array("id",
								"id_site",
								"home");
		$q->tables = array("tbl_site");
		$q->filters = "(id_site=" . $id_sito . ")";
		if ($q->Open()){
			$row = $q->GetNextRecord();
			return $row[2];
		} else {
			return "/";
		}
	}
	
	public function set_id_page($id){
		if (is_numeric($id)) {
			$this->id_page = $id;
			$this->set_title_page($this->id_page);
		 } else {
		 	echo "Pagina inesistente!";
		 	return false;
		 }
	}

	public function let_id_page(){
		return $this->id_page;
	}

	public function set_opt($var=0){
		$oVal = new cValidate;
		$this->opt = $oVal->_txt($oVal->_sql($var));
	}

	public function let_opt(){
		return $this->opt;
	}
	
	public function set_user($id_sito,$obj){
		if (is_object($obj)) {
			$this->oUt = $obj;
			if($this->oUt->id>0){
				$this->oUt->AggOnline($this->oUt->id);
			}
		 } else {
		 	echo "Non e' un oggetto.";
		 	return false;
		 }
	}
 	
	protected function test(){
		echo "test";
	}
 	
 	protected function set_title_page($IdP){ 		
 		$sSql="select titolo from tbl_pagine where id_sito='" . $this->id_sito . "' and id='" . $IdP . "'";
 		$risultato=mysql_query($sSql);
 		if(!$risultato){
 			$this->page_title="Titolo assente";
 			return false;
 			exit;
 		}else{
 			$row=mysql_fetch_assoc($risultato);
 			$this->page_title = $row["titolo"];
 			return true;
 		}
 	}

 	public function read_page($IdP){ 		
 		$sSql="select * from tbl_pagine where id_sito='" . $this->id_sito . "' and id='" . $IdP . "'";
 		$risultato=mysql_query($sSql);
 		if(!$risultato){
 			return false;
 			exit;
 		}else{
 			return mysql_fetch_assoc($risultato);
 		}
 	}
 	
 	//Da implementare con il controllo in un campo utente della pagina di default personalizzata
 	public function DefaultPage($obj,$IdP=1){
			if ($this->let_id_page()==0) {
				$q = new Query();
				$q->fields = array("id",
										"id_site",
										"DefaultAnonymousPage",
										"DefaultUserPage");
				$q->tables = array("tbl_site");
				$q->filters = "(id_site=" . $this->let_id_sito() . ")";
				if ($q->Open()){
					$row = $q->GetNextRecord();
					if($this->oUt->id == 0){
						return $row[2];
					}else{
						return $row[3];
					}
				}
			} else {
				return $IdP;
			}
 	}
 	
	//verifica il contenuto di una pagina e restituisce 0 se non è di tipo statico e -1 se non esiste o ci sono problemi di connessione
	public function read_pag_id_cont($IdP){
		$sSql="select idC from tbl_rel_pag_cont where id_sito='" . $this->let_id_sito() . "' and idP='" . $IdP . "';";
		$risultato=mysql_query($sSql);
		if($risultato){
			$row=mysql_fetch_assoc($risultato);
			return $row["idC"];
		} else {
			return -1;
		}
	}
		
 	public function read_gest_page($IdP){ 		
 		$sSql="select page_address_gest from tbl_pagine where id_sito='" . $this->id_sito . "' and id='" . $IdP . "'";
 		$risultato=mysql_query($sSql);
 		if(!$risultato){
 			return false;
 			exit;
 		}else{
 			$row=mysql_fetch_assoc($risultato);
 			return $row["page_address_gest"];
 		}
 	}

 	
 	/*
 	 * quando invocata inserisce la pagina e ne restituisce l'ID
 	 */
 	public function InsertPage($Titolo){
 		if(get_magic_quotes_gpc==1){
 			$Titolo=addslashes($Titolo);
 		}
		$sSql="insert into tbl_pagine(id_sito,linguaggio,titolo,page_address) values('" . $this->id_sito . "','-1','".mysql_real_escape_string($Titolo)."','');";
 		mysql_query($sSql);
 		echo mysql_error();
 		return mysql_insert_id();
 	}
 	public function InsertPageCont($IdPg,$IdCont){
 		$sSql="insert into tbl_rel_pag_cont set  id_sito='" . $this->id_sito . "',idP='".$IdPg."',idC='".$IdCont."';";
 		mysql_query($sSql);
 		echo mysql_error();
 	}
 	public function InsertPageMenu($IdPg,$IdCont,$IdPagPadre,$TitoloMenu){ 		
 		//Calcola l'id padre data la pagina padre
 		if(get_magic_quotes_gpc()){
 			$TitoloMenu=addslashes($TitoloMenu);
 		}
 		if(substr($IdPagPadre,0,1)=="G"){
 			$IdPagPadre=substr($IdPagPadre,1);
 			$IdPadre=$IdPagPadre;
 		}else{
 			$IdPagPadre=substr($IdPagPadre,1);
 			$sSql="select id from tbl_menu where id_sito='" . $this->id_sito . "' and id_pagina='".$IdPagPadre."'";
	 		$risultato=mysql_query($sSql);
	 		if($risultato){
	 			$row=mysql_fetch_assoc($risultato);
	 			$IdPadre=$row["id"];
	 		}else{
	 			echo mysql_error();
	 		} 		
 		}
 		//trova l'ordine giusto nel menu
 		$sSql="select menu_order from tbl_menu where id_sito='" . $this->id_sito . "' and Padre='".$IdPadre."' order by menu_order desc limit 0,1";
 		$risultato=mysql_query($sSql);
 		if($risultato){
 			$row=mysql_fetch_assoc($risultato);
 			$order=$row["menu_order"]+1;
 		}else{
 			echo mysql_error();
 		}
 		//Inserisce la pagina nel menu
 		$sSql="insert into tbl_menu set id_sito='" . $this->id_sito . "',menu_order='".$order."',title='".$TitoloMenu."',Padre='".$IdPadre."',id_pagina='".$IdPg."';";
 		$risultato=mysql_query($sSql);
 		if($risultato){
 			return true;
 		}else{
 			echo mysql_error();
 			return false;
 		}
 		
 		//mysql_query($sSql);
 		//echo mysql_error();
 	} 
 	
 	public function delete_page($iIdP){
 		//funzione per cancellare la pagina associata al contenuto e il menu associato alla pagina.
 		$errore=false;
 		$sSql="delete from tbl_pagine where id_sito='" . $this->id_sito . "' and id='" . $iIdP . "'";
 		//echo $sSql . "<br>";
		if(!mysql_query($sSql)){
			echo "Errore SQL (cPage.php,riga: 109): " . mysql_error();
			$errore=true;
		}
		if($errore==false){
			$sSql="delete from tbl_rel_pag_cont where id_sito='" . $this->id_sito . "' and idP='" . $iIdP . "'";
			//echo $sSql . "<br>";
			if(!mysql_query($sSql)){
				echo "Errore SQL (cPage.php,riga: 118): " . mysql_error();
				$errore=true;
			}
		}
		if($errore==false){
			$sSql="delete from tbl_menu where id_sito='" . $this->id_sito . "' and id_pagina='" . $iIdP . "'";
			//echo $sSql . "<br>";
			if(!mysql_query($sSql)){
				echo "Errore SQL (cPage.php,riga: 126): " . mysql_error();
				$errore=true;
			}
		}
 	}
 	
 	//determina se il menu a cui appartiene quella pagina ha dei sottomenu
 	public function verify_child($iIdP){
		$sSql="select id from tbl_menu where id_sito='" . $this->id_sito . "' and id_pagina='" . $iIdP . "'";
 		$risultato=mysql_query($sSql);
 		if($risultato){
 			$row=mysql_fetch_assoc($risultato);
 			$sSql="select count(id) as counter from tbl_menu where id_sito='" . $this->id_sito . "' and padre='" . $row["id"] . "'";
 			mysql_free_result($risultato);
	 		$risultato=mysql_query($sSql);
	 		if($risultato){
	 			$row=mysql_fetch_assoc($risultato);
	 			return $row["counter"];
	 		}
 		}
 	}
 	
 	/**
 	 * Show the online user
 	 * return: html online user list
 	 * */
 	public function show_online(){
		$ary_online=$this->oUt->list_user_online();
		$tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/online.tpl.php'));
	    if($ary_online){
    		$html="";
		$js=" OnMouseOut=\"javascript: this.style.width='30'\" OnMouseOver=\"javascript: this.style.width='55'\" ";
		$js_name=" OnMouseOut=\"javascript:this.style.fontSize='12'\" OnMouseOver=\"javascript: this.style.fontSize='18'\" ";
	    	foreach($ary_online as $value){		
			$deadtime=$this->oUt->Inattivo($value["IDUt"]);
			if((int)$deadtime>=0 and (int)$deadtime<2){
			    	$html.="<tr ".$js_name."><td class='testo'><a href='javascript: void(0);'><img ".$js." width='30' border='0' src='".$this->oUt->url_img($value["IDUt"])."'></a></td><td><a href=\"javascript: apriUtente('','".$value["IDUt"]."');\">".$value["User"]."</a></td><td class='numerico'>".date('d-m-Y H:i:s',$value["DataOra"])."</td><td class='numerico'>".$deadtime."</td></tr>\r\n";
			}else{
				$this->oUt->delete_user_online($value["IDUt"]);
			}
    		}
	    }else{
		    $html.="<tr><td colspan='3' class='testo'>Nessun utente online</td></tr>\r\n";
        }
		$tpl = preg_replace("#<!-- USER -->#", $html, $tpl);
		
		echo $tpl;	
	}
}
 
?>
