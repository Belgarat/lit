<%

Set Con = Server.CreateObject("ADODB.Connection")
Set RsO = Server.CreateObject("ADODB.Recordset")
Set RsD = Server.CreateObject("ADODB.Recordset")

cString=Application("DB_CONNECTIONSTRING_Portale")
Dim sFiltro
Con.Open cString

con.Execute("delete from tblgalfiltro")

set RsO = con.Execute("select * from tblgalleria where descrizione<>''")

do while not RsO.EOF	
		sFiltro=split(RsO.Fields("descrizione"),";")
		for each oFiltro in sFiltro
			set rsd = con.Execute("select * from tblgalfiltro where filtro like '%" & oFiltro & "%'")
			if Rsd.EOF=true then
				con.Execute("insert into tblgalfiltro(Filtro) values('" & oFiltro & "')")
			end if
			rsd.Close
		next
		sFiltro=""
	RsO.MoveNext
loop

rso.Close
con.Close

Response.Write "Aggiornamento filtro eseguito con successo!<br><a href='../index.asp?action=galleria'>Torna indietro</a>"

%>