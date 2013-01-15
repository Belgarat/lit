<?php
/*
 *      cSoci.php
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
 */
?>
<?php
require_once(SRV_ROOT . "/lib/lib_math.php");
class cSoci {
	protected $classname;
	protected $version;
	private $id_sito;
	private $oUt;
	private $aPermission;
	private $id_socio = 1; 
	
	function __construct(){
	 	$this->classname = "cSoci";
	 	$this->version = "Lux in tenebra <br> 2009-10 www.luxintenebra.net";
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

    public function set_id_socio($id){
		if (is_numeric($id)) {
			$this->id_socio = $id;
		 } else {
			$this->id_socio = 0;
		 }
	}

	public function let_id_socio(){
		return $this->id_socio;
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
            $permission[$value] = $this->oUt->fArrayPermission($this->let_id_sito(),$value,$id,$this->oUt->id, 'tbl_dati_personali', 'id_sito', 'id', 'id');
        }

        return $permission;
    }


	#Show: metodo che viene lanciato di default quando la pagina viene inclusa dal modulo cContent.php
	public function show($opt=""){
	
		$tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/template.tpl.php'));

		if($this->aPermission["Show"]==0){
			$q = new Query();
			$q->fields = array("id",
									"nome",
									"cognome",
									"socio",
									"come_lit");
			$q->tables = array("tbl_dati_personali");
			#q->filters = "(id_site='" . $this->let_id_sito() . "' and IdUt='0')";
			
			if ($q->Open()){
				$row_soci="";
				while ($row = $q->GetNextRecord()) {
				    $row_soci .= "<tr>\r\n";
                    if($this->let_id_socio()!=$row[0]){
				        $row_soci .= "<td>" . $row[1] . "</td>\r\n";
                    }else{
				        $row_soci .= "<form name='formSoci' method='POST'><td><input type='text' size='" . strlen($row[1])  . "' value='" . $row[1] . "' name='name_" . $row[0]  . "'></td>\r\n";
                    }
	                if($this->let_id_socio()!=$row[0]){
				        $row_soci .= "<td>" . $row[2] . "</td>\r\n";
                    }else{
	        		    $row_soci .= "<td><input type='text' size='" . strlen($row[2])  . "' value='" . $row[2] . "' name='name_" . $row[0]  . "'></td>\r\n";
                    }
	                if($this->let_id_socio()!=$row[0]){
				        $row_soci .= "<td>" . $row[3] . "</td>\r\n";
                    }else{
        			    $row_soci .= "<td><input type='text' size='" . strlen($row[3])  . "' value='" . $row[3] . "' name='name_" . $row[0]  . "'></td>\r\n";
                    }
	                if($this->let_id_socio()!=$row[0]){
                        $row_soci .= "<td>";
                    }else{
                        $row_soci .= "<td><input type='button' name='btn_save' value='Save'></form>";
                    }
					$ajax="Ajax.Updater('table_soci','src/ajax/cSoci_form_edit.php','{method: get,parameters: url=idsocio=" . $row[0] . "');";
				    $row_soci .= "<a href='javascript:void(0);' onclick='javascript: " . $ajax . "'>" . $row[0] . "</a></td>\r\n";
				    $row_soci .= "</tr>\r\n";
				}
			}
		}
		
		$tpl = eregi_replace("<!--ROW_SOCI-->", $row_soci, $tpl);
		
		echo $tpl;

	}
}
?>
