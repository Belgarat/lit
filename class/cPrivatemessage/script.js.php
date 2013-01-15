<script type="text/javascript">
function show_message(id_element, url, mtd, param, second){
	
	var udiv = new Ajax.Request(url,
		{method: mtd,
		parameters: param,
		onSuccess: function(transport)
			{
				var objjson = transport.responseText;
				if (objjson){
					inner_read_div(objjson,id_element);
				} else {
					alert("Errore nella procedura di estrazione dati!");
				}
			},
		onFailure: OperationFailed
		});

}

function inner_read_div(objtext, id_element){
	$(id_element).update(objtext);
}

function delete_message(id_element, url, mtd, param, second){
	
	var udelete = new Ajax.Updater(id_element, url,
		{method: mtd,
		parameters: param,
		});
	
	$('msg_obj').update("");
	$('msg_body').update("");
	$('msg_sign').update("");	

}

function compile_message(obj,id_element)
{		

	//$('id_msg_new_obj').update('Re: '+obj.obj);
	jsajax="delete_message('id_msg_list','src/ajax/PrivateMessage_delete.php','get','idmsg="+obj.Id+"');";
	jsajax_rispondi="$('id_msg_new_dst').value='"+obj.Destinatario+"';$('id_msg_new_obj').value='Re: "+obj.Obj+"';Effect.Appear('id_msg_write', { duration: 0.5 });";
	$('msg_show_bar').update('<ul><li><a href="javascript:void(0);" onclick="javascript: '+jsajax_rispondi+'">Rispondi</a></li><li>Archivia</li><li><a id="id_test" href=\"javascript: void(0);\" onClick=\"javascript: '+jsajax+'">Elimina</a></li></ul>').innerHTML;


	$('msg_obj').update(obj.Obj);
	$('msg_body').update(obj.Body);
	$('msg_sign').update(obj.Sign);	

	
}

function show_folders(id_element, url, mtd, param, second){
	
	var udiv = new Ajax.Updater(id_element, url,
		{method: mtd,
		parameters: param,
		});

}

var udiv = new Ajax.PeriodicalUpdater('id_msg_link', 'src/ajax/PrivateMessage_new.php', {
  method: 'get', frequency: 3, decay: 2
});


</script>
