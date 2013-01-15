
var myRequest = null;

/*tinyMCE.init({
    theme : "advanced",
    mode: "exact",
    elements : "news",
    theme_advanced_toolbar_location : "top",
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,"
    + "justifyleft,justifycenter,justifyright,justifyfull,formatselect,"
    + "",
    theme_advanced_buttons2 : "bullist,numlist,outdent,indent,separator,link,unlink,anchor,image,undo,redo,cleanup,code,separator,charmap",
    theme_advanced_buttons3 : "",
    height:"350px",
    width:"100%",
    language : "it"
    //file_browser_callback : 'myFileBrowser'
});*/
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});


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
        if(sId=='news'){
        	tinyMCE.setContent(myRequest.responseText);
        }else{				        	
        	e = document.getElementById(sId);
        	document.forms['formNews'][sId].value = myRequest.responseText;
        }
    }
}

function Ajax_script_exec(sSourceFile,sIdElement,iIdNews,sNameElement) {
    myRequest = CreateXmlHttpReq(function() {myHandler(sIdElement)});
    myRequest.open("GET",sSourceFile + "&IdN=" + iIdNews + "&Elem=" + sNameElement + "&rand="+escape(Math.random()));
    myRequest.send(null);
}

function select_modify(Id_News,Id_Sito,Id_Tipo){
	var e = document.getElementById('lblStato');
	var aut = document.getElementById('id_txtAuthor');
	var lblaut = document.getElementById('lblAuthor');
	e.innerHTML="Modalit&agrave modifica";
	aut.style.display="inline";
	aut.style.visibility="visible";
	lblaut.style.display="inline";
	lblaut.style.visibility="visible";
	url="RqNews.php";
	var data = "st="+Id_Sito+"&tp="+Id_Tipo+"&IdN="+Id_News+"&Elem=id_news";
	var aj = new Ajax.Request(url, {method:'get',parameters: data,onComplete: function(or){$("id_txtId_news").value = or.responseText;}});
	var data = "st="+Id_Sito+"&tp="+Id_Tipo+"&IdN="+Id_News+"&Elem=id_utente";
	var aj = new Ajax.Request(url, {method:'get',parameters: data,onComplete: function(or){$("id_txtId_utente").value = or.responseText;}});
	var data0 = "st="+Id_Sito+"&tp="+Id_Tipo+"&IdN="+Id_News+"&Elem=author";
	var aj0 = new Ajax.Request(url, {method:'get',parameters: data0,onComplete: function(or){$("id_txtAuthor").value = or.responseText;}});
	var data1 = "st="+Id_Sito+"&tp="+Id_Tipo+"&IdN="+Id_News+"&Elem=title";
	var aj1 = new Ajax.Request(url, {method:'get',parameters: data1,onComplete: function(or){$("id_txtTitle").value = or.responseText;}});
	var data2 = "st="+Id_Sito+"&tp="+Id_Tipo+"&IdN="+Id_News+"&Elem=body";
	var aj2 = new Ajax.Request(url, {method:'get',parameters: data2,onComplete: function(or){tinyMCE.activeEditor.setContent(or.responseText);}});
	var data3 = "st="+Id_Sito+"&tp="+Id_Tipo+"&IdN="+Id_News+"&Elem=datetime";
	var aj3 = new Ajax.Request(url, {method:'get',parameters: data3,onComplete: function(or){$("id_txtDatetime").value = or.responseText;}});
}
function clear_form(){
	$("formNews").reset();
	//tinyMCE.setContent("");	
	$("lblStato").innerHTML = "Modalit&agrave inserimento";
	$("lblAuthor").hide();
	$("id_txtAuthor").hide();
}				

