<?php
/*
 * Created on 05/giu/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
Class cValidate
{

	public $classname;
	public $version;
	
	public function __construct()
	{ 
	 	$this->classname = "cValidate";
	 	$this->version = "Lix in tenebra <br> 2004 www.luxintenebra.net";
	}

	public function _txt($string){
		$txt = $string;
		return $txt;
	}

	public function _url_img($str){
		return ereg("^((http(s?)\:\/\/|~/|/)?([\w]+:\w+@)?([a-zA-Z]{1}([\w-]+.)+([\w]{2,5}))(:[\d]{1,5})?((/?\w+/)+|/?)(\w+.(jpg|png|gif))",$str);
	}

	public function _sql($string){
		if (get_magic_quotes_gpc()==0) {
			$sql = mysql_real_escape_string($string);
		} else {
			$sql=$string;
		}
		return $sql;
	}

	public function checkDataNascita($mese,$giorno,$anno) {
		$eta_min = 13;
		$eta_max = 100;
		
		if (checkdate($mese,$giorno,$anno)) {
			return false;
		}
		
		list($anno_cur,$mese_cur,$giorno_cur) = explode(',',date('Y,m,d'));
		$anno_min = $anno_cur - $eta_max;
		$anno_max = $anno_cur - $eta_min;
		echo "$anno_min,$anno_max,$mese,$giorno,$anno";
		
		if (($anno > $anno_min) && ($anno < $anno_max)) {
			return true;
		}elseif(($anno == $anno_max) &&
			(($mese < $mese_cur) ||
			(($mese == $mese_cur) && ($giorno <= $giorno_cur)))) {
			return true;
		}elseif(($anno == $anno_min) &&
			(($mese > $mese_cur) ||
			(($mese == $mese_cur && ($giorno > $giorno_cur))))) {
			return true;
		}else{
			return false;
		}
	}

	/*Da il tipo dei campi di un risultato di una query in un array*/
	public function TipoCampi($aRow){
			for ($i=0;$i<mysql_num_fields($aRow);$i++){			
				$aTipo[mysql_field_name($aRow,$i)]=mysql_field_type($aRow,$i);
			}
			return $aTipo;
	}

	
	public function SqlVal(&$ary,$tab){
		
		$sSql="select * from ".$tab." where ID=" . $ary["ID"];
		$risultato = mysql_query($sSql);
		if (!$risultato){
			return $ary;
			exit;
		}else{
			//ciclo per creare l'array che identifica il tipo campo.
			$aTipo=$this->TipoCampi($risultato);
			mysql_free_result($risultato);
		}		
		
		//a seconda del tipo di campo ne impone il valore
		//string mette caratteri di escape
		//int impone valore intero
		//blob niente
		//datetime impone data
		foreach ($ary as $key => &$value){
			//switch per i valori.				
			switch ($aTipo[$key]){
				case "int":	
					$value = intval($value);		
					break;
				case "string":
					$value = $this->_sql($value);
					break;
				default:
			}
		}
		return $ary;
	}
	
	public function br2nl($string)
	{
		return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	}
	
}
?>
