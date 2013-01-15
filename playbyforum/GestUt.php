<?php
	session_start();
	require_once($_SERVER["DOCUMENT_ROOT"]."/class/oConn.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/forum/Class/utente.php");

	$oConn = new connessione("/inc/config1.php");
	$oConn->connect();
	
	$oUt = new cUtente($oConn);
	$oUt->id=intval($_SESSION["ID"]);
	$oUt->Leggi();

if ($oUt["moderatore"]==1 and $oUt["master"]>70){
?>
<HTML>
	<HEAD>
		<link type='text/xml' rel='alternate' href='/Default.vsdisco'/>

		<TITLE>Lux in tenebra, forum Medieval - Fantasy - Dungeons & Dragons</TITLE>
		<meta content="http://schemas.microsoft.com/intellisense/ie5" name="vs_targetSchema">
		<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<LINK href="./style.css" type="text/css" rel="STYLESHEET">
		<LINK href="./site.css" type="text/css" rel="STYLESHEET">
	</HEAD>
	<BODY style="BACKGROUND-COLOR: rgb(24,5,25)" text="#ffffff" bgColor="#180519" leftMargin="0" topMargin="0" MARGINHEIGHT="0" MARGINWIDTH="0">
	<TABLE width="100%" cellSpacing="0" cellPadding="0" align="center" border="0" NOF="LY" ID="Table1">
		<TR vAlign="top">
			<TD align=center>
				<IMG id="IMG1" alt="" src="./images/Logo2.gif" align="middle">
			</TD>
		</TR>
		<TR VALIGN="TOP">
			<TD align=center>
<?php
if ($_GET["iIdUt"]==""){
	$oUt.Lista;
}else{ 

	$oUt->iIdUt=request.QueryString("iIdUt");

	$oUt->Leggi;

	select case request.Form("cmdMod")
		case "Modifica"
			$oUt->Name=replace(request.Form("Name"),"'","''")
			$oUt->Password=replace(request.Form("Password"),"'","''")
			$oUt->Admin=replace(request.Form("Admin"),"'","''")
			$oUt->Signature=replace(request.Form("Signature"),"'","''")
			$oUt->Motto=replace(request.Form("Motto"),"'","''")
			'$oUt->LastLogin=replace(request.Form("Name"),"'","''")
			$oUt->Email=replace(request.Form("Email"),"'","''")
			$oUt->IP=replace(request.Form("IP"),"'","''")
			$oUt->MasterID=replace(request.Form("MasterID"),"'","''")
			$oUt->Master=replace(request.Form("Master"),"'","''")
			$oUt->Moderatore=replace(request.Form("Moderatore"),"'","''")
			$oUt->ICQ=replace(request.Form("ICQ"),"'","''")
			$oUt->MSN=replace(request.Form("MSN"),"'","''")
			$oUt->Hobby=replace(request.Form("Hobby"),"'","''")
			$oUt->LibAut=replace(request.Form("LibAut"),"'","''")
			$oUt->Amici=replace(request.Form("Amici"),"'","''")
			$oUt->ImmUt=replace(request.Form("ImmUt"),"'","''")
			$oUt->Tipo=replace(request.Form("Tipo"),"'","''")
			$oUt->DtISO=replace(request.Form("DtIso"),"'","''")
			$oUt->Timer=replace(request.Form("Timer"),"'","''")
			'$oUt->DtISOCreazione=replace(request.Form("DtISOCreazione"),"'","''")
			'$oUt->TimerCreazione=replace(request.Form("TimerCreazione"),"'","''")
			$oUt->GiorniCancMsg=replace(request.Form("GiorniCancMsg"),"'","''")
			$oUt->MsgArchivio=replace(request.Form("MsgArchivio"),"'","''")
			$oUt->Abilitato=replace(request.Form("Abilitato"),"'","''")
			
			$oUt->Aggiorna
			
			Response.Write "Aggiornamento eseguito!<br><br>"
			Response.Write "<a href='./GestUt.asp?iIdUt=" & request.QueryString("iIdUt") & "'>Indietro</a>"
		case "Indietro"
			response.Redirect "./GestUt.asp"
		case else
			?>
			<form name="frmMod" method=post action="./GestUt.asp?iIdUt=<?php=request.QueryString("iIdUt")?>">
			<table border=1>
			<tr>
				<td>
					iIdUt:
				</td>
				<td>
					<input type="text" name="iIdUt" value="<?php=$oUt->iIdUt?>"><br>
				</td>
			</tr>
			<tr>
				<td>
					Name: 
				</td>
				<td>
					<input type="text" name="Name" value="<?php=$oUt->name?>"><br>
				</td>
			</tr>
			<tr>
				<td>
					Password: 
				</td>
				<td>
					<input type="text" name="Password" value="<?php=$oUt->Password?>"><br>
				</td>
			</tr>
			<tr>
			<td>
			Admin:
			</td>
			<td>
			<input type="text" name="Admin" value="<?php=$oUt->Admin?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			Signature: 
			</td>
			<td>
			<textarea cols=20 rows=5 name="Signature"><?php=$oUt->Signature?></textarea><br>
			</td>
			</tr>
			<tr>
			<td>
			Motto: 
			</td>
			<td>
			<textarea cols=20 rows=5 name="Motto"><?php=$oUt->Motto?></textarea><br>
			</td>
			</tr>
			<tr>
			<td>
			LastLogin: 
			</td>
			<td>
			<input type="text" name="LastLogin" value="<?php=$oUt->LastLogin?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			Email: 
			</td>
			<td>
			<input type="text" name="Email" value="<?php=$oUt->Email?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			IP: 
			</td>
			<td>
			<input type="text" name="IP" value="<?php=$oUt->IP?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			MasterID: 
			</td>
			<td>
			<input type="text" name="MasterID" value="<?php=$oUt->MasterID?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			Master: 
			</td>
			<td>
			<input type="text" name="Master" value="<?php=$oUt->Master?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			Moderatore: 
			</td>
			<td>
			<input type="text" name="Moderatore" value="<?php=$oUt->Moderatore?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			ICQ: 
			</td>
			<td>
			<input type="text" name="ICQ" value="<?php=$oUt->ICQ?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			MSN: 
			</td>
			<td>
			<input type="text" name="MSN" value="<?php=$oUt->MSN?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			Hobby: 
			</td>
			<td>
			<input type="text" name="Hobby" value="<?php=$oUt->Hobby?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			LibAut: 
			</td>
			<td>
			<input type="text" name="LibAut" value="<?php=$oUt->LibAut?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			Amici: 
			</td>
			<td>
			<input type="text" name="Amici" value="<?php=$oUt->Amici?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			ImmUt: 
			</td>
			<td>
			<input type="text" name="ImmUt" value="<?php=$oUt->ImmUt?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			Tipo: 
			</td>
			<td>
			<input type="text" name="Tipo" value="<?php=$oUt->Tipo?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			DtISO: 
			</td>
			<td>
			<input type="text" name="DtISO" value="<?php=$oUt->DtISO?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			Timer: 
			</td>
			<td>
			<input type="text" name="Timer" value="<?php=$oUt->Timer?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			DtISOCreazione: 
			</td>
			<td>
			<input type="text" name="DtISOCreazione" value="<?php=$oUt->DtISOCreazione?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			TimerCreazione: 
			</td>
			<td>
			<input type="text" name="TimerCreazione" value="<?php=$oUt->TimerCreazione?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			GiorniCancMsg: 
			</td>
			<td>
			<input type="text" name="GiorniCancMsg" value="<?php=$oUt->GiorniCancMsg?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			MsgArchivio: 
			</td>
			<td>
			<input type="text" name="MsgArchivio" value="<?php=$oUt->MsgArchivio?>"><br>
			</td>
			</tr>
			<tr>
			<td>
			Abilitato: 
			</td>
			<td>
			<input type="text" name="Abilitato" value="<?php=$oUt->Abilitato?>"><br>
			</td>
			</tr>
			<tr>
			<td colspan="2">
				<input type=submit name="cmdMod" value="Modifica">
				<input type=submit name="cmdMod" value="Indietro">
			</td>
			</tr>
			</table>
			</form>
			<?php
		end select

}

?>
</td>
</tr>
</table>
</body>
</html>

<?php
}else{
	echo "./index.php";
}
?>