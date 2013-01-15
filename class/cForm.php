<?php
class cForm
{

	private $classname;
	private $sEsc;
	public $upload_root;
	
	/*crea la casella di input html con i valori che gli vengono passati
	*sTag � una stringa con i parametri meno importanti da passare con propriet�=valore
	*/
	Public function formInput($sTipo="text",$sNome="",$sValore="",$sLunghezza=0,$sTag=""){
		//$sTStd; //tipi standard per la casella di input
		//$bTest; //booleano per i test di corrispondenza tipo
		//$oT;
		
		$bTest=false;
		
		//carico l'array contente i tipi validi
		$sTStd[0]="button";
		$sTStd[1]="checkbox";
		$sTStd[2]="file";
		$sTStd[3]="hidden";
		$sTStd[4]="image";
		$sTStd[5]="password";
	    $sTStd[6]="radio";
	    $sTStd[7]="reset";
	    $sTStd[8]="submit";
	    $sTStd[9]="text";
	    
	    foreach($sTStd as $value){
			if(($sTipo==$value) or ($bTest==true)){
				$bTest=true;
			}else{
				$bTest=false;
			}
	    }
		
		//nel caso di tipo inesistente lo imposta come casella di testo semplice.
		if($bTest==false){
			$sTipo="text";
		}
		
		$sValore=htmlentities($sValore);
		
		if($sTag==""){
			echo "<input type=\"" . $sTipo . "\" name=\"" . $sNome . "\" id=\"" . "id_" . $sNome . "\" value=\"" . $sValore . "\" size=\"" . $sLunghezza . "\">";
		}else{
			if(!(stripos("id=",$sTag)) or !(stripos("id =",$sTag))){
				echo "<input type=\"" . $sTipo . "\" name=\"" . $sNome . "\" id=\"" . "id_" . $sNome . "\" value=\"" . $sValore . "\" size=\"" . $sLunghezza . "\" " . $sTag . ">";
			} else {
				echo "<input type=\"" . $sTipo . "\" name=\"" . $sNome . "\" value=\"" . $sValore . "\" size=\"" . $sLunghezza . "\" " . $sTag . ">";
			}
		}
		
	}
	
	public function formTextarea($sNome,$sValore,$sRows,$sCols,$sTag){
			
		$sValore=htmlentities($sValore);
		
		if($sTag==""){
			echo "<textarea Rows=\"" . $sRows . "\" name=\"" . $sNome  . "\" id=\"" . "id_" . $sNome . "\" Cols=\"" . $sCols . "\">" . $sValore . "</textarea>";
		}else{
			if(!(stripos("id=",$sTag)) or !(stripos("id =",$sTag))){
				echo "<textarea Rows=\"" . $sRows . "\" name=\"" . $sNome  . "\" id=\"" . "id_" . $sNome . "\" Cols=\"" . $sCols . "\" " . $sTag . ">" . $sValore . "</textarea>";
			} else {
				echo "<textarea Rows=\"" . $sRows . "\" name=\"" . $sNome  . "\" Cols=\"" . $sCols . "\" " . $sTag . ">" . $sValore . "</textarea>";
			}
		}
		
	}
	
	public function formLabel($sId,$sTitle="",$sValore="",$sTag=""){
			
		$sValore=htmlentities($sValore);
			
		if($sTag==""){
			echo "<label id=\"" . $sId . "\" title=\"" . $sTitle . "\">" . $sValore . "</label>";
		}else{
			echo "<label id=\"" . $sId . "\" title=\"" . $sTitle . "\" " . $sTag . ">" . $sValore . "</label>";
		}
		
	}

	public function formQuote($sId,$sTitle="",$sValore="",$sTag=""){
			
		$sValore=htmlentities($sValore);
			
		if($sTag==""){
			echo "<blockquote id=\"" . $sId . "\" title=\"" . $sTitle . "\">" . $sValore . "</blockquote>";
		}else{
			echo "<blockquote id=\"" . $sId . "\" title=\"" . $sTitle . "\" " . $sTag . ">" . $sValore . "</blockquote>";
		}
		
	}

	public function formBr($iNum=1){
		
		if($iNum==1){
			echo "<br>";
		}else{
			for($i=0;$i<$iNum;$i++){
				echo "<br>";
			}
		}
	}
	
	public function formOpenForm($sName="",$sAction="",$sMethod="",$sEnctype="",$sTag=""){
			
		if($sTag==""){
			echo "<form name=\"" . $sName  . "\" enctype=\"" . $sEnctype . "\" method=\"" . $sMethod . "\" action=\"" . $sAction . "\">";
		}else{
			echo "<form name=\"" . $sName  . "\" enctype=\"" . $sEnctype . "\" method=\"" . $sMethod . "\" action=\"" . $sAction . "\" " . $sTag . ">";
		}
	}
	
	public function formCloseForm(){
		echo "</form>";
	}
	
	public function formUpload($sAction,$sTag=""){
		echo "<form name=\"formUpload\" enctype=\"multipart/form-data\" action=\"".$sAction."\" ".$sTag." method=\"POST\">";
		echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10240000000\" />";
	}

	public function formCloseUpload(){
		$this->formLabel("lbupload","Upload file","File da caricare:");
		$this->formBr(1);
		$this->formInput("file","userfile","","40");
		$this->formInput("submit","userfile","Upload","60");		
		echo "</form>";
	}
	
	public function formUploadExec($subDir=""){
		if((substr($subDir,0,1)<>"/") and ($subDir<>"")){
			$subDir="/" . $subDir;
		}
		if((substr($subDir,-1,1)<>"/") and ($subDir<>"")){		
			$subDir.="/";
		}
		$uploaddir = $_SERVER["DOCUMENT_ROOT"] . $this->upload_root . $subDir;
		$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
		
		echo "<pre>";
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
		    echo "Immagine inserita con successo!\n";
		    return true;
		} else {		    
		    echo $_FILES['userfile']['error'];
		    echo "Possibile attacco tramite file upload!\n";
		  	return false;
		}
		//print_r($_FILES); 
		print "</pre>";	
	}
	
	public function formOpenDiv($sId,$sClass,$sTitle="",$sTag=""){
			
		if($sTag==""){
			echo "<div id=\"" . $sId . "\" title=\"" . $sTitle . "\" class=\"" . $sClass . "\">";
		}else{
			echo "<div id=\"" . $sId . "\" title=\"" . $sTitle . "\" class=\"" . $sClass . "\" " . $sTag . ">";
		}
		
	}	
	public function formCloseDiv(){
		echo "</div>";
	}
	
	//Il valore da passare deve essere nel formato: id_valore1,nome_valore1;id_valore2,nome_valore2 ecc...
	public function formSelect($sId="",$sNome="",$sValore="",$sLunghezza=0,$sTag="", $jolly=""){

                $sValore=split(";",htmlentities($sValore));
		
		if($sTag==""){
			echo "<select name=\"" . $sNome . "\">";
		}else{
			echo "<select name=\"" . $sNome . "\"" . $sTag . ">";
		}

		foreach($sValore as $value){
			$sRecord=split(",",$value);
			if(substr($sRecord[1],0,1)==$jolly){
				echo "<option style=\"font-weight:bold;\" value=\"" . $sRecord[0] . "\">Gruppo: " . substr($sRecord[1],1) . "</option>";
			}else{
				echo "<option value=\"" . $sRecord[0] . "\">" . $sRecord[1] . "</option>";
			}
		}

		echo "</select>";
		
	}
	
	//Il valore da passare deve essere nel formato: id_valore1,nome_valore1;id_valore2,nome_valore2 ecc...
	public function fieldSelect($sId="",$sNome="",$aValue="",$sLunghezza=0,$sTag="", $jolly=""){
		
		if($sTag==""){
			echo "<select name=\"" . $sNome . "\">";
		}else{
			echo "<select name=\"" . $sNome . "\"" . $sTag . ">";
		}

		foreach($aValue as $key=>$value){
			if(substr($value,0,1)==$jolly){
				echo "<option style=\"font-weight:bold;\" value=\"" . $key . "\">Gruppo: " . substr($value,1) . "</option>";
			}else{
				echo "<option value=\"" . $key . "\">" . $value . "</option>";
			}
		}

		echo "</select>";
		
	}	

	public function formList($sId="",$sNome="",$sValore="",$sLunghezza=0,$sTag=""){

                $sValore=split(";",htmlentities($sValore));
		
		if($sTag==""){
			echo "<select name=\"" . $sNome . "\" size=\"" . $sLunghezza . "\">";
		}else{
			echo "<select name=\"" . $sNome . "\" size=\"" . $sLunghezza . "\" " . $sTag . ">";
		}

		foreach($sValore as $value){
			$sRecord=split(",",$value);
			echo "<option value=\"" . $sRecord[0] . "\">" . $sRecord[1] . "</option>";
		}

		echo "</select>";
		
	}
}
?>
