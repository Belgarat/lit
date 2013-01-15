<?php
/*
 *      cNoticeboard.php
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
?>
<?php
require_once(SRV_ROOT . "/lib/lib_math.php");
class cNoticeboard {
	protected $classname;
	protected $version;
	private $id_sito;
	private $oUt;
	private $aPermission;
	
	function __construct(){
	 	$this->classname = "cNoticeboard";
	 	$this->version = "Lux in tenebra <br> 2008-09 www.luxintenebra.net";
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

	//data una tabella restituisce l'id casuale di un record
	private function random_id($tbl_name){
		$max=1;
		if($tbl_name!=""){
			$q = new Query();
			$q->fields = array("count(id)");
			$q->tables = array($tbl_name);
			if ($q->Open()){
				$tmp = $q->GetNextRecord();
				$max = $tmp[0];
			}
		}
		
		do{
			$rand_id=random_number_range(0,$max);
			$q->fields = array("id");
			$q->tables = array($tbl_name);
			$q->filters = "(id=" . $rand_id  . ")";
			$q->Open();
		}while (!$row = $q->GetNextRecord());

		return $rand_id;
	}

	private function read_field($id, $table, $fields){
		$id = (int) $id;
		echo $id;
		$value = array();
		if(!(($id==0))){
			$q = new Query();
			$q->fields = $fields;
			$q->tables = array($table);
			$q->filters = "(id=" . $id . ")";
			if($q->Open()){
				$value = $q->GetNextRecord();
			}
		}
		return $value;
	}

	public function show($opt=""){
		$noticeboard = implode("", file(SRV_ROOT . '/class/cNoticeboard/template.tpl'));
		//print_r($this->read_field($this->random_id("tblfrasi"),"tblfrasi", array("Messaggio","autore","Avventura")));
		if($this->aPermission["Show"]==1){
			$q = new Query();
			$q->fields = array("id",
									"board_name",
									"module_name",
									"module_path",
									"options",
									"module_description");
			$q->tables = array("tbl_noticeboard");
			$q->filters = "(id_site='" . $this->let_id_sito() . "' and IdUt='0')";
			
			if ($q->Open()){
				while ($row = $q->GetNextRecord()) {
                    require_once(SRV_ROOT . $row[3]);
				    $objClass = new $row[2];
				    $objClass->set_id_sito($this->let_id_sito());
				    $objClass->set_user($this->let_id_sito(),$this->oUt);
				    $noticeboard = preg_replace("#<!-- " . $row[1] . "_TITLE -->#", $row[5], $noticeboard);
				    $options=explode(",",$row[4]);
                    $noticeboard = preg_replace("#<!-- " . $row[1] . " -->#", $objClass->show_board($options[0],$options[1],$options[2]), $noticeboard);
				}
			}
		}
		
		//$noticeboard = eregi_replace("<!-- IMG_BOARD5 -->", SITE_IMG . "/perga.jpg", $noticeboard);
		echo $noticeboard;
	}
	
	

}
?>
