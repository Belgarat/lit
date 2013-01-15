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

function fInsAmb()
Dim RsAmb
Dim sSql
set RsAmb = server.CreateObject("ADODB.Recordset")

select case request.Form("cmdNome")
	case "Avanti"
		%>
		<table ID="Table3">
			<tr>
				<td>
					<form name="frmAmb" method="post" action="./GestTerritorioAmb.asp?Sez=ins&Tipo=<%=request.QueryString("Tipo")%>" ID="Form2">
						Nome <%=fFindNomeTipo(request.QueryString("Tipo"))%>: <input type="text" name="txtNome" size="30" value="<%=request.Form("txtNome")%>" readonly><br>
						Descrizione: <br><textarea name="txtDescrizione" rows="30" cols="100"></textarea><br>
						Immagine: <input type=text name="txtImm" size=100 value=""><br>
						Ordine: <input type=text name="txtOrdine" size=1 value=""><br><br><br>
						<input type="Submit" name="cmdNome" value="Inserisci">
						<input type="Submit" name="cmdNome" value="AnteprimaIns">
					</form>
				</td>
				<td valign="top">
					<%sConsole()%>
				</td>									
			</tr>
		</table>
		<%
		
	case "Aggiungi"
		if request.QueryString("Tipo")=1 then
			response.write "Non è possibile aggiungere una regione ad un'altra regione."
			response.write "<br><a href='./GestTerritorioAmb.asp?Sez=ins&Tipo=" & request.QueryString("Tipo") & "'>Indietro</a>"
		else
			%>
			<table ID="Table2">
				<tr>
					<td>
					<form name="frmAmb" method="post" action="./GestTerritorioAmb.asp?Sez=ins&Tipo=<%=request.QueryString("Tipo")%>" ID="Form3">
						Nome Regione: <input type="text" name="txtReg" size="30" value="<%=fFindNomeReg(request.Form("txtArea"))%>" readonly ID="Text5"><input type="hidden" name="txtArea" size="30" value="<%=request.Form("txtArea")%>" readonly ID="Hidden1"><br>
						Nome <%=fFindNomeTipo(request.QueryString("Tipo"))%>: <input type="text" name="txtNome" size="30" value="" ID="Text2"><br>
						Descrizione: <br><textarea name="txtDescrizione" rows="30" cols="100" ID="Textarea1"></textarea><br>
						Immagine: <input type=text name="txtImm" size=100 value="" ID="Text10"><br>
						Ordine: <input type=text name="txtOrdine" size=1 value="" ID="Text3"><br><br><br>
						<input type="Submit" name="cmdNome" value="Collega" ID="Submit3">
						<input type="Submit" name="cmdNome" value="AnteprimaCol" ID="Submit6">
					</form>
					</td>
					<td valign="top">
					<%sConsole()%>
					</td>
				</tr>
		</table>
			<%
		end if
			
	case "Inserisci"
		sNome=replace(request.Form("txtNome"),"'","''")
		sDesc=replace(request.Form("txtDescrizione"),"'","''")
		iTipo=request.QueryString("Tipo")
		sImm=replace(request.Form("txtImm"),"'","''")
		iOrdine=replace(request.Form("txtOrdine"),"'","''")
		if iOrdine="" then iOrdine=0
		ConPort.Errors.Clear
		'ConPort.BeginTrans
		sSql="insert into tblamb_aree_ind(nomearea,dtiso_mod,timer_mod) values('" & sNome & "',CURDATE()+0,Time_to_sec(now()));"
		'ConPort.execute(sSql)
		sSql="select ID from tblamb_aree_ind order by id desc"
		RsAmb.open sSql,ConPort
		sSql="insert into tblamb_aree_desc(idarea,idtipo,nome,descrizione,ordine,Imm) values(" & RsAmb.fields(0) & "," & iTipo & ",'" & sNome & "', '" & sDesc & "'," & iOrdine & ",'" & sImm & "');"
		ConPort.execute(sSql)
		response.Write sSql
		rsAmb.close
		'if ConPort.errors.count<>0 then
		'	ConPort.RollBackTrans
		'	response.Write "Inserimento non effettuato riprovare!<br><br>"
		'else
		'	ConPort.CommitTrans
			response.Write "Inserimento effettuato!<br><br>"
		'end if
		response.Write "<a href='./GestTerritorioAmb.asp'>Torna all'inizio procedura</a>"
		
	case "Collega"
		sNome=replace(request.Form("txtNome"),"'","''")
		iReg=request.Form("txtArea")
		sDesc=replace(request.Form("txtDescrizione"),"'","''")
		iTipo=request.QueryString("Tipo")
		sImm=replace(request.Form("txtImm"),"'","''")
		iOrdine=replace(request.Form("txtOrdine"),"'","''")
		ConPort.Errors.Clear
		'ConPort.BeginTrans
		sSql="insert into tblamb_aree_desc(idarea,idtipo,nome,descrizione,ordine,Imm) values(" & iReg & "," & iTipo & ",'" & sNome & "', '" & sDesc & "'," & iOrdine & ",'" & sImm & "');"
		ConPort.execute(sSql)
		'if ConPort.errors.count<>0 then
		'	ConPort.RollBackTrans
		'	response.Write "Inserimento non effettuato riprovare!<br><br>"
		'else
		'	ConPort.CommitTrans
			response.Write "Inserimento effettuato!<br><br>"
		'end if
		response.Write "<a href='./GestTerritorioAmb.asp'>Torna all'inizio procedura</a>"
		
	case "AnteprimaIns"
		%>
		<table ID="Table4">
			<tr>
				<td>
					<form name="frmAmb" method="post" action="./GestTerritorioAmb.asp?Sez=ins&Tipo=<%=request.QueryString("Tipo")%>" ID="Form4">
					Nome <%=fFindNomeTipo(request.QueryString("Tipo"))%>: <input type="text" name="txtNome" size="30" value="<%=request.Form("txtNome")%>" readonly ID="Text4"><br>
					Descrizione: <br><textarea name="txtDescrizione" rows="30" cols="100" ID="Textarea2"><%=request.Form("txtDescrizione")%></textarea><br>
					Immagine: <input type=text name="txtImm" size=100 value="<%=request.Form("txtImm")%>" ID="Text11"><br>
					Ordine: <input type=text name="txtOrdine" size=1 value="<%=request.Form("txtOrdine")%>" ID="Text6"><br><br><br>
					<input type="Submit" name="cmdNome" value="Inserisci" ID="Submit4">
					<input type="Submit" name="cmdNome" value="AnteprimaIns" ID="Submit5">
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
	case "AnteprimaCol"
		%>
		<table ID="Table5">
			<tr>
				<td>
					<form name="frmAmb" method="post" action="./GestTerritorioAmb.asp?Sez=ins&Tipo=<%=request.QueryString("Tipo")%>" ID="Form5">
						Nome Regione: <input type="text" name="txtReg" size="30" value="<%=request.Form("txtReg")%>" readonly ID="Text7"><input type="hidden" name="txtArea" size="30" value="<%=request.Form("txtArea")%>" readonly ID="Hidden2"><br>
						Nome <%=fFindNomeTipo(request.QueryString("Tipo"))%>: <input type="text" name="txtNome" size="30" value="<%=request.Form("txtNome")%>" ID="Text8"><br>
						Descrizione: <br><textarea name="txtDescrizione" rows="30" cols="100" ID="Textarea3"><%=request.Form("txtDescrizione")%></textarea><br>
						Immagine: <input type=text name="txtImm" size=100 value="<%=request.Form("txtImm")%>" ID="Text12"><br>
						Ordine: <input type=text name="txtOrdine" size=1 value="<%=request.Form("txtOrdine")%>" ID="Text9"><br><br><br>
						<input type="Submit" name="cmdNome" value="Collega" ID="Submit7">
						<input type="Submit" name="cmdNome" value="AnteprimaCol" ID="Submit8">
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
	case else
		%>
		<table ID="Table1">
			<%if request.QueryString("Tipo")=1 then%>		
			<tr>
				<th>
					Scegliere nome area
					<hr>
				</th>
			</tr>
			<tr>
				<td align=center>
					<form method="post" action="./GestTerritorioAmb.asp?Sez=ins&Tipo=<%=request.QueryString("Tipo")%>" ID="Form1">
						<input type="text" name="txtNome" size="30" ID="Text1">
						<input type="Submit" name="cmdNome" value="Avanti" ID="Submit1">
					</form>
				</td>
			</tr>
			<%else%>
			<tr>
				<th>
					Scegliere area esistente a cui aggiungere:
					<hr>
				</th>
			</tr>
				<form method="post" action="./GestTerritorioAmb.asp?Sez=ins&Tipo=<%=request.QueryString("Tipo")%>">			
				<%
				sSql="select * from tblamb_aree_ind"
				RsAmb.Open sSql,ConPort
				do while not RsAmb.EOF
					%>				
					<tr>
						<td align=center>
							<input type=radio name="txtArea" value="<%=RsAmb("ID")%>"><%=RsAmb("nomearea")%>
						</td>
					</tr>		
					<%
					RsAmb.MoveNext
				loop			
				%>
				<tr>
					<td align=center>
						<input type="Submit" name="cmdNome" value="Aggiungi" ID="Submit2">
					</td>
				</tr>
				</form>
			<%end if%>
		</table>
		<%	
end select
end function
%>


<%
'############################################################
												'MAIN PROGRAM
'############################################################
if int(request.QueryString("Tipo"))>0 then
	Call fInsAmb()
else
	Call fTipoAmb()
end if
%>