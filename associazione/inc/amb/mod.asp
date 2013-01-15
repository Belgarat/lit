<script language="Javascript" type="text/javascript">
<!--
  // aggiunge una emoticon nella textarea del messaggio
  function fFormatta(text) {
  	text = '[' + text + ']';
  	if (document.frmAmb.txtDescrizione.createTextRange && document.frmAmb.txtDescrizione.caretPos) {
  		var caretPos = document.frmAmb.txtDescrizione.caretPos;
  		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
  		document.ftmAmb.txtDescrizione.focus();
  	} else {
  	document.frmAmb.txtDescrizione.value  += text;
  	document.frmAmb.txtDescrizione.focus();
  	}
  }
//-->
</script>

<%

function fModAmb()
Dim RsMod
Dim sSql

select case request.Form("cmdNome")
			
	case "Modifica"
		sNome=replace(request.Form("txtNome"),"'","''")
		sDesc=replace(request.Form("txtDescrizione"),"'","''")
		iTipo=request.QueryString("Tipo")
		iOrdine=replace(request.Form("txtOrdine"),"'","''")
		if iOrdine="" then iOrdine=0
		ConPort.Errors.Clear
		'ConPort.BeginTrans
		sSql="update tblamb_aree_desc set nome='" & sNome & "', descrizione='" & sDesc & "',ordine=" & iOrdine & " where ID=" & request.QueryString("Area") & ";"
		ConPort.execute(sSql)
		'response.Write sSql
		'if ConPort.errors.count<>0 then
		'	ConPort.RollBackTrans
		'	response.Write "Modifica non effettuata riprovare!<br><br>"
		'else
		'	ConPort.CommitTrans
			response.Write "Modifica effettuata!<br><br>"
	'	end if
		response.Write "<a href='./GestTerritorioAmb.asp'>Torna all'inizio procedura</a>"
				
	case "AnteprimaMod"
		set RsMod = server.CreateObject("ADODB.Recordset")
		sSql="select * from tblamb_aree_desc where ID=" & request.QueryString("Area")
		RsMod.Open sSql,ConPort		
		%>
		<table ID="Table4">
			<tr>
				<td>
					<form name="frmAmb" method="post" action="./GestTerritorioAmb.asp?Sez=mod&Area=<%=request.QueryString("Area")%>" ID="Form2">
					Nome : <input type="text" name="txtNome" size="30" value="<%=request.Form("txtNome")%>" readonly ID="Text4"><br>
					Descrizione: <br><textarea name="txtDescrizione" rows="30" cols="100" ID="Textarea2"><%=request.Form("txtDescrizione")%></textarea><br>
					Ordine: <input type=text name="txtOrdine" size=1 value="<%=request.Form("txtOrdine")%>" ID="Text6"><br><br><br>
					<input type="Submit" name="cmdNome" value="Modifica" ID="Submit4">
					<input type="Submit" name="cmdNome" value="AnteprimaMod" ID="Submit5">
					</form>					
				</td>
				<td valign="top">
					<%sConsole()%>
				</td>
			</tr>
			<tr>
				<td>
					<blockquote>
					<%
					response.Write fInterprete(request.Form("txtDescrizione"))
					%>
					</blockquote>				
				</td>
			</tr>
		</table>
		<%
		RsMod.close
	case else
		'FORM DI MODIFICA
		set RsMod = server.CreateObject("ADODB.Recordset")
		sSql="select * from tblamb_aree_desc where ID=" & request.QueryString("Area")
		RsMod.Open sSql,ConPort		
		%>
		<table ID="Table1">
			<tr>
				<td>
					<form name="frmAmb" method="post" action="./GestTerritorioAmb.asp?Sez=mod&Area=<%=request.QueryString("Area")%>" ID="Form1">
						Nome: <input type="text" name="txtNome" size="30" value="<%=RsMod.fields("Nome")%>" readonly ID="Text1"><br>
						Descrizione: <br><textarea name="txtDescrizione" rows="30" cols="100" ID="Textarea4"><%=RsMod.fields("descrizione")%></textarea><br>
						Ordine: <input type=text name="txtOrdine" size=1 value="<%=RsMod.fields("Ordine")%>" ID="Text10"><br><br><br>
						<input type="Submit" name="cmdNome" value="Modifica" ID="Submit1">
						<input type="Submit" name="cmdNome" value="AnteprimaMod" ID="Submit2">
					</form>
				</td>
				<td valign="top">
					<%sConsole()%>
				</td>									
			</tr>
		</table>
		<%
		RsMod.close
end select
end function
%>


<%
'############################################################
												'MAIN PROGRAM
'############################################################
if int(request.QueryString("Area"))>0 then
	Call fModAmb()
else
	Call fListaAmb()
end if
%>