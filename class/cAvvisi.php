<?php
/*
 *      cBase.php
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
 *
 *ATTENZIONE -------- Classe con metodi e proprietà di base da utilizzare per generare altre classi.
 */
?>
<?php
require_once(SRV_ROOT . "/lib/lib_math.php");
class cAvvisi {
	protected $classname;
	protected $version;
	private $id_sito;
	private $oUt;
	private $aPermission;
	public	$IdUt;
	public	$Avviso;
	public	$DataOra;
	
	function __construct(){
	 	$this->classname = "cAvvisi";
	 	$this->version = "Lux in tenebra <br> 2008-09 www.luxintenebra.net";
		$this->IdUt=0;
		$this->Avviso="";
		$this->DataOra=time();
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

	#Show: metodo che viene lanciato di default quando la pagina viene inclusa dal modulo cContent.php
	public function show($opt=""){
	}

	public function insert(){
		if($this->IdUt==0){
			$sSql="insert into tblavvisi set IdUt='" . $this->oUt->id  . "',Avviso='" . $this->Avviso  . "',DataOra='" . $this->DataOra  . "';";
		}else{
			$sSql="insert into tblavvisi set IdUt='" . $this->IdUt  . "',Avviso='" . $this->Avviso  . "',DataOra='" . $this->DataOra  . "';";
		}

	}

	public function delete(){
	}

	public function read(){
	}

}
?>
