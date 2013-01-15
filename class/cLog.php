<?php
/*
	Log object
	
	Trace the user in the portal.

    Versions:
    20060221 - Belgarat - Starting developing
		
	Table cp_events_log fields
  	 id
	 user_id
	 id_session
	 ip_addr
	 time_stamp
	 url_string
	 detail
	 value

*/


Class cLog{

	private $classname;
	
	public $iId;
	public $user_id; //da impostare
	public $id_session;
	public $ip_addr;
	public $time_stamp;
	public $url_string; //da impostare
	public $detail; //da impostare facoltativo
	public $value; //da impostare facoltativo
	public $browser;
	public $level=3;
	public $id_sito;
	public $sConStr;
	public $sReferer;
	public static $trace_error;
	private static $log_level=3;
	public static $write_method; //0=solo video, 1=file, 2=database
	private static $name_dest; //nome destinazione dei dati, se file vale come nome file, se database come nome db
	public static $display; //se impostata a 0(default) non mostra nulla a video, a 1 visualizza gli errori del log level impostato	
	
	function __construct($wr_mode){
		
		//imposta il reporting degli errori a nullo
		error_reporting(0);
		
		//imposta di default un livello di logging degli errori
		self::$trace_error = array(E_WARNING,E_USER_ERROR,E_USER_NOTICE,E_USER_ERROR);
		
		//setta la variabili che identifica il tipo di memorizzazione degli errori
		self::$write_method=$wr_mode;
		
		self::$display=0;
		
		//imposta quale funzione dovrà gestire gli errori
		set_error_handler("cLog::LitErrorHandler");
		
	    $this->id_sito=$_SERVER["SITO"];
	    $this->user_id = 0;
	    $this->time_stamp = 0;
	    $this->detail = "";
	    $this->value = 0;
	    $this->sReferer="";
	
	}
	
	//funzione con il compito di gestire le segnalazioni di errore
	public static function LitErrorHandler($errno, $errstr, $errfile, $errline, $vars="") {	  
		if ((in_array($errno,self::$trace_error)) or (in_array("*",self::$trace_error))) {
		    switch(self::$write_method){
		    	case 1:
		    		echo "|$errno|$errstr|$errfile|$errline\r\n";
		    		error_log(time() . "|$errno|$errstr|$errfile|$errline\r\n" . "",3,SRV_ROOT . "/lit_err.log");
		    		break;
		    	case 2:
		    		if(get_magic_quotes_gpc()){
		    			$errstr=str_replace("'","''",$errstr);
		    			$errfile=str_replace("'","''",$errfile);
		    		}
		    		$sSql="insert tbl_error(date_time,err_no,err_str,err_file,err_line) values(";
		    		$sSql.=time() . ",'$errno','$err_str','$errfile','$errline'";
		    		$sSql.=");";
		    		mysql_query($sSql);
		    		//decommentare in caso di errori nell'Sql.
		    		//echo mysql_error();
		    		break;
		    }
		    if(self::$display==1){
		    	echo "Err. [$errno] - [$errstr] nel file: $errfile alla linea $errline<br>\r\n";
		    }
		}
		
	}
	
	//se impostata va a definire illivello di logging che si desidera
	public function set_log_level($level){
		if(is_numeric($level)){
			switch($level){
				case 0:
					self::$trace_error = array();
					break;
				case 1:
					self::$trace_error = array(E_WARNING,E_USER_ERROR,E_USER_NOTICE,E_USER_ERROR);
					break;
				case 2:
					self::$trace_error = array(E_NOTICE,E_WARNING,E_USER_ERROR,E_USER_NOTICE,E_USER_ERROR);
					break;
				case 3:
					self::$trace_error = array("*");
					break;
			}
		}else{
			switch($level){
				case "NONE":
					self::$trace_error = array();
					break;
				case "LOW":
					self::$trace_error = array(E_WARNING,E_USER_ERROR,E_USER_NOTICE,E_USER_ERROR);
					break;
				case "MEDIUM":
					self::$trace_error = array(E_NOTICE,E_WARNING,E_USER_ERROR,E_USER_NOTICE,E_USER_ERROR);
					break;
				case "HIGHT":
					self::$trace_error = array("*");
					break;
			}			
		}
	}

	//write the information in the cp_events_log table
	function scrivi()
	{	
		if($this->level >= $_SERVER["log_level"]){
			if(get_magic_quotes_gpc()){
				//control the string
				$sDet = substr(str_replace("'","''",$sDet),0,254);
				$sURL = substr(str_replace("'","''",$_SERVER["REQUEST_URI"]),0,254);
			}else{
				$sDet = substr($sDet,0,254);
				$sURL = substr($_SERVER["REQUEST_URI"],0,254);				
			}
			
			if (!is_numeric($this->value))
			{
				$this->value=0;
			}
			
			$this->url_string = $_SERVER["REQUEST_URI"];
		    $this->id_session = session_id();
		    $this->browser = $_SERVER["HTTP_USER_AGENT"]; 
		    $this->ip_addr = $_SERVER["REMOTE_ADDR"];
			//query insert log data
			$sSql="insert into tbl_log" . 
				"(id_sito,user_id,id_session,ip_addr,browser,time_stamp,url_string,detail,value,referer)" .
				" values (" . $this->id_sito . "," . $this->user_id . ", '" . $this->id_session . "', '" .
				$this->ip_addr . "','" . $this->browser . "', " . time() . ", '" . $this->url_string . "', '" . $this->detail . "', " . $this->value . ",'".$this->sReferer."')";
			//execute the query and test the result
			$Result=mysql_query($sSql);
			if (!$Result)
			{
				printf("Query log non corretta, inserimento non riuscito!" . mysql_error());
				exit();
			}
		}
	}

}
?>
