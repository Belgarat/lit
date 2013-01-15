<?php
class cLogin extends cPage
	{
	
	public $testo;
	private $classname;
	
	public function __construct()
	{ 
	 	$this->classname = "cLogin";
	 	$version = "Lux in tenebra <br> 2008-09 www.luxintenebra.net";	 	
	}
	
	//public function set_user($id_sito,$obj){
	//	if (is_object($obj)) {
	//		$this->oUt = $obj;
	//	 	$this->aPermission = $this->oUt->fArrayPermission($id_sito,$this->classname);
	//	 } else {
	//	 	echo "Non e' un oggetto.";
	//	 	return false;
	//	 }
	//}
	
	private function redirect($url,$tempo = FALSE ){
	 if(!headers_sent() && $tempo == FALSE ){
	  header('Location:' . $url);
	 }elseif(!headers_sent() && $tempo != FALSE ){
	  header('Refresh:' . $tempo . ';' . $url);
	 }else{
	  if($tempo == FALSE ){
	    $tempo = 0;
	  }
	  echo "<meta http-equiv=\"refresh\" content=\"" . $tempo . ";url=" . $url . "\">";
	  }
	}


	public function show($opt=""){

		if($_POST["Submit"]!=""){
		
			$User=$_POST["txtUsername"];
			$Pwd=$_POST["txtPassword"];
			$IdUtente=$this->oUt->Login($User,$Pwd);
			$this->oUt->id=intval($IdUtente);
			$this->oUt->Leggi();
			if ($IdUtente<>-1){
				$_SESSION["ID"]=$IdUtente;
				$this->oUt->dati["IP"]=$_SERVER["HTTP_HOST"];
				$this->oUt->dati["LastLogin"]=date("Y-m-d H:i:s");
				$this->oUt->Aggiorna($this->oUt->dati);	
				$this->oUt->Leggi();
				$this->oUt->AggOnline($_SESSION["ID"]);
				
				if(!file_exists(SRV_ROOT . "/home/" . $this->oUt->dati["Name"])){
					//mkdir(SRV_ROOT . "/home/" . $this->oUt->dati["Name"],0775);
					//chmod(SRV_ROOT . "/home/" . $this->oUt->dati["Name"],0775);
					$this->oUt->verify_skel($_SERVER["SITO"]);
				}else{
					$this->oUt->verify_skel($_SERVER["SITO"]);
				}
				//echo "Login effettuato con successo.<br><br><a href=\"http://" . . "\">Guarda la notice-board</a>";
			}else{
				echo "Login o password errati!";
			}		
		
			echo "<meta http-equiv=\"refresh\" content=\"" . 0 . ";url=" .  $_SERVER["SCRIPT_NAME"] . "\">";
		
		}else{
			if($_SESSION["ID"]==""){
				?>
				<form name="form1" method="post" action="./index.php?IdP=
				<?php
				echo $_GET["IdP"];
				?>
				">
					<table border="0" cellspacing="10" cellpadding="0">
						<tr>
							<td><font SIZE="3">Username:</font></td>
							<td align=center><font> <input type="text" name="txtUsername" maxlength="50">
								</font>
							</td>
						</tr>
						<tr>
							<td><font SIZE="3">Password:</font></td>
							<td align=center><font> <input type="password" name="txtPassword" maxlength="50">
								</font>
							</td>
						</tr>
						<tr>
							<td><font>&nbsp;</font></td>
							<td align=Right><font> <input type="submit" name="Submit" value="Submit">
								</font>
							</td>
						</tr>
						<tr VALIGN="TOP" ALIGN="center">
							<td colspan=2>
								<br><p><a href="./FunModUt.asp?iT=1">Dimenticato la password?</a></p>
							</td>
						</tr>
					</table>
				</form>
			<?php
			}else{
				echo "Login gi&agrave effettuato con successo.";
			}
		}
	}
}
?>
