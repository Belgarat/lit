<?php
/*
 * Created on 02/ott/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class cMail{
	public $object; //mail subject
	public $message; //body message
	public $From;
	public $ReturnPath;
	public $HTML; //if true type text, if false type HTML
	private $To;
	private $Cc;
	private $Bcc;
	private $intestazione; //other tag intestazione
	
	function __construct(){
		//di default il messaggio è di tipo testo
		$HTML=false;
	}
	
	public function Add_To($mail){
		if(eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$mail)){
			$this->To[]=$mail;
		}
	}
	
	public function Add_Cc($mail){
		if(eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$mail)){
			$this->Cc[]=$mail;
		}
	}

	public function Add_Bcc($mail){
		if(eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$mail)){
			$this->Bcc[]=$mail;
		}
	}

	public function Add_From($mail){
		if(eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$mail)){
			$this->From=$mail;
		}
	}
	
	public function Add_ReturnPath($mail){
		if(eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$mail)){
			$this->ReturnPath=$mail;
		}
	}
	

	//show basic preview message (unformatted)
	public function Preview(){
		if($this->HTML){
			$this->intestazione="MIME-Version: 1.0\r\n";
			$this->intestazione.="Content-type: text/html; charset=iso-8859-1\r\n";
		}else{
			$this->message=htmlentities($this->message);
		}

		$receiver="";
		foreach($this->To as $value){
			if($receiver==""){
				$receiver=$value;
			}else{
				$receiver.="," . $value;
			}
		}
		
		$receiverCc="";
		if ($this->Cc != ""){
			foreach($this->Cc as $value){
				if($receiverCc==""){
					$receiverCc=$value;
				}else{
					$receiverCc.="," . $value;
				}
			}
		}
		$receiverCc.="\r\n";

		$receiverBcc="";
		if ($this->Bcc != ""){
			foreach($this->Bcc as $value){
				if($receiverBcc==""){
					$receiverBcc=$value;
				}else{
					$receiverBcc.="," . $value;
				}
			}
		}
		$receiverBcc.="\r\n";
		
		if(!eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$this->From)){
			$this->From="\r\n";
		}else{
			$this->From.="\r\n";
		}
		
		$this->intestazione.="From:" . $this->From;
		$this->intestazione.="Cc:" . $receiverCc;
		$this->intestazione.="Bcc:" . $receiverBcc;
		
		echo $this->intestazione . "<br>";
		echo $receiver . "<br>";
		echo $this->object . "<br>";
		echo $this->message . "<br>";
		
	}
	

	//send message
	public function Send(){
		
		//verify type mail
		/*if($this->HTML){
			$this->intestazione="MIME-Version: 1.0\r\n";
			$this->intestazione.=" Content-Type: text/html;\n charset=\"iso-8859-1\"\n";
			$this->intestazione.=" Content-Transfer-Encoding: 7bit\n\n";
		}else{
			$this->message=htmlentities($this->message);
		}*/

		$receiver="";
		foreach($this->To as $value){
			if($receiver==""){
				$receiver=$value;
			}else{
				$receiver.="," . $value;
			}
		}
		
		$receiverCc="";
		if ($this->Cc != ""){
			foreach($this->Cc as $value){
				if($receiverCc==""){
					$receiverCc=$value;
				}else{
					$receiverCc.="," . $value;
				}
			}
		}
		$receiverCc.="\r\n";

		$receiverBcc="";
		if ($this->Bcc != ""){
			foreach($this->Bcc as $value){
				if($receiverBcc==""){
					$receiverBcc=$value;
				}else{
					$receiverBcc.="," . $value;
				}
			}
		}
		$receiverBcc.="\r\n";
		
		if(!eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$this->From)){
			$this->From="\r\n";
		}else{
			$this->From.="\r\n";
		}
		
        if(!eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$this->ReturnPath)){
			$this->ReturnPath="\r\n";
		}else{
			$this->ReturnPath.="\r\n";
		}
		
		//$this->intestazione.="From: " . $this->From;
		//$this->intestazione.="Return-Path: " . $this->ReturnPath;
		//$this->intestazione.="CC: " . $receiverCc;
		//$this->intestazione.="Bcc: " . $receiverBcc;
		
		//verify type mail
		if($this->HTML){
			$this->intestazione="MIME-Version: 1.0\r\n";
			$this->intestazione.="Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
			$this->intestazione.="From: " . $this->From;
			$this->intestazione.="Reply-To: " . $this->ReturnPath;
			$this->intestazione.="CC: " . $receiverCc;
			$this->intestazione.="Bcc: " . $receiverBcc;
			//$this->intestazione.="Content-Transfer-Encoding: 7bit\n\n";
		}else{
			$this->intestazione.="From: " . $this->From;
			$this->intestazione.="Replay-To: " . $this->ReturnPath;
			$this->intestazione.="CC: " . $receiverCc;
			$this->intestazione.="Bcc: " . $receiverBcc;
		
			$this->message=htmlentities($this->message);
		}
		
		//echo $this->intestazione . "<br>";
		mail($receiver,$this->object,$this->message,$this->intestazione);
		
	}

}
?>
