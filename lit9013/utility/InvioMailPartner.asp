<HTML>
	<HEAD>
	<!--METADATA TYPE="typelib" UUID="CD000000-8B95-11D1-82DB-00C04FB1625D" NAME="CDO for Windows 2000 Type Library" -->
	</HEAD>
	<BODY bgColor="#180519">
		<%
Set Con = Server.CreateObject("ADODB.Connection")
Set rsQuery = Server.CreateObject("ADODB.Recordset")
		
if Request.Form("Send")<>"" then

	txtMsg=Request.Form("txtMsg")
	txtOgg=Request.Form("txtOgg")

	set mail =createobject("CDO.Message")
	Set mConf =createobject("CDO.Configuration")
	Set Flds = mConf.Fields
		
	Flds(cdoSendUsingMethod) = cdoSendUsingPort
	Flds(cdoSMTPServer) = "smtp.luxintenebra.net"
	Flds(cdoSMTPServerPort) = 25
	Flds(cdoSMTPAuthenticate) = cdoBasic
	Flds(cdoSendUserName) = "postmaster@luxintenebra.net"
	Flds(cdoSendPassword) = "pblt3zr1"
	Flds.Update

	Set Mail.Configuration = mConf

	Mail.From = "master@luxintenebra.net" ' From address
   'Mail.FromName = "Forum - Lux in tenebra" ' optional

	Con.Open Application("DB_CONNECTIONSTRING")		
	Set rsQuery = Con.Execute("SELECT email FROM userdb where abilitato=0")
   
	Mail.to=""
	Mail.sender="master@luxintenebra.net"
	'sInd="viacart@tin.it,marcobrunet@tin.it,ddd_hhh@hhh.ff"
	do while not rsQuery.EOF
		sInd=sInd & trim(cStr(rsquery.Fields("email"))) & ","
		rsquery.MoveNext
	loop
	
	sBCC=split(sInd,",")
	for each iMail in sBCC
		Mail.BCC=iMail
		' message subject
		Mail.Subject = txtOgg
		' message body
		Mail.HTMLBody = txtMsg
		'Mail.IsHTML = True
		strErr = ""
		bSuccess = False
		On Error Resume Next ' catch errors
		Mail.Send ' send message
		If Err <> 0 Then ' error occurred
			strErr = Err.Description
			Response.Write "<FONT color='#ffffff'>Errore: " & strErr & " Sull'indirizzo: " & iMail & "</font><br>"
		else
			bSuccess = True
			Response.Write "<FONT color='#ffffff'>Mail inviata correttamente!!! Sull'indirizzo: " & iMail & "</font><br>"
		End If
	next
	Set Mail = nothing	
	rsquery.Close
	con.Close
else

	%>
		<FORM id="FORM1" name="FORM1" action="" method="post">
			<FONT color="#ffffff">Oggetto: <%session("Name")%> Tuo nome: <input type="submit" name="txtOgg" value="send"></FONT><br>
			<textarea name="txtMsg" rows="30" cols="50"></textarea> <input type="submit" name="Send" value="send">
		</FORM>
		<%

end if
%>
	</BODY>
</HTML>
