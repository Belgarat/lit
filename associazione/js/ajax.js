
var myRequest = null;

// Create the XML HTTP request object. We try to be
// more cross-browser as possible.
function CreateXmlHttpReq(handler) {
  var agt = navigator.userAgent.toLowerCase();
  var is_ie5 = (agt.indexOf('msie 5') != -1);
  var xmlhttp = null;
  try {
    xmlhttp = new XMLHttpRequest();
    try {
        // Fix for some version of Mozilla browser.
        http_request.overrideMimeType('text/xml');
    } catch(e) { }
    xmlhttp.onload = handler;
    xmlhttp.onerror = handler;
  } catch(e) {
    var control = (is_ie5) ? "Microsoft.XMLHTTP" : "Msxml2.XMLHTTP";
    xmlhttp = new ActiveXObject(control);
    xmlhttp.onreadystatechange = handler;
  }
  return xmlhttp;
}

function myHandler(sId) {
    if (myRequest.readyState == 4 && myRequest.status == 200) {
        e = document.getElementById(sId);
        e.innerHTML = myRequest.responseText;
    }
}

function Ajax_script_exec(sSourceFile,sIdElement) {
    myRequest = CreateXmlHttpReq(myHandler(sIdElement));
    myRequest.open("GET",sSourceFile);
    myRequest.send(null);
}

function VediNascondi(sName,iTipo) {
    var e = document.getElementById(sName);
    if (iTipo == "0") {
        e.style.visibility = 'visible';
        e.style.display = 'block';
		  myRequest = CreateXmlHttpReq2(myHandler2);
		  myRequest.open("GET","aggsess.asp?Val=0");
		  myRequest.send(null);        
    } else {
        e.style.visibility = 'hidden';
        e.style.display = 'none';
		myRequest = CreateXmlHttpReq2(myHandler2);
		myRequest.open("GET","aggsess.asp?Val=1");
		myRequest.send(null);        
    }
}
function VediEmo() {
    var e = document.getElementById("idEmo");
    e.style.visibility = 'visible';
    e.style.display = 'block';
	myRequest = CreateXmlHttpReq2(myHandler2);
	myRequest.open("GET","aggsess.asp?Val=0");
	myRequest.send(null);        
}
function NascondiEmo() {
    var e = document.getElementById("idEmo");
    e.style.visibility = 'hidden';
    e.style.display = 'none';
	myRequest = CreateXmlHttpReq2(myHandler2);
	myRequest.open("GET","aggsess.asp?Val=1");
	myRequest.send(null);        
}

function VediBg() {
    var e = document.getElementById("idBg2");
    e.style.visibility = 'visible';
    e.style.display = 'block';
	myRequest = CreateXmlHttpReq2(myHandler2);
	myRequest.open("GET","aggsess.asp?Val=0");
	myRequest.send(null);        
}
function NascondiBg() {
    var e = document.getElementById("idBg2");
    e.style.visibility = 'hidden';
    e.style.display = 'none';
	myRequest = CreateXmlHttpReq2(myHandler2);
	myRequest.open("GET","aggsess.asp?Val=1");
	myRequest.send(null);        
}
function VediDesc() {
    var e = document.getElementById("IdDesc2");
    e.style.visibility = 'visible';
    e.style.display = 'block';
	myRequest = CreateXmlHttpReq2(myHandler2);
	myRequest.open("GET","aggsess.asp?Val=0");
	myRequest.send(null);        
}
function NascondiDesc() {
    var e = document.getElementById("IdDesc2");
    e.style.visibility = 'hidden';
    e.style.display = 'none';
	myRequest = CreateXmlHttpReq2(myHandler2);
	myRequest.open("GET","aggsess.asp?Val=1");
	myRequest.send(null);        
}
function InserisciTesto(Stringa) {
document.parent.getElementById('txtImmagini').innerText = Stringa;
/*Return true;*/
}
function fClickMostraNascondi(Id,flag) {
	var IdDiv = document.getElementById(Id);
	if ((IdDiv.style.visibility=="hidden")||(flag==1)) {
		IdDiv.style.visibility="visible";
		IdDiv.style.display="block";
		}
	else
		{
		IdDiv.style.visibility="hidden";
		IdDiv.style.display="none";
		}
}

function fClickMostra(Id) {
	var IdDiv = document.getElementById(Id);
	IdDiv.style.visibility="visible";
	IdDiv.style.display="block";
}

function fClickNascondi(Id) {
	var IdDiv = document.getElementById(Id);
	IdDiv.style.visibility="hidden";
	IdDiv.style.display="none";
}

function upload_img(sImm,iId)
{
open("./upload_amb_imm.php?NomeImm=" + sImm + "&IdC=" + iId, "", "width="+650+",height="+220+",scrollbars=yes, left="+((screen.width-550)/2)+",top="+((screen.height-420)/2)+"");
} 		

function upload_att(sImm,iId)
{
open("./upload_amb_att.php?NomeImm=" + sImm + "&IdC=" + iId, "", "width="+650+",height="+220+",scrollbars=yes, left="+((screen.width-550)/2)+",top="+((screen.height-420)/2)+"");
}

function open_url(sURL)
{
open(sURL, "", "width="+350+",height="+220+",scrollbars=yes, left="+((screen.width-550)/2)+",top="+((screen.height-420)/2)+"");
}

function onfocus_bg(Id){
	var IdDiv = document.getElementById(Id);
	IdDiv.style.background="#b19bcf";
}

function onblur_bg(Id){
	var IdDiv = document.getElementById(Id);
	IdDiv.style.background="white";
}

function Modulo() {
   // Variabili associate ai campi del modulo
   var nome = document.modulo.nome.value;
   var cognome = document.modulo.cognome.value;
   var nato_dove = document.modulo.nato_dove.value;
   var nato_prov = document.modulo.nato_prov.value;
   var nato_quando = document.modulo.nato_quando.value;
   var res_via = document.modulo.res_via.value;
   var res_cap = document.modulo.res_cap.value;
   var res_citta = document.modulo.res_citta.value;
   var res_prov = document.modulo.res_prov.value;
   var email = document.modulo.email.value;		     
   var anno = document.modulo.anno.options[document.modulo.anno.selectedIndex].value;
   var consenso = document.modulo.consenso[0].checked;
   // Espressione regolare dell'email
   var email_reg_exp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-]{2,})+\.)+([a-zA-Z0-9]{2,})+$/;		     
      //Effettua il controllo sul campo NOME
      if ((nome == "") || (nome == "undefined")) {
         alert("Il campo Nome � obbligatorio.");
         document.modulo.nome.focus();
         return false;
      }
      //Effettua il controllo sul campo COGNOME
      else if ((cognome == "") || (cognome == "undefined")) {
         alert("Il campo Cognome � obbligatorio.");
         document.modulo.cognome.focus();
         return false;
      }
      //Effettua il controllo sul campo NICKNAME
      else if ((nato_dove == "") || (nato_dove == "undefined")) {
         alert("Il campo 'Nato a' � obbligatorio.");
         document.modulo.nato_a.focus();
         return false;
      }
      //Effettua il controllo sul campo NICKNAME
      else if ((nato_prov == "") || (nato_prov == "undefined")) {
         alert("Il campo 'in provincia' � obbligatorio.");
         document.modulo.nato_a.focus();
         return false;
      }
      //Effettua il controllo sul campo DATA DI NASCITA
      else if (document.modulo.nato_quando.value.substring(2,3) != "/" ||
         document.modulo.nato_quando.value.substring(5,6) != "/" ||
         isNaN(document.modulo.nato_quando.value.substring(0,2)) ||
         isNaN(document.modulo.nato_quando.value.substring(3,5)) ||
         isNaN(document.modulo.nato_quando.value.substring(6,10))) {
           alert("Inserire nascita in formato gg/mm/aaaa");
            document.modulo.nato_quando.value = "";
            document.modulo.nato_quando.focus();
            return false;
      }
      else if (document.modulo.nato_quando.value.substring(0,2) > 31) {
         alert("Impossibile utilizzare un valore superiore a 31 per i giorni");
         document.modulo.nato_quando.select();
         return false;
      }
      else if (document.modulo.nato_quando.value.substring(3,5) > 12) {
         alert("Impossibile utilizzare un valore superiore a 12 per i mesi");
         document.modulo.nato_quando.value = "";
         document.modulo.nato_quando.focus();
         return false;
      }
      else if (document.modulo.nato_quando.value.substring(6,10) < 1900) {
         alert("Impossibile utilizzare un valore inferiore a 1900 per l'anno");
         document.modulo.nato_quando.value = "";
         document.modulo.nato_quando.focus();
         return false;
      }
      //Effettua il controllo sul campo CITTA'
      else if ((res_via == "") || (res_via == "undefined")) {
        alert("Il campo 'Via' � obbligatorio.");
        document.modulo.res_via.focus();
        return false;
      }
      //Effettua il controllo sul campo INDIRIZZO
      else if ((res_cap == "") || (res_cap == "undefined")) {
         alert("Il campo 'C.A.P.' � obbligatorio.");
         document.modulo.res_cap.focus();
         return false;
      }
      //Effettua il controllo sul campo INDIRIZZO
      else if ((res_citta == "") || (res_citta == "undefined")) {
         alert("Il campo 'citt�' � obbligatorio.");
         document.modulo.res_citta.focus();
         return false;
      }
      //Effettua il controllo sul campo INDIRIZZO
      else if ((res_prov == "") || (res_prov == "undefined")) {
         alert("Il campo 'provincia' � obbligatorio.");
         document.modulo.prov.focus();
         return false;
      }
      else if (!email_reg_exp.test(email) || (email == "") || (email == "undefined")) {
         alert("Inserire un indirizzo email corretto.");
         document.modulo.email.select();
         return false;
      }
      //Effettua il controllo sul campo CITTA'
      else if ((anno == "") || (anno == "undefined")) {
        alert("Il campo 'anno' � obbligatorio.");
        document.modulo.anno.focus();
        return false;
      }
      //Effettua il controllo sul campo CITTA'      
      else if ((consenso == false)) {		
        alert("E' necessario dare il consenso per procedere con l'iscrizione!");
        document.modulo.consenso.focus();
        return false;
      }
      //INVIA IL MODULO
      else {
         document.modulo.action = "registra_iscrizione.asp";
         document.modulo.submit();
      }
}

function apriUtente(lUser)
{
open("/src/ProfUt.php?PG=" + lUser, "", "width="+700+",height="+583+",scrollbars=yes, left="+((screen.width-700)/2)+",top="+((screen.height-583)/2)+"");
} 