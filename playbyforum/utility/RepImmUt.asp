<%
set ConRP = server.CreateObject("ADODB.Connection")
set rsRP = server.CreateObject("ADODB.Recordset")
ConRP.Open Application("DB_CONNECTIONSTRING")
Set rsRP = ConRP.Execute("SELECT id,photo FROM pgdb where lcase(photo) like '%www.luxintenebra.net/galleria%'")
do while not rsRP.EOF
    sLinkImm=replace(lcase(rsRP.fields(1)),"galleria","public/galleria")
    ConRP.Execute("Update pgdb set photo='" & sLinkImm & "' where ID=" & rsRP.Fields("id"))
    Response.Write rsRP.Fields(1) & "<br>"
    rsRP.MoveNext
loop
rsRP.Close
ConRP.Close

%>