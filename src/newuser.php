<?php
/*
 * Created on 29/set/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
//Controllo stringa password e nome utente
//Messaggio di avvenuta abilitazione
require_once("cfg/config.php");
require_once("cfg/pbf_global.php");
require_once(SRV_ROOT . "/class/cValidate.php");
require_once(SRV_ROOT . "/class/utente.php");
require_once(SRV_ROOT . "/class/cMail.php");
require_once(SRV_ROOT . "/class/class.phpmailer.php");
$oUt = new cUtente;

//Estraggo dati utente

$aryDatiUtente["Name"]=$_POST["txtNickname"];
$aryDatiUtente["Password"]=$oUt->Critta($_POST["txtPassword"]);
$aryDatiUtente["Email"]=$_POST["txtEmail"];
//$aryDatiUtente["ICQ"]=$_POST["txtICQ"];
//$aryDatiUtente["MSN"]=$_POST["txtMSN"];
//$aryDatiUtente["Signature"]=$_POST["txtSignature"];
$aryDatiUtente["ImmUt"]=$_POST["txtPhoto"];
$aryDatiUtente["Abilitato"]=1;

//Estraggo dati personali

$aryDatiPers["nome"]=$_POST["txtNome"];
$aryDatiPers["cognome"]=$_POST["txtCognome"];
$aryDatiPers["data_nascita"]=$_POST["txtDataNascita"];
$aryDatiPers["luogo_nascita"]=$_POST["txtLuogoNascita"];
$aryDatiPers["professione"]=$_POST["txtProfessione"];
$aryDatiPers["residenza"]=$_POST["txtResidenza"];
$aryDatiPers["cellulare"]=$_POST["txtCellulare"];
$aryDatiPers["come_lit"]=$_POST["txtCome"];

//CONTROLLI SUI DATI INSERITI DALL'UTENTE
unset($errore);
foreach($aryDatiUtente as $key => $value){
	//SISTEMARE CONTROLLO DATI PERSONALI.
	switch($key){
		case "Name":
			if(strlen($value)<5){
				$errore[]="Nickname minore di cinque caratteri.";
			}
			break;
		case "Password":
			if(strlen($value)<8){
				$errore[]="Password minore di otto caratteri.";
			}
			if($value!=$oUt->Critta($_POST["txtConfermaPassword"])){
				$errore[]="Le password inserite sono diverse, controllare.";
			}
			break;
		case "Email":
			$result = eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$value);
			if($result == false){
				$errore[]="E-mail in un formato non valido, ricontrollare.";
			}
			break;
	}
}
foreach($aryDatiPers as $key => $value){
	switch($key){
		case "nome":
			if((strlen($value)<2) or ($value=="")){
				$errore[]="Nome troppo corto.";
			}
			break;
		case "cognome":
			if((strlen($value)<2) or ($value=="")){
				$errore[]="Cognome troppo corto.";
			}
			break;
		case "data_nascita":
			$oVal = new cValidate;
			if($oVal->checkDataNascita(substr($value,0,2),substr($value,3,2),substr($value,6,4))){
				$errore[]="Data non valida o utente con meno di 14 anni.";
			}
			break;
	}
}


if(count($errore)!=0){
	foreach($errore as $k => $err){
		echo ($k + 1) . " - Errore:" . $err . "<br>";
	}
}else{
	//REGISTRA UTENTE
	$Iid=$oUt->Inserisci($aryDatiUtente);
	//echo $Iid;
	$aryDatiPers["id"]=$Iid;
	$oUt->Inserisci_personali($aryDatiPers);
	$hash=$oUt->new_hash($Iid);
/*	$oMail = new cMail;
	$oMail->HTML=true;
	$oMail->Add_From="webmaster@luxintenebra.net";
	$oMail->Add_ReturnPath="webmaster@luxintenebra.net";
	$oMail->Add_To($aryDatiUtente["Email"]);
	$oMail->object="Conferma registrazione al forum di Lux in tenebra.";
	$oMail->message="<html><head><title>Mail attivazione utente</title></head><body>";
	$oMail->message.="Benvenuto in Lux in Tenebra " . $aryDatiUtente["Name"] . ". Ora cliccando sul link sottostante sarà possibile attivare l'utente appena registrato.";
	$oMail->message.="<br><br><a href=\"" . $_SERVER["DOMAIN"] . "/index.php?h=" . $hash . "\">Attiva utenza.</a>";
	$oMail->message.="</body></html>";
	//$oMail->Preview();
	$oMail->Send();
	echo "<b>Utente registrato con successo.</b><br><br>";
	echo "E' stata inviata una mail al vostro indirizzo di posta elettronica con un link per l'attivazione dell'account.<br>";
	echo "Una volta effettuata l'attivazione sar&agrave possibile loggarsi.<br>";*/



$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

try {
  $mail->AddReplyTo('webmaster@luxintenebra.net', 'Webmaster');
  $mail->AddAddress($aryDatiUtente["Email"], $aryDatiUtente["Name"]);
  $mail->SetFrom('webmaster@luxintenebra.net', 'Lit-registrer');
  $mail->Subject = 'Conferma registrazione al forum di Lux in tenebra.';
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
  $reg_body='<html><head><title>Mail attivazione utente</title></head><body>';
  $reg_body.='Benvenuto in Lux in Tenebra ' . $aryDatiUtente["Name"] . '. Ora cliccando sul link sottostante sara&grave possibile attivare l\'utente appena registrato.';
  $reg_body.="<br><br><a href=\"" . $_SERVER["DOMAIN"] . "/index.php?h=" . $hash . "\">Attiva utenza.</a>";
  $reg_body.="</body></html>";
  $mail->MsgHTML($reg_body);
  $mail->Send();
  echo "Message Sent OK\n";
  echo "<b>Utente registrato con successo.</b><br><br>";
  echo "E' stata inviata una mail al vostro indirizzo di posta elettronica con un link per l'attivazione dell'account.<br>";
  echo "Una volta effettuata l'attivazione sar&agrave possibile loggarsi.<br><br><br><b>SE LA MAIL NON VIENE RECAPITATA POTREBBE ESSERE STATA VISTA COME SPAM.</b>";

} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}

}
?>
