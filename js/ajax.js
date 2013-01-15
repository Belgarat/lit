function InserisciTesto(Stringa) {
document.parent.getElementById('txtImmagini').innerText = Stringa;
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

function upload_img(sImm,iId,home)
{
open("./"+home+"/upload_amb_imm.php?NomeImm=" + sImm + "&IdC=" + iId, "", "width="+650+",height="+220+",scrollbars=yes, left="+((screen.width-550)/2)+",top="+((screen.height-420)/2)+"");
} 		

function upload_att(sImm,iId,home)
{
open("./"+home+"/upload_amb_att.php?NomeImm=" + sImm + "&IdC=" + iId, "", "width="+650+",height="+220+",scrollbars=yes, left="+((screen.width-550)/2)+",top="+((screen.height-420)/2)+"");
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

function apriUtente(sPerc,lUser)
{
open(sPerc+"/src/ProfUt.php?PG=" + lUser, "", "width="+700+",height="+583+",scrollbars=yes, left="+((screen.width-700)/2)+",top="+((screen.height-583)/2)+"");
}
/*Function che mostra gli elementi con effetto fade
 * id_element : HTML element id
 * url : address server side script
 * mtd : method, post or get
 * param : parameter for script
 * second : number of second for animation
 * */
function ShowElement(id_element, url, mtd, param, second){
	
	if ( second == '' ) {
		second = 1.5;
	}
	
	var udiv = new Ajax.Request(url,
		{method: mtd,
		parameters: param,
		onSuccess: function(transport)
			{
				var objjson = transport.responseText.evalJSON(true);
				if (objjson){
					compileForm(objjson,id_element);
				} else {
					alert("Errore nella procedura di estrazione dati!");
				}
			},
		onFailure: OperationFailed
		});


}

function ShowThreadEdit(id_element, url, mtd, param, second){
	
	if ( second == '' ) {
		second = 1.5;
	}
	
	var udiv = new Ajax.Request(url,
		{method: mtd,
		parameters: param,
		onSuccess: function(transport)
			{
				var objjson = transport.responseText.evalJSON(true);
				if (objjson){
					compileThreadForm(objjson,id_element);
				} else {
					alert("Errore nella procedura di estrazione dati!");
				}
			},
		onFailure: OperationFailed
		});

}

function compileForm(obj,id_element)
{
	switch (id_element){
		case "formEdit":
			Effect.Appear('formEditPost', { duration: 1.5 });
			$('id_txtEditIdPost').value = obj.id;
			$('id_txtEditNome').value = obj.Name;
			$('id_txtEditOggetto').value = obj.Obj;
			$('id_txtEditMessaggio').value = obj.Body;
			$('id_txtEditFirma').value = obj.Sign;
			break;
		case "id_formEditThread":
			Effect.Appear('formEditPost', { duration: 1.5 });
			$('id_txtEditIdPost').value = obj.id;
			$('id_txtEditNome').value = obj.Name;
			$('id_txtEditOggetto').value = obj.Obj;	
			$('id_txtEditMessaggio').value = obj.Body;
			$('id_txtEditFirma').value = obj.Sign;
			break;
	}
}

function compileThreadForm(obj,id_element)
{
	Effect.Appear('formEditThread', { duration: 1.5 });
	$('id_txtEditIdThread').value = obj.id;
	$('id_txtEditTitleThread').value = obj.Title;
	$('id_txtEditDescrizione').value = obj.Desc;	
	$('id_txtEditModeratori').value = obj.Mod;
	$('id_txtEditTipo').value = obj.Type;
        $('id_txtEditArgument').value = obj.Argument;
	$('id_txtEditImmagine').value = obj.Img;
	$('id_txtEditOrdine').value = obj.Ord;
	$('id_txtEditStatus').value = obj.Status;
}

function OperationFailed(){
	alert("Operazione richiesta, fallita. Sovraccarico di richieste al server, probabile causa.");
}

/*function UpdateElement(id_element, url_set_upd, url_let_upd, mtd, param, second){
	new Ajax.Updater('formEditPost', '<?php echo HTTP_ROOT . "/src/ajax/forum_form_save.php"; ?>', {method: 'post',parameters: Form.serialize($('formEditPost'))}); 
	new Ajax.Updater('<?php echo $this->let_id_post(); ?>', '<?php echo HTTP_ROOT . "/src/ajax/forum_post_read.php"; ?>', {method: 'post',parameters: Form.serialize($('formEditPost'))});">
}*/

function ffade(id_element){
	Effect.Fade(id_element, { duration: 1.5 });
}

function fappear(id_element){
	Effect.Appear(id_element, { duration: 1.5 });
}

function init(e,path,url){
	Event.observe(window.document.body, 'click', function(event) {
		var element = Event.element(event);		
		switch (element.identify()) {
			case "aShowReply":
				fappear('DivReply');
				break;
			case "aShowInsertThread":
				fappear('formInsertThread');
				break;
			case "aShowReplyBottom":
				fappear('DivReply');
				break;
			case "aHideReply":
				ffade('DivReply');
				break;
			case "aHideEditPost":
				ffade('formEditPost');
				break;
			case "aHideEditThread":
				ffade('formEditThread');
				break;
			case "aHideInsertThread":
				ffade('formInsertThread');
				break;
		}
	});
	Event.observe('aShowSmile', 'mouseover', function(event) {
		$('iSmile').show();
		$('iSmile').style.top=-200+Event.pointerY(event)/2+'px';
		$('iSmile').style.left=20+Event.pointerX(event)/2+'px';
	});	
	Event.observe('aShowEditSmile', 'mouseover', function(event) {
		$('iSmile').show();
		$('iSmile').style.top=-200+Event.pointerY(event)/2+'px';
		$('iSmile').style.left=20+Event.pointerX(event)/2+'px';
	});	
	Event.observe('aHideSmile', 'click', function(event) {
		$('iSmile').hide();
	});
}

function AjaxWorkingOn(){
	$('id_working').setOpacity(0.8);
	$('id_working').update('<img src=\'img/lit_working.gif\' width=\'100%\' alt=\'wait\'/>');
}
function AjaxWorkingOff(){
	$('id_working').update('');
}

function ToggleOnline(event){
    var udiv = new Ajax.Updater('id_PluginBoard', 'src/ajax/utente_users_online_list.php');
    $('id_PluginBoard').style.left=$('id_PluginBoard').getStyle('left');
    $('id_PluginBoard').style.top=$('id_PluginBoard').getStyle('top');
    $('id_PluginBoard').toggle();
    
}